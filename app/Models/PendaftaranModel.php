<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table            = 'pendaftaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'pelatihan_id', 'nama', 'email', 'nomor_hp', 'status',
    ];

    protected $useTimestamps = false; // Dikelola oleh DB default & ON UPDATE CURRENT_TIMESTAMP

    /**
     * Hitung total pendaftaran aktif (status Terdaftar).
     */
    public function countAktif(): int
    {
        return $this->where('status', 'Terdaftar')->countAllResults();
    }

    /**
     * Cek apakah email sudah terdaftar pada pelatihan dengan status 'Terdaftar'.
     */
    public function cekDuplikat(int $pelatihanId, string $email): bool
    {
        return $this->where('pelatihan_id', $pelatihanId)
                    ->where('email', $email)
                    ->where('status', 'Terdaftar')
                    ->countAllResults() > 0;
    }

    /**
     * Ambil data pendaftaran beserta detail pelatihan terkait.
     */
    public function getPendaftaranDenganPelatihan(int $id): ?array
    {
        return $this->select('pendaftaran.*, pelatihan.judul as judul_pelatihan, pelatihan.instruktur, pelatihan.tanggal, pelatihan.jam_mulai, pelatihan.jam_selesai')
                    ->join('pelatihan', 'pelatihan.id = pendaftaran.pelatihan_id')
                    ->where('pendaftaran.id', $id)
                    ->first();
    }
}
