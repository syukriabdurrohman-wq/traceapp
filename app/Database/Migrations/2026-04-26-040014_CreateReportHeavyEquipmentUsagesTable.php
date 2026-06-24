<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportHeavyEquipmentUsagesTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportHeavyEquipmentUsages')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportHeavyEquipmentUsages` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `heavy_equipment_category_id` bigint unsigned DEFAULT NULL,
        `equipment_label` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
        `quantity` int NOT NULL DEFAULT '0',
        `volume` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `unit` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT 'unit',
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_report_heavy_usage_report` (`daily_report_id`),
        KEY `idx_report_heavy_usage_category` (`heavy_equipment_category_id`),
        CONSTRAINT `fk_report_heavy_usage_category` FOREIGN KEY (`heavy_equipment_category_id`) REFERENCES `HeavyEquipmentCategories` (`id`) ON DELETE SET NULL,
        CONSTRAINT `fk_report_heavy_usage_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportHeavyEquipmentUsages', true);
    }
}
