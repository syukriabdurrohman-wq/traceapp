<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRefreshTokensTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('RefreshTokens')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `RefreshTokens` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `user_id` bigint unsigned NOT NULL,
        `token_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
        `expires_at` datetime NOT NULL,
        `revoked_at` datetime DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_refresh_tokens_hash` (`token_hash`),
        KEY `idx_refresh_tokens_user` (`user_id`),
        CONSTRAINT `fk_refresh_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('RefreshTokens', true);
    }
}
