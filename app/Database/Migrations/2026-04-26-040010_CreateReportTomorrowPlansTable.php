<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportTomorrowPlansTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportTomorrowPlans')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportTomorrowPlans` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `summary_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_report_tomorrow_report` (`daily_report_id`),
        CONSTRAINT `fk_report_tomorrow_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportTomorrowPlans', true);
    }
}
