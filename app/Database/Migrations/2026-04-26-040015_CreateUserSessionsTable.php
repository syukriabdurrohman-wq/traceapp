<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserSessionsTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('UserSessions')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `UserSessions` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `user_id` bigint unsigned NOT NULL,
        `session_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `last_activity_at` datetime DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_user_sessions_session` (`session_id`),
        KEY `idx_user_sessions_user` (`user_id`),
        CONSTRAINT `fk_user_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('UserSessions', true);
    }
}
