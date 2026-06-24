<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportRealizationItemsTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportRealizationItems')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportRealizationItems` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `work_item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `unit` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `plan_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `realization_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `deviation_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `partner` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `sort_order` int NOT NULL DEFAULT '0',
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_report_realization_report` (`daily_report_id`),
        CONSTRAINT `fk_report_realization_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportRealizationItems', true);
    }
}
