<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('AuditLogs')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `AuditLogs` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `user_id` bigint unsigned DEFAULT NULL,
        `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
        `entity_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
        `entity_id` bigint DEFAULT NULL,
        `meta_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
        `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_audit_logs_user` (`user_id`),
        KEY `idx_audit_logs_action` (`action`),
        CONSTRAINT `fk_audit_logs_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE SET NULL,
        CONSTRAINT `AuditLogs_chk_1` CHECK (json_valid(`meta_json`))
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('AuditLogs', true);
    }
}
