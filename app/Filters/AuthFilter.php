<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Cek apakah admin sudah login.
     * Jika belum, redirect ke /login.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('admin_logged_in')) {
            return redirect()->to('/login');
        }
    }

    /**
     * Tidak ada aksi setelah response.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan
    }
}
