<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateHeavyEquipmentCategories extends Migration
{
    public function up(): void
    {
        $this->db->query(
            "UPDATE `HeavyEquipmentCategories`
             SET `name` = 'Tongkang', `slug` = 'tongkang', `sort_order` = 1, `is_active` = 1, `updated_at` = NOW()
             WHERE (`slug` = 'ponton' OR `name` = 'Ponton')
             AND NOT EXISTS (
                 SELECT 1 FROM (SELECT `id` FROM `HeavyEquipmentCategories` WHERE `slug` = 'tongkang') AS existing_tongkang
             )"
        );

        $this->db->query(
            "UPDATE `HeavyEquipmentCategories`
             SET `name` = 'Tongkang', `sort_order` = 1, `is_active` = 1, `updated_at` = NOW()
             WHERE `slug` = 'tongkang' OR `name` = 'Tongkang'"
        );

        $this->db->query(
            "UPDATE `HeavyEquipmentCategories`
             SET `is_active` = 0, `sort_order` = 93, `updated_at` = NOW()
             WHERE (`slug` = 'ponton' OR `name` = 'Ponton')
             AND EXISTS (
                 SELECT 1 FROM (SELECT `id` FROM `HeavyEquipmentCategories` WHERE `slug` = 'tongkang') AS existing_tongkang
             )"
        );

        $this->upsertCategory('Tongkang', 'tongkang', 1, 1);
        $this->upsertCategory('Boring Machine', 'boring-machine', 2, 1);
        $this->upsertCategory('Crane Service', 'crane', 3, 1);
        $this->upsertCategory('Vibro Hammer', 'vibro-hammer', 4, 1);
        $this->upsertCategory('Truck Mixer', 'truck-mixer', 5, 1);
        $this->upsertCategory('Excavator', 'excavator', 6, 1);
        $this->upsertCategory('Loader', 'loader', 7, 1);
        $this->upsertCategory('Dump Truck', 'dump-truck', 8, 1);

        $this->deactivateCategory('bulldozer', 90);
        $this->deactivateCategory('vibroroller', 91);
        $this->deactivateCategory('hyab-crane', 92);
    }

    public function down(): void
    {
        $this->upsertCategory('Dump Truck', 'dump-truck', 1, 1);
        $this->upsertCategory('Excavator', 'excavator', 2, 1);
        $this->upsertCategory('Bulldozer', 'bulldozer', 3, 1);
        $this->upsertCategory('Loader', 'loader', 4, 1);
        $this->upsertCategory('Vibroroller', 'vibroroller', 5, 1);
        $this->upsertCategory('Hyab Crane', 'hyab-crane', 6, 1);
        $this->upsertCategory('Crane', 'crane', 7, 1);
        $this->upsertCategory('Boring Machine', 'boring-machine', 8, 1);

        $this->deactivateCategory('tongkang', 93);
        $this->deactivateCategory('vibro-hammer', 94);
        $this->deactivateCategory('truck-mixer', 95);
    }

    private function upsertCategory(string $name, string $slug, int $sortOrder, int $isActive): void
    {
        $this->db->query(
            'INSERT INTO `HeavyEquipmentCategories` (`name`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`)
             VALUES (?, ?, ?, ?, NOW(), NOW())
             ON DUPLICATE KEY UPDATE
             `name` = VALUES(`name`),
             `sort_order` = VALUES(`sort_order`),
             `is_active` = VALUES(`is_active`),
             `updated_at` = VALUES(`updated_at`)',
            [$name, $slug, $sortOrder, $isActive]
        );
    }

    private function deactivateCategory(string $slug, int $sortOrder): void
    {
        $this->db->query(
            'UPDATE `HeavyEquipmentCategories`
             SET `is_active` = 0, `sort_order` = ?, `updated_at` = NOW()
             WHERE `slug` = ?',
            [$sortOrder, $slug]
        );
    }
}
