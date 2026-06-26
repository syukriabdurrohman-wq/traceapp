<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWorkerCategoryLabels extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('WorkerCategories')) {
            return;
        }

        $updates = [
            'survey' => 'Surveyor',
            'pekerja-harian' => 'Operator',
            'tukang' => 'Welder',
        ];

        foreach ($updates as $slug => $name) {
            $this->db->table('WorkerCategories')
                ->where('slug', $slug)
                ->update(['name' => $name, 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }

    public function down(): void
    {
        if (! $this->db->tableExists('WorkerCategories')) {
            return;
        }

        $updates = [
            'survey' => 'Survey',
            'pekerja-harian' => 'Pekerja Harian',
            'tukang' => 'Tukang',
        ];

        foreach ($updates as $slug => $name) {
            $this->db->table('WorkerCategories')
                ->where('slug', $slug)
                ->update(['name' => $name, 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }
}
