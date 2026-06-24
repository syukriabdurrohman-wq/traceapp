<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStructureFieldsToReportLocations extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('ReportLocations')) {
            return;
        }

        $fields = $this->db->getFieldNames('ReportLocations');
        $addFields = [];

        if (! in_array('structure_location', $fields, true)) {
            $addFields['structure_location'] = [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'current_location',
            ];
        }

        if (! in_array('structure_point', $fields, true)) {
            $addFields['structure_point'] = [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'structure_location',
            ];
        }

        if ($addFields !== []) {
            $this->forge->addColumn('ReportLocations', $addFields);
        }
    }

    public function down(): void
    {
        if (! $this->db->tableExists('ReportLocations')) {
            return;
        }

        $fields = $this->db->getFieldNames('ReportLocations');

        if (in_array('structure_point', $fields, true)) {
            $this->forge->dropColumn('ReportLocations', 'structure_point');
        }

        if (in_array('structure_location', $fields, true)) {
            $this->forge->dropColumn('ReportLocations', 'structure_location');
        }
    }
}
