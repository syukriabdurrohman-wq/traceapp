<?php

namespace App\Services;

use App\Libraries\Notification;
use App\Models\AuditLogModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use App\Models\UserSessionModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Session\Session;

class AuthService
{
    public function __construct(
        private readonly UserModel $userModel = new UserModel(),
        private readonly RoleModel $roleModel = new RoleModel(),
        private readonly UserSessionModel $userSessionModel = new UserSessionModel(),
        private readonly AuditLogModel $auditLogModel = new AuditLogModel(),
        private readonly Notification $notification = new Notification(),
        private readonly ?Session $session = null,
    ) {
    }

    public function register(array $data): array
    {
        $data['phone'] = $this->normalizePhone((string) ($data['phone'] ?? ''));
        $data['email'] = trim((string) ($data['email'] ?? ''));

        $validation = service('validation');
        $rules      = config(\Config\Validation::class)->authRegister;

        if (! $validation->setRules($rules)->run($data)) {
            return ['success' => false, 'errors' => $validation->getErrors()];
        }

        $role = $this->roleModel->findByCode('Supervisor');

        if ($role === null) {
            return ['success' => false, 'errors' => ['role' => 'Role Supervisor tidak ditemukan di database.']];
        }

        $userId = $this->userModel->insert([
            'role_id'       => $role['id'],
            'full_name'     => trim((string) $data['fullName']),
            'email'         => $data['email'] === '' ? null : strtolower($data['email']),
            'username'      => trim((string) $data['username']),
            'phone'         => $data['phone'],
            'password_hash' => password_hash((string) $data['password'], PASSWORD_DEFAULT),
            'status'        => 'Active',
        ], true);

        $this->writeAudit((int) $userId, 'Register', 'Users', (int) $userId, ['username' => $data['username']]);

        return ['success' => true, 'userId' => $userId];
    }

    public function startOtpLogin(string $phone, string $password, RequestInterface $request): array
    {
        $phone = $this->normalizePhone($phone);
        $user = $this->verifyCredentials($phone, $password);

        if ($user === null) {
            return ['success' => false, 'errors' => ['login' => 'Nomor HP atau password tidak valid.']];
        }

        $otp = (string) random_int(100000, 999999);
        $session = $this->resolveSession();
        $session->set('pendingOtpLogin', [
            'user_id'    => (int) $user['id'],
            'otp_hash'   => password_hash($otp, PASSWORD_DEFAULT),
            'expires_at' => time() + 300,
            'phone'      => $phone,
        ]);

        $message = "Kode OTP login " . trace_app_name() . ": {$otp}. Berlaku 5 menit.";
        $sent = $this->notification->sendWhatsapp($phone, $message);
        $this->writeAudit((int) $user['id'], 'RequestLoginOtp', 'Users', (int) $user['id'], [
            'ip' => $request->getIPAddress(),
            'wa_sent' => $sent,
        ]);

        return ['success' => true, 'waSent' => $sent];
    }

    public function verifyOtpLogin(string $otp, RequestInterface $request): array
    {
        $pending = $this->resolveSession()->get('pendingOtpLogin');

        if (! is_array($pending) || empty($pending['user_id'])) {
            return ['success' => false, 'errors' => ['otp' => 'Sesi OTP tidak ditemukan. Silakan login ulang.']];
        }

        if ((int) ($pending['expires_at'] ?? 0) < time()) {
            $this->resolveSession()->remove('pendingOtpLogin');
            return ['success' => false, 'errors' => ['otp' => 'OTP sudah kedaluwarsa. Silakan login ulang.']];
        }

        if (! password_verify(trim($otp), (string) ($pending['otp_hash'] ?? ''))) {
            return ['success' => false, 'errors' => ['otp' => 'OTP tidak valid.']];
        }

        $user = $this->userModel->findDetailedById((int) $pending['user_id']);
        if ($user === null) {
            return ['success' => false, 'errors' => ['otp' => 'User tidak ditemukan.']];
        }

        $this->resolveSession()->remove('pendingOtpLogin');
        $this->completeLogin($user, $request);

        return ['success' => true];
    }

    public function attempt(string $login, string $password, RequestInterface $request): bool
    {
        $user = $this->verifyCredentials($login, $password);

        if ($user === null) {
            return false;
        }

        $this->completeLogin($user, $request);

        return true;
    }

