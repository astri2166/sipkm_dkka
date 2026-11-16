<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ====================================================================
// FASE FRONTEND PREVIEW ROUTES (TAHAP 2 - TAHAP 5)
// Nanti pada Tahap 6-9 akan dihubungkan ke Controller & AuthFilter penuh
// ====================================================================

// Tahap 2: Halaman Publik & Autentikasi
$routes->get('/', 'PreviewController::landingLomba');
$routes->get('login', 'PreviewController::login');
$routes->get('register', 'PreviewController::register');

// Tahap 3: Halaman Frontend Mahasiswa (Ketua & Anggota Kelompok)
$routes->group('mahasiswa', static function ($routes) {
    $routes->get('dashboard', 'PreviewController::mhsDashboard');
    $routes->get('kelompok', 'PreviewController::mhsKelompok');
    $routes->get('proposal', 'PreviewController::mhsProposal');
    $routes->get('tracking', 'PreviewController::mhsTracking');
});

// Tahap 4: Halaman Frontend Dosen Peninjau
$routes->group('dosen', static function ($routes) {
    $routes->get('dashboard', 'PreviewController::dosenDashboard');
    $routes->get('peninjauan', 'PreviewController::dosenPeninjauan');
    $routes->get('peninjauan/detail/(:num)', 'PreviewController::dosenDetailPeninjauan/$1');
});

// Tahap 5: Halaman Frontend Admin DKKA & Super Admin
$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'PreviewController::adminDashboard');
    $routes->get('pengguna', 'PreviewController::adminPengguna');
    $routes->get('dosen/detail/(:num)', 'PreviewController::adminDetailDosen/$1');
    $routes->get('kelompok', 'PreviewController::adminKelompok');
    $routes->get('proposal', 'PreviewController::adminProposal');
    $routes->get('lomba', 'PreviewController::adminLomba');
});