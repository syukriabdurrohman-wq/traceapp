<?php

namespace App\Controllers;

use App\Services\DashboardService;
use App\Services\DailyReportService;

class ManagerController extends BaseController
{
    public function __construct(
        private readonly DashboardService $dashboardService = new DashboardService(),
        private readonly DailyReportService $dailyReportService = new DailyReportService(),
    ) {
    }

    public function index(): string
    {
        return $this->page('Manager/OverviewPage', [
            'pageTitle' => 'Manager View',
            'overview'  => $this->dashboardService->getManagerOverview(),
            'reports'   => array_slice($this->dailyReportService->getReports(), 0, 10),
        ]);
    }
}
