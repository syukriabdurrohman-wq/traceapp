<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportWorkerUpdateModel extends Model
{
    protected $table         = 'ReportWorkerUpdates';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['daily_report_id', 'worker_category_id', 'category_label', 'quantity', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
