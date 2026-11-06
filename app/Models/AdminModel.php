<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admin';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = ['username', 'password'];

    protected $useTimestamps    = false; // created_at diatur oleh DB default

    /**
     * Cari admin berdasarkan username.
     *
     * @param string $username
     * @return array|null
     */
    public function getByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }
}
