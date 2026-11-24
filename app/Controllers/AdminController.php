<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KelompokPkmModel;
use App\Models\ProposalPkmModel;
use App\Models\PeninjauanProposalModel;
use App\Models\InformasiLombaModel;

class AdminController extends BaseController
{
    protected UserModel $userModel;
    protected KelompokPkmModel $kelompokModel;
    protected ProposalPkmModel $proposalModel;
    protected PeninjauanProposalModel $peninjauanModel;
    protected InformasiLombaModel $lombaModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->kelompokModel   = new KelompokPkmModel();
        $this->proposalModel   = new ProposalPkmModel();
        $this->peninjauanModel = new PeninjauanProposalModel();
        $this->lombaModel      = new InformasiLombaModel();
    }

    /**
     * Helper mendapatkan data Administrator DKKA aktif dari sesi login / database
     */
    private function getAdminAktif(): array
    {
        $idUser = session()->get('id_user') ?? session()->get('idUser');
        $admin = null;

        if ($idUser) {
            $admin = $this->userModel->findByUserId((int) $idUser);
        }

        if (! $admin) {
            $admin = $this->userModel->where('role', 'Admin')->first();
            $admin = $this->userModel->formatUserAliases($admin);
        }

        if (! $admin) {
            $admin = [
                'idAdmin'   => 2,
                'idUser'    => 2,
                'id_user'   => 2,
                'nipAdmin'  => '199205142018032002',
                'namaAdmin' => 'Rina Kartika, S.E.',
                'jabatan'   => 'Admin DKKA',
                'role'      => 'Admin',
            ];
        }

        return $admin;
    }

    /**
     * Dashboard Utama Administrator DKKA
     */
    public function dashboard()
    {
        $admin = $this->getAdminAktif();

        // Agregasi statistik operasional aktual dari 5 tabel database
        $stats = [
            'totalProposal'  => $this->proposalModel->countAllResults(),
            'proposalLayak'  => $this->proposalModel->where('statusProposal', 'Layak')->countAllResults(),
            'totalKelompok'  => $this->kelompokModel->countAllResults(),
            'totalMahasiswa' => $this->userModel->whereIn('role', ['Ketua', 'Member'])->where('status_akun', 'Aktif')->countAllResults(),
            'totalDosen'     => $this->userModel->where('role', 'Dosen')->where('status_akun', 'Aktif')->countAllResults(),
            'needPlotting'   => $this->proposalModel->where('statusProposal', 'Diunggah')->countAllResults(),
            'totalLomba'     => $this->lombaModel->countAllResults(),
        ];

        $masterTemplateUrl      = env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/16nQdJOWCSaw7Da5kdwJt2rxSq8EfWlk-?usp=sharing');
        $daftarLombaTerbaru     = $this->lombaModel->orderBy('idLomba', 'DESC')->findAll(3);
        $daftarProposalPlotting = $this->proposalModel->getProposalDashboard(10);
        $daftarDosen            = $this->userModel->getAllDosen();

        // Hitung sebaran aktual seluruh 10 Bidang PKM Belmawa
        $sebaranRaw = $this->proposalModel->select('jenisPKM, COUNT(*) as total')
                                          ->groupBy('jenisPKM')
                                          ->findAll();
        $sebaranMap = [];
        foreach ($sebaranRaw as $sr) {
            $sebaranMap[strtoupper(trim($sr['jenisPKM']))] = (int) $sr['total'];
        }

        $daftar10Skema = [
            'PKM-RE'  => ['nama' => 'Riset Eksakta', 'color' => '#0284c7'],
            'PKM-RSH' => ['nama' => 'Riset Sosial Humaniora', 'color' => '#2563eb'],
            'PKM-K'   => ['nama' => 'Kewirausahaan', 'color' => '#d97706'],
            'PKM-PM'  => ['nama' => 'Pengabdian kepada Masyarakat', 'color' => '#16a34a'],
            'PKM-PI'  => ['nama' => 'Penerapan IPTEK', 'color' => '#0d9488'],
            'PKM-KC'  => ['nama' => 'Karsa Cipta', 'color' => '#7c3aed'],
            'PKM-KI'  => ['nama' => 'Karya Inovatif', 'color' => '#dc2626'],
            'PKM-VGK' => ['nama' => 'Video Gagasan Konstruktif', 'color' => '#9333ea'],
            'PKM-GFT' => ['nama' => 'Gagasan Futuristik Tertulis', 'color' => '#db2777'],
            'PKM-AI'  => ['nama' => 'Artikel Ilmiah', 'color' => '#475569'],
        ];

        $totalProp = (int) ($stats['totalProposal'] ?? 0);
        $sebaran10Bidang = [];
        foreach ($daftar10Skema as $kode => $info) {
            $cnt = 0;
            foreach ($sebaranMap as $dbKode => $val) {
                if ($dbKode === $kode || strpos($dbKode, $kode) !== false) {
                    $cnt += $val;
                }
            }
            $pct = $totalProp > 0 ? round(($cnt / $totalProp) * 100, 1) : 0;
            $sebaran10Bidang[] = [
                'kode'       => $kode,
                'nama'       => $info['nama'],
                'color'      => $info['color'],
                'total'      => $cnt,
                'persentase' => $pct,
            ];
        }

        $data = [
            'title'                  => 'Dashboard Administrator DKKA | SIPKM-DKKA',
            'pageHeader'             => 'Dashboard Admin DKKA',
            'role'                   => 'Admin',
            'activeMenu'             => 'dashboard',
            'userName'               => $admin['namaAdmin'],
            'stats'                  => $stats,
            'masterTemplateUrl'      => $masterTemplateUrl,
            'daftarLombaTerbaru'     => $daftarLombaTerbaru,
            'daftarProposalPlotting' => $daftarProposalPlotting,
            'daftarDosen'            => $daftarDosen,
            'sebaran10Bidang'        => $sebaran10Bidang,
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * 1. Kelola Dosen Peninjau: Tampilkan Daftar Dosen & Beban Tugas
     */
    public function dosen()
    {
        $admin = $this->getAdminAktif();
        $daftarDosen = $this->userModel->getDosenWithStats();

        $data = [
            'title'       => 'Kelola Dosen Peninjau | SIPKM-DKKA',
            'pageHeader'  => 'Manajemen Dosen Peninjau PKM',
            'role'        => 'Admin',
            'activeMenu'  => 'dosen',
            'userName'    => $admin['namaAdmin'],
            'daftarDosen' => $daftarDosen,
        ];

        return view('admin/dosen', $data);
    }

    /**
     * Tambah Akun Dosen Peninjau Baru (1x INSERT langsung ke tabel users)
     */
    public function tambahDosen()
    {
        $rules = [
            'nipDosen'  => 'required|min_length[5]|max_length[30]|is_unique[users.nip]',
            'namaDosen' => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi data Dosen Peninjau dengan benar.');
        }

        $nipDosen  = trim($this->request->getPost('nipDosen'));
        $namaDosen = trim($this->request->getPost('namaDosen'));
        $email     = trim($this->request->getPost('email'));

        $inserted = $this->userModel->insert([
            'id_kelompok'   => null,
            'username'      => $nipDosen,
            'email'         => $email,
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'nama_lengkap'  => $namaDosen,
            'role'          => 'Dosen',
            'nip'           => $nipDosen,
            'nim'           => null,
            'program_studi' => null,
            'jabatan'       => null,
            'status_akun'   => 'Aktif',
        ]);

        if (! $inserted) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan dosen baru ke sistem.');
        }

        return redirect()->to(base_url('admin/dosen'))
                         ->with('success', 'Dosen Peninjau ' . esc($namaDosen) . ' berhasil didaftarkan!');
    }

    /**
     * Edit Data Dosen Peninjau langsung di tabel users
     */
    public function editDosen(int $idDosen)
    {
        $dosen = $this->userModel->find($idDosen);
        if (! $dosen || $dosen['role'] !== 'Dosen') {
            return redirect()->back()->with('error', 'Data Dosen Peninjau tidak ditemukan.');
        }

        $rules = [
            'nipDosen'  => "required|min_length[5]|max_length[30]|is_unique[users.nip,id_user,{$idDosen}]",
            'namaDosen' => 'required|min_length[3]|max_length[100]',
            'email'     => "required|valid_email|max_length[100]|is_unique[users.email,id_user,{$idDosen}]",
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon periksa kembali kolom data dosen yang diubah.');
        }

        $nipDosen  = trim($this->request->getPost('nipDosen'));
        $namaDosen = trim($this->request->getPost('namaDosen'));
        $email     = trim($this->request->getPost('email'));

        $userData = [
            'username'     => $nipDosen,
            'email'        => $email,
            'nama_lengkap' => $namaDosen,
            'nip'          => $nipDosen,
        ];

        $passwordBaru = $this->request->getPost('password');
        if (! empty($passwordBaru)) {
            $userData['password'] = password_hash($passwordBaru, PASSWORD_BCRYPT);
        }

        $this->userModel->update($idDosen, $userData);

        return redirect()->to(base_url('admin/dosen'))
                         ->with('success', 'Data Dosen Peninjau ' . esc($namaDosen) . ' berhasil diperbarui!');
    }

    /**
     * Mengubah Status Akun Dosen (Aktif / Nonaktif)
     */
    public function toggleStatusDosen(int $idUser)
    {
        $user = $this->userModel->find($idUser);
        if (! $user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $currentStatus = $user['status_akun'] ?? $user['statusAkun'] ?? 'Aktif';
        $newStatus = ($currentStatus === 'Aktif') ? 'Nonaktif' : 'Aktif';
        $this->userModel->update($idUser, ['status_akun' => $newStatus]);

        return redirect()->to(base_url('admin/dosen'))
                         ->with('success', 'Status akun dosen ' . esc($user['username']) . ' berhasil diubah menjadi: ' . $newStatus);
    }

    /**
     * Halaman Detail & Histori Dosen Peninjau (2 Tab: Pengajuan Peninjauan & Hasil Peninjauan)
     */
    public function detailDosen(int $idDosen)
    {
        $admin = $this->getAdminAktif();

        $dosen = $this->userModel->findByUserId($idDosen);
        if (! $dosen || $dosen['role'] !== 'Dosen') {
            return redirect()->to(base_url('admin/dosen'))->with('error', 'Data Dosen Peninjau tidak ditemukan.');
        }

        // Tab 1 & Tab 2 Query Penugasan berdasarkan users.id_user milik Dosen
        $tugasAdminKeDosen = $this->peninjauanModel->getTugasByDosen($idDosen);
        $hasilDosenKeAdmin = array_filter($tugasAdminKeDosen, fn($t) => $t['statusPenugasan'] === 'Selesai');

        $data = [
            'title'             => 'Histori Dosen: ' . $dosen['namaDosen'] . ' | SIPKM-DKKA',
            'pageHeader'        => 'Detail & Histori Dosen Peninjau',
            'role'              => 'Admin',
            'activeMenu'        => 'dosen',
            'userName'          => $admin['namaAdmin'],
            'dosen'             => $dosen,
            'tugasAdminKeDosen' => $tugasAdminKeDosen,
            'hasilDosenKeAdmin' => array_values($hasilDosenKeAdmin),
        ];

        return view('admin/detail_dosen', $data);
    }

    /**
     * 2. Kelola Kelompok PKM: Daftar Kelompok, Susunan Anggota, & Filter
     */
    public function kelompok()
    {
        $admin = $this->getAdminAktif();
        $daftarKelompok = $this->kelompokModel->getKelompokWithDetails();

        // Ambil daftar anggota untuk modal rincian tiap kelompok dari tabel users
        foreach ($daftarKelompok as &$k) {
            $k['anggota'] = $this->userModel->getAnggotaByKelompok((int) $k['idKelompok']);
        }

        $data = [
            'title'          => 'Kelola Kelompok PKM | SIPKM-DKKA',
            'pageHeader'     => 'Pengelolaan Kelompok PKM Mahasiswa',
            'role'           => 'Admin',
            'activeMenu'     => 'kelompok',
            'userName'       => $admin['namaAdmin'],
            'daftarKelompok' => $daftarKelompok,
        ];

        return view('admin/kelompok', $data);
    }

    /**
     * Hapus / Pembubaran Kelompok PKM
     */
    public function hapusKelompok(int $idKelompok)
    {
        $kelompok = $this->kelompokModel->find($idKelompok);
        if (! $kelompok) {
            return redirect()->back()->with('error', 'Kelompok tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Lepas asosiasi seluruh anggota di tabel users: reset id_kelompok = null & kembalikan role 'Member'
        $anggota = $this->userModel->getAnggotaByKelompok($idKelompok);
        foreach ($anggota as $ang) {
            $this->userModel->update($ang['id_user'], [
                'id_kelompok' => null,
                'role'        => 'Member',
            ]);
        }

        // 2. Hapus proposal dan peninjauan jika ada
        $proposal = $this->proposalModel->findByKelompokId($idKelompok);
        if ($proposal) {
            $this->peninjauanModel->where('idProposal', $proposal['idProposal'])->delete();
            $this->proposalModel->delete($proposal['idProposal']);
        }

        // 3. Hapus kelompok
        $this->kelompokModel->delete($idKelompok);

        $db->transComplete();

        return redirect()->to(base_url('admin/kelompok'))->with('success', 'Kelompok ' . esc($kelompok['namaKelompok']) . ' berhasil dibubarkan.');
    }

    /**
     * 3. Kelola Proposal PKM & Sidang Pleno Penetapan Kelayakan
     */
    public function proposal()
    {
        $admin = $this->getAdminAktif();

        $daftarProposal = $this->proposalModel->getProposalWithPlotting();
        $daftarDosen    = $this->userModel->getAllDosen();

        $masterTemplateUrl = env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/16nQdJOWCSaw7Da5kdwJt2rxSq8EfWlk-?usp=sharing');

        $data = [
            'title'             => 'Kelola Proposal & Plotting | SIPKM-DKKA',
            'pageHeader'        => 'Pengelolaan Proposal & Sidang Pleno',
            'role'              => 'Admin',
            'activeMenu'        => 'proposal',
            'userName'          => $admin['namaAdmin'],
            'daftarProposal'    => $daftarProposal,
            'daftarDosen'       => $daftarDosen,
            'masterTemplateUrl' => $masterTemplateUrl,
        ];

        return view('admin/proposal', $data);
    }

    /**
     * Plotting Satuan: Penugasan 1 Dosen ke 1 Proposal (id_user Dosen)
     */
    public function plottingDosen()
    {
        $idProposal = (int) $this->request->getPost('idProposal');
        $idDosen    = (int) $this->request->getPost('idDosen'); // id_user dari Dosen Peninjau

        if (empty($idProposal) || empty($idDosen)) {
            return redirect()->back()->with('error', 'Mohon pilih proposal dan dosen peninjau dengan lengkap.');
        }

        $dosen = $this->userModel->findByUserId($idDosen);
        $proposal = $this->proposalModel->find($idProposal);

        if (! $dosen || $dosen['role'] !== 'Dosen' || ! $proposal) {
            return redirect()->back()->with('error', 'Data proposal atau dosen tidak valid.');
        }

        $peninjauan = $this->peninjauanModel->findByProposalId($idProposal);

        if ($peninjauan) {
            $this->peninjauanModel->update($peninjauan['idPeninjauan'], [
                'id_user'         => $idDosen,
                'statusPenugasan' => 'Belum Ditinjau',
                'tglPenugasan'    => date('Y-m-d'),
            ]);
        } else {
            $this->peninjauanModel->insert([
                'idProposal'      => $idProposal,
                'id_user'         => $idDosen,
                'statusPenugasan' => 'Belum Ditinjau',
                'tglPenugasan'    => date('Y-m-d'),
            ]);
        }

        if ($proposal['statusProposal'] === 'Diunggah') {
            $this->proposalModel->update($idProposal, ['statusProposal' => 'Sedang Ditinjau']);
        }

        $redirectTo = trim((string) $this->request->getPost('redirect_to'));
        $targetUrl  = ! empty($redirectTo) ? $redirectTo : base_url('admin/proposal');

        return redirect()->to($targetUrl)
                         ->with('success', 'Proposal #' . $idProposal . ' berhasil ditugaskan ke Dosen Peninjau: ' . esc($dosen['namaDosen']));
    }

    /**
     * Plotting Massal (Bulk Plotting Checklist) ke Satu Dosen Peninjau
     */
    public function bulkPlottingDosen()
    {
        $idDosen = (int) $this->request->getPost('idDosen');
        $selectedStr = $this->request->getPost('selectedProposals');

        $proposals = is_array($selectedStr) ? $selectedStr : explode(',', (string) $selectedStr);
        $proposals = array_filter(array_map('intval', $proposals));

        if (empty($idDosen) || empty($proposals)) {
            return redirect()->back()->with('error', 'Pilih minimal satu proposal dan tentukan dosen peninjau.');
        }

        $dosen = $this->userModel->findByUserId($idDosen);
        if (! $dosen || $dosen['role'] !== 'Dosen') {
            return redirect()->back()->with('error', 'Dosen peninjau tidak valid atau tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($proposals as $idProp) {
            $peninjauan = $this->peninjauanModel->findByProposalId($idProp);
            if ($peninjauan) {
                $this->peninjauanModel->update($peninjauan['idPeninjauan'], [
                    'id_user'         => $idDosen,
                    'statusPenugasan' => 'Belum Ditinjau',
                    'tglPenugasan'    => date('Y-m-d'),
                ]);
            } else {
                $this->peninjauanModel->insert([
                    'idProposal'      => $idProp,
                    'id_user'         => $idDosen,
                    'statusPenugasan' => 'Belum Ditinjau',
                    'tglPenugasan'    => date('Y-m-d'),
                ]);
            }

            $prop = $this->proposalModel->find($idProp);
            if ($prop && $prop['statusProposal'] === 'Diunggah') {
                $this->proposalModel->update($idProp, ['statusProposal' => 'Sedang Ditinjau']);
            }
        }

        $db->transComplete();

        return redirect()->to(base_url('admin/proposal'))
                         ->with('success', count($proposals) . ' proposal berhasil di-plotting secara serentak ke ' . esc($dosen['namaDosen']) . '!');
    }

    /**
     * Penetapan Status Kelayakan Sidang Pleno DKKA (Layak / Tidak Layak)
     */
    public function tetapkanKelayakan()
    {
        $idProposal     = (int) $this->request->getPost('idProposal');
        $statusProposal = $this->request->getPost('statusProposal');

        if (! in_array($statusProposal, ['Layak', 'Tidak Layak'], true)) {
            return redirect()->back()->with('error', 'Status kelayakan hanya boleh Layak atau Tidak Layak.');
        }

        $proposal = $this->proposalModel->find($idProposal);
        if (! $proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }

        $this->proposalModel->update($idProposal, [
            'statusProposal' => $statusProposal,
        ]);

        $statusText = ($statusProposal === 'Layak') ? 'LAYAK DIDANAI' : 'TIDAK LAYAK';
        return redirect()->to(base_url('admin/proposal'))
                         ->with('success', 'Sidang Pleno: Usulan Proposal #' . $idProposal . ' resmi ditetapkan: ' . $statusText . '!');
    }

    /**
     * 4. Kelola Papan Informasi Lomba Publik
     */
    public function lomba()
    {
        $admin = $this->getAdminAktif();
        $daftarLomba = $this->lombaModel->getActiveLomba();

        $data = [
            'title'       => 'Kelola Papan Informasi Lomba | SIPKM-DKKA',
            'pageHeader'  => 'Manajemen Papan Informasi Lomba',
            'role'        => 'Admin',
            'activeMenu'  => 'lomba',
            'userName'    => $admin['namaAdmin'],
            'daftarLomba' => $daftarLomba,
        ];

        return view('admin/lomba', $data);
    }

    /**
     * Tambah & Publikasikan Informasi Lomba Baru (dengan Upload Poster)
     */
    public function tambahLomba()
    {
        $admin = $this->getAdminAktif();

        $rules = [
            'judulLomba'      => 'required|min_length[5]|max_length[200]',
            'kategoriLomba'   => 'required',
            'timelineMulai'   => 'required|valid_date[Y-m-d]',
            'timelineSelesai' => 'required|valid_date[Y-m-d]',
            'deskripsiLomba'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi formulir informasi lomba dengan benar.');
        }

        $posterName = 'poster-pkm-2026.jpg';

        $filePoster = $this->request->getFile('poster');
        if ($filePoster && $filePoster->isValid() && ! $filePoster->hasMoved()) {
            $newName = $filePoster->getRandomName();
            $filePoster->move(FCPATH . 'assets/uploads/poster', $newName);
            $posterName = $newName;
        }

        $this->lombaModel->insert([
            'id_user'         => $admin['id_user'],
            'judulLomba'      => trim($this->request->getPost('judulLomba')),
            'kategoriLomba'   => trim($this->request->getPost('kategoriLomba')),
            'timelineMulai'   => $this->request->getPost('timelineMulai'),
            'timelineSelesai' => $this->request->getPost('timelineSelesai'),
            'deskripsiLomba'  => trim($this->request->getPost('deskripsiLomba')),
            'posterBanner'    => $posterName,
        ]);

        return redirect()->to(base_url('admin/lomba'))
                         ->with('success', 'Informasi lomba baru berhasil dipublikasikan ke papan pengumuman publik!');
    }

    /**
     * Hapus Informasi Lomba
     */
    public function hapusLomba(int $idLomba)
    {
        $lomba = $this->lombaModel->find($idLomba);
        if (! $lomba) {
            return redirect()->back()->with('error', 'Informasi lomba tidak ditemukan.');
        }

        $this->lombaModel->delete($idLomba);

        return redirect()->to(base_url('admin/lomba'))
                         ->with('success', 'Informasi lomba ' . esc($lomba['judulLomba']) . ' berhasil dihapus.');
    }
}
