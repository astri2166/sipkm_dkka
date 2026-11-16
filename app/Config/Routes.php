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
    $routes->get('dashboard', 'PreviewController::mhsDashboard');
    $routes->get('kelompok', 'PreviewController::mhsKelompok');
    $routes->get('proposal', 'PreviewController::mhsProposal');
    $routes->get('tracking', 'PreviewController::mhsTracking');
});

// ====================================================================
// RUTE MODUL DOSEN PENINJAU (DIPROTEKSI AUTHFILTER)
// ====================================================================
$routes->group('dosen', ['filter' => 'auth:Dosen Peninjau'], static function ($routes) {
    $routes->get('dashboard', 'PreviewController::dosenDashboard');
    $routes->get('peninjauan', 'PreviewController::dosenPeninjauan');
    $routes->get('peninjauan/detail/(:num)', 'PreviewController::dosenDetailPeninjauan/$1');
});

// ====================================================================
// RUTE MODUL ADMIN DKKA & SUPER ADMIN (DIPROTEKSI AUTHFILTER)
// ====================================================================
$routes->group('admin', ['filter' => 'auth:Super Admin,Admin'], static function ($routes) {
    $routes->get('dashboard', 'PreviewController::adminDashboard');
    $routes->get('pengguna', 'PreviewController::adminPengguna');
    $routes->get('dosen/detail/(:num)', 'PreviewController::adminDetailDosen/$1');
    $routes->get('kelompok', 'PreviewController::adminKelompok');
    $routes->get('proposal', 'PreviewController::adminProposal');
    $routes->get('lomba', 'PreviewController::adminLomba');
});
