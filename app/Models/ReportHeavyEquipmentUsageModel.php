<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportHeavyEquipmentUsageModel extends Model
{
    protected $table         = 'ReportHeavyEquipmentUsages';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['daily_report_id', 'heavy_equipment_category_id', 'equipment_label', 'quantity', 'volume', 'unit', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
