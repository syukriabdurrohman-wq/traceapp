<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHeavyEquipmentCategoriesTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('HeavyEquipmentCategories')) {
            return;
        }

        $this->db->query(<<<'SQL'
        CREATE TABLE `HeavyEquipmentCategories` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
        `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
        `sort_order` int NOT NULL DEFAULT '0',
        `is_active` tinyint(1) NOT NULL DEFAULT '1',
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_heavy_categories_slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(): void
    {
        $this->forge->dropTable('HeavyEquipmentCategories', true);
    }
}
