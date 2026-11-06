<?php

namespace App\Controllers;

use App\Models\PelatihanModel;
use App\Models\PendaftaranModel;
use CodeIgniter\Controller;
use Config\Database;

class PendaftaranController extends Controller
{
    protected $helpers = ['form', 'url'];

    /**
     * GET /daftar/{id} — Tampilkan form pendaftaran peserta.
     */
    public function form($id = null)
    {
        $pelatihanModel = new PelatihanModel();
        $pelatihan = $pelatihanModel->getPelatihanById((int) $id);

        if (! $pelatihan) {
            return redirect()->to('/')->with('error', 'Pelatihan tidak ditemukan.');
        }

        if ((int) $pelatihan['sisa_kuota'] <= 0 || $pelatihan['status'] === 'Penuh') {
            return redirect()->to('/')->with('error', 'Pendaftaran gagal dibuka karena kuota pelatihan ini sudah penuh.');
        }

        $data = [
            'title'      => 'Form Pendaftaran — ' . $pelatihan['judul'],
            'pelatihan'  => $pelatihan,
            'validation' => \Config\Services::validation(),
        ];

        return view('public/form_pendaftaran', $data);
    }

    /**
     * POST /daftar/{id} — Proses simpan data pendaftaran peserta dengan validasi lengkap.
     */
    public function simpan($id = null)
    {
        $pelatihanId      = (int) $id;
        $pelatihanModel   = new PelatihanModel();
        $pendaftaranModel = new PendaftaranModel();

        $pelatihan = $pelatihanModel->getPelatihanById($pelatihanId);
        if (! $pelatihan) {
            return redirect()->to('/')->with('error', 'Pelatihan tidak ditemukan.');
        }

        // --- ATURAN VALIDASI DATA LENGKAP (FR-03, NFR-01, EP & BVA) ---
        $rules = [
            'nama' => [
                'rules'  => 'required|min_length[3]|max_length[50]|regex_match[/^[a-zA-Z\s]+$/]',
                'errors' => [
                    'required'    => 'Nama lengkap wajib diisi.',
                    'min_length'  => 'Nama lengkap minimal 3 karakter.',
                    'max_length'  => 'Nama lengkap maksimal 50 karakter.',
                    'regex_match' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Alamat email wajib diisi.',
                    'valid_email' => 'Format alamat email tidak valid (contoh: nama@domain.com).',
                ],
            ],
            'nomor_hp' => [
                'rules'  => 'required|regex_match[/^[0-9]+$/]|min_length[10]|max_length[13]',
                'errors' => [
                    'required'    => 'Nomor HP wajib diisi.',
                    'regex_match' => 'Nomor HP hanya boleh berisi angka.',
                    'min_length'  => 'Nomor HP minimal 10 digit angka.',
                    'max_length'  => 'Nomor HP maksimal 13 digit angka.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama    = trim($this->request->getPost('nama'));
        $email   = trim($this->request->getPost('email'));
        $nomorHp = trim($this->request->getPost('nomor_hp'));

        // 1. Tolak jika sisa_kuota sudah 0
        if ((int) $pelatihan['sisa_kuota'] <= 0 || $pelatihan['status'] === 'Penuh') {
            return redirect()->to('/')->with('error', 'Pendaftaran ditolak. Kuota pelatihan ini sudah penuh.');
        }

        // 2. Tolak jika email yang sama sudah berstatus 'Terdaftar' pada pelatihan itu (FR-04)
        if ($pendaftaranModel->cekDuplikat($pelatihanId, $email)) {
            return redirect()->back()->withInput()->with('error', 'Pendaftaran ditolak. Email ' . esc($email) . ' sudah terdaftar pada pelatihan ini.');
        }

        // 3. Simpan pendaftaran dan kurangi sisa_kuota dalam satu transaksi database
        $db = Database::connect();
        $db->transBegin();

        try {
            // Update atomik: kurangi sisa_kuota jika masih > 0
            $db->query(
                "UPDATE pelatihan SET sisa_kuota = sisa_kuota - 1 WHERE id = ? AND sisa_kuota > 0",
                [$pelatihanId]
            );

            if ($db->affectedRows() === 0) {
                $db->transRollback();
                return redirect()->to('/')->with('error', 'Pendaftaran gagal karena kuota pelatihan baru saja habis.');
            }

            // Simpan pendaftar
            $dataPendaftaran = [
                'pelatihan_id' => $pelatihanId,
                'nama'         => $nama,
                'email'        => $email,
                'nomor_hp'     => $nomorHp,
                'status'       => 'Terdaftar',
            ];

            $pendaftaranModel->insert($dataPendaftaran);
            $pendaftaranId = $pendaftaranModel->getInsertID();

            // 4. Set status pelatihan menjadi 'Penuh' bila sisa_kuota menjadi 0
            $queryCek = $db->query("SELECT sisa_kuota FROM pelatihan WHERE id = ?", [$pelatihanId]);
            $rowCek   = $queryCek->getRowArray();

            if ($rowCek && (int) $rowCek['sisa_kuota'] === 0) {
                $db->query("UPDATE pelatihan SET status = 'Penuh' WHERE id = ?", [$pelatihanId]);
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses pendaftaran.');
            }

            $db->transCommit();

            return redirect()->to('/sukses/' . $pendaftaranId);
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * GET /sukses/{id_pendaftaran} — Halaman sukses pendaftaran peserta.
     */
    public function sukses($id = null)
    {
        $pendaftaranModel = new PendaftaranModel();
        $pendaftaran = $pendaftaranModel->getPendaftaranDenganPelatihan((int) $id);

        if (! $pendaftaran) {
            return redirect()->to('/')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $data = [
            'title'       => 'Pendaftaran Berhasil — #' . sprintf('%05d', $pendaftaran['id']),
            'pendaftaran' => $pendaftaran,
        ];

        return view('public/sukses', $data);
    }
}
