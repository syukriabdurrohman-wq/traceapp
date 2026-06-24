<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePasswordResetsTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('PasswordResets')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `PasswordResets` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `email` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
        `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `expires_at` datetime NOT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_password_resets_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('PasswordResets', true);
    }
}
