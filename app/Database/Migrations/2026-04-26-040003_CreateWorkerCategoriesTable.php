<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkerCategoriesTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('WorkerCategories')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `WorkerCategories` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
        `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
        `sort_order` int NOT NULL DEFAULT '0',
        `is_active` tinyint(1) NOT NULL DEFAULT '1',
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_worker_categories_slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('WorkerCategories', true);
    }
}
