<?php

namespace App\Controllers;

use App\Services\DashboardService;

class DashboardController extends BaseController
{
    public function __construct(
        private readonly DashboardService $dashboardService = new DashboardService(),
    ) {
    }

    public function index(): string
    {
        return $this->page('Dashboard/HomePage', [
            'pageTitle' => 'Dashboard',
            'pageClass' => 'HomePage',
            'homeData'  => $this->dashboardService->getHomeData(),
        ]);
    }
}
