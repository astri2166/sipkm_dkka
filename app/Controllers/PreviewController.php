<?php

namespace App\Controllers;

class PreviewController extends BaseController
{
    // ====================================================================
    // TAHAP 2: HALAMAN PUBLIK & AUTENTIKASI
    // ====================================================================

    public function landingLomba()
    {
        $lombaModel = new \App\Models\InformasiLombaModel();
        $daftarLomba = $lombaModel->orderBy('idLomba', 'DESC')->findAll();

        if (empty($daftarLomba)) {
            $daftarLomba = [
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
        }

        $data['daftarLomba'] = $daftarLomba;

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
    // TAHAP 4: FRONTEND UI/UX MODUL DOSEN PENINJAU
    // ====================================================================

    private function getDosenMockData()
    {
        return [
            'dosen' => [
                'idDosen'   => 1,
                'nipDosen'  => '0315088501',
                'namaDosen' => 'Dr. Ahmad Fauzi, M.T.',
                'email'     => 'ahmad.fauzi@budiluhur.ac.id',
            ],
            'masterTemplateUrl' => env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/MASTER_TEMPLATE_PENILAIAN_DKKA'),
            'daftarTugas' => [
                [
                    'idPeninjauan'        => 1,
                    'idProposal'          => 1,
                    'judulProposal'       => 'Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI',
                    'jenisPKM'            => 'PKM-KC',
                    'namaKelompok'        => 'Tim Inovasi Smart Campus UBL',
                    'nimKetua'            => '2412501001',
                    'tahunPeriode'        => '2026',
                    'tglPenugasan'        => '2026-11-11',
                    'tglSelesaiTinjau'    => '2026-11-14',
                    'statusPenugasan'     => 'Selesai',
                    'statusProposal'      => 'Sudah Ditinjau',
                    'tautanGoogleDocs'    => 'https://drive.google.com/drive/folders/1ContohFolderProposalTimUBL2026',
                    'tautanRekapDrive'    => 'https://drive.google.com/drive/folders/1HasilRekapPenilaianDosenUBL2026',
                    'catatanAdministrasi' => "1. Margin kiri sudah 4 cm, namun daftar pustaka belum menggunakan format Harvard secara konsisten.\n2. Perbaiki penomoran halaman romawi pada lembar pengesahan.",
                    'catatanSubstansi'    => "1. Latar belakang masalah sangat kuat dan relevan dengan SDG 11.\n2. Metodologi kalibrasi sensor MQ-135 perlu diperjelas pada Bab 3.\n3. Perbaiki rincian anggaran biaya pos operasional agar tidak melebihi 25% dari total plafon pendanaan.",
                ],
                [
                    'idPeninjauan'        => 2,
                    'idProposal'          => 2,
                    'judulProposal'       => 'Aplikasi Deteksi Dini Retinopati Diabetik Menggunakan Arsitektur ResNet-50',
                    'jenisPKM'            => 'PKM-KC',
                    'namaKelompok'        => 'Tim BioMedika UBL',
                    'nimKetua'            => '2412501050',
                    'tahunPeriode'        => '2026',
                    'tglPenugasan'        => '2026-11-15',
                    'tglSelesaiTinjau'    => null,
                    'statusPenugasan'     => 'Belum Ditinjau',
                    'statusProposal'      => 'Sedang Ditinjau',
                    'tautanGoogleDocs'    => 'https://docs.google.com/document/d/1ContohRetinopati2026',
                    'tautanRekapDrive'    => null,
                    'catatanAdministrasi' => '',
                    'catatanSubstansi'    => '',
                ],
                [
                    'idPeninjauan'        => 3,
                    'idProposal'          => 3,
                    'judulProposal'       => 'Pemberdayaan UMKM Sentra Pengrajin Bambu Melalui Pemasaran Digital Omnichannel',
                    'jenisPKM'            => 'PKM-PM',
                    'namaKelompok'        => 'Tim Sinergi Pengabdian UBL',
                    'nimKetua'            => '2412501088',
                    'tahunPeriode'        => '2026',
                    'tglPenugasan'        => '2026-11-12',
                    'tglSelesaiTinjau'    => '2026-11-15',
                    'statusPenugasan'     => 'Selesai',
                    'statusProposal'      => 'Sudah Ditinjau',
                    'tautanGoogleDocs'    => 'https://docs.google.com/document/d/1ContohUMKMBambu2026',
                    'tautanRekapDrive'    => 'https://drive.google.com/drive/folders/1HasilRekapUMKM2026',
                    'catatanAdministrasi' => "1. Format penomoran halaman dan margin sudah tepat 4-3-3-3.\n2. Surat kesediaan mitra pengabdian telah terlampir bermeterai 10.000.",
                    'catatanSubstansi'    => "1. Solusi transfer teknologi dan pelatihan pemasaran tepat sasaran bagi mitra.\n2. Tolok ukur peningkatan omzet mitra perlu ditambahkan target persentase kenaikan penjualan.",
                ],
            ],
        ];
    }

    public function dosenDashboard()
    {
        $mock = $this->getDosenMockData();
        $tugas = $mock['daftarTugas'];

        $countBelum = count(array_filter($tugas, fn($t) => $t['statusPenugasan'] === 'Belum Ditinjau'));
        $countSelesai = count(array_filter($tugas, fn($t) => $t['statusPenugasan'] === 'Selesai'));

        $data = [
            'title'             => 'Dashboard Dosen Peninjau | SIPKM-DKKA',
            'pageHeader'        => 'Dashboard Dosen Peninjau',
            'role'              => 'Dosen Peninjau',
            'activeMenu'        => 'dashboard',
            'userName'          => $mock['dosen']['namaDosen'],
            'dosen'             => $mock['dosen'],
            'masterTemplateUrl' => $mock['masterTemplateUrl'],
            'daftarTugas'       => $tugas,
            'countBelum'        => $countBelum,
            'countSelesai'      => $countSelesai,
        ];

        return view('dosen/dashboard', $data);
    }

    public function dosenPeninjauan()
    {
        $mock = $this->getDosenMockData();
        $statusFilter = $this->request->getGet('status') ?? '';

        $tugas = $mock['daftarTugas'];
        if ($statusFilter !== '') {
            $tugas = array_filter($tugas, fn($t) => $t['statusPenugasan'] === $statusFilter);
        }

        $data = [
            'title'             => 'Peninjauan Proposal PKM | SIPKM-DKKA',
            'pageHeader'        => 'Peninjauan Proposal PKM',
            'role'              => 'Dosen Peninjau',
            'activeMenu'        => 'peninjauan',
            'userName'          => $mock['dosen']['namaDosen'],
            'dosen'             => $mock['dosen'],
            'masterTemplateUrl' => $mock['masterTemplateUrl'],
            'daftarTugas'       => $tugas,
            'statusFilter'      => $statusFilter,
        ];

        return view('dosen/peninjauan', $data);
    }

    public function dosenDetailPeninjauan($id = 1)
    {
        $mock = $this->getDosenMockData();
        $selected = null;
        foreach ($mock['daftarTugas'] as $t) {
            if ($t['idPeninjauan'] == $id || $t['idProposal'] == $id) {
                $selected = $t;
                break;
            }
        }
        if (!$selected) {
            $selected = $mock['daftarTugas'][0];
        }

        $data = [
            'title'             => 'Evaluasi Proposal #' . $selected['idProposal'] . ' | SIPKM-DKKA',
            'pageHeader'        => 'Detail Evaluasi Peninjauan',
            'role'              => 'Dosen Peninjau',
            'activeMenu'        => 'peninjauan',
            'userName'          => $mock['dosen']['namaDosen'],
            'dosen'             => $mock['dosen'],
            'masterTemplateUrl' => $mock['masterTemplateUrl'],
            'tugas'             => $selected,
        ];

        return view('dosen/detail_peninjauan', $data);
    }

    // ====================================================================
    // TAHAP 5: FRONTEND UI/UX MODUL ADMIN DKKA & SUPER ADMIN
    // ====================================================================

    private function getAdminMockData()
    {
        return [
            'stats' => [
                'totalProposal'  => 12,
                'proposalLayak'  => 4,
                'totalKelompok'  => 15,
                'totalMahasiswa' => 48,
                'totalDosen'     => 6,
                'needPlotting'   => 2,
                'totalLomba'     => 3,
            ],
            'masterTemplateUrl' => env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/MASTER_TEMPLATE_PENILAIAN_DKKA'),
            'daftarDosen' => [
                [
                    'idDosen'      => 1,
                    'nipDosen'     => '0315088501',
                    'namaDosen'    => 'Dr. Ahmad Fauzi, M.T.',
                    'email'        => 'ahmad.fauzi@budiluhur.ac.id',
                    'tugasSelesai' => 2,
                    'tugasPending' => 1,
                    'statusAkun'   => 'Aktif',
                ],
                [
                    'idDosen'      => 2,
                    'nipDosen'     => '0322118902',
                    'namaDosen'    => 'Siti Rahmawati, S.Kom., M.Cs.',
                    'email'        => 'siti.rahmawati@budiluhur.ac.id',
                    'tugasSelesai' => 1,
                    'tugasPending' => 2,
                    'statusAkun'   => 'Aktif',
                ],
                [
                    'idDosen'      => 3,
                    'nipDosen'     => '0308048103',
                    'namaDosen'    => 'Hendra Setiawan, Ph.D.',
                    'email'        => 'hendra.setiawan@budiluhur.ac.id',
                    'tugasSelesai' => 3,
                    'tugasPending' => 0,
                    'statusAkun'   => 'Aktif',
                ],
            ],
            'daftarAdmin' => [
                [
                    'idAdmin'    => 1,
                    'nipAdmin'   => '198801012015011001',
                    'namaAdmin'  => 'Budi Santoso, M.Kom.',
                    'jabatan'    => 'Super Admin',
                    'username'   => 'superadmin',
                    'email'      => 'superadmin.dkka@budiluhur.ac.id',
                    'statusAkun' => 'Aktif',
                ],
                [
                    'idAdmin'    => 2,
                    'nipAdmin'   => '199205142018032002',
                    'namaAdmin'  => 'Rina Kartika, S.E.',
                    'jabatan'    => 'Admin DKKA',
                    'username'   => 'rina_dkka',
                    'email'      => 'rina.kartika@budiluhur.ac.id',
                    'statusAkun' => 'Aktif',
                ],
            ],
            'daftarKelompok' => [
                [
                    'idKelompok'    => 1,
                    'namaKelompok'  => 'Tim Inovasi Smart Campus UBL',
                    'tahunPeriode'  => '2026',
                    'nimKetua'      => '2412501001',
                    'namaKetua'     => 'Rizky Pratama',
                    'jumlahAnggota' => 2,
                    'jenisPKM'      => 'PKM-KC',
                    'judulProposal' => 'Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI',
                    'anggota'       => [
                        ['nim' => '2412501001', 'namaMahasiswa' => 'Rizky Pratama', 'programStudi' => 'Sistem Informasi'],
                        ['nim' => '2412501002', 'namaMahasiswa' => 'Nadia Putri', 'programStudi' => 'Teknik Informatika'],
                    ],
                ],
                [
                    'idKelompok'    => 2,
                    'namaKelompok'  => 'Tim BioMedika UBL',
                    'tahunPeriode'  => '2026',
                    'nimKetua'      => '2412501050',
                    'namaKetua'     => 'Farhan Ramadhan',
                    'jumlahAnggota' => 3,
                    'jenisPKM'      => 'PKM-KC',
                    'judulProposal' => 'Aplikasi Deteksi Dini Retinopati Diabetik Menggunakan ResNet-50',
                    'anggota'       => [
                        ['nim' => '2412501050', 'namaMahasiswa' => 'Farhan Ramadhan', 'programStudi' => 'Teknik Informatika'],
                        ['nim' => '2412501051', 'namaMahasiswa' => 'Gita Gutawa', 'programStudi' => 'Sistem Informasi'],
                        ['nim' => '2412501052', 'namaMahasiswa' => 'Haikal Kamil', 'programStudi' => 'Teknik Informatika'],
                    ],
                ],
                [
                    'idKelompok'    => 3,
                    'namaKelompok'  => 'Tim Sinergi Pengabdian UBL',
                    'tahunPeriode'  => '2026',
                    'nimKetua'      => '2412501088',
                    'namaKetua'     => 'Ilham Saputra',
                    'jumlahAnggota' => 3,
                    'jenisPKM'      => 'PKM-PM',
                    'judulProposal' => 'Pemberdayaan UMKM Sentra Pengrajin Bambu Melalui Pemasaran Digital Omnichannel',
                    'anggota'       => [
                        ['nim' => '2412501088', 'namaMahasiswa' => 'Ilham Saputra', 'programStudi' => 'Manajemen'],
                        ['nim' => '2412501089', 'namaMahasiswa' => 'Joko Anwar', 'programStudi' => 'Sistem Informasi'],
                        ['nim' => '2412501090', 'namaMahasiswa' => 'Kartika Sari', 'programStudi' => 'Ilmu Komunikasi'],
                    ],
                ],
            ],
            'daftarProposal' => [
                [
                    'idProposal'       => 1,
                    'idKelompok'       => 1,
                    'namaKelompok'     => 'Tim Inovasi Smart Campus UBL',
                    'nimKetua'         => '2412501001',
                    'judulProposal'    => 'Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI',
                    'jenisPKM'         => 'PKM-KC',
                    'tautanGoogleDocs' => 'https://drive.google.com/drive/folders/1ContohFolderProposalTimUBL2026',
                    'statusProposal'   => 'Sudah Ditinjau',
                    'tglUnggah'        => '2026-11-10',
                    'idDosen'          => 1,
                    'namaDosen'        => 'Dr. Ahmad Fauzi, M.T.',
                    'nipDosen'         => '0315088501',
                ],
                [
                    'idProposal'       => 2,
                    'idKelompok'       => 2,
                    'namaKelompok'     => 'Tim BioMedika UBL',
                    'nimKetua'         => '2412501050',
                    'judulProposal'    => 'Aplikasi Deteksi Dini Retinopati Diabetik Menggunakan ResNet-50',
                    'jenisPKM'         => 'PKM-KC',
                    'tautanGoogleDocs' => 'https://docs.google.com/document/d/1ContohRetinopati2026',
                    'statusProposal'   => 'Sedang Ditinjau',
                    'tglUnggah'        => '2026-11-12',
                    'idDosen'          => 1,
                    'namaDosen'        => 'Dr. Ahmad Fauzi, M.T.',
                    'nipDosen'         => '0315088501',
                ],
                [
                    'idProposal'       => 3,
                    'idKelompok'       => 3,
                    'namaKelompok'     => 'Tim Sinergi Pengabdian UBL',
                    'nimKetua'         => '2412501088',
                    'judulProposal'    => 'Pemberdayaan UMKM Sentra Pengrajin Bambu Melalui Pemasaran Digital Omnichannel',
                    'jenisPKM'         => 'PKM-PM',
                    'tautanGoogleDocs' => 'https://docs.google.com/document/d/1ContohUMKMBambu2026',
                    'statusProposal'   => 'Layak',
                    'tglUnggah'        => '2026-11-11',
                    'idDosen'          => 2,
                    'namaDosen'        => 'Siti Rahmawati, S.Kom., M.Cs.',
                    'nipDosen'         => '0322118902',
                ],
                [
                    'idProposal'       => 4,
                    'idKelompok'       => 4,
                    'namaKelompok'     => 'Tim AgriTech Future',
                    'nimKetua'         => '2412501099',
                    'judulProposal'    => 'Sistem Irigasi Cerdas Tanaman Hidroponik Berbasis Machine Learning',
                    'jenisPKM'         => 'PKM-PI',
                    'tautanGoogleDocs' => 'https://docs.google.com/document/d/1ContohAgriTech2026',
                    'statusProposal'   => 'Diunggah',
                    'tglUnggah'        => '2026-11-15',
                    'idDosen'          => null,
                    'namaDosen'        => null,
                    'nipDosen'         => null,
                ],
            ],
        ];
    }

    public function adminDashboard()
    {
        $role = $this->request->getGet('role') ?? 'Admin';
        $mock = $this->getAdminMockData();

        $data = [
            'title'             => 'Dashboard Administrator | SIPKM-DKKA',
            'pageHeader'        => 'Dashboard ' . ($role === 'Super Admin' ? 'Super Admin' : 'Admin DKKA'),
            'role'              => $role,
            'activeMenu'        => 'dashboard',
            'userName'          => ($role === 'Super Admin') ? 'Budi Santoso, M.Kom.' : 'Rina Kartika, S.E.',
            'stats'             => $mock['stats'],
            'masterTemplateUrl' => $mock['masterTemplateUrl'],
        ];

        return view('admin/dashboard', $data);
    }

    public function adminPengguna()
    {
        $role = $this->request->getGet('role') ?? 'Admin';
        $tab = $this->request->getGet('tab') ?? 'dosen';
        $mock = $this->getAdminMockData();

        $data = [
            'title'       => 'Kelola Pengguna Sistem | SIPKM-DKKA',
            'pageHeader'  => 'Manajemen Pengguna Sistem',
            'role'        => $role,
            'activeMenu'  => ($tab === 'admin') ? 'pengguna' : 'dosen',
            'activeTab'   => $tab,
            'userName'    => ($role === 'Super Admin') ? 'Budi Santoso, M.Kom.' : 'Rina Kartika, S.E.',
            'daftarDosen' => $mock['daftarDosen'],
            'daftarAdmin' => $mock['daftarAdmin'],
        ];

        return view('admin/pengguna', $data);
    }

    public function adminDetailDosen($id = 1)
    {
        $role = $this->request->getGet('role') ?? 'Admin';
        $mockDosen = $this->getDosenMockData();
        $dosen = $mockDosen['dosen'];

        $tugasAdminKeDosen = $mockDosen['daftarTugas'];
        $hasilDosenKeAdmin = array_filter($tugasAdminKeDosen, fn($t) => $t['statusPenugasan'] === 'Selesai');

        $data = [
            'title'              => 'Histori Dosen: ' . $dosen['namaDosen'] . ' | SIPKM-DKKA',
            'pageHeader'         => 'Detail & Histori Dosen Peninjau',
            'role'               => $role,
            'activeMenu'         => 'dosen',
            'userName'           => ($role === 'Super Admin') ? 'Budi Santoso, M.Kom.' : 'Rina Kartika, S.E.',
            'dosen'              => $dosen,
            'tugasAdminKeDosen'  => $tugasAdminKeDosen,
            'hasilDosenKeAdmin'  => $hasilDosenKeAdmin,
        ];

        return view('admin/detail_dosen', $data);
    }

    public function adminKelompok()
    {
        $role = $this->request->getGet('role') ?? 'Admin';
        $mock = $this->getAdminMockData();

        $data = [
            'title'          => 'Kelola Kelompok PKM | SIPKM-DKKA',
            'pageHeader'     => 'Pengelolaan Kelompok PKM Mahasiswa',
            'role'           => $role,
            'activeMenu'     => 'kelompok',
            'userName'       => ($role === 'Super Admin') ? 'Budi Santoso, M.Kom.' : 'Rina Kartika, S.E.',
            'daftarKelompok' => $mock['daftarKelompok'],
        ];

        return view('admin/kelompok', $data);
    }

    public function adminProposal()
    {
        $role = $this->request->getGet('role') ?? 'Admin';
        $mock = $this->getAdminMockData();

        $data = [
            'title'             => 'Kelola Proposal & Plotting | SIPKM-DKKA',
            'pageHeader'        => 'Pengelolaan Proposal & Sidang Pleno',
            'role'              => $role,
            'activeMenu'        => 'proposal',
            'userName'          => ($role === 'Super Admin') ? 'Budi Santoso, M.Kom.' : 'Rina Kartika, S.E.',
            'daftarProposal'    => $mock['daftarProposal'],
            'daftarDosen'       => $mock['daftarDosen'],
            'masterTemplateUrl' => $mock['masterTemplateUrl'],
        ];

        return view('admin/proposal', $data);
    }

    public function adminLomba()
    {
        $role = $this->request->getGet('role') ?? 'Admin';

        // Re-use data lomba dari landingLomba
        $daftarLomba = [
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

        $data = [
            'title'       => 'Kelola Papan Informasi Lomba | SIPKM-DKKA',
            'pageHeader'  => 'Manajemen Papan Informasi Lomba',
            'role'        => $role,
            'activeMenu'  => 'lomba',
            'userName'    => ($role === 'Super Admin') ? 'Budi Santoso, M.Kom.' : 'Rina Kartika, S.E.',
            'daftarLomba' => $daftarLomba,
        ];

        return view('admin/lomba', $data);
    }
}
