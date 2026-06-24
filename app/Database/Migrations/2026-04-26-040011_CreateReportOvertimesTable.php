<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportOvertimesTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportOvertimes')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportOvertimes` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `is_enabled` tinyint(1) NOT NULL DEFAULT '0',
        `start_time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `end_time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `summary_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_report_overtime_report` (`daily_report_id`),
        CONSTRAINT `fk_report_overtime_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportOvertimes', true);
    }
}
