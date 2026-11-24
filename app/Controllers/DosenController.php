<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProposalPkmModel;
use App\Models\PeninjauanProposalModel;
use App\Models\KelompokPkmModel;

class DosenController extends BaseController
{
    protected UserModel $userModel;
    protected ProposalPkmModel $proposalModel;
    protected PeninjauanProposalModel $peninjauanModel;
    protected KelompokPkmModel $kelompokModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->proposalModel   = new ProposalPkmModel();
        $this->peninjauanModel = new PeninjauanProposalModel();
        $this->kelompokModel   = new KelompokPkmModel();
    }

    /**
     * Helper untuk mengambil data Dosen Peninjau aktif dari sesi login / database
     */
    private function getDosenAktif(): array
    {
        $idUser = session()->get('id_user') ?? session()->get('idUser');
        $dosen = null;

        if ($idUser) {
            $dosen = $this->userModel->findByUserId((int) $idUser);
        }

        if (! $dosen) {
            $dosen = $this->userModel->where('role', 'Dosen')->first();
            $dosen = $this->userModel->formatUserAliases($dosen);
        }

        if (! $dosen) {
            $dosen = [
                'idDosen'   => 3,
                'idUser'    => 3,
                'id_user'   => 3,
                'nipDosen'  => '0315088501',
                'namaDosen' => 'Dr. Ahmad Fauzi, M.T.',
                'email'     => 'ahmad.fauzi@budiluhur.ac.id',
                'role'      => 'Dosen',
            ];
        }

        return $dosen;
    }

    /**
     * Dashboard Utama Dosen Peninjau
     */
    public function dashboard()
    {
        $dosen = $this->getDosenAktif();

        $daftarTugas  = $this->peninjauanModel->getTugasByDosen((int) $dosen['id_user']);
        $countBelum   = count(array_filter($daftarTugas, fn($t) => $t['statusPenugasan'] === 'Belum Ditinjau'));
        $countSelesai = count(array_filter($daftarTugas, fn($t) => $t['statusPenugasan'] === 'Selesai'));

        $masterTemplateUrl = env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/1thAAeuQ7cBhFvA02yIUNgj34UhDxtOhy?usp=sharing');

        $data = [
            'title'             => 'Dashboard Dosen Peninjau | SIPKM-DKKA',
            'pageHeader'        => 'Dashboard Dosen Peninjau',
            'role'              => 'Dosen Peninjau',
            'activeMenu'        => 'dashboard',
            'userName'          => $dosen['namaDosen'],
            'dosen'             => $dosen,
            'masterTemplateUrl' => $masterTemplateUrl,
            'daftarTugas'       => $daftarTugas,
            'countBelum'        => $countBelum,
            'countSelesai'      => $countSelesai,
        ];

        return view('dosen/dashboard', $data);
    }

    /**
     * Halaman Daftar Tugas Peninjauan Proposal PKM
     */
    public function peninjauan()
    {
        $dosen = $this->getDosenAktif();

        $statusFilter = $this->request->getGet('status') ?? '';
        $skemaFilter  = $this->request->getGet('skema') ?? '';
        $search       = strtolower(trim((string) ($this->request->getGet('search') ?? '')));

        $daftarTugas = $this->peninjauanModel->getTugasByDosen((int) $dosen['id_user']);

        // Filter status jika parameter disertakan
        if ($statusFilter !== '') {
            $daftarTugas = array_filter($daftarTugas, fn($t) => $t['statusPenugasan'] === $statusFilter);
        }

        // Filter skema PKM jika parameter disertakan
        if ($skemaFilter !== '') {
            $daftarTugas = array_filter($daftarTugas, fn($t) => $t['jenisPKM'] === $skemaFilter);
        }

        // Filter pencarian keyword
        if ($search !== '') {
            $daftarTugas = array_filter($daftarTugas, function ($t) use ($search) {
                $haystack = strtolower($t['judulProposal'] . ' ' . $t['namaKelompok'] . ' ' . $t['nimKetua'] . ' ' . $t['jenisPKM']);
                return str_contains($haystack, $search);
            });
        }

        $masterTemplateUrl = env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/1thAAeuQ7cBhFvA02yIUNgj34UhDxtOhy?usp=sharing');

        $data = [
            'title'             => 'Peninjauan Proposal PKM | SIPKM-DKKA',
            'pageHeader'        => 'Peninjauan Proposal PKM',
            'role'              => 'Dosen Peninjau',
            'activeMenu'        => 'peninjauan',
            'userName'          => $dosen['namaDosen'],
            'dosen'             => $dosen,
            'masterTemplateUrl' => $masterTemplateUrl,
            'daftarTugas'       => array_values($daftarTugas),
            'statusFilter'      => $statusFilter,
            'skemaFilter'       => $skemaFilter,
            'search'            => $search,
        ];

        return view('dosen/peninjauan', $data);
    }

    /**
     * Detail Lembar Peninjauan Proposal (Card 1, Card 2, & Upload Rekap Drive)
     */
    public function detailPeninjauan(int $idPeninjauan)
    {
        $dosen = $this->getDosenAktif();

        $tugas = $this->peninjauanModel->getDetailPeninjauan($idPeninjauan);

        if (! $tugas) {
            return redirect()->to(base_url('dosen/peninjauan'))->with('error', 'Penugasan proposal tidak ditemukan dalam sistem.');
        }

        // Hak akses verifikasi: tugas harus milik dosen yang bersangkutan (id_user)
        $sessionRole = session()->get('role');
        if ((int) $tugas['id_user'] !== (int) $dosen['id_user'] && ! in_array($sessionRole, ['SuperAdmin', 'Super Admin', 'Admin'], true)) {
            return redirect()->to(base_url('dosen/peninjauan'))->with('error', 'Akses ditolak: Proposal ini tidak ditugaskan kepada Anda.');
        }

        $masterTemplateUrl = env('sipkm.templatePenilaianUrl', 'https://drive.google.com/drive/folders/1thAAeuQ7cBhFvA02yIUNgj34UhDxtOhy?usp=sharing');

        $data = [
            'title'             => 'Evaluasi Proposal #' . $tugas['idProposal'] . ' | SIPKM-DKKA',
            'pageHeader'        => 'Detail Evaluasi Peninjauan',
            'role'              => 'Dosen Peninjau',
            'activeMenu'        => 'peninjauan',
            'userName'          => $dosen['namaDosen'],
            'dosen'             => $dosen,
            'masterTemplateUrl' => $masterTemplateUrl,
            'tugas'             => $tugas,
        ];

        return view('dosen/detail_peninjauan', $data);
    }

    /**
     * Menyimpan Catatan Review Evaluasi Administrasi (Card 1) & Substansi (Card 2)
     */
    public function simpanCatatanCard(int $idPeninjauan)
    {
        $dosen = $this->getDosenAktif();
        $tugas = $this->peninjauanModel->find($idPeninjauan);

        if (! $tugas) {
            return redirect()->to(base_url('dosen/peninjauan'))->with('error', 'Data penugasan proposal tidak ditemukan.');
        }

        $sessionRole = session()->get('role');
        if ((int) $tugas['id_user'] !== (int) $dosen['id_user'] && ! in_array($sessionRole, ['SuperAdmin', 'Super Admin', 'Admin'], true)) {
            return redirect()->to(base_url('dosen/peninjauan'))->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk menilai proposal ini.');
        }

        $rules = [
            'catatanAdministrasi' => 'required|min_length[5]',
            'catatanSubstansi'    => 'required|min_length[5]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi catatan Card 1 (Administrasi) dan Card 2 (Substansi) minimal 5 karakter.');
        }

        $catatanAdministrasi = trim($this->request->getPost('catatanAdministrasi'));
        $catatanSubstansi    = trim($this->request->getPost('catatanSubstansi'));

        // Update peninjauan_proposal
        $this->peninjauanModel->update($idPeninjauan, [
            'catatanAdministrasi' => $catatanAdministrasi,
            'catatanSubstansi'    => $catatanSubstansi,
        ]);

        // Perbarui status proposal mahasiswa menjadi 'Sudah Ditinjau' jika sebelumnya 'Diunggah' atau 'Sedang Ditinjau'
        $proposal = $this->proposalModel->find($tugas['idProposal']);
        if ($proposal && in_array($proposal['statusProposal'], ['Diunggah', 'Sedang Ditinjau'], true)) {
            $this->proposalModel->update($proposal['idProposal'], [
                'statusProposal' => 'Sudah Ditinjau',
            ]);
        }

        return redirect()->to(base_url('dosen/peninjauan/detail/' . $idPeninjauan))
                         ->with('success', 'Catatan Card 1 (Administrasi) & Card 2 (Substansi) berhasil tersimpan!');
    }

    /**
     * Mengirimkan Tautan Rekapitulasi Penilaian Google Drive ke Admin DKKA
     */
    public function kirimRekapPenilaian(int $idPeninjauan)
    {
        $dosen = $this->getDosenAktif();
        $tugas = $this->peninjauanModel->find($idPeninjauan);

        if (! $tugas) {
            return redirect()->to(base_url('dosen/peninjauan'))->with('error', 'Data penugasan proposal tidak ditemukan.');
        }

        $sessionRole = session()->get('role');
        if ((int) $tugas['id_user'] !== (int) $dosen['id_user'] && ! in_array($sessionRole, ['SuperAdmin', 'Super Admin', 'Admin'], true)) {
            return redirect()->to(base_url('dosen/peninjauan'))->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk mengirimkan rekap proposal ini.');
        }

        $rules = [
            'tautanRekapDrive' => 'required|valid_url',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon masukkan tautan URL Google Drive yang valid.');
        }

        $tautanRekapDrive = trim($this->request->getPost('tautanRekapDrive'));

        if (! preg_match('/^https:\/\/(docs|drive)\.google\.com\/.+/i', $tautanRekapDrive)) {
            return redirect()->back()->withInput()->with('error', 'Tautan rekapitulasi penilaian wajib berupa tautan Google Drive / Docs resmi (diawali https://drive.google.com/ atau https://docs.google.com/).');
        }

        // Update peninjauan_proposal: simpan link, ubah statusPenugasan jadi 'Selesai', catat tanggal selesai
        $this->peninjauanModel->update($idPeninjauan, [
            'tautanRekapDrive' => $tautanRekapDrive,
            'statusPenugasan'  => 'Selesai',
            'tglSelesaiTinjau' => date('Y-m-d'),
        ]);

        // Pastikan status proposal mahasiswa berstatus 'Sudah Ditinjau'
        $proposal = $this->proposalModel->find($tugas['idProposal']);
        if ($proposal && in_array($proposal['statusProposal'], ['Diunggah', 'Sedang Ditinjau'], true)) {
            $this->proposalModel->update($proposal['idProposal'], [
                'statusProposal' => 'Sudah Ditinjau',
            ]);
        }

        return redirect()->to(base_url('dosen/peninjauan/detail/' . $idPeninjauan))
                         ->with('success', 'Tautan rekapitulasi penilaian Google Drive berhasil diserahkan ke DKKA. Status penugasan Anda kini: Selesai.');
    }
}
