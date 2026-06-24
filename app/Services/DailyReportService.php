<?php

namespace App\Services;

use App\Libraries\Notification;
use App\Models\DailyReportModel;
use App\Models\HeavyEquipmentCategoryModel;
use App\Models\ReportHeavyEquipmentUsageModel;
use App\Models\ReportPhotoModel;
use App\Models\ReportWorkerUpdateModel;
use App\Models\UserModel;
use App\Models\WorkerCategoryModel;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Database;

class DailyReportService
{
    public function __construct(
        private readonly DailyReportModel $dailyReportModel = new DailyReportModel(),
        private readonly ReportPhotoModel $reportPhotoModel = new ReportPhotoModel(),
        private readonly WorkerCategoryModel $workerCategoryModel = new WorkerCategoryModel(),
        private readonly ReportWorkerUpdateModel $reportWorkerUpdateModel = new ReportWorkerUpdateModel(),
        private readonly HeavyEquipmentCategoryModel $heavyEquipmentCategoryModel = new HeavyEquipmentCategoryModel(),
        private readonly ReportHeavyEquipmentUsageModel $reportHeavyEquipmentUsageModel = new ReportHeavyEquipmentUsageModel(),
        private readonly UserModel $userModel = new UserModel(),
        private readonly ReportSummaryService $reportSummaryService = new ReportSummaryService(),
        private readonly AuthService $authService = new AuthService(),
        private readonly Notification $notification = new Notification(),
    ) {
    }

    public function getFormOptions(): array
    {
        return [
            'areas' => [
                ['code' => 'AreaLanal', 'label' => 'Area Lanal'],
                ['code' => 'AreaSwangi', 'label' => 'Area Swangi'],
                ['code' => 'AreaRpi', 'label' => 'Area RPI'],
                ['code' => 'AreaLaut', 'label' => 'Area Laut'],
                ['code' => 'Lainnya', 'label' => 'Lainnya'],
            ],
            'weatherOptions' => ['Cerah', 'Hujan', 'Mendung'],
            'workerUsers'    => $this->userModel->getActiveReportUsers(),
            'workerCategories' => $this->workerCategoryModel->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll(),
            'heavyCategories'  => $this->heavyEquipmentCategoryModel->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll(),
        ];
    }

    public function getReports(array $filters = []): array
    {
        $builder = $this->dailyReportModel
            ->select('DailyReports.*, Users.full_name as worker_name, ReportLocations.area_label, ReportLocations.current_location')
            ->join('Users', 'Users.id = DailyReports.worker_user_id', 'left')
            ->join('ReportLocations', 'ReportLocations.daily_report_id = DailyReports.id', 'left')
            ->orderBy('DailyReports.report_date', 'DESC')
            ->orderBy('DailyReports.created_at', 'DESC');

        if (($filters['reportDate'] ?? '') !== '') {
            $builder->where('DailyReports.report_date', $filters['reportDate']);
        }

        if (($filters['workerUserId'] ?? '') !== '') {
            $builder->where('DailyReports.worker_user_id', (int) $filters['workerUserId']);
        }

        if (($filters['status'] ?? '') !== '') {
            $builder->where('DailyReports.status', $filters['status']);
        }

        return $builder->findAll();
    }

    public function saveDraftFromRequest(array $payload, array $files, array $actor): array
    {
        $validation = service('validation');
        $rules      = config(\Config\Validation::class)->dailyReport;

        if (! $validation->setRules($rules)->run($payload)) {
            return ['success' => false, 'errors' => $validation->getErrors()];
        }

        $reportId      = (int) ($payload['reportId'] ?? 0);
        $existing      = $reportId > 0 ? $this->getReportBundle($reportId) : null;
        $existingPhotos= $existing['photos'] ?? [];

        if ($reportId === 0) {
            $matchedReport = $this->dailyReportModel
                ->where('report_date', $payload['reportDate'])
                ->where('worker_user_id', (int) $payload['workerUserId'])
                ->first();

            if ($matchedReport !== null) {
                $matchedBundle = $this->getReportBundle((int) $matchedReport['id']);

                if ($matchedBundle !== null && $this->authService->canManageReport($actor, $matchedBundle['report'])) {
                    $reportId       = (int) $matchedReport['id'];
                    $existing       = $matchedBundle;
                    $existingPhotos = $existing['photos'] ?? [];
                }
            }
        }

        if ($existing !== null && ! $this->authService->canManageReport($actor, $existing['report'])) {
            return ['success' => false, 'errors' => ['authorization' => 'Anda tidak berhak mengubah laporan ini.']];
        }

        $workerUpdates    = $this->normalizeWorkerUpdates($payload);
        $heavyEquipment   = $this->normalizeHeavyEquipment($payload);
        $realizationItems = $this->normalizeRealizationItems($payload);
        $lightToolRows    = $this->normalizeLightToolRows($payload);
        $realizationText  = $this->buildRealizationSummary($realizationItems, (string) ($payload['realizationSummary'] ?? ''));
        $lightToolText    = $this->buildLightToolSummary($lightToolRows, (string) ($payload['lightToolSummary'] ?? ''));
        $uploadedPhotos = $this->filterPhotoInputs($files['photos'] ?? []);

        if ($uploadedPhotos === [] && $existingPhotos === []) {
            return ['success' => false, 'errors' => ['photos' => 'Minimal satu foto dokumentasi wajib diunggah.']];
        }

        foreach ($uploadedPhotos as $photo) {
            if (! $photo->isValid()) {
                return ['success' => false, 'errors' => ['photos' => 'Salah satu file foto tidak valid.']];
            }

            $extension = strtolower((string) $photo->getClientExtension());
            if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                return ['success' => false, 'errors' => ['photos' => 'Format foto hanya boleh JPG, JPEG, PNG, atau WEBP.']];
            }

            if (($photo->getSizeByUnit('kb') ?? 0) > 5120) {
                return ['success' => false, 'errors' => ['photos' => 'Ukuran setiap foto maksimal 5 MB.']];
            }
        }

        $db = Database::connect();
        $db->transStart();

        if ($reportId > 0) {
            $reportId = (int) $this->dailyReportModel->update($reportId, [
                'report_date'         => $payload['reportDate'],
                'worker_user_id'      => (int) $payload['workerUserId'],
                'created_by_user_id'  => $existing['report']['created_by_user_id'],
                'weather_code'        => $payload['weatherCode'],
                'realization_summary' => $realizationText,
                'status'              => 'Draft',
            ]);
            $reportId = (int) ($payload['reportId'] ?? 0);
        } else {
            $reportId = (int) $this->dailyReportModel->insert([
                'report_code'         => $this->generateReportCode(),
                'report_date'         => $payload['reportDate'],
                'worker_user_id'      => (int) $payload['workerUserId'],
                'created_by_user_id'  => (int) $actor['id'],
                'weather_code'        => $payload['weatherCode'],
                'realization_summary' => $realizationText,
                'status'              => 'Draft',
            ], true);
        }

