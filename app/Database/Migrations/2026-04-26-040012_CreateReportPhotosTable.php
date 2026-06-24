<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportPhotosTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportPhotos')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportPhotos` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
        `file_size` bigint unsigned NOT NULL,
        `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `sort_order` int NOT NULL DEFAULT '0',
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_report_photos_report` (`daily_report_id`),
        CONSTRAINT `fk_report_photos_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportPhotos', true);
    }
}
