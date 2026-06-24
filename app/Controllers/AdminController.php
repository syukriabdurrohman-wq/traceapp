<?php

namespace App\Controllers;

use App\Models\DailyReportModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use App\Services\AuthService;
use App\Services\DailyReportService;
use Config\Database;

class AdminController extends BaseController
{
    public function __construct(
        private readonly UserModel $userModel = new UserModel(),
        private readonly RoleModel $roleModel = new RoleModel(),
        private readonly DailyReportService $dailyReportService = new DailyReportService(),
    ) {
    }

    public function users(): string
    {
        $editUserId = (int) ($this->request->getGet('edit') ?? 0);
        $editUser   = $editUserId > 0 ? $this->userModel->findDetailedById($editUserId) : null;

        return $this->page('Admin/UserManagementPage', [
            'pageTitle' => 'Kelola User',
            'users'     => $this->userModel->getActiveUsersForManagement(),
            'roles'     => $this->roleModel->orderBy('id', 'ASC')->findAll(),
            'editUser'  => $editUser,
        ]);
    }

    public function saveUser()
    {
        $payload = $this->request->getPost([
            'userId',
            'fullName',
            'email',
            'username',
            'phone',
            'roleId',
            'status',
            'password',
        ]);

        $validation = service('validation');
        if (! $validation->setRules(config(\Config\Validation::class)->adminUser)->run($payload)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'role_id'   => (int) $payload['roleId'],
            'full_name' => trim((string) $payload['fullName']),
            'email'     => trim((string) $payload['email']) === '' ? null : strtolower(trim((string) $payload['email'])),
            'username'  => trim((string) $payload['username']),
            'phone'     => (new AuthService())->normalizePhone((string) ($payload['phone'] ?? '')),
            'status'    => $payload['status'],
        ];

        if (trim((string) ($payload['password'] ?? '')) !== '') {
            $data['password_hash'] = password_hash((string) $payload['password'], PASSWORD_DEFAULT);
        }

        $userId = (int) ($payload['userId'] ?? 0);

        if ($userId > 0) {
            $existing = $this->userModel->find($userId);
            if ($existing === null) {
                return redirect()->back()->with('error', 'User yang akan diubah tidak ditemukan.');
            }

            if ($data['email'] !== null && $existing['email'] !== $data['email'] && $this->userModel->where('email', $data['email'])->where('id !=', $userId)->countAllResults() > 0) {
                return redirect()->back()->withInput()->with('error', 'Email sudah dipakai user lain.');
            }

            if ($existing['username'] !== $data['username'] && $this->userModel->where('username', $data['username'])->where('id !=', $userId)->countAllResults() > 0) {
                return redirect()->back()->withInput()->with('error', 'Username sudah dipakai user lain.');
            }

            if ($existing['phone'] !== $data['phone'] && $this->userModel->where('phone', $data['phone'])->where('id !=', $userId)->countAllResults() > 0) {
                return redirect()->back()->withInput()->with('error', 'Nomor HP sudah dipakai user lain.');
            }

            $this->userModel->update($userId, $data);
            $message = 'Data user berhasil diperbarui.';
        } else {
            if (($data['email'] !== null && $this->userModel->where('email', $data['email'])->countAllResults() > 0) || $this->userModel->where('username', $data['username'])->countAllResults() > 0 || $this->userModel->where('phone', $data['phone'])->countAllResults() > 0) {
                return redirect()->back()->withInput()->with('error', 'Email, username, atau nomor HP sudah digunakan.');
            }

            if (! array_key_exists('password_hash', $data)) {
                return redirect()->back()->withInput()->with('error', 'Password wajib diisi untuk user baru.');
            }

            $this->userModel->insert($data);
            $message = 'User baru berhasil ditambahkan.';
        }

        return redirect()->to(base_url('admin/users'))->with('success', $message);
    }

    public function toggleUserStatus(int $userId)
    {
        $user = $this->userModel->find($userId);

        if ($user === null) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        $this->userModel->update($userId, [
            'status' => $user['status'] === 'Active' ? 'Inactive' : 'Active',
        ]);

        return redirect()->to(base_url('admin/users'))->with('success', 'Status user berhasil diubah.');
    }

    public function deleteUser(int $userId)
    {
        $actor = $this->authService->currentUser();

        if ($actor !== null && (int) $actor['id'] === $userId) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = $this->userModel->find($userId);
        if ($user === null) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        $db = Database::connect();
        $db->transStart();

        $reportModel = new DailyReportModel();
        $reports = $reportModel->groupStart()
            ->where('worker_user_id', $userId)
            ->orWhere('created_by_user_id', $userId)
            ->groupEnd()
            ->findAll();

        foreach ($reports as $report) {
            $reportModel->delete($report['id'], true);
        }

        $this->userModel->delete($userId, true);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Gagal menghapus user dan data terkait.');
        }

        $this->authService->writeAudit((int) $actor['id'], 'DeleteUser', 'Users', $userId, ['username' => $user['username']]);

        return redirect()->to(base_url('admin/users'))->with('success', 'User dan seluruh data laporan terkait berhasil dihapus permanen.');
    }

    public function reports(): string
    {
        $currentUser = $this->authService->currentUser();
        $isSupervisor = $currentUser['role_code'] === 'Supervisor';

        $filters = [
            'reportDate'   => (string) ($this->request->getGet('reportDate') ?? ''),
            'workerUserId' => (string) ($this->request->getGet('workerUserId') ?? ''),
            'status'       => (string) ($this->request->getGet('status') ?? ''),
        ];

        // Jika dia Supervisor, paksa filter agar dia hanya bisa melihat laporannya sendiri
        if ($isSupervisor) {
            $filters['workerUserId'] = (string) $currentUser['id'];
        }

        return $this->page('Admin/ReportManagementPage', [
            'pageTitle'   => 'Monitoring Laporan',
            'reports'     => $this->dailyReportService->getReports($filters),
            'reportUsers' => (new UserModel())->getActiveReportUsers(),
            'filters'     => $filters,
            'isSupervisor'=> $isSupervisor,
        ]);
    }
}