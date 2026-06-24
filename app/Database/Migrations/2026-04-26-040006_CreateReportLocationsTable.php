<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportLocationsTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportLocations')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportLocations` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `current_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `area_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
        `area_label` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
        `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_report_locations_report` (`daily_report_id`),
        CONSTRAINT `fk_report_locations_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportLocations', true);
    }
}
