<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KelompokPkmModel;
use App\Models\ProposalPkmModel;
use App\Models\PeninjauanProposalModel;

class MahasiswaController extends BaseController
{
    protected UserModel $userModel;
    protected KelompokPkmModel $kelompokModel;
    protected ProposalPkmModel $proposalModel;
    protected PeninjauanProposalModel $peninjauanModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->kelompokModel   = new KelompokPkmModel();
        $this->proposalModel   = new ProposalPkmModel();
        $this->peninjauanModel = new PeninjauanProposalModel();
    }

    /**
     * Helper mendapatkan data Mahasiswa aktif dari sesi login / tabel users
     */
    private function getMahasiswaAktif(): array
    {
        $idUser = session()->get('id_user') ?? session()->get('idUser');
        $mhs = null;

        if ($idUser) {
            $mhs = $this->userModel->findByUserId((int) $idUser);
        }

        if (! $mhs) {
            // Coba ambil mahasiswa ketua atau member pertama dari database
            $mhs = $this->userModel->whereIn('role', ['Ketua', 'Member'])->first();
            $mhs = $this->userModel->formatUserAliases($mhs);
        }

        if (! $mhs) {
            $mhs = [
                'idMahasiswa'   => 5,
                'idUser'        => 5,
                'id_user'       => 5,
                'idKelompok'    => 1,
                'id_kelompok'   => 1,
                'nim'           => '2412501001',
                'namaMahasiswa' => 'Rizky Pratama',
                'nama_lengkap'  => 'Rizky Pratama',
                'programStudi'  => 'Sistem Informasi',
                'program_studi' => 'Sistem Informasi',
                'role'          => 'Ketua',
            ];
        }

        return $mhs;
    }

    /**
     * Helper memetakan role ENUM DB ke label tampilan view (Frontend Compatibility)
     */
    private function getRoleViewLabel(string $role): string
    {
        if ($role === 'Ketua' || $role === 'Ketua Kelompok') {
            return 'Ketua Kelompok';
        }
        return 'Anggota Kelompok';
    }

    /**
     * Dashboard Utama Mahasiswa
     */
    public function dashboard()
    {
        $mhs = $this->getMahasiswaAktif();
        $rawRole = session()->get('role') ?? $mhs['role'] ?? 'Ketua';
        $roleView = $this->getRoleViewLabel($rawRole);

        $idKelompok = $mhs['id_kelompok'] ?? $mhs['idKelompok'];
        $hasKelompok = ! empty($idKelompok);

        $kelompok      = null;
        $daftarAnggota = [];
        $proposal      = null;
        $dosen         = null;
        $hasProposal   = false;

        if ($hasKelompok) {
            $kelompok      = $this->kelompokModel->find($idKelompok);
            $daftarAnggota = $this->userModel->getAnggotaByKelompok((int) $idKelompok);
            $proposal      = $this->proposalModel->findByKelompokId((int) $idKelompok);

            if ($proposal) {
                $hasProposal = true;
                $peninjauan  = $this->peninjauanModel->findByProposalId((int) $proposal['idProposal']);
                if ($peninjauan && ! empty($peninjauan['id_user'])) {
                    $dosen = $this->userModel->findByUserId((int) $peninjauan['id_user']);
                }
            }
        }

        $data = [
            'title'         => 'Dashboard Mahasiswa | SIPKM-DKKA',
            'pageHeader'    => 'Dashboard Mahasiswa',
            'role'          => $roleView,
            'activeMenu'    => 'dashboard',
            'userName'      => $mhs['namaMahasiswa'],
            'mahasiswa'     => $mhs,
            'hasKelompok'   => $hasKelompok,
            'hasProposal'   => $hasProposal,
            'kelompok'      => $kelompok,
            'daftarAnggota' => $daftarAnggota,
            'proposal'      => $proposal,
            'dosen'         => $dosen,
        ];

        return view('mahasiswa/dashboard', $data);
    }

    /**
     * Halaman Data Kelompok PKM & Formulir Pembentukan Kelompok
     */
    public function kelompok()
    {
        $mhs = $this->getMahasiswaAktif();
        $rawRole = session()->get('role') ?? $mhs['role'] ?? 'Ketua';
        $roleView = $this->getRoleViewLabel($rawRole);

        $idKelompok = $mhs['id_kelompok'] ?? $mhs['idKelompok'];
        $hasKelompok = ! empty($idKelompok);

        $kelompok      = null;
        $daftarAnggota = [];
        $kodeKelompok  = 'PKM-UBL-2026-X89';

        if ($hasKelompok) {
            $kelompok      = $this->kelompokModel->find($idKelompok);
            $daftarAnggota = $this->userModel->getAnggotaByKelompok((int) $idKelompok);
            $kodeKelompok  = 'PKM-UBL-' . ($kelompok['tahunPeriode'] ?? '2026') . '-K' . $kelompok['idKelompok'];
        }

        // Ambil mahasiswa yang belum punya kelompok (id_kelompok IS NULL) untuk opsi invite
        $mahasiswaBebas = $this->userModel->getMahasiswaBebas();

        $data = [
            'title'          => 'Data Kelompok PKM | SIPKM-DKKA',
            'pageHeader'     => 'Data Kelompok PKM',
            'role'           => $roleView,
            'activeMenu'     => 'kelompok',
            'userName'       => $mhs['namaMahasiswa'],
            'mahasiswa'      => $mhs,
            'hasKelompok'    => $hasKelompok,
            'kelompok'       => $kelompok,
            'daftarAnggota'  => $daftarAnggota,
            'kodeKelompok'   => $kodeKelompok,
            'mahasiswaBebas' => $mahasiswaBebas,
        ];

        return view('mahasiswa/kelompok', $data);
    }

    /**
     * Proses Pembentukan Kelompok Baru (Method: buatKelompok())
     */
    public function buatKelompok()
    {
        $mhs = $this->getMahasiswaAktif();
        $idKelompok = $mhs['id_kelompok'] ?? $mhs['idKelompok'];

        if (! empty($idKelompok)) {
            return redirect()->to(base_url('mahasiswa/kelompok'))->with('error', 'Anda sudah tergabung dalam suatu kelompok PKM.');
        }

        $rules = [
            'namaKelompok' => 'required|min_length[3]|max_length[100]',
            'tahunPeriode' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon isi nama kelompok dengan benar.');
        }

        $namaKelompok   = trim($this->request->getPost('namaKelompok'));
        $tahunPeriode   = trim($this->request->getPost('tahunPeriode') ?? '2026');
        $anggotaDipilih = $this->request->getPost('anggota') ?? [];

        // Validasi ketersediaan anggota yang dipilih dari tabel users (khusus role Member, bukan sesama Ketua)
        $nimValid = [];
        foreach ($anggotaDipilih as $nim) {
            $calon = $this->userModel->findByNim($nim);
            if ($calon && empty($calon['id_kelompok']) && $calon['nim'] !== $mhs['nim'] && $calon['role'] !== 'Ketua') {
                $nimValid[] = $calon;
            }
        }

        $totalAnggota = 1 + count($nimValid);

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Simpan kelompok baru
        $this->kelompokModel->insert([
            'namaKelompok'  => $namaKelompok,
            'tahunPeriode'  => $tahunPeriode,
            'nimKetua'      => $mhs['nim'],
            'jumlahAnggota' => $totalAnggota,
        ]);
        $newIdKelompok = $this->kelompokModel->getInsertID();

        // 2. Update ketua kelompok di tabel users: isi id_kelompok & ubah role menjadi 'Ketua'
        $this->userModel->update($mhs['id_user'], [
            'id_kelompok' => $newIdKelompok,
            'role'        => 'Ketua',
        ]);

        // 3. Update seluruh anggota yang dipilih di tabel users: isi id_kelompok
        foreach ($nimValid as $calon) {
            $this->userModel->update($calon['id_user'], [
                'id_kelompok' => $newIdKelompok,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal membentuk kelompok. Silakan coba kembali.');
        }

        // Perbarui sesi aktif
        session()->set([
            'role'        => 'Ketua',
            'id_kelompok' => $newIdKelompok,
            'idKelompok'  => $newIdKelompok,
        ]);

        return redirect()->to(base_url('mahasiswa/kelompok'))->with('success', 'Kelompok PKM berhasil dibentuk! Status Anda kini adalah Ketua Kelompok.');
    }

    /**
     * Menambahkan Anggota Baru ke Kelompok (Method: tambahAnggota())
     */
    public function tambahAnggota()
    {
        $mhs = $this->getMahasiswaAktif();
        $rawRole = session()->get('role') ?? $mhs['role'];
        $idKelompok = $mhs['id_kelompok'] ?? $mhs['idKelompok'];

        if (! in_array($rawRole, ['Ketua', 'Ketua Kelompok'], true) || empty($idKelompok)) {
            return redirect()->to(base_url('mahasiswa/kelompok'))->with('error', 'Hanya Ketua Kelompok yang berwenang menambahkan anggota.');
        }

        $nim = trim((string) $this->request->getPost('nim'));
        if (empty($nim)) {
            return redirect()->back()->with('error', 'Silakan pilih mahasiswa yang akan diundang.');
        }

        $calon = $this->userModel->findByNim($nim);
        if (! $calon || ! empty($calon['id_kelompok']) || $calon['role'] === 'Ketua') {
            return redirect()->back()->with('error', 'Mahasiswa tersebut tidak dapat ditambahkan sebagai anggota (sudah memiliki kelompok, tidak terdaftar, atau terdaftar sebagai Ketua).');
        }

        $currentAnggota = $this->userModel->getAnggotaByKelompok($idKelompok);
        if (count($currentAnggota) >= 5) {
            return redirect()->back()->with('error', 'Kelompok sudah mencapai batas maksimal 5 mahasiswa.');
        }

        // Update mahasiswa di tabel users
        $this->userModel->update($calon['id_user'], [
            'id_kelompok' => $idKelompok,
        ]);

        // Update jumlah anggota di tabel kelompok_pkm
        $this->kelompokModel->update($idKelompok, [
            'jumlahAnggota' => count($currentAnggota) + 1,
        ]);

        return redirect()->to(base_url('mahasiswa/kelompok'))->with('success', 'Mahasiswa ' . esc($calon['namaMahasiswa']) . ' berhasil ditambahkan ke dalam kelompok.');
    }

    /**
     * Formulir Input Proposal PKM
     */
    public function proposal()
    {
        $mhs = $this->getMahasiswaAktif();
        $rawRole = session()->get('role') ?? $mhs['role'] ?? 'Ketua';
        $roleView = $this->getRoleViewLabel($rawRole);
        $idKelompok = $mhs['id_kelompok'] ?? $mhs['idKelompok'];

        $kelompok    = null;
        $proposal    = null;
        $hasProposal = false;

        if (! empty($idKelompok)) {
            $kelompok = $this->kelompokModel->find($idKelompok);
            $proposal = $this->proposalModel->findByKelompokId((int) $idKelompok);
            if ($proposal) {
                $hasProposal = true;
            }
        }

        $data = [
            'title'       => 'Input Proposal PKM | SIPKM-DKKA',
            'pageHeader'  => 'Input Proposal PKM',
            'role'        => $roleView,
            'activeMenu'  => 'proposal',
            'userName'    => $mhs['namaMahasiswa'],
            'mahasiswa'   => $mhs,
            'kelompok'    => $kelompok,
            'proposal'    => $proposal,
            'hasProposal' => $hasProposal,
        ];

        return view('mahasiswa/proposal', $data);
    }

    /**
     * Memproses Pengajuan Proposal
     */
    public function kirimProposal()
    {
        $mhs = $this->getMahasiswaAktif();
        $rawRole = session()->get('role') ?? $mhs['role'];
        $idKelompok = $mhs['id_kelompok'] ?? $mhs['idKelompok'];

        if (! in_array($rawRole, ['Ketua', 'Ketua Kelompok'], true) || empty($idKelompok)) {
            return redirect()->to(base_url('mahasiswa/proposal'))->with('error', 'Hanya Ketua Kelompok yang dapat mengajukan atau memperbarui proposal.');
        }

        $rules = [
            'jenisPKM'         => 'required',
            'judulProposal'    => 'required|min_length[10]|max_length[250]',
            'tautanGoogleDocs' => 'required|valid_url',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi seluruh kolom formulir proposal dengan benar.');
        }

        $tautanGoogleDocs = trim($this->request->getPost('tautanGoogleDocs'));

        if (! $this->validasiTautan($tautanGoogleDocs)) {
            return redirect()->back()->withInput()->with('error', 'Tautan berkas wajib diawali dengan https://docs.google.com/ atau https://drive.google.com/.');
        }

        $proposalLama = $this->proposalModel->findByKelompokId((int) $idKelompok);

        $proposalData = [
            'idKelompok'       => (int) $idKelompok,
            'judulProposal'    => trim($this->request->getPost('judulProposal')),
            'jenisPKM'         => trim($this->request->getPost('jenisPKM')),
            'tautanGoogleDocs' => $tautanGoogleDocs,
            'statusProposal'   => 'Diunggah',
            'tglUnggah'        => date('Y-m-d'),
        ];

        if ($proposalLama) {
            $this->proposalModel->update($proposalLama['idProposal'], $proposalData);
            $msg = 'Berkas proposal berhasil diperbarui!';
        } else {
            $this->proposalModel->insert($proposalData);
            $msg = 'Proposal PKM berhasil diajukan! Status usulan: Diunggah.';
        }

        return redirect()->to(base_url('mahasiswa/tracking'))->with('success', $msg);
    }

    protected function validasiTautan(string $url): bool
    {
        return (bool) preg_match('/^https:\/\/(docs|drive)\.google\.com\/.+/i', $url);
    }

    /**
     * Halaman Tracking Status Proposal & 2 Card Catatan
     */
    public function tracking()
    {
        $mhs = $this->getMahasiswaAktif();
        $rawRole = session()->get('role') ?? $mhs['role'] ?? 'Ketua';
        $roleView = $this->getRoleViewLabel($rawRole);
        $idKelompok = $mhs['id_kelompok'] ?? $mhs['idKelompok'];

        $kelompok       = null;
        $proposal       = null;
        $peninjauan     = null;
        $dosen          = null;
        $statusProposal = 'Diunggah';

        if (! empty($idKelompok)) {
            $kelompok = $this->kelompokModel->find($idKelompok);
            $proposal = $this->proposalModel->findByKelompokId((int) $idKelompok);

            if ($proposal) {
                $statusProposal = $proposal['statusProposal'] ?? 'Diunggah';

                $peninjauan = $this->peninjauanModel->findByProposalId((int) $proposal['idProposal']);
                if ($peninjauan && ! empty($peninjauan['id_user'])) {
                    $dosen = $this->userModel->findByUserId((int) $peninjauan['id_user']);
                }
            }
        }

        if (! $peninjauan) {
            $peninjauan = [
                'idPeninjauan'        => null,
                'tglPenugasan'        => '-',
                'tglSelesaiTinjau'    => null,
                'catatanAdministrasi' => null,
                'catatanSubstansi'    => null,
                'tautanRekapDrive'    => null,
            ];
        }

        if (! $dosen) {
            $dosen = [
                'namaDosen' => 'Belum Diplotting DKKA',
                'nipDosen'  => '-',
            ];
        }

        $data = [
            'title'          => 'Tracking Status Proposal | SIPKM-DKKA',
            'pageHeader'     => 'Tracking Status Proposal',
            'role'           => $roleView,
            'activeMenu'     => 'tracking',
            'userName'       => $mhs['namaMahasiswa'],
            'mahasiswa'      => $mhs,
            'kelompok'       => $kelompok ?? ['namaKelompok' => 'Belum Berkelompok'],
            'proposal'       => $proposal ?? ['jenisPKM' => 'PKM', 'judulProposal' => 'Belum Mengajukan Proposal', 'tglUnggah' => '-', 'tautanGoogleDocs' => '#'],
            'statusProposal' => $statusProposal,
            'peninjauan'     => $peninjauan,
            'dosen'          => $dosen,
        ];

        return view('mahasiswa/tracking', $data);
    }
}
