<?php

namespace App\Controllers\Admin;

use App\Models\PelatihanModel;
use App\Models\PendaftaranModel;
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PelatihanController extends Controller
{
    protected $helpers = ['form', 'url'];

    /**
     * GET /admin/pelatihan — Tampilkan daftar seluruh pelatihan yang dikelola admin.
     */
    public function index()
    {
        $pelatihanModel = new PelatihanModel();
        $pelatihan = $pelatihanModel->getAllOrdered();

        $data = [
            'title'     => 'Kelola Pelatihan — Admin Panel',
            'pelatihan' => $pelatihan,
        ];

        return view('admin/pelatihan/index', $data);
    }

    /**
     * GET /admin/pelatihan/tambah — Form tambah data pelatihan baru.
     */
    public function tambah()
    {
        $data = [
            'title'      => 'Tambah Pelatihan Baru — Admin Panel',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/pelatihan/tambah', $data);
    }

    /**
     * POST /admin/pelatihan/tambah — Proses simpan data pelatihan baru dengan validasi lengkap.
     */
    public function simpan()
    {
        $rules = [
            'judul' => [
                'rules'  => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'Judul pelatihan wajib diisi.',
                    'min_length' => 'Judul pelatihan minimal 3 karakter.',
                    'max_length' => 'Judul pelatihan maksimal 150 karakter.',
                ],
            ],
            'instruktur' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama instruktur wajib diisi.',
                    'min_length' => 'Nama instruktur minimal 3 karakter.',
                    'max_length' => 'Nama instruktur maksimal 100 karakter.',
                ],
            ],
            'tanggal' => [
                'rules'  => 'required|valid_date[Y-m-d]',
                'errors' => [
                    'required'   => 'Tanggal pelaksanaan wajib diisi.',
                    'valid_date' => 'Format tanggal tidak valid.',
                ],
            ],
            'jam_mulai' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Jam mulai wajib diisi.',
                ],
            ],
            'jam_selesai' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Jam selesai wajib diisi.',
                ],
            ],
            'kuota' => [
                'rules'  => 'required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[100]',
                'errors' => [
                    'required'              => 'Kuota peserta wajib diisi.',
                    'is_natural_no_zero'    => 'Kuota harus berupa bilangan bulat positif.',
                    'greater_than_equal_to' => 'Kuota minimal 1 peserta.',
                    'less_than_equal_to'    => 'Kuota maksimal 100 peserta.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $judul       = trim($this->request->getPost('judul'));
        $instruktur  = trim($this->request->getPost('instruktur'));
        $tanggal     = $this->request->getPost('tanggal');
        $jamMulai    = $this->request->getPost('jam_mulai');
        $jamSelesai  = $this->request->getPost('jam_selesai');
        $kuota       = (int) $this->request->getPost('kuota');

        // Validasi: Tanggal tidak boleh sebelum hari ini
        $today = date('Y-m-d');
        if ($tanggal < $today) {
            return redirect()->back()->withInput()->with('error', 'Tanggal pelaksanaan tidak boleh sebelum tanggal hari ini.');
        }

        // Validasi: Jam selesai harus lebih besar dari jam mulai
        if ($jamSelesai <= $jamMulai) {
            return redirect()->back()->withInput()->with('error', 'Jam selesai harus lebih besar dari jam mulai.');
        }

        $pelatihanModel = new PelatihanModel();
        $pelatihanModel->insert([
            'judul'       => $judul,
            'instruktur'  => $instruktur,
            'tanggal'     => $tanggal,
            'jam_mulai'   => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'kuota'       => $kuota,
            'sisa_kuota'  => $kuota,
            'status'      => 'Tersedia',
        ]);

        return redirect()->to('/admin/pelatihan')->with('success', 'Pelatihan baru berhasil ditambahkan.');
    }

    /**
     * GET /admin/pelatihan/ubah/{id} — Form ubah data pelatihan.
     */
    public function ubah($id = null)
    {
        $pelatihanModel   = new PelatihanModel();
        $pendaftaranModel = new PendaftaranModel();

        $pelatihan = $pelatihanModel->getPelatihanById((int) $id);
        if (! $pelatihan) {
            return redirect()->to('/admin/pelatihan')->with('error', 'Pelatihan tidak ditemukan.');
        }

        // Hitung peserta aktif untuk acuan minimal kuota baru
        $jumlahTerdaftar = $pendaftaranModel->where('pelatihan_id', $id)
                                            ->where('status', 'Terdaftar')
                                            ->countAllResults();

        $data = [
            'title'            => 'Ubah Pelatihan — ' . $pelatihan['judul'],
            'pelatihan'        => $pelatihan,
            'jumlah_terdaftar' => $jumlahTerdaftar,
            'validation'       => \Config\Services::validation(),
        ];

        return view('admin/pelatihan/ubah', $data);
    }

    /**
     * POST /admin/pelatihan/ubah/{id} — Proses update data pelatihan dengan validasi lengkap.
     */
    public function update($id = null)
    {
        $pelatihanId      = (int) $id;
        $pelatihanModel   = new PelatihanModel();
        $pendaftaranModel = new PendaftaranModel();

        $pelatihan = $pelatihanModel->getPelatihanById($pelatihanId);
        if (! $pelatihan) {
            return redirect()->to('/admin/pelatihan')->with('error', 'Pelatihan tidak ditemukan.');
        }

        $rules = [
            'judul' => [
                'rules'  => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'Judul pelatihan wajib diisi.',
                    'min_length' => 'Judul pelatihan minimal 3 karakter.',
                    'max_length' => 'Judul pelatihan maksimal 150 karakter.',
                ],
            ],
            'instruktur' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama instruktur wajib diisi.',
                    'min_length' => 'Nama instruktur minimal 3 karakter.',
                    'max_length' => 'Nama instruktur maksimal 100 karakter.',
                ],
            ],
            'tanggal' => [
                'rules'  => 'required|valid_date[Y-m-d]',
                'errors' => [
                    'required'   => 'Tanggal pelaksanaan wajib diisi.',
                    'valid_date' => 'Format tanggal tidak valid.',
                ],
            ],
            'jam_mulai' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Jam mulai wajib diisi.',
                ],
            ],
            'jam_selesai' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Jam selesai wajib diisi.',
                ],
            ],
            'kuota' => [
                'rules'  => 'required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[100]',
                'errors' => [
                    'required'              => 'Kuota peserta wajib diisi.',
                    'is_natural_no_zero'    => 'Kuota harus berupa bilangan bulat positif.',
                    'greater_than_equal_to' => 'Kuota minimal 1 peserta.',
                    'less_than_equal_to'    => 'Kuota maksimal 100 peserta.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $judul       = trim($this->request->getPost('judul'));
        $instruktur  = trim($this->request->getPost('instruktur'));
        $tanggal     = $this->request->getPost('tanggal');
        $jamMulai    = $this->request->getPost('jam_mulai');
        $jamSelesai  = $this->request->getPost('jam_selesai');
        $kuotaBaru   = (int) $this->request->getPost('kuota');

        // Validasi: Tanggal tidak boleh sebelum hari ini
        $today = date('Y-m-d');
        if ($tanggal < $today) {
            return redirect()->back()->withInput()->with('error', 'Tanggal pelaksanaan tidak boleh sebelum tanggal hari ini.');
        }

        // Validasi: Jam selesai harus lebih besar dari jam mulai
        if ($jamSelesai <= $jamMulai) {
            return redirect()->back()->withInput()->with('error', 'Jam selesai harus lebih besar dari jam mulai.');
        }

        // Hitung jumlah peserta berstatus 'Terdaftar' saat ini
        $jumlahTerdaftar = $pendaftaranModel->where('pelatihan_id', $pelatihanId)
                                            ->where('status', 'Terdaftar')
                                            ->countAllResults();

        // Validasi: Tolak jika kuota baru lebih kecil dari jumlah peserta terdaftar
        if ($kuotaBaru < $jumlahTerdaftar) {
            return redirect()->back()->withInput()->with(
                'error',
                "Kuota baru ($kuotaBaru) tidak boleh lebih kecil dari jumlah peserta yang saat ini berstatus Terdaftar ($jumlahTerdaftar peserta)."
            );
        }

        // Hitung sisa kuota baru
        $sisaKuotaBaru = $kuotaBaru - $jumlahTerdaftar;

        // Tentukan status baru (Tersedia / Penuh)
        $statusBaru = ($sisaKuotaBaru === 0) ? 'Penuh' : 'Tersedia';

        $pelatihanModel->update($pelatihanId, [
            'judul'       => $judul,
            'instruktur'  => $instruktur,
            'tanggal'     => $tanggal,
            'jam_mulai'   => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'kuota'       => $kuotaBaru,
            'sisa_kuota'  => $sisaKuotaBaru,
            'status'      => $statusBaru,
        ]);

        return redirect()->to('/admin/pelatihan')->with('success', 'Data pelatihan berhasil diperbarui.');
    }

    /**
     * POST /admin/pelatihan/hapus/{id} — Hapus data pelatihan.
     * Menangkap kegagalan foreign key (ON DELETE RESTRICT) dengan pesan ramah.
     */
    public function hapus($id = null)
    {
        $pelatihanId    = (int) $id;
        $pelatihanModel = new PelatihanModel();

        $pelatihan = $pelatihanModel->getPelatihanById($pelatihanId);
        if (! $pelatihan) {
            return redirect()->to('/admin/pelatihan')->with('error', 'Pelatihan tidak ditemukan.');
        }

        try {
            $pelatihanModel->delete($pelatihanId);
            return redirect()->to('/admin/pelatihan')->with(
                'success',
                'Pelatihan "' . esc($pelatihan['judul']) . '" berhasil dihapus.'
            );
        } catch (DatabaseException $e) {
            // Tangkap kegagalan RESTRICT foreign key
            return redirect()->to('/admin/pelatihan')->with(
                'error',
                'Pelatihan "' . esc($pelatihan['judul']) . '" tidak dapat dihapus karena masih memiliki data riwayat pendaftaran peserta.'
            );
        } catch (\Throwable $e) {
            return redirect()->to('/admin/pelatihan')->with(
                'error',
                'Gagal menghapus pelatihan: ' . $e->getMessage()
            );
        }
    }
}
