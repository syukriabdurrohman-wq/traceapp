<?php

namespace App\Models;

use CodeIgniter\Model;

class HeavyEquipmentCategoryModel extends Model
{
    protected $table         = 'HeavyEquipmentCategories';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'slug', 'sort_order', 'is_active', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
