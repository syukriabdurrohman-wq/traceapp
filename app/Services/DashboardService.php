<?php

namespace App\Services;

use App\Models\DailyReportModel;
use App\Models\UserModel;

class DashboardService
{
    public function __construct(
        private readonly DailyReportModel $dailyReportModel = new DailyReportModel(),
        private readonly UserModel $userModel = new UserModel(),
    ) {
    }

    public function getHomeData(): array
    {
        $today       = date('Y-m-d');
        $reportUsers = $this->userModel->getActiveReportUsers();
        $submitted   = $this->dailyReportModel
            ->select('worker_user_id')
            ->where('report_date', $today)
            ->where('status', 'Submitted')
            ->findAll();

        $submittedIds = array_map(static fn (array $row): int => (int) $row['worker_user_id'], $submitted);

        $statusBoard = array_map(static function (array $user) use ($submittedIds): array {
            $isDone = in_array((int) $user['id'], $submittedIds, true);

            return [
                'name'   => $user['full_name'],
                'status' => $isDone ? 'Sudah Mengisi Laporan Harian' : 'Belum Mengisi Laporan Harian',
                'done'   => $isDone,
            ];
        }, $reportUsers);

        $leaderboard = $this->dailyReportModel
            ->select('Users.full_name, COUNT(DailyReports.id) as total_report')
            ->join('Users', 'Users.id = DailyReports.worker_user_id', 'left')
            ->where('DailyReports.status', 'Submitted')
            ->where('DailyReports.report_date >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('DailyReports.worker_user_id, Users.full_name')
            ->orderBy('total_report', 'DESC')
            ->findAll(5);

        $latestReport = $this->dailyReportModel
            ->select('DailyReports.*, Users.full_name')
            ->join('Users', 'Users.id = DailyReports.worker_user_id', 'left')
            ->where('DailyReports.status', 'Submitted')
            ->orderBy('DailyReports.report_date', 'DESC')
            ->first();

        return [
            'today'          => $today,
            'reportUsers'    => $reportUsers,
            'submittedCount' => count($submittedIds),
            'pendingCount'   => max(count($reportUsers) - count($submittedIds), 0),
            'statusBoard'    => $statusBoard,
            'leaderboard'    => $leaderboard,
            'latestReport'   => $latestReport,
        ];
    }

    public function getManagerOverview(): array
    {
        $trend = $this->dailyReportModel
            ->select('report_date, COUNT(id) as total_report')
            ->where('status', 'Submitted')
            ->where('report_date >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('report_date')
            ->orderBy('report_date', 'ASC')
            ->findAll();

        $weatherSummary = $this->dailyReportModel
            ->select('weather_code, COUNT(id) as total')
            ->where('status', 'Submitted')
            ->where('report_date >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('weather_code')
            ->findAll();

        return [
            'trend'          => $trend,
            'weatherSummary' => $weatherSummary,
        ];
    }
}
