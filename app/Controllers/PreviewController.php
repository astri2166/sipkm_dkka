<?php

namespace App\Controllers;

class PreviewController extends BaseController
{
    // ====================================================================
    // TAHAP 2: HALAMAN PUBLIK & AUTENTIKASI
    // ====================================================================

    public function landingLomba()
    {
        $data['daftarLomba'] = [
            [
                'idLomba'         => 1,
                'judulLomba'      => 'Program Kreativitas Mahasiswa (PKM) Internal UBL 2026',
                'kategoriLomba'   => 'PKM & Karya Ilmiah',
                'deskripsiLomba'  => "Seleksi pendanaan dan pembinaan proposal PKM 10 bidang tingkat Universitas Budi Luhur menuju PIMNAS 2026.\n\nKetentuan:\n1. Tim terdiri dari 3-5 mahasiswa aktif.\n2. Proposal diunggah dalam format tautan Google Drive/Docs.",
                'timelineMulai'   => '2026-11-01',
                'timelineSelesai' => '2026-12-15',
                'posterBanner'    => 'poster-pkm-2026.jpg',
            ],
            [
                'idLomba'         => 2,
                'judulLomba'      => 'GEMASTIK XIX — Divisi Pengembangan Perangkat Lunak',
                'kategoriLomba'   => 'Teknologi & IT',
                'deskripsiLomba'  => "Pagelaran Mahasiswa Nasional Bidang Teknologi Informasi dan Komunikasi. Daftarkan tim terbaikmu melalui DKKA Universitas Budi Luhur.",
                'timelineMulai'   => '2026-11-10',
                'timelineSelesai' => '2026-12-20',
                'posterBanner'    => 'poster-gemastik-2026.jpg',
            ],
            [
                'idLomba'         => 3,
                'judulLomba'      => 'Kompetisi Bisnis Mahasiswa Indonesia (KBMI) 2026',
                'kategoriLomba'   => 'Kewirausahaan',
                'deskripsiLomba'  => "Ajang hibah modal usaha dan pendampingan bisnis bagi mahasiswa aktif yang memiliki rintisan usaha inovatif.",
                'timelineMulai'   => '2026-11-15',
                'timelineSelesai' => '2027-01-10',
                'posterBanner'    => 'poster-kbmi-2026.jpg',
            ],
        ];

        return view('public/landing_lomba', $data);
    }

    public function login()
    {
        return view('public/login');
    }

    public function register()
    {
        return view('public/register');
    }

    // ====================================================================
    // STUB / PLACEHOLDER UNTUK TAHAP SELANJUTNYA (TAHAP 3 - 5)
    // Akan diisi tampilan lengkap saat memasuki Tahap masing-masing
    // ====================================================================

    // Tahap 3: Mahasiswa
    public function mhsDashboard()
    {
        return $this->renderPlaceholder('Dashboard Mahasiswa', 'Ketua Kelompok', 'dashboard', 'Tahap 3');
    }

    public function mhsKelompok()
    {
        return $this->renderPlaceholder('Data Kelompok PKM', 'Ketua Kelompok', 'kelompok', 'Tahap 3');
    }

    public function mhsProposal()
    {
        return $this->renderPlaceholder('Input Proposal PKM', 'Ketua Kelompok', 'proposal', 'Tahap 3');
    }

    public function mhsTracking()
    {
        return $this->renderPlaceholder('Tracking Status Proposal', 'Ketua Kelompok', 'tracking', 'Tahap 3');
    }

    // Tahap 4: Dosen Peninjau
    public function dosenDashboard()
    {
        return $this->renderPlaceholder('Dashboard Dosen Peninjau', 'Dosen Peninjau', 'dashboard', 'Tahap 4');
    }

    public function dosenPeninjauan()
    {
        return $this->renderPlaceholder('Peninjauan Proposal', 'Dosen Peninjau', 'peninjauan', 'Tahap 4');
    }

    public function dosenDetailPeninjauan($id = 1)
    {
        return $this->renderPlaceholder("Detail Peninjauan Proposal #$id", 'Dosen Peninjau', 'peninjauan', 'Tahap 4');
    }

    // Tahap 5: Admin DKKA & Super Admin
    public function adminDashboard()
    {
        $role = $this->request->getGet('role') ?? 'Admin';
        return $this->renderPlaceholder('Dashboard Admin DKKA', $role, 'dashboard', 'Tahap 5');
    }

    public function adminPengguna()
    {
        $tab = $this->request->getGet('tab') ?? 'dosen';
        $role = ($tab === 'admin') ? 'Super Admin' : 'Admin';
        return $this->renderPlaceholder('Kelola Pengguna & Dosen', $role, $tab, 'Tahap 5');
    }

    public function adminDetailDosen($id = 1)
    {
        return $this->renderPlaceholder("Detail Dosen Peninjau #$id", 'Admin', 'dosen', 'Tahap 5');
    }

    public function adminKelompok()
    {
        return $this->renderPlaceholder('Kelola Kelompok PKM', 'Admin', 'kelompok', 'Tahap 5');
    }

    public function adminProposal()
    {
        return $this->renderPlaceholder('Kelola Proposal & Plotting', 'Admin', 'proposal', 'Tahap 5');
    }

    public function adminLomba()
    {
        return $this->renderPlaceholder('Kelola Informasi Lomba', 'Admin', 'lomba', 'Tahap 5');
    }

    /**
     * Helper render placeholder view agar navigasi preview tidak error
     */
    private function renderPlaceholder($title, $role, $menu, $tahap)
    {
        $roleParam = $this->request->getGet('role');
        if ($roleParam) {
            $role = $roleParam;
        }

        $html = '<div class="sipkm-card p-4 text-center">' .
            '<div class="mb-3 text-warning"><i class="bi bi-clock-history display-4"></i></div>' .
            '<h4 class="fw-bold text-dark">' . esc($title) . '</h4>' .
            '<p class="text-muted">Halaman ini adalah bagian dari <strong>' . esc($tahap) . '</strong> Frontend UI/UX SIPKM-DKKA.</p>' .
            '<div class="alert alert-info d-inline-block text-start small">' .
            '<i class="bi bi-info-circle me-1"></i> Saat ini <strong>Tahap 2 (Halaman Publik & Auth)</strong> telah selesai diimplementasikan.<br>' .
            'Ketik atau konfirmasi <strong>"Lanjut ' . esc($tahap) . '"</strong> untuk men-generate tampilan antarmuka ini.' .
            '</div>' .
            '<div class="mt-3">' .
            '<a href="' . base_url('/') . '" class="btn btn-outline-primary btn-sm me-2"><i class="bi bi-globe me-1"></i> Buka Papan Lomba Publik</a>' .
            '<a href="' . base_url('login') . '" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-in-right me-1"></i> Halaman Login</a>' .
            '</div>' .
            '</div>';

        // Render via layout main
        $content = view('layouts/main', [
            'title'      => esc($title) . ' | SIPKM-DKKA Preview',
            'pageHeader' => esc($title),
            'role'       => $role,
            'activeMenu' => $menu,
            'userName'   => 'Pengguna Preview (' . esc($role) . ')',
        ]);

        return str_replace('<?= $this->renderSection(\'content\') ?>', $html, $content);
    }
}