        $this->upsertSingleTable('ReportLocations', 'daily_report_id', $reportId, [
            'current_location' => trim((string) $payload['currentLocation']),
            'area_code'        => $payload['areaCode'],
            'area_label'       => $this->resolveAreaLabel($payload['areaCode']),
            'reason'           => trim((string) ($payload['locationReason'] ?? '')),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);

        $this->upsertSingleTable('ReportMaterialSummaries', 'daily_report_id', $reportId, [
            'summary_text' => trim((string) $payload['materialSummary']),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->upsertSingleTable('ReportToolSummaries', 'daily_report_id', $reportId, [
            'summary_text' => $lightToolText,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->upsertSingleTable('ReportObstacleSummaries', 'daily_report_id', $reportId, [
            'obstacle_shape' => trim((string) $payload['obstacleShape']),
            'obstacle_cause' => trim((string) $payload['obstacleCause']),
            'obstacle_impact'=> trim((string) $payload['obstacleImpact']),
            'additional_note'=> trim((string) ($payload['obstacleNote'] ?? '')),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        $this->upsertSingleTable('ReportTomorrowPlans', 'daily_report_id', $reportId, [
            'summary_text' => trim((string) $payload['tomorrowPlan']),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $overtimeEnabled = (int) ($payload['overtimeEnabled'] ?? 0) === 1 ? 1 : 0;
        $this->upsertSingleTable('ReportOvertimes', 'daily_report_id', $reportId, [
            'is_enabled'   => $overtimeEnabled,
            'start_time'   => $overtimeEnabled ? (string) ($payload['overtimeStart'] ?? '18:00') : null,
            'end_time'     => $overtimeEnabled ? (string) ($payload['overtimeEnd'] ?? '24:00') : null,
            'summary_text' => trim((string) ($payload['overtimeSummary'] ?? '')),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->reportWorkerUpdateModel->where('daily_report_id', $reportId)->delete();
        foreach ($workerUpdates as $item) {
            $this->reportWorkerUpdateModel->insert([
                'daily_report_id'   => $reportId,
                'worker_category_id'=> $item['category_id'],
                'category_label'    => $item['label'],
                'quantity'          => $item['quantity'],
            ]);
        }

        $this->reportHeavyEquipmentUsageModel->where('daily_report_id', $reportId)->delete();
        foreach ($heavyEquipment as $item) {
            $this->reportHeavyEquipmentUsageModel->insert([
                'daily_report_id'            => $reportId,
                'heavy_equipment_category_id'=> $item['category_id'],
                'equipment_label'            => $item['label'],
                'quantity'                   => $item['quantity'],
                'volume'                     => $item['volume'],
                'unit'                       => $item['unit'],
            ]);
        }

        $db->table('ReportRealizationItems')->where('daily_report_id', $reportId)->delete();
        foreach ($realizationItems as $index => $item) {
            $db->table('ReportRealizationItems')->insert([
                'daily_report_id' => $reportId,
                'work_item'       => $item['work_item'],
                'unit'            => $item['unit'],
                'plan_text'       => $item['plan_text'],
                'realization_text'=> $item['realization_text'],
                'deviation_text'  => $item['deviation_text'],
                'partner'         => $item['partner'],
                'sort_order'      => $index + 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
        }

        $db->table('ReportLightToolUsages')->where('daily_report_id', $reportId)->delete();
        foreach ($lightToolRows as $index => $item) {
            $db->table('ReportLightToolUsages')->insert([
                'daily_report_id' => $reportId,
                'tool_label'      => $item['tool_label'],
                'volume'          => $item['volume'],
                'unit'            => $item['unit'],
                'sort_order'      => $index + 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
        }

        foreach ($uploadedPhotos as $index => $photo) {
            $stored = $this->storePhoto($photo);
            $this->reportPhotoModel->insert([
                'daily_report_id' => $reportId,
                'file_name'       => $stored['fileName'],
                'file_path'       => $stored['filePath'],
                'mime_type'       => $stored['mimeType'],
                'file_size'       => $stored['fileSize'],
                'sort_order'      => count($existingPhotos) + $index + 1,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return ['success' => false, 'errors' => ['database' => 'Gagal menyimpan draft laporan.']];
        }

        $this->authService->writeAudit((int) $actor['id'], 'SaveDraft', 'DailyReports', $reportId);

        return ['success' => true, 'reportId' => $reportId];
    }

    public function submit(int $reportId, array $actor, bool $autoSendWa = false): array
    {
        $bundle = $this->getReportBundle($reportId);

        if ($bundle === null) {
            return ['success' => false, 'message' => 'Laporan tidak ditemukan.'];
        }

        if (! $this->authService->canManageReport($actor, $bundle['report'])) {
            return ['success' => false, 'message' => 'Anda tidak memiliki akses untuk submit laporan ini.'];
        }

        $updateData = [
            'status' => 'Submitted',
        ];
        
        $bundle['report']['status'] = 'Submitted';

        // Setel submitted_at jika baru pertama kali atau diedit jika update paska submit
        if (empty($bundle['report']['submitted_at'])) {
            $updateData['submitted_at'] = date('Y-m-d H:i:s');
            $bundle['report']['submitted_at'] = $updateData['submitted_at'];
        } else {
            $updateData['edited_at'] = date('Y-m-d H:i:s');
            $bundle['report']['edited_at'] = $updateData['edited_at'];
        }

        $summary = $this->reportSummaryService->build($bundle);
        $updateData['whatsapp_summary'] = $summary;

        $this->dailyReportModel->update($reportId, $updateData);

        $waSent = false;
        if ($autoSendWa) {
            $waSent = $this->notification->sendWhatsapp((string) env('fonnte.groupId'), $summary);
        }

        $this->authService->writeAudit((int) $actor['id'], 'SubmitReport', 'DailyReports', $reportId);

        return [
            'success'     => true,
            'summary'     => $summary,
            'waRequested' => $autoSendWa,
            'waSent'      => $waSent,
        ];
    }

    public function getReportBundle(int $reportId): ?array
    {
        $report = $this->dailyReportModel
            ->select('DailyReports.*, worker.full_name as worker_name, creator.full_name as creator_name')
            ->join('Users as worker', 'worker.id = DailyReports.worker_user_id', 'left')
            ->join('Users as creator', 'creator.id = DailyReports.created_by_user_id', 'left')
            ->where('DailyReports.id', $reportId)
            ->first();

        if ($report === null) {
            return null;
        }

        $db = Database::connect();

        $worker = $this->userModel->findDetailedById((int) $report['worker_user_id']);

        return [
            'report'         => $report,
            'worker'         => $worker ?? ['full_name' => $report['worker_name']],
            'location'       => $db->table('ReportLocations')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['current_location' => '', 'area_label' => '', 'reason' => ''],
            'material'       => $db->table('ReportMaterialSummaries')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['summary_text' => ''],
            'tool'           => $db->table('ReportToolSummaries')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['summary_text' => ''],
            'obstacle'       => $db->table('ReportObstacleSummaries')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['obstacle_shape' => '', 'obstacle_cause' => '', 'obstacle_impact' => '', 'additional_note' => ''],
            'tomorrow'       => $db->table('ReportTomorrowPlans')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['summary_text' => ''],
            'overtime'       => $db->table('ReportOvertimes')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['is_enabled' => 0, 'start_time' => '', 'end_time' => '', 'summary_text' => ''],
            'photos'         => $this->reportPhotoModel->where('daily_report_id', $reportId)->orderBy('sort_order', 'ASC')->findAll(),
            'workerUpdates'  => $this->reportWorkerUpdateModel->where('daily_report_id', $reportId)->orderBy('quantity', 'DESC')->findAll(),
            'heavyEquipment' => $this->reportHeavyEquipmentUsageModel->where('daily_report_id', $reportId)->orderBy('quantity', 'DESC')->findAll(),
            'realizationItems' => $db->table('ReportRealizationItems')->where('daily_report_id', $reportId)->orderBy('sort_order', 'ASC')->get()->getResultArray(),
            'lightTools'     => $db->table('ReportLightToolUsages')->where('daily_report_id', $reportId)->orderBy('sort_order', 'ASC')->get()->getResultArray(),
            'checklist'      => $this->buildChecklist($reportId),
        ];
    }

    public function buildChecklist(int $reportId): array
    {
        $bundle = $this->getReportBundleWithoutChecklist($reportId);

        return [
            ['label' => 'Update Lokasi Pekerjaan', 'done' => trim((string) $bundle['location']['current_location']) !== ''],
            ['label' => 'Update Dokumentasi Pekerjaan', 'done' => $bundle['photos'] !== []],
            ['label' => 'Update Kondisi Cuaca', 'done' => trim((string) $bundle['report']['weather_code']) !== ''],
            ['label' => 'Update Pekerja', 'done' => $bundle['workerUpdates'] !== []],
            ['label' => 'Update Realisasi Pekerjaan', 'done' => ($bundle['realizationItems'] ?? []) !== [] || trim((string) $bundle['report']['realization_summary']) !== ''],
            ['label' => 'Update Alat Berat', 'done' => $bundle['heavyEquipment'] !== []],
            ['label' => 'Update Alat Kerja Ringan', 'done' => ($bundle['lightTools'] ?? []) !== [] || trim((string) $bundle['tool']['summary_text']) !== ''],
            ['label' => 'Update Material & Bahan', 'done' => trim((string) $bundle['material']['summary_text']) !== ''],
            ['label' => 'Update Kendala Pekerjaan', 'done' => trim((string) $bundle['obstacle']['obstacle_shape']) !== ''],
            ['label' => 'Update Rencana Pekerjaan Esok', 'done' => trim((string) $bundle['tomorrow']['summary_text']) !== ''],
            ['label' => 'Update Jam Lembur', 'done' => array_key_exists('is_enabled', $bundle['overtime'])],
        ];
    }

    public function buildChecklistSummary(int $reportId): array
    {
        $items = $this->buildChecklist($reportId);

        return [
            'items'      => $items,
            'doneCount'  => count(array_filter($items, static fn (array $item): bool => $item['done'])),
            'totalCount' => count($items),
        ];
    }

    private function getReportBundleWithoutChecklist(int $reportId): array
    {
        $db     = Database::connect();
        $report = $this->dailyReportModel->where('id', $reportId)->first();

        return [
            'report'         => $report ?? [],
            'location'       => $db->table('ReportLocations')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['current_location' => ''],
            'material'       => $db->table('ReportMaterialSummaries')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['summary_text' => ''],
            'tool'           => $db->table('ReportToolSummaries')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['summary_text' => ''],
            'obstacle'       => $db->table('ReportObstacleSummaries')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['obstacle_shape' => ''],
            'tomorrow'       => $db->table('ReportTomorrowPlans')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['summary_text' => ''],
            'overtime'       => $db->table('ReportOvertimes')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['is_enabled' => 0],
            'photos'         => $this->reportPhotoModel->where('daily_report_id', $reportId)->findAll(),
            'workerUpdates'  => $this->reportWorkerUpdateModel->where('daily_report_id', $reportId)->findAll(),
            'heavyEquipment' => $this->reportHeavyEquipmentUsageModel->where('daily_report_id', $reportId)->findAll(),
            'realizationItems' => $db->table('ReportRealizationItems')->where('daily_report_id', $reportId)->get()->getResultArray(),
            'lightTools'     => $db->table('ReportLightToolUsages')->where('daily_report_id', $reportId)->get()->getResultArray(),
        ];
    }

    private function normalizeWorkerUpdates(array $payload): array
    {
        $items      = [];
        $categories = $this->workerCategoryModel->findAll();
        $quantities = $payload['workerUpdates'] ?? [];

        foreach ($categories as $category) {
            $quantity = (int) ($quantities[$category['id']] ?? 0);
            if ($quantity > 0) {
                $items[] = [
                    'category_id' => (int) $category['id'],
                    'label'       => $category['name'],
                    'quantity'    => $quantity,
                ];
            }
        }

        $customLabel    = trim((string) ($payload['workerCustomLabel'] ?? ''));
        $customQuantity = (int) ($payload['workerCustomQuantity'] ?? 0);
        if ($customLabel !== '' && $customQuantity > 0) {
            $items[] = [
                'category_id' => null,
                'label'       => $customLabel,
                'quantity'    => $customQuantity,
            ];
        }

        foreach (($payload['workerCustomRows'] ?? []) as $row) {
            $label = trim((string) ($row['label'] ?? ''));
            $quantity = (int) ($row['quantity'] ?? 0);
            if ($label !== '' && $quantity > 0) {
                $items[] = [
                    'category_id' => null,
                    'label'       => $label,
                    'quantity'    => $quantity,
                ];
            }
        }

        return $items;
    }

    private function normalizeHeavyEquipment(array $payload): array
    {
        $items      = [];
        $categories = $this->heavyEquipmentCategoryModel->findAll();
        $quantities = $payload['heavyEquipment'] ?? [];

        foreach ($categories as $category) {
            $quantity = (int) ($quantities[$category['id']] ?? 0);
            if ($quantity > 0) {
                $items[] = [
                    'category_id' => (int) $category['id'],
                    'label'       => $category['name'],
                    'quantity'    => $quantity,
                    'volume'      => (string) $quantity,
                    'unit'        => 'unit',
                ];
            }
        }

        $customLabel    = trim((string) ($payload['heavyCustomLabel'] ?? ''));
        $customQuantity = (int) ($payload['heavyCustomQuantity'] ?? 0);
        if ($customLabel !== '' && $customQuantity > 0) {
            $items[] = [
                'category_id' => null,
                'label'       => $customLabel,
                'quantity'    => $customQuantity,
                'volume'      => (string) $customQuantity, // mem-force valuenya jadi keterangan jumlah
                'unit'        => 'unit',
            ];
        }

        foreach (($payload['heavyCustomRows'] ?? []) as $row) {
            $label = trim((string) ($row['label'] ?? ''));
            $quantity = (int) ($row['quantity'] ?? 0);
            if ($label !== '' && $quantity > 0) {
                $items[] = [
                    'category_id' => null,
                    'label'       => $label,
                    'quantity'    => $quantity,
                    'volume'      => (string) $quantity, // mem-force valuenya jadi keterangan jumlah
                    'unit'        => 'unit',             // otomatis selalu 'unit'
                ];
            }
        }

        return $items;
    }

    private function normalizeRealizationItems(array $payload): array
    {
        $items = [];
        foreach (($payload['realizationItems'] ?? []) as $row) {
            $workItem = trim((string) ($row['work_item'] ?? ''));
            if ($workItem === '') {
                continue;
            }

            $items[] = [
                'work_item'        => $workItem,
                'unit'             => trim((string) ($row['unit'] ?? '')),
                'plan_text'        => trim((string) ($row['plan_text'] ?? '')),
                'realization_text' => trim((string) ($row['realization_text'] ?? '')),
                'deviation_text'   => trim((string) ($row['deviation_text'] ?? '')),
                'partner'          => trim((string) ($row['partner'] ?? '')),
            ];
        }

        return $items;
    }

    private function normalizeLightToolRows(array $payload): array
    {
        $items = [];
        foreach (($payload['lightTools'] ?? []) as $row) {
            $label = trim((string) ($row['tool_label'] ?? ''));
            if ($label === '') {
                continue;
            }

            $items[] = [
                'tool_label' => $label,
                'volume'     => trim((string) ($row['volume'] ?? '')),
                'unit'       => trim((string) ($row['unit'] ?? '')),
            ];
        }

        return $items;
    }

    private function buildRealizationSummary(array $items, string $fallback): string
    {
        if ($items === []) {
            return trim($fallback);
        }

        return implode("\n", array_map(static function (array $item): string {
            return trim($item['work_item'] . ' | Sat: ' . $item['unit'] . ' | Rencana: ' . $item['plan_text'] . ' | Realisasi: ' . $item['realization_text'] . ' | Deviasi: ' . $item['deviation_text'] . ' | Rekanan: ' . $item['partner']);
        }, $items));
    }

    private function buildLightToolSummary(array $items, string $fallback): string
    {
        if ($items === []) {
            return trim($fallback);
        }

        return implode("\n", array_map(static function (array $item): string {
            return trim($item['tool_label'] . ' | Volume: ' . $item['volume'] . ' ' . $item['unit']);
        }, $items));
    }

    /**
     * @param array<int|string, mixed> $rawPhotos
     * @return UploadedFile[]
     */
    private function filterPhotoInputs(array $rawPhotos): array
    {
        $photos = [];

        foreach ($rawPhotos as $photo) {
            if ($photo instanceof UploadedFile && $photo->getClientName() !== '') {
                $photos[] = $photo;
            }
        }

        return $photos;
    }

    private function storePhoto(UploadedFile $photo): array
    {
        $targetDirectory = FCPATH . 'Uploads/Reports';
        if (! is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0775, true);
        }

        $newName = $photo->getRandomName();
        $photo->move($targetDirectory, $newName);

        $file = new File($targetDirectory . DIRECTORY_SEPARATOR . $newName);

        return [
            'fileName' => $newName,
            'filePath' => 'Uploads/Reports/' . $newName,
            'mimeType' => $file->getMimeType(),
            'fileSize' => $file->getSize(),
        ];
    }

    private function resolveAreaLabel(string $code): string
    {
        return match ($code) {
            'AreaLanal'  => 'Area Lanal',
            'AreaSwangi' => 'Area Swangi',
            'AreaRpi'    => 'Area RPI',
            'AreaLaut'   => 'Area Laut',
            default      => 'Lainnya',
        };
    }

    private function upsertSingleTable(string $table, string $keyColumn, int $keyValue, array $data): void
    {
        $db       = Database::connect();
        $existing = $db->table($table)->where($keyColumn, $keyValue)->get()->getRowArray();

        if ($existing === null) {
            $data[$keyColumn] = $keyValue;
            $data['created_at'] = date('Y-m-d H:i:s');
            $db->table($table)->insert($data);

            return;
        }

        $db->table($table)->where($keyColumn, $keyValue)->update($data);
    }

    private function generateReportCode(): string
    {
        return 'RPT-' . date('YmdHis') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    }
}