<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\Controller;
use Config\Services;

class AuthController extends Controller
{
    protected $helpers = ['form', 'url'];

    /**
     * GET /login — Tampilkan form login admin.
     * Jika sudah login, redirect ke /admin.
     */
    public function index()
    {
        $session = session();

        // Sudah login? Langsung ke dashboard admin
        if ($session->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }

        return view('auth/login');
    }

    /**
     * POST /login — Proses login admin dengan proteksi Brute Force (Rate Limiting).
     * Maksimal 5 percobaan dalam 60 detik per alamat IP.
     */
    public function proses()
    {
        $session = session();

        // Sudah login? Langsung ke dashboard
        if ($session->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }

        // --- PROTEKSI BRUTE FORCE (CI4 THROTTLER) ---
        $throttler   = Services::throttler();
        $throttleKey = md5($this->request->getIPAddress() . '_admin_login');

        // Batasi maksimal 5 percobaan login per 60 detik
        if ($throttler->check($throttleKey, 5, 60) === false) {
            $seconds = $throttler->getTokenTime();
            $session->setFlashdata(
                'error',
                "Terlalu banyak percobaan login. Demi keamanan, silakan tunggu {$seconds} detik sebelum mencoba kembali."
            );

            return redirect()->to('/login')->withInput();
        }

        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        $adminModel = new AdminModel();
        $admin = $adminModel->getByUsername($username);

        // Validasi kredensial: username ditemukan DAN hash password BCrypt cocok
        if ($admin && password_verify($password, $admin['password'])) {
            $session->set([
                'admin_logged_in' => true,
                'admin_id'        => $admin['id'],
                'admin_username'  => $admin['username'],
            ]);

            return redirect()->to('/admin');
        }

        // Login gagal — pesan error umum untuk mencegah user enumeration
        $session->setFlashdata('error', 'Username atau password salah.');

        return redirect()->to('/login')->withInput();
    }

    /**
     * GET /logout — Logout admin, hapus session, redirect ke /login.
     */
    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/login');
    }
}
