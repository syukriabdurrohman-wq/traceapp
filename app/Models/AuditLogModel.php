<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table         = 'AuditLogs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['user_id', 'action', 'entity_type', 'entity_id', 'meta_json', 'ip_address', 'created_at'];
    protected $useTimestamps = false;
}
