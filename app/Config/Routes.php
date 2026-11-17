<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ====================================================================
// RUTE PUBLIK & AUTENTIKASI (TAHAP 2 & TAHAP 6)
// ====================================================================
$routes->get('/', 'PreviewController::landingLomba');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::verifikasiLogin');

$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::prosesRegister');

$routes->get('logout', 'AuthController::logout');

// ====================================================================
// RUTE MODUL MAHASISWA (DIPROTEKSI AUTHFILTER PER ROLE)
// ====================================================================
$routes->group('mahasiswa', ['filter' => 'auth:Ketua Kelompok,Anggota Kelompok'], static function ($routes) {
    $routes->get('dashboard', 'MahasiswaController::dashboard');
    $routes->get('kelompok', 'MahasiswaController::kelompok');
    $routes->post('kelompok/buat', 'MahasiswaController::buatKelompok');
    $routes->post('kelompok/tambah-anggota', 'MahasiswaController::tambahAnggota');
    $routes->get('proposal', 'MahasiswaController::proposal');
    $routes->post('proposal/simpan', 'MahasiswaController::kirimProposal');
    $routes->get('tracking', 'MahasiswaController::tracking');
});

// ====================================================================
// RUTE MODUL DOSEN PENINJAU (DIPROTEKSI AUTHFILTER)
// ====================================================================
$routes->group('dosen', ['filter' => 'auth:Dosen Peninjau'], static function ($routes) {
    $routes->get('dashboard', 'DosenController::dashboard');
    $routes->get('peninjauan', 'DosenController::peninjauan');
    $routes->get('peninjauan/detail/(:num)', 'DosenController::detailPeninjauan/$1');
    $routes->post('peninjauan/simpan-catatan/(:num)', 'DosenController::simpanCatatanCard/$1');
    $routes->post('peninjauan/kirim-rekap/(:num)', 'DosenController::kirimRekapPenilaian/$1');
});

// ====================================================================
// RUTE MODUL ADMIN DKKA & SUPER ADMIN (DIPROTEKSI AUTHFILTER)
// ====================================================================
$routes->group('admin', ['filter' => 'auth:Super Admin,Admin'], static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('pengguna', 'AdminController::pengguna');
    $routes->post('pengguna/tambah-admin', 'AdminController::tambahAdmin');
    $routes->post('pengguna/tambah-dosen', 'AdminController::tambahDosen');
    $routes->get('pengguna/toggle-status/(:num)', 'AdminController::toggleStatusPengguna/$1');
    $routes->get('dosen/detail/(:num)', 'AdminController::detailDosen/$1');
    $routes->get('kelompok', 'AdminController::kelompok');
    $routes->post('kelompok/tambah', 'AdminController::tambahKelompok');
    $routes->get('kelompok/hapus/(:num)', 'AdminController::hapusKelompok/$1');
    $routes->get('proposal', 'AdminController::proposal');
    $routes->post('proposal/plotting', 'AdminController::plottingDosen');
    $routes->post('proposal/bulk-plotting', 'AdminController::bulkPlottingDosen');
    $routes->post('proposal/tetapkan-kelayakan', 'AdminController::tetapkanKelayakan');
    $routes->get('lomba', 'AdminController::lomba');
    $routes->post('lomba/tambah', 'AdminController::tambahLomba');
    $routes->get('lomba/hapus/(:num)', 'AdminController::hapusLomba/$1');
});
