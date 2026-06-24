<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportObstacleSummariesTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportObstacleSummaries')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportObstacleSummaries` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `obstacle_shape` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `obstacle_cause` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `obstacle_impact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `additional_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_report_obstacle_report` (`daily_report_id`),
        CONSTRAINT `fk_report_obstacle_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportObstacleSummaries', true);
    }
}
