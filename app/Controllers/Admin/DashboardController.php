<?php

namespace App\Controllers\Admin;

use App\Models\PelatihanModel;
use App\Models\PendaftaranModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    protected $helpers = ['url'];

    /**
     * GET /admin — Dashboard admin dengan ringkasan data.
     */
    public function index()
    {
        $pelatihanModel   = new PelatihanModel();
        $pendaftaranModel = new PendaftaranModel();

        $data = [
            'total_pelatihan'  => $pelatihanModel->countAll(),
            'total_peserta'    => $pendaftaranModel->countAktif(),
            'admin_username'   => session()->get('admin_username'),
        ];

        return view('admin/dashboard', $data);
    }
}
