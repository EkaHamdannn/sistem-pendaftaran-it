<?php

namespace App\Models;

use CodeIgniter\Model;

class PelatihanModel extends Model
{
    protected $table            = 'pelatihan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'judul', 'instruktur', 'tanggal',
        'jam_mulai', 'jam_selesai',
        'kuota', 'sisa_kuota', 'status',
    ];

    protected $useTimestamps = false; // Dikelola oleh DB default & ON UPDATE CURRENT_TIMESTAMP

    /**
     * Ambil semua data pelatihan, diurutkan berdasarkan tanggal dan jam mulai terdekat.
     */
    public function getAllOrdered(): array
    {
        return $this->orderBy('tanggal', 'ASC')
                    ->orderBy('jam_mulai', 'ASC')
                    ->findAll();
    }

    /**
     * Ambil data pelatihan berdasarkan ID.
     */
    public function getPelatihanById(int $id): ?array
    {
        return $this->find($id);
    }

    /**
     * Hitung total semua pelatihan.
     */
    public function countAll(): int
    {
        return $this->countAllResults();
    }
}
