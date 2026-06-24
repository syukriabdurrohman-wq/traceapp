<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TraceMasterSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesSeeder::class);
        $this->call(WorkerCategoriesSeeder::class);
        $this->call(HeavyEquipmentCategoriesSeeder::class);
    }
}
