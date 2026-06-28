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
        $workerCategories = array_map(
            fn (array $category): array => array_replace($category, [
                'name' => $this->normalizeWorkerCategoryName((string) ($category['name'] ?? '')),
            ]),
            $this->workerCategoryModel->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll()
        );

        $heavyCategories = $this->heavyEquipmentCategoryModel->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll();
        $heavyCategories = $this->sortHeavyEquipmentCategories($heavyCategories);

        return [
            'areas' => [
                ['code' => 'AreaLanal', 'label' => 'Area Lanal'],
                ['code' => 'AreaSwangi', 'label' => 'Area Swangi'],
                ['code' => 'AreaRpi', 'label' => 'Area RPI'],
                ['code' => 'AreaLaut', 'label' => 'Area Laut'],
                ['code' => 'Lainnya', 'label' => 'Lainnya'],
            ],
            'currentLocations' => ['Area Laut', 'Area Swangi', 'Area Lanal', 'Area RPI', 'Lainnya'],
            'structureLocations' => [
                'PL1',
                'PL2',
                'P22',
                'P23',
                'P24',
                'P25',
                'P26',
                'P27',
                'P28',
                'P29',
                'P30',
                'P31',
                'P32',
                'P33',
                'P34',
                'Fender PL1',
                'Fender PL2',
                'Fender P22',
                'Fender P23',
            ],
            'weatherOptions' => ['Cerah', 'Mendung', 'Gerimis', 'Hujan', 'Badai'],
            'workerUsers'    => $this->userModel->getActiveReportUsers(),
            'workerCategories' => $workerCategories,
            'heavyCategories'  => $heavyCategories,
        ];
    }

    private function sortHeavyEquipmentCategories(array $categories): array
    {
        $order = [
            'tongkang' => 1,
            'boring-machine' => 2,
            'crane' => 3,
            'vibro-hammer' => 4,
            'truck-mixer' => 5,
            'excavator' => 6,
            'loader' => 7,
            'dump-truck' => 8,
        ];

        usort($categories, static function (array $left, array $right) use ($order): int {
            $leftSlug = (string) ($left['slug'] ?? '');
            $rightSlug = (string) ($right['slug'] ?? '');

            return ($order[$leftSlug] ?? 99) <=> ($order[$rightSlug] ?? 99)
                ?: ((int) ($left['sort_order'] ?? 0) <=> (int) ($right['sort_order'] ?? 0));
        });

        return $categories;
    }

    private function normalizeWorkerCategoryName(string $name): string
    {
        return match ($name) {
            'Survey' => 'Surveyor',
            'Pekerja Harian' => 'Operator',
            'Tukang' => 'Welder',
            default => $name,
        };
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
        $payload['areaCode'] = $this->resolveAreaCodeFromCurrentLocation((string) ($payload['currentLocation'] ?? ''));

        $validation = service('validation');
        $rules      = config(\Config\Validation::class)->dailyReport;
        $uploadedPhotos = $this->filterPhotoInputs($files['photos'] ?? []);
        $photoError = $this->validateUploadedPhotos($uploadedPhotos);

        if ($photoError !== null) {
            return ['success' => false, 'errors' => ['photos' => $photoError]];
        }

        if (! $validation->setRules($rules)->run($payload)) {
            return $this->failAndPreservePhotos($validation->getErrors(), $payload, $uploadedPhotos, $actor);
        }

        $currentLocation = $this->resolveCurrentLocation($payload);
        if ($currentLocation === '') {
            return $this->failAndPreservePhotos(['currentLocationManual' => 'Lokasi terkini manual wajib diisi jika memilih Lainnya.'], $payload, $uploadedPhotos, $actor);
        }

        $requiresStructure = trim((string) ($payload['currentLocation'] ?? '')) === 'Area Laut';
        $structureLocation = trim((string) ($payload['structureLocation'] ?? ''));
        $structurePoint    = trim((string) ($payload['structurePoint'] ?? ''));

        if ($requiresStructure && ($structureLocation === '' || $structurePoint === '')) {
            $errors = [];

            if ($structureLocation === '') {
                $errors['structureLocation'] = 'Lokasi struktur wajib dipilih jika Lokasi Terkini Area Laut.';
            }

            if ($structurePoint === '') {
                $errors['structurePoint'] = 'Titik struktur wajib diisi jika Lokasi Terkini Area Laut.';
            }

            return $this->failAndPreservePhotos($errors, $payload, $uploadedPhotos, $actor);
        }

        if (! $requiresStructure) {
            $structureLocation = '';
            $structurePoint    = '';
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

        //commet
        $workerUpdates    = $this->normalizeWorkerUpdates($payload);
        $heavyEquipment   = $this->normalizeHeavyEquipment($payload);
        $realizationItems = $this->normalizeRealizationItems($payload);
        $lightToolRows    = $this->normalizeLightToolRows($payload);
        $realizationText  = $this->buildRealizationSummary($realizationItems, (string) ($payload['realizationSummary'] ?? ''));
        $lightToolText    = $this->buildLightToolSummary($lightToolRows, (string) ($payload['lightToolSummary'] ?? ''));
        if ($uploadedPhotos === [] && $existingPhotos === []) {
            return ['success' => false, 'errors' => ['photos' => 'Minimal satu foto dokumentasi wajib diunggah.']];
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
            'current_location' => $currentLocation,
            'structure_location' => $structureLocation,
            'structure_point' => $structurePoint,
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
            'obstacle_cause' => '',
            'obstacle_impact'=> '',
            'additional_note'=> '',
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

        $this->storePhotosForReport($reportId, $uploadedPhotos, count($existingPhotos));

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
            'location'       => $db->table('ReportLocations')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['current_location' => '', 'structure_location' => '', 'structure_point' => '', 'area_code' => '', 'area_label' => '', 'reason' => ''],
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
            'location'       => $db->table('ReportLocations')->where('daily_report_id', $reportId)->get()->getRowArray() ?? ['current_location' => '', 'structure_location' => '', 'structure_point' => '', 'area_code' => ''],
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
                    'label'       => $this->normalizeWorkerCategoryName((string) $category['name']),
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
        $items          = [];
        $categories     = $this->heavyEquipmentCategoryModel->findAll();
        $quantities     = $payload['heavyEquipment'] ?? [];
        $selections     = $payload['heavyEquipmentSelections'] ?? [];
        $manualInputs   = $payload['heavyEquipmentManual'] ?? [];
        $dropdownSlugs  = array_keys($this->heavyEquipmentDropdownOptions());

        foreach ($categories as $category) {
            if ((int) ($category['is_active'] ?? 0) !== 1) {
                continue;
            }

            if (in_array((string) ($category['slug'] ?? ''), $dropdownSlugs, true)) {
                $selection = trim((string) ($selections[$category['id']] ?? ''));

                if ($selection === 'Lainnya') {
                    $selection = trim((string) ($manualInputs[$category['id']] ?? ''));
                }

                if ($selection !== '') {
                    $items[] = [
                        'category_id' => (int) $category['id'],
                        'label'       => $category['name'] . ' - ' . $selection,
                        'quantity'    => 1,
                        'volume'      => '1',
                        'unit'        => 'unit',
                    ];
                }

                continue;
            }

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

    private function heavyEquipmentDropdownOptions(): array
    {
        return [
            'tongkang' => ['Palmindo', 'PCF-1861', 'PCF-1865', 'Aquaria', 'BDU', 'Pipe Carrier', 'Bayu Bahtera'],
            'boring-machine' => ['SR 405', 'SR 215', 'SR 265', 'XR 280'],
            'crane' => ['Kobelco 7150', 'QUY 150', 'LS 248 RH', 'BM 800 HD', 'SC800', 'Hitachi 275 DC'],
        ];
    }

    private function normalizeRealizationItems(array $payload): array
    {
        $items = [];
        foreach (($payload['realizationItems'] ?? []) as $row) {
            $workItem = $this->resolveRealizationWorkItem($row);
            if ($workItem === '') {
                continue;
            }

            $items[] = [
                'work_item'        => $workItem,
                'unit'             => trim((string) ($row['unit'] ?? '')),
                'plan_text'        => trim((string) ($row['plan_text'] ?? '')),
                'realization_text' => trim((string) ($row['realization_text'] ?? '')),
                'deviation_text'   => $this->calculateDeviationText($row),
                'partner'          => $this->resolveRealizationPartner($row),
            ];
        }

        return $items;
    }

    private function resolveRealizationWorkItem(array $row): string
    {
        $workItem = trim((string) ($row['work_item'] ?? ''));

        if ($workItem === 'Lainnya') {
            return trim((string) ($row['work_item_manual'] ?? ''));
        }

        return $workItem;
    }

    private function calculateDeviationText(array $row): string
    {
        $plan        = $this->readNumericText((string) ($row['plan_text'] ?? ''));
        $realization = $this->readNumericText((string) ($row['realization_text'] ?? ''));

        if ($plan === null || $realization === null) {
            return '';
        }

        return $this->formatNumericText($realization - $plan);
    }

    private function readNumericText(string $value): ?float
    {
        if (! preg_match('/-?\d+(?:[.,]\d+)?/', str_replace(' ', '', $value), $matches)) {
            return null;
        }

        return (float) str_replace(',', '.', $matches[0]);
    }

    private function formatNumericText(float $value): string
    {
        $formatted = rtrim(rtrim(number_format($value, 4, '.', ''), '0'), '.');

        return str_replace('.', ',', $formatted === '-0' ? '0' : $formatted);
    }

    private function resolveRealizationPartner(array $row): string
    {
        $partner = trim((string) ($row['partner'] ?? ''));

        if ($partner === 'Lainnya') {
            return trim((string) ($row['partner_manual'] ?? ''));
        }

        return $partner;
    }

    private function normalizeLightToolRows(array $payload): array
    {
        $items = [];
        $counterLabels = [
            'genset' => 'Genset',
            'winch' => 'Winch',
            'guide-beam' => 'Guide Beam',
            'trafo-las' => 'Trafo Las',
            'bar-bender' => 'Bar Bender',
            'bar-cutter' => 'Bar Cutter',
            'gerinda' => 'Gerinda',
            'alat-surveying' => 'Alat Surveying (per Set)',
        ];
        $dropdownLabels = [
            'core-barrel' => 'Core Barrel',
            'bucket-barrel' => 'Bucket Barrel',
        ];

        foreach ($counterLabels as $slug => $label) {
            $quantity = trim((string) ($payload['lightToolCounts'][$slug] ?? ''));

            if ($quantity !== '' && (float) str_replace(',', '.', $quantity) > 0) {
                $items[] = [
                    'tool_label' => $label,
                    'volume'     => $quantity,
                    'unit'       => 'unit',
                ];
            }
        }

        foreach ($dropdownLabels as $slug => $label) {
            $selections = $payload['lightToolSelections'][$slug] ?? [];
            $manualSelections = $payload['lightToolManual'][$slug] ?? [];

            if (! is_array($selections)) {
                $selections = [$selections];
            }

            if (! is_array($manualSelections)) {
                $manualSelections = [$manualSelections];
            }

            foreach (array_values($selections) as $index => $rawSelection) {
                $selection = trim((string) $rawSelection);

                if ($selection === 'Lainnya') {
                    $selection = trim((string) ($manualSelections[$index] ?? ''));
                }

                if ($selection === '') {
                    continue;
                }

                $items[] = [
                    'tool_label' => $label . ' - ' . $selection,
                    'volume'     => '1',
                    'unit'       => 'unit',
                ];
            }
        }

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

    private function validateUploadedPhotos(array $uploadedPhotos): ?string
    {
        foreach ($uploadedPhotos as $photo) {
            if (! $photo->isValid()) {
                return 'Salah satu file foto tidak valid.';
            }

            $extension = strtolower((string) $photo->getClientExtension());
            if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                return 'Format foto hanya boleh JPG, JPEG, PNG, atau WEBP.';
            }

            if (($photo->getSizeByUnit('kb') ?? 0) > 5120) {
                return 'Ukuran setiap foto maksimal 5 MB.';
            }
        }

        return null;
    }

    private function failAndPreservePhotos(array $errors, array $payload, array $uploadedPhotos, array $actor): array
    {
        $result = ['success' => false, 'errors' => $errors];
        $reportId = $this->preserveUploadedPhotosForFailedSubmission($payload, $uploadedPhotos, $actor);

        if ($reportId !== null) {
            $result['reportId'] = $reportId;
        }

        return $result;
    }

    private function preserveUploadedPhotosForFailedSubmission(array $payload, array $uploadedPhotos, array $actor): ?int
    {
        if ($uploadedPhotos === []) {
            return null;
        }

        try {
            $reportId = (int) ($payload['reportId'] ?? 0);
            $existingPhotos = [];

            if ($reportId > 0) {
                $existing = $this->getReportBundle($reportId);
                if ($existing === null || ! $this->authService->canManageReport($actor, $existing['report'])) {
                    return null;
                }

                $existingPhotos = $existing['photos'] ?? [];
            } else {
                $reportDate = $this->resolveDraftReportDate($payload);
                $workerUserId = $this->resolveDraftWorkerUserId($payload, $actor);

                $matchedReport = $this->dailyReportModel
                    ->where('report_date', $reportDate)
                    ->where('worker_user_id', $workerUserId)
                    ->first();

                if ($matchedReport !== null) {
                    $matchedBundle = $this->getReportBundle((int) $matchedReport['id']);

                    if ($matchedBundle === null || ! $this->authService->canManageReport($actor, $matchedBundle['report'])) {
                        return null;
                    }

                    $reportId = (int) $matchedReport['id'];
                    $existingPhotos = $matchedBundle['photos'] ?? [];
                } else {
                    $reportId = (int) $this->dailyReportModel->insert([
                        'report_code'         => $this->generateReportCode(),
                        'report_date'         => $reportDate,
                        'worker_user_id'      => $workerUserId,
                        'created_by_user_id'  => (int) $actor['id'],
                        'weather_code'        => $this->resolveDraftWeatherCode($payload),
                        'realization_summary' => trim((string) ($payload['realizationSummary'] ?? '')),
                        'status'              => 'Draft',
                    ], true);
                }
            }

            if ($reportId <= 0) {
                return null;
            }

            $this->storePhotosForReport($reportId, $uploadedPhotos, count($existingPhotos));

            return $reportId;
        } catch (\Throwable) {
            return null;
        }
    }

    private function resolveDraftReportDate(array $payload): string
    {
        $reportDate = trim((string) ($payload['reportDate'] ?? ''));
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $reportDate);

        if ($date !== false && $date->format('Y-m-d') === $reportDate) {
            return $reportDate;
        }

        return date('Y-m-d');
    }

    private function resolveDraftWorkerUserId(array $payload, array $actor): int
    {
        $workerUserId = (int) ($payload['workerUserId'] ?? 0);

        return $workerUserId > 0 ? $workerUserId : (int) $actor['id'];
    }

    private function resolveDraftWeatherCode(array $payload): string
    {
        $weatherCode = trim((string) ($payload['weatherCode'] ?? ''));

        return in_array($weatherCode, ['Cerah', 'Mendung', 'Gerimis', 'Hujan', 'Badai'], true) ? $weatherCode : 'Cerah';
    }

    private function storePhotosForReport(int $reportId, array $uploadedPhotos, int $existingCount): void
    {
        foreach ($uploadedPhotos as $index => $photo) {
            $stored = $this->storePhoto($photo);
            $this->reportPhotoModel->insert([
                'daily_report_id' => $reportId,
                'file_name'       => $stored['fileName'],
                'file_path'       => $stored['filePath'],
                'mime_type'       => $stored['mimeType'],
                'file_size'       => $stored['fileSize'],
                'sort_order'      => $existingCount + $index + 1,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
        }
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

    private function resolveAreaCodeFromCurrentLocation(string $currentLocation): string
    {
        return match (trim($currentLocation)) {
            'Area Lanal'  => 'AreaLanal',
            'Area Swangi' => 'AreaSwangi',
            'Area RPI'    => 'AreaRpi',
            'Area Laut'   => 'AreaLaut',
            default       => 'Lainnya',
        };
    }

    private function resolveCurrentLocation(array $payload): string
    {
        $selected = trim((string) ($payload['currentLocation'] ?? ''));

        if ($selected === 'Lainnya') {
            return trim((string) ($payload['currentLocationManual'] ?? ''));
        }

        return $selected;
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
