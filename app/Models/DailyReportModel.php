<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyReportModel extends Model
{
    protected $table            = 'DailyReports';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'report_code',
        'report_date',
        'worker_user_id',
        'created_by_user_id',
        'weather_code',
        'realization_summary',
        'status',
        'submitted_at',
        'edited_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'lokasi_struktur',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}