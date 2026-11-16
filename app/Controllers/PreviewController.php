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
    // TAHAP 3: FRONTEND UI/UX MODUL MAHASISWA
    // ====================================================================

    public function mhsDashboard()
    {
        $role = $this->request->getGet('role') ?? 'Ketua Kelompok';
        $hasKelompok = $this->request->getGet('has_kelompok') !== '0';

        $data = [
            'title'         => 'Dashboard Mahasiswa | SIPKM-DKKA',
            'pageHeader'    => 'Dashboard Mahasiswa',
            'role'          => $role,
            'activeMenu'    => 'dashboard',
            'userName'      => ($role === 'Ketua Kelompok') ? 'Rizky Pratama' : 'Nadia Putri',
            'hasKelompok'   => $hasKelompok,
            'hasProposal'   => $hasKelompok,
            'mahasiswa'     => [
                'nim'           => ($role === 'Ketua Kelompok') ? '2412501001' : '2412501002',
                'namaMahasiswa' => ($role === 'Ketua Kelompok') ? 'Rizky Pratama' : 'Nadia Putri',
                'programStudi'  => ($role === 'Ketua Kelompok') ? 'Sistem Informasi' : 'Teknik Informatika',
                'noWhatsapp'    => ($role === 'Ketua Kelompok') ? '081234567801' : '081234567802',
            ],
            'kelompok'      => [
                'idKelompok'    => 1,
                'namaKelompok'  => 'Tim Inovasi Smart Campus UBL',
                'tahunPeriode'  => '2026',
                'nimKetua'      => '2412501001',
                'jumlahAnggota' => 2,
            ],
            'daftarAnggota' => [
                [
                    'nim'           => '2412501001',
                    'namaMahasiswa' => 'Rizky Pratama',
                    'programStudi'  => 'Sistem Informasi',
                    'noWhatsapp'    => '081234567801',
                    'email'         => '2412501001@student.budiluhur.ac.id',
                ],
                [
                    'nim'           => '2412501002',
                    'namaMahasiswa' => 'Nadia Putri',
                    'programStudi'  => 'Teknik Informatika',
                    'noWhatsapp'    => '081234567802',
                    'email'         => '2412501002@student.budiluhur.ac.id',
                ],
            ],
            'proposal'      => [
                'idProposal'       => 1,
                'judulProposal'    => 'Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI',
                'jenisPKM'         => 'PKM-KC',
                'tautanGoogleDocs' => 'https://drive.google.com/drive/folders/1ContohFolderProposalTimUBL2026',
                'statusProposal'   => 'Sudah Ditinjau',
                'tglUnggah'        => '2026-11-10',
            ],
            'dosen'         => [
                'nipDosen'  => '0315088501',
                'namaDosen' => 'Dr. Ahmad Fauzi, M.T.',
            ],
        ];

        return view('mahasiswa/dashboard', $data);
    }

    public function mhsKelompok()
    {
        $role = $this->request->getGet('role') ?? 'Ketua Kelompok';
        $hasKelompok = $this->request->getGet('has_kelompok') !== '0';

        $data = [
            'title'          => 'Data Kelompok PKM | SIPKM-DKKA',
            'pageHeader'     => 'Data Kelompok PKM',
            'role'           => $role,
            'activeMenu'     => 'kelompok',
            'userName'       => ($role === 'Ketua Kelompok') ? 'Rizky Pratama' : 'Nadia Putri',
            'hasKelompok'    => $hasKelompok,
            'kodeKelompok'   => 'PKM-UBL-2026-X89',
            'mahasiswa'      => [
                'nim'           => ($role === 'Ketua Kelompok') ? '2412501001' : '2412501002',
                'namaMahasiswa' => ($role === 'Ketua Kelompok') ? 'Rizky Pratama' : 'Nadia Putri',
                'programStudi'  => ($role === 'Ketua Kelompok') ? 'Sistem Informasi' : 'Teknik Informatika',
            ],
            'kelompok'       => [
                'idKelompok'    => 1,
                'namaKelompok'  => 'Tim Inovasi Smart Campus UBL',
                'tahunPeriode'  => '2026',
                'nimKetua'      => '2412501001',
                'jumlahAnggota' => 2,
            ],
            'daftarAnggota'  => [
                [
                    'nim'           => '2412501001',
                    'namaMahasiswa' => 'Rizky Pratama',
                    'programStudi'  => 'Sistem Informasi',
                    'noWhatsapp'    => '081234567801',
                    'email'         => '2412501001@student.budiluhur.ac.id',
                ],
                [
                    'nim'           => '2412501002',
                    'namaMahasiswa' => 'Nadia Putri',
                    'programStudi'  => 'Teknik Informatika',
                    'noWhatsapp'    => '081234567802',
                    'email'         => '2412501002@student.budiluhur.ac.id',
                ],
            ],
            'mahasiswaBebas' => [
                [
                    'nim'           => '2412501872',
                    'namaMahasiswa' => 'Alya Zahra Maharani',
                    'programStudi'  => 'Sistem Informasi',
                    'noWhatsapp'    => '081234567872',
                ],
                [
                    'nim'           => '2412501873',
                    'namaMahasiswa' => 'Dimas Arya Sena',
                    'programStudi'  => 'Teknik Informatika',
                    'noWhatsapp'    => '081234567873',
                ],
            ],
        ];

        return view('mahasiswa/kelompok', $data);
    }

    public function mhsProposal()
    {
        $role = $this->request->getGet('role') ?? 'Ketua Kelompok';

        $data = [
            'title'       => 'Input Proposal PKM | SIPKM-DKKA',
            'pageHeader'  => 'Input Proposal PKM',
            'role'        => $role,
            'activeMenu'  => 'proposal',
            'userName'    => ($role === 'Ketua Kelompok') ? 'Rizky Pratama' : 'Nadia Putri',
            'hasProposal' => true,
            'mahasiswa'   => [
                'nim'           => '2412501001',
                'namaMahasiswa' => 'Rizky Pratama',
            ],
            'kelompok'    => [
                'idKelompok'   => 1,
                'namaKelompok' => 'Tim Inovasi Smart Campus UBL',
                'tahunPeriode' => '2026',
                'nimKetua'     => '2412501001',
            ],
            'proposal'    => [
                'idProposal'       => 1,
                'judulProposal'    => 'Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI',
                'jenisPKM'         => 'PKM-KC',
                'tautanGoogleDocs' => 'https://drive.google.com/drive/folders/1ContohFolderProposalTimUBL2026',
                'statusProposal'   => 'Sudah Ditinjau',
                'tglUnggah'        => '2026-11-10',
            ],
        ];

        return view('mahasiswa/proposal', $data);
    }

    public function mhsTracking()
    {
        $role = $this->request->getGet('role') ?? 'Ketua Kelompok';
        $statusParam = $this->request->getGet('status') ?? 'Sudah Ditinjau';

        $data = [
            'title'          => 'Tracking Status Proposal | SIPKM-DKKA',
            'pageHeader'     => 'Tracking Status Proposal',
            'role'           => $role,
            'activeMenu'     => 'tracking',
            'userName'       => ($role === 'Ketua Kelompok') ? 'Rizky Pratama' : 'Nadia Putri',
            'statusProposal' => $statusParam,
            'kelompok'       => [
                'idKelompok'   => 1,
                'namaKelompok' => 'Tim Inovasi Smart Campus UBL',
                'tahunPeriode' => '2026',
                'nimKetua'     => '2412501001',
            ],
            'proposal'       => [
                'idProposal'       => 1,
                'judulProposal'    => 'Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI',
                'jenisPKM'         => 'PKM-KC',
                'tautanGoogleDocs' => 'https://drive.google.com/drive/folders/1ContohFolderProposalTimUBL2026',
                'statusProposal'   => $statusParam,
                'tglUnggah'        => '2026-11-10',
            ],
            'dosen'          => [
                'nipDosen'  => '0315088501',
                'namaDosen' => 'Dr. Ahmad Fauzi, M.T.',
            ],
            'peninjauan'     => [
                'idPeninjauan'        => 1,
                'catatanAdministrasi' => "1. Margin kiri sudah 4 cm, namun daftar pustaka belum menggunakan format Harvard secara konsisten.\n2. Perbaiki penomoran halaman romawi pada lembar pengesahan.",
                'catatanSubstansi'    => "1. Latar belakang masalah sangat kuat dan relevan dengan SDG 11.\n2. Metodologi kalibrasi sensor MQ-135 perlu diperjelas pada Bab 3.\n3. Perbaiki rincian anggaran biaya pos operasional agar tidak melebihi 25% dari total plafon pendanaan.",
                'tautanRekapDrive'    => 'https://drive.google.com/drive/folders/1HasilRekapPenilaianDosenUBL2026',
                'statusPenugasan'     => 'Selesai',
                'tglPenugasan'        => '2026-11-11',
                'tglSelesaiTinjau'    => '2026-11-14',
            ],
        ];

        return view('mahasiswa/tracking', $data);
    }

    // ====================================================================
    // STUB / PLACEHOLDER UNTUK TAHAP SELANJUTNYA (TAHAP 4 - 5)
    // ====================================================================

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
            '<i class="bi bi-info-circle me-1"></i> Saat ini <strong>Tahap 2 & Tahap 3 (Frontend Mahasiswa)</strong> telah selesai diimplementasikan.<br>' .
            'Ketik atau konfirmasi <strong>"Lanjut ' . esc($tahap) . '"</strong> untuk men-generate tampilan antarmuka ini.' .
            '</div>' .
            '<div class="mt-3">' .
            '<a href="' . base_url('mahasiswa/dashboard') . '" class="btn btn-outline-primary btn-sm me-2"><i class="bi bi-speedometer2 me-1"></i> Dashboard Mahasiswa</a>' .
            '<a href="' . base_url('/') . '" class="btn btn-primary btn-sm"><i class="bi bi-globe me-1"></i> Papan Lomba Publik</a>' .
            '</div>' .
            '</div>';

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