    private function completeLogin(array $user, RequestInterface $request): void
    {
        $session = $this->resolveSession();
        $session->regenerate();
        $sessionId = session_id();
        $session->set('authUser', [
            'id'                => (int) $user['id'],
            'full_name'         => $user['full_name'],
            'email'             => $user['email'],
            'username'          => $user['username'],
            'phone'             => $user['phone'] ?? '',
            'role_code'         => $user['role_code'],
            'role_name'         => $user['role_name'],
            'status'            => $user['status'],
            'profile_photo_path'=> $user['profile_photo_path'] ?? null,
        ]);

        $this->userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        if ($sessionId !== '') {
            $this->userSessionModel->where('session_id', $sessionId)->delete();
            $this->userSessionModel->insert([
                'user_id'          => $user['id'],
                'session_id'       => $sessionId,
                'ip_address'       => $request->getIPAddress(),
                'user_agent'       => substr((string) $request->getUserAgent(), 0, 255),
                'last_activity_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->writeAudit((int) $user['id'], 'Login', 'Users', (int) $user['id'], ['ip' => $request->getIPAddress()]);
    }

    public function verifyCredentials(string $login, string $password): ?array
    {
        $user = $this->userModel->findByLogin(trim($login));

        if ($user === null || $user['status'] !== 'Active') {
            return null;
        }

        if (! password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }

    public function currentUser(): ?array
    {
        $sessionUser = $this->resolveSession()->get('authUser');

        if (! is_array($sessionUser) || empty($sessionUser['id'])) {
            return null;
        }

        $freshUser = $this->userModel->findDetailedById((int) $sessionUser['id']);

        if ($freshUser === null) {
            return $sessionUser;
        }

        $authUser = [
            'id'                 => (int) $freshUser['id'],
            'full_name'          => $freshUser['full_name'],
            'email'              => $freshUser['email'],
            'username'           => $freshUser['username'],
            'phone'              => $freshUser['phone'] ?? '',
            'role_code'          => $freshUser['role_code'],
            'role_name'          => $freshUser['role_name'],
            'status'             => $freshUser['status'],
            'profile_photo_path' => $freshUser['profile_photo_path'] ?? null,
        ];

        $this->resolveSession()->set('authUser', $authUser);

        return $authUser;
    }

    public function isLoggedIn(): bool
    {
        return $this->currentUser() !== null;
    }

    public function logout(): void
    {
        $currentUser = $this->currentUser();
        $session     = $this->resolveSession();
        $sessionId   = session_id();

        if ($currentUser !== null) {
            if ($sessionId !== '') {
                $this->userSessionModel->where('session_id', $sessionId)->delete();
            }
            $this->writeAudit((int) $currentUser['id'], 'Logout', 'Users', (int) $currentUser['id']);
        }

        $session->remove('authUser');
        $session->regenerate(true);
    }

    public function writeAudit(?int $userId, string $action, string $entityType, ?int $entityId = null, array $meta = []): void
    {
        $this->auditLogModel->insert([
            'user_id'    => $userId,
            'action'     => $action,
            'entity_type'=> $entityType,
            'entity_id'  => $entityId,
            'meta_json'  => $meta === [] ? null : json_encode($meta, JSON_UNESCAPED_UNICODE),
            'ip_address' => service('request')->getIPAddress(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function canManageReport(array $user, array $report): bool
    {
        if (in_array($user['role_code'], ['Admin', 'Manager'], true)) {
            return true;
        }

        return (int) $user['id'] === (int) $report['created_by_user_id'] || (int) $user['id'] === (int) $report['worker_user_id'];
    }

    private function resolveSession(): Session
    {
        return $this->session ?? service('session');
    }

    /**
     * Menormalisasi nomor HP untuk penyimpanan database.
     * Membersihkan karakter non-digit tanpa mengubah format awalan (62, 08, atau 8) sesuai input user.
     */
    public function normalizePhone(string $phone): string
    {
        // Hanya bersihkan karakter non-digit agar login konsisten (misal: menghapus spasi atau tanda hubung)
        $phone = preg_replace('/[^0-9]/', '', $phone) ?? '';

        if ($phone === '') {
            return '';
        }

        return $phone;
    }
}
