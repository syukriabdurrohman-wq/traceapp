<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'Users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'role_id',
        'full_name',
        'email',
        'username',
        'phone',
        'profile_photo_path',
        'password_hash',
        'status',
        'last_login_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    public function findByLogin(string $login): ?array
    {
        return $this->select('Users.*, Roles.code as role_code, Roles.name as role_name')
            ->join('Roles', 'Roles.id = Users.role_id', 'left')
            ->groupStart()
                ->where('Users.email', $login)
                ->orWhere('Users.username', $login)
                ->orWhere('Users.phone', $login)
            ->groupEnd()
            ->first();
    }

    public function findByPhone(string $phone): ?array
    {
        return $this->select('Users.*, Roles.code as role_code, Roles.name as role_name')
            ->join('Roles', 'Roles.id = Users.role_id', 'left')
            ->where('Users.phone', $phone)
            ->first();
    }

    public function findDetailedById(int $userId): ?array
    {
        return $this->select('Users.*, Roles.code as role_code, Roles.name as role_name')
            ->join('Roles', 'Roles.id = Users.role_id', 'left')
            ->where('Users.id', $userId)
            ->first();
    }

    public function getActiveReportUsers(): array
    {
        return $this->select('Users.id, Users.full_name, Users.email, Users.username, Roles.code as role_code, Roles.name as role_name')
            ->join('Roles', 'Roles.id = Users.role_id', 'left')
            ->where('Users.status', 'Active')
            ->where('Roles.code', 'Supervisor')
            ->orderBy('Users.full_name', 'ASC')
            ->findAll();
    }

    public function getActiveUsersForManagement(): array
    {
        return $this->select('Users.*, Roles.code as role_code, Roles.name as role_name')
            ->join('Roles', 'Roles.id = Users.role_id', 'left')
            ->orderBy('Users.created_at', 'DESC')
            ->findAll();
    }
}
