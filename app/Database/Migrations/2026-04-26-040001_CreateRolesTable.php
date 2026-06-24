<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('Roles')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `Roles` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
        `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_roles_code` (`code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('Roles', true);
    }
}
