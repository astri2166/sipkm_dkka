<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\DosenPeninjauModel;
use App\Models\MahasiswaModel;
use App\Models\KelompokPkmModel;
use App\Models\ProposalPkmModel;
use App\Models\PeninjauanProposalModel;
use App\Models\InformasiLombaModel;

class AdminController extends BaseController
{
    protected UserModel $userModel;
    protected AdminModel $adminModel;
    protected DosenPeninjauModel $dosenModel;
    protected MahasiswaModel $mahasiswaModel;
    protected KelompokPkmModel $kelompokModel;
    protected ProposalPkmModel $proposalModel;
    protected PeninjauanProposalModel $peninjauanModel;
    protected InformasiLombaModel $lombaModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->adminModel      = new AdminModel();
        $this->dosenModel      = new DosenPeninjauModel();
        $this->mahasiswaModel  = new MahasiswaModel();
        $this->kelompokModel   = new KelompokPkmModel();
        $this->proposalModel   = new ProposalPkmModel();
        $this->peninjauanModel = new PeninjauanProposalModel();
        $this->lombaModel      = new InformasiLombaModel();
    }

    /**
     * Helper mendapatkan data administrator aktif dari sesi login
     */
    private function getAdminAktif(): array
    {
        $idUser = session()->get('idUser');
        $admin  = $this->adminModel->findByUserId((int) $idUser);

        if (! $admin) {
            $role = session()->get('role') ?? 'Admin';
            $admin = [
                'idAdmin'   => 1,
                'idUser'    => $idUser ?? 1,
                'nipAdmin'  => '198801012015011001',
                'namaAdmin' => ($role === 'Super Admin') ? 'Budi Santoso, M.Kom.' : 'Rina Kartika, S.E.',
                'jabatan'   => ($role === 'Super Admin') ? 'Super Admin' : 'Admin DKKA',
            ];
        }

        return $admin;
    }

    /**
     * Dashboard Utama Administrator & Super Admin
     */
    public function dashboard()
    {
        $admin = $this->getAdminAktif();
        $role  = session()->get('role') ?? 'Admin';

        // Agregasi statistik operasional aktual dari database
        $stats = [
            'totalProposal'  => $this->proposalModel->countAllResults(),
            'proposalLayak'  => $this->proposalModel->where('statusProposal', 'Layak')->countAllResults(),
            'totalKelompok'  => $this->kelompokModel->countAllResults(),
            'totalMahasiswa' => $this->mahasiswaModel->countAllResults(),
            'totalDosen'     => $this->dosenModel->countAllResults(),
            'needPlotting'   => $this->proposalModel->where('statusProposal', 'Diunggah')->countAllResults(),
            'totalLomba'     => $this->lombaModel->countAllResults(),
        ];

        $masterTemplateUrl = env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/MASTER_TEMPLATE_PENILAIAN_DKKA');

        $data = [
            'title'             => 'Dashboard Administrator | SIPKM-DKKA',
            'pageHeader'        => 'Dashboard ' . ($role === 'Super Admin' ? 'Super Admin' : 'Admin DKKA'),
            'role'              => $role,
            'activeMenu'        => 'dashboard',
            'userName'          => $admin['namaAdmin'],
            'stats'             => $stats,
            'masterTemplateUrl' => $masterTemplateUrl,
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Manajemen Pengguna Sistem (Dosen Peninjau & Admin DKKA)
     */
    public function pengguna()
    {
        $admin = $this->getAdminAktif();
        $role  = session()->get('role') ?? 'Admin';
        $tab   = $this->request->getGet('tab') ?? 'dosen';

        $daftarDosen = $this->dosenModel->getDosenWithStats();
        $daftarAdmin = $this->adminModel->getAdminWithUser();

        $data = [
            'title'       => 'Kelola Pengguna Sistem | SIPKM-DKKA',
            'pageHeader'  => 'Manajemen Pengguna Sistem',
            'role'        => $role,
            'activeMenu'  => ($tab === 'admin') ? 'pengguna' : 'dosen',
            'activeTab'   => $tab,
            'userName'    => $admin['namaAdmin'],
            'daftarDosen' => $daftarDosen,
            'daftarAdmin' => $daftarAdmin,
        ];

        return view('admin/pengguna', $data);
    }

    /**
     * Tambah Akun Administrator DKKA Baru (Khusus Super Admin)
     */
    public function tambahAdmin()
    {
        $role = session()->get('role');
        if ($role !== 'Super Admin') {
            return redirect()->to(base_url('admin/pengguna?tab=admin'))->with('error', 'Akses ditolak: Hanya Super Admin yang berwenang menambah akun administrator.');
        }

        $rules = [
            'nipAdmin'  => 'required|min_length[5]|max_length[30]|is_unique[admin.nipAdmin]',
            'namaAdmin' => 'required|min_length[3]|max_length[100]',
            'jabatan'   => 'required|in_list[Admin DKKA,Super Admin]',
            'username'  => 'required|min_length[4]|max_length[50]|is_unique[user.username]',
            'email'     => 'required|valid_email|max_length[100]|is_unique[user.email]',
            'password'  => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi seluruh kolom isian akun admin dengan benar.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insert ke tabel user
        $this->userModel->insert([
            'username'   => trim($this->request->getPost('username')),
            'email'      => trim($this->request->getPost('email')),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'       => $this->request->getPost('jabatan'),
            'statusAkun' => 'Aktif',
        ]);
        $idUser = $this->userModel->getInsertID();

        // 2. Insert ke tabel admin
        $this->adminModel->insert([
            'idUser'    => $idUser,
            'nipAdmin'  => trim($this->request->getPost('nipAdmin')),
            'namaAdmin' => trim($this->request->getPost('namaAdmin')),
            'jabatan'   => $this->request->getPost('jabatan'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan admin baru ke sistem.');
        }

        return redirect()->to(base_url('admin/pengguna?tab=admin'))
                         ->with('success', 'Akun Administrator ' . esc($this->request->getPost('namaAdmin')) . ' berhasil didaftarkan!');
    }

    /**
     * Tambah Akun Dosen Peninjau Baru
     */
    public function tambahDosen()
    {
        $rules = [
            'nipDosen'  => 'required|min_length[5]|max_length[30]|is_unique[dosen_peninjau.nipDosen]',
            'namaDosen' => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|max_length[100]|is_unique[dosen_peninjau.email]|is_unique[user.email]',
            'password'  => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi data Dosen Peninjau dengan benar.');
        }

        $nipDosen  = trim($this->request->getPost('nipDosen'));
        $namaDosen = trim($this->request->getPost('namaDosen'));
        $email     = trim($this->request->getPost('email'));

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insert ke tabel user
        $this->userModel->insert([
            'username'   => $nipDosen,
            'email'      => $email,
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'       => 'Dosen Peninjau',
            'statusAkun' => 'Aktif',
        ]);
        $idUser = $this->userModel->getInsertID();

        // 2. Insert ke tabel dosen_peninjau
        $this->dosenModel->insert([
            'idUser'    => $idUser,
            'nipDosen'  => $nipDosen,
            'namaDosen' => $namaDosen,
            'email'     => $email,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan dosen baru ke sistem.');
        }

        return redirect()->to(base_url('admin/pengguna?tab=dosen'))
                         ->with('success', 'Dosen Peninjau ' . esc($namaDosen) . ' berhasil ditambahkan!');
    }

    /**
     * Mengubah Status Akun Pengguna (Aktif / Nonaktif)
     */
    public function toggleStatusPengguna(int $idUser)
    {
        $user = $this->userModel->find($idUser);
        if (! $user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $newStatus = ($user['statusAkun'] === 'Aktif') ? 'Nonaktif' : 'Aktif';
        $this->userModel->update($idUser, ['statusAkun' => $newStatus]);

        return redirect()->back()->with('success', 'Status akun pengguna ' . esc($user['username']) . ' berhasil diubah menjadi: ' . $newStatus);
    }

    /**
     * Halaman Detail & Histori Dosen Peninjau (2 Tab: Penugasan & Hasil)
     */
    public function detailDosen(int $idDosen)
    {
        $admin = $this->getAdminAktif();
        $role  = session()->get('role') ?? 'Admin';

        $dosen = $this->dosenModel->find($idDosen);
        if (! $dosen) {
            return redirect()->to(base_url('admin/pengguna?tab=dosen'))->with('error', 'Data Dosen Peninjau tidak ditemukan.');
        }

        // Tab 1 & Tab 2 Query Penugasan
        $tugasAdminKeDosen = $this->peninjauanModel->getTugasByDosen($idDosen);
        $hasilDosenKeAdmin = array_filter($tugasAdminKeDosen, fn($t) => $t['statusPenugasan'] === 'Selesai');

        $data = [
            'title'             => 'Histori Dosen: ' . $dosen['namaDosen'] . ' | SIPKM-DKKA',
            'pageHeader'        => 'Detail & Histori Dosen Peninjau',
            'role'              => $role,
            'activeMenu'        => 'dosen',
            'userName'          => $admin['namaAdmin'],
            'dosen'             => $dosen,
            'tugasAdminKeDosen' => $tugasAdminKeDosen,
            'hasilDosenKeAdmin' => array_values($hasilDosenKeAdmin),
        ];

        return view('admin/detail_dosen', $data);
    }

    /**
     * Pengelolaan Kelompok PKM Mahasiswa
     */
    public function kelompok()
    {
        $admin = $this->getAdminAktif();
        $role  = session()->get('role') ?? 'Admin';

        $daftarKelompok = $this->kelompokModel->getKelompokWithDetails();

        // Ambil daftar anggota untuk modal rincian tiap kelompok
        foreach ($daftarKelompok as &$k) {
            $k['anggota'] = $this->mahasiswaModel->getAnggotaByKelompok((int) $k['idKelompok']);
        }

        $mahasiswaBebas = $this->mahasiswaModel->getMahasiswaBebas();

        $data = [
            'title'          => 'Kelola Kelompok PKM | SIPKM-DKKA',
            'pageHeader'     => 'Pengelolaan Kelompok PKM Mahasiswa',
            'role'           => $role,
            'activeMenu'     => 'kelompok',
            'userName'       => $admin['namaAdmin'],
            'daftarKelompok' => $daftarKelompok,
            'mahasiswaBebas' => $mahasiswaBebas,
        ];

        return view('admin/kelompok', $data);
    }

    /**
     * Tambah Kelompok Manual oleh Admin DKKA
     */
    public function tambahKelompok()
    {
        $rules = [
            'namaKelompok' => 'required|min_length[3]|max_length[100]',
            'tahunPeriode' => 'required',
            'nimKetua'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi data kelompok dengan benar.');
        }

        $nimKetua = $this->request->getPost('nimKetua');
        $ketua = $this->mahasiswaModel->findByNim($nimKetua);

        if (! $ketua || ! empty($ketua['idKelompok'])) {
            return redirect()->back()->with('error', 'Mahasiswa tersebut sudah memiliki kelompok atau tidak terdaftar.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->kelompokModel->insert([
            'namaKelompok'  => trim($this->request->getPost('namaKelompok')),
            'tahunPeriode'  => trim($this->request->getPost('tahunPeriode')),
            'nimKetua'      => $nimKetua,
            'jumlahAnggota' => 1,
        ]);
        $idKelompok = $this->kelompokModel->getInsertID();

        // Update mahasiswa & role user
        $this->mahasiswaModel->update($ketua['idMahasiswa'], ['idKelompok' => $idKelompok]);
        $this->userModel->update($ketua['idUser'], ['role' => 'Ketua Kelompok']);

        $db->transComplete();

        return redirect()->to(base_url('admin/kelompok'))->with('success', 'Kelompok PKM baru berhasil didaftarkan secara manual!');
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

        // 1. Lepas asosiasi seluruh anggota
        $anggota = $this->mahasiswaModel->getAnggotaByKelompok($idKelompok);
        foreach ($anggota as $ang) {
            $this->mahasiswaModel->update($ang['idMahasiswa'], ['idKelompok' => null]);
            $this->userModel->update($ang['idUser'], ['role' => 'Anggota Kelompok']);
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
     * Manajemen Proposal & Sidang Pleno Kelayakan DKKA
     */
    public function proposal()
    {
        $admin = $this->getAdminAktif();
        $role  = session()->get('role') ?? 'Admin';

        $daftarProposal = $this->proposalModel->getProposalWithPlotting();
        $daftarDosen    = $this->dosenModel->findAll();

        $masterTemplateUrl = env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/MASTER_TEMPLATE_PENILAIAN_DKKA');

        $data = [
            'title'             => 'Kelola Proposal & Plotting | SIPKM-DKKA',
            'pageHeader'        => 'Pengelolaan Proposal & Sidang Pleno',
            'role'              => $role,
            'activeMenu'        => 'proposal',
            'userName'          => $admin['namaAdmin'],
            'daftarProposal'    => $daftarProposal,
            'daftarDosen'       => $daftarDosen,
            'masterTemplateUrl' => $masterTemplateUrl,
        ];

        return view('admin/proposal', $data);
    }

    /**
     * Plotting Satuan: Penugasan 1 Dosen ke 1 Proposal
     */
    public function plottingDosen()
    {
        $idProposal = (int) $this->request->getPost('idProposal');
        $idDosen    = (int) $this->request->getPost('idDosen');

        if (empty($idProposal) || empty($idDosen)) {
            return redirect()->back()->with('error', 'Mohon pilih proposal dan dosen peninjau dengan lengkap.');
        }

        $dosen = $this->dosenModel->find($idDosen);
        $proposal = $this->proposalModel->find($idProposal);

        if (! $dosen || ! $proposal) {
            return redirect()->back()->with('error', 'Data proposal atau dosen tidak valid.');
        }

        $peninjauan = $this->peninjauanModel->findByProposalId($idProposal);

        if ($peninjauan) {
            // Update dosen peninjau
            $this->peninjauanModel->update($peninjauan['idPeninjauan'], [
                'idDosen'         => $idDosen,
                'statusPenugasan' => 'Belum Ditinjau',
                'tglPenugasan'    => date('Y-m-d'),
            ]);
        } else {
            // Insert peninjauan baru
            $this->peninjauanModel->insert([
                'idProposal'      => $idProposal,
                'idDosen'         => $idDosen,
                'statusPenugasan' => 'Belum Ditinjau',
                'tglPenugasan'    => date('Y-m-d'),
            ]);
        }

        // Update status proposal mahasiswa menjadi 'Sedang Ditinjau' jika masih 'Diunggah'
        if ($proposal['statusProposal'] === 'Diunggah') {
            $this->proposalModel->update($idProposal, ['statusProposal' => 'Sedang Ditinjau']);
        }

        return redirect()->to(base_url('admin/proposal'))
                         ->with('success', 'Proposal #' . $idProposal . ' berhasil ditugaskan ke Dosen Peninjau: ' . esc($dosen['namaDosen']));
    }

    /**
     * Plotting Massal (Bulk Plotting Checklist) ke Satu Dosen Peninjau
     */
    public function bulkPlottingDosen()
    {
        $idDosen = (int) $this->request->getPost('idDosen');
        $selectedStr = $this->request->getPost('selectedProposals'); // Comma-separated atau array
        $proposals = is_array($selectedStr) ? $selectedStr : explode(',', (string) $selectedStr);
        $proposals = array_filter(array_map('intval', $proposals));

        if (empty($idDosen) || empty($proposals)) {
            return redirect()->back()->with('error', 'Pilih minimal satu proposal dan tentukan dosen peninjau.');
        }

        $dosen = $this->dosenModel->find($idDosen);
        if (! $dosen) {
            return redirect()->back()->with('error', 'Dosen peninjau tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($proposals as $idProp) {
            $peninjauan = $this->peninjauanModel->findByProposalId($idProp);
            if ($peninjauan) {
                $this->peninjauanModel->update($peninjauan['idPeninjauan'], [
                    'idDosen'         => $idDosen,
                    'statusPenugasan' => 'Belum Ditinjau',
                    'tglPenugasan'    => date('Y-m-d'),
                ]);
            } else {
                $this->peninjauanModel->insert([
                    'idProposal'      => $idProp,
                    'idDosen'         => $idDosen,
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
     * Halaman Manajemen Papan Informasi Lomba Publik
     */
    public function lomba()
    {
        $admin = $this->getAdminAktif();
        $role  = session()->get('role') ?? 'Admin';

        $daftarLomba = $this->lombaModel->getActiveLomba();

        $data = [
            'title'       => 'Kelola Papan Informasi Lomba | SIPKM-DKKA',
            'pageHeader'  => 'Manajemen Papan Informasi Lomba',
            'role'        => $role,
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

        $posterName = 'poster-pkm-2026.jpg'; // Default fallback

        $filePoster = $this->request->getFile('poster');
        if ($filePoster && $filePoster->isValid() && ! $filePoster->hasMoved()) {
            $newName = $filePoster->getRandomName();
            $filePoster->move(FCPATH . 'assets/uploads/poster', $newName);
            $posterName = $newName;
        }

        $this->lombaModel->insert([
            'idAdmin'         => $admin['idAdmin'],
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
