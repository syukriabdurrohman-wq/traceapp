<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportWorkerUpdatesTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('ReportWorkerUpdates')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `ReportWorkerUpdates` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `daily_report_id` bigint unsigned NOT NULL,
        `worker_category_id` bigint unsigned DEFAULT NULL,
        `category_label` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
        `quantity` int NOT NULL DEFAULT '0',
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_report_worker_updates_report` (`daily_report_id`),
        KEY `idx_report_worker_updates_category` (`worker_category_id`),
        CONSTRAINT `fk_report_worker_updates_category` FOREIGN KEY (`worker_category_id`) REFERENCES `WorkerCategories` (`id`) ON DELETE SET NULL,
        CONSTRAINT `fk_report_worker_updates_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('ReportWorkerUpdates', true);
    }
}
