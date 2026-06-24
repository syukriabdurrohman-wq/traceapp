<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\DailyReportService;
use App\Services\DashboardService;

class ReportApiController extends BaseController
{
    public function __construct(
        private readonly DashboardService $dashboardService = new DashboardService(),
        private readonly DailyReportService $dailyReportService = new DailyReportService(),
    ) {
    }

    public function today()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->dashboardService->getHomeData(),
        ]);
    }

    public function detail(int $reportId)
    {
        $bundle = $this->dailyReportService->getReportBundle($reportId);

        if ($bundle === null) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Laporan tidak ditemukan.',
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $bundle,
        ]);
    }
}
