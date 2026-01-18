<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'role',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'role'     => 'required|in_list[admin,orang_tua]',
        'status'   => 'in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'    => 'Username harus diisi',
            'min_length'  => 'Username minimal 3 karakter',
            'is_unique'   => 'Username sudah terdaftar',
        ],
        'email' => [
            'required'    => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique'   => 'Email sudah terdaftar',
        ],
        'password' => [
            'required'   => 'Password harus diisi',
            'min_length' => 'Password minimal 8 karakter',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash password sebelum insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Get user by username
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Get active users by role
     */
    public function getActiveUsersByRole($role)
    {
        return $this->where('role', $role)
            ->where('status', 'active')
            ->findAll();
    }

    /**
     * Count users by role
     */
    public function countByRole($role)
    {
        return $this->where('role', $role)->countAllResults();
    }
}
