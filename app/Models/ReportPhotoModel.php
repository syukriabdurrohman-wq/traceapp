<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportPhotoModel extends Model
{
    protected $table         = 'ReportPhotos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['daily_report_id', 'file_name', 'file_path', 'mime_type', 'file_size', 'caption', 'sort_order', 'created_at'];
    protected $useTimestamps = false;
}
