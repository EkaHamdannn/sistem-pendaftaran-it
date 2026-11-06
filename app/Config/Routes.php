<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// ROUTE PUBLIK
// ============================================================

// Halaman utama: daftar pelatihan
$routes->get('/', 'PelatihanController::index');

// Pendaftaran peserta
$routes->get('daftar/(:num)', 'PendaftaranController::form/$1');
$routes->post('daftar/(:num)', 'PendaftaranController::simpan/$1');

// Halaman sukses pendaftaran
$routes->get('sukses/(:num)', 'PendaftaranController::sukses/$1');

// ============================================================
// ROUTE AUTENTIKASI
// ============================================================

$routes->get('login', 'AuthController::index');
$routes->post('login', 'AuthController::proses');
$routes->get('logout', 'AuthController::logout');

// ============================================================
// ROUTE ADMIN (dilindungi filter 'auth')
// ============================================================

$routes->group('admin', ['filter' => 'auth'], function ($routes) {

    // Dashboard
    $routes->get('/', 'Admin\DashboardController::index');

    // Kelola Pelatihan (CRUD)
    $routes->get('pelatihan', 'Admin\PelatihanController::index');
    $routes->get('pelatihan/tambah', 'Admin\PelatihanController::tambah');
    $routes->post('pelatihan/tambah', 'Admin\PelatihanController::simpan');
    $routes->get('pelatihan/ubah/(:num)', 'Admin\PelatihanController::ubah/$1');
    $routes->post('pelatihan/ubah/(:num)', 'Admin\PelatihanController::update/$1');
    $routes->post('pelatihan/hapus/(:num)', 'Admin\PelatihanController::hapus/$1');

    // Daftar Peserta per Pelatihan
    $routes->get('peserta/(:num)', 'Admin\PesertaController::index/$1');
    $routes->post('peserta/batalkan/(:num)', 'Admin\PesertaController::batalkan/$1');
});
