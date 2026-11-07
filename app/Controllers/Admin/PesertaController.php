<?php

namespace App\Controllers\Admin;

use App\Models\PelatihanModel;
use App\Models\PendaftaranModel;
use CodeIgniter\Controller;
use Config\Database;

class PesertaController extends Controller
{
    protected $helpers = ['form', 'url'];

    /**
     * GET /admin/peserta/{id_pelatihan} — Tampilkan daftar seluruh peserta per pelatihan.
     */
    public function index($id_pelatihan = null)
    {
        $pelatihanId      = (int) $id_pelatihan;
        $pelatihanModel   = new PelatihanModel();
        $pendaftaranModel = new PendaftaranModel();

        $pelatihan = $pelatihanModel->getPelatihanById($pelatihanId);
        if (! $pelatihan) {
            return redirect()->to('/admin/pelatihan')->with('error', 'Pelatihan tidak ditemukan.');
        }

        // Ambil semua peserta (baik Terdaftar maupun Dibatalkan)
        $peserta = $pendaftaranModel->where('pelatihan_id', $pelatihanId)
                                    ->orderBy('id', 'ASC')
                                    ->findAll();

        $totalTerdaftar  = 0;
        $totalDibatalkan = 0;
        foreach ($peserta as $p) {
            if ($p['status'] === 'Terdaftar') {
                $totalTerdaftar++;
            } elseif ($p['status'] === 'Dibatalkan') {
                $totalDibatalkan++;
            }
        }

        $data = [
            'title'            => 'Daftar Peserta — ' . $pelatihan['judul'],
            'pelatihan'        => $pelatihan,
            'peserta'          => $peserta,
            'total_terdaftar'  => $totalTerdaftar,
            'total_dibatalkan' => $totalDibatalkan,
        ];

        return view('admin/peserta/index', $data);
    }

    /**
     * POST /admin/peserta/batalkan/{id_pendaftaran} — Batalkan pendaftaran peserta.
     * Mengubah status menjadi 'Dibatalkan', menambah sisa_kuota + 1,
     * dan mengembalikan status pelatihan ke 'Tersedia' jika sebelumnya 'Penuh'
     * dalam satu transaksi database.
     */
    public function batalkan($id = null)
    {
        $pendaftaranId    = (int) $id;
        $pendaftaranModel = new PendaftaranModel();
        $pelatihanModel   = new PelatihanModel();

        $pendaftaran = $pendaftaranModel->find($pendaftaranId);
        if (! $pendaftaran) {
            return redirect()->to('/admin/pelatihan')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $pelatihanId = (int) $pendaftaran['pelatihan_id'];

        // Hanya boleh batalkan yang berstatus 'Terdaftar'
        if ($pendaftaran['status'] !== 'Terdaftar') {
            return redirect()->to('/admin/peserta/' . $pelatihanId)->with(
                'error',
                'Pendaftaran peserta ' . esc($pendaftaran['nama']) . ' sudah dibatalkan sebelumnya.'
            );
        }

        $db = Database::connect();
        $db->transBegin();

        try {
            // 1. Ubah status pendaftaran menjadi 'Dibatalkan'
            $pendaftaranModel->update($pendaftaranId, [
                'status' => 'Dibatalkan',
            ]);

            // 2. Tambah sisa_kuota + 1 dan kembalikan status pelatihan menjadi 'Tersedia'
            $db->query(
                "UPDATE pelatihan SET sisa_kuota = sisa_kuota + 1, status = 'Tersedia' WHERE id = ?",
                [$pelatihanId]
            );

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to('/admin/peserta/' . $pelatihanId)->with(
                    'error',
                    'Terjadi kesalahan saat memproses pembatalan pendaftaran.'
                );
            }

            $db->transCommit();

            return redirect()->to('/admin/peserta/' . $pelatihanId)->with(
                'success',
                'Pendaftaran peserta ' . esc($pendaftaran['nama']) . ' berhasil dibatalkan. Sisa kuota pelatihan bertambah 1.'
            );
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/admin/peserta/' . $pelatihanId)->with(
                'error',
                'Gagal membatalkan pendaftaran: ' . $e->getMessage()
            );
        }
    }

    /**
     * POST /admin/peserta/hapus/{id} — Hapus data riwayat pendaftaran peserta secara permanen langsung dari UI.
     */
    public function hapus($id = null)
    {
        $pendaftaranId    = (int) $id;
        $pendaftaranModel = new PendaftaranModel();

        $pendaftaran = $pendaftaranModel->find($pendaftaranId);
        if (! $pendaftaran) {
            return redirect()->to('/admin/pelatihan')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $pelatihanId = (int) $pendaftaran['pelatihan_id'];
        $db = Database::connect();
        $db->transBegin();

        try {
            // Jika peserta berstatus 'Terdaftar', kembalikan kuota + 1
            if ($pendaftaran['status'] === 'Terdaftar') {
                $db->query(
                    "UPDATE pelatihan SET sisa_kuota = sisa_kuota + 1, status = 'Tersedia' WHERE id = ?",
                    [$pelatihanId]
                );
            }

            // Hapus data pendaftaran
            $pendaftaranModel->delete($pendaftaranId);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to('/admin/peserta/' . $pelatihanId)->with(
                    'error',
                    'Terjadi kesalahan saat menghapus data peserta.'
                );
            }

            $db->transCommit();

            return redirect()->to('/admin/peserta/' . $pelatihanId)->with(
                'success',
                'Data peserta ' . esc($pendaftaran['nama']) . ' berhasil dihapus secara permanen.'
            );
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/admin/peserta/' . $pelatihanId)->with(
                'error',
                'Gagal menghapus data peserta: ' . $e->getMessage()
            );
        }
    }
}
