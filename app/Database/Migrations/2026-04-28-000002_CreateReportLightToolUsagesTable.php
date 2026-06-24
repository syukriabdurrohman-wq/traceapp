<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportLightToolUsagesTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportLightToolUsages')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportLightToolUsages` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `tool_label` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
        `volume` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `unit` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `sort_order` int NOT NULL DEFAULT '0',
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_report_light_tool_report` (`daily_report_id`),
        CONSTRAINT `fk_report_light_tool_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportLightToolUsages', true);
    }
}
