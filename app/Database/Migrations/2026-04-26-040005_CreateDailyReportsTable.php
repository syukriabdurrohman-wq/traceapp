<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDailyReportsTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('DailyReports')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `DailyReports` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `report_code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
        `report_date` date NOT NULL,
        `worker_user_id` bigint unsigned NOT NULL,
        `created_by_user_id` bigint unsigned NOT NULL,
        `weather_code` enum('Cerah','Hujan','Mendung') COLLATE utf8mb4_unicode_ci NOT NULL,
        `realization_summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
        `whatsapp_summary` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `status` enum('Draft','Submitted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
        `submitted_at` datetime DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        `deleted_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_daily_reports_code` (`report_code`),
        UNIQUE KEY `uniq_daily_reports_date_user` (`report_date`,`worker_user_id`),
        KEY `idx_daily_reports_status` (`status`),
        KEY `idx_daily_reports_worker` (`worker_user_id`),
        KEY `fk_daily_reports_created_user` (`created_by_user_id`),
        CONSTRAINT `fk_daily_reports_created_user` FOREIGN KEY (`created_by_user_id`) REFERENCES `Users` (`id`),
        CONSTRAINT `fk_daily_reports_worker_user` FOREIGN KEY (`worker_user_id`) REFERENCES `Users` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('DailyReports', true);
    }
}
