<?php

namespace App\Controllers;

use App\Models\PelatihanModel;
use CodeIgniter\Controller;

class PelatihanController extends Controller
{
    protected $helpers = ['url'];

    /**
     * GET / — Daftar pelatihan yang tersedia (halaman publik).
     */
    public function index()
    {
        $pelatihanModel = new PelatihanModel();

        $data = [
            'title'     => 'Daftar Pelatihan IT',
            'pelatihan' => $pelatihanModel->getAllOrdered(),
        ];

        return view('public/daftar_pelatihan', $data);
    }
}
