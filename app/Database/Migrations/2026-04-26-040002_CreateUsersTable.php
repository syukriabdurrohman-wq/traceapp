<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('Users')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `Users` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `role_id` bigint unsigned NOT NULL,
        `full_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
        `email` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `username` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
        `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
        `profile_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
        `last_login_at` datetime DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        `deleted_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_users_email` (`email`),
        UNIQUE KEY `uniq_users_username` (`username`),
        UNIQUE KEY `uniq_users_phone` (`phone`),
        KEY `idx_users_role` (`role_id`),
        KEY `idx_users_status` (`status`),
        CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `Roles` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('Users', true);
    }
}
