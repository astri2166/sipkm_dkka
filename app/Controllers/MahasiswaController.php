<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\KelompokPkmModel;
use App\Models\ProposalPkmModel;
use App\Models\PeninjauanProposalModel;
use App\Models\DosenPeninjauModel;

class MahasiswaController extends BaseController
{
    protected UserModel $userModel;
    protected MahasiswaModel $mahasiswaModel;
    protected KelompokPkmModel $kelompokModel;
    protected ProposalPkmModel $proposalModel;
    protected PeninjauanProposalModel $peninjauanModel;
    protected DosenPeninjauModel $dosenModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->mahasiswaModel  = new MahasiswaModel();
        $this->kelompokModel   = new KelompokPkmModel();
        $this->proposalModel   = new ProposalPkmModel();
        $this->peninjauanModel = new PeninjauanProposalModel();
        $this->dosenModel      = new DosenPeninjauModel();
    }

    /**
     * Mengambil data mahasiswa aktif yang sedang login dari database
     */
    private function getMahasiswaAktif(): array
    {
        $idUser = session()->get('idUser');
        $mhs = $this->mahasiswaModel->findByUserId((int) $idUser);

        if (! $mhs) {
            // Fallback jika login via preview atau akun dummy
            $mhs = [
                'idMahasiswa'   => 1,
                'idUser'        => $idUser ?? 5,
                'idKelompok'    => 1,
                'nim'           => '2412501001',
                'namaMahasiswa' => 'Rizky Pratama',
                'programStudi'  => 'Sistem Informasi',
                'noWhatsapp'    => '081234567801',
            ];
        }

        return $mhs;
    }

    /**
     * Dashboard Utama Mahasiswa
     */
    public function dashboard()
    {
        $mhs = $this->getMahasiswaAktif();
        $role = session()->get('role') ?? 'Ketua Kelompok';

        // Query data kelompok aktual dari database
        $kelompok = null;
        $daftarAnggota = [];
        $proposal = null;
        $dosen = null;
        $hasKelompok = ! empty($mhs['idKelompok']);
        $hasProposal = false;

        if ($hasKelompok) {
            $kelompok = $this->kelompokModel->find($mhs['idKelompok']);
            $daftarAnggota = $this->mahasiswaModel->getAnggotaByKelompok($mhs['idKelompok']);
            $proposal = $this->proposalModel->findByKelompokId($mhs['idKelompok']);

            if ($proposal) {
                $hasProposal = true;
                // Cek peninjauan proposal
                $peninjauan = $this->peninjauanModel->findByProposalId($proposal['idProposal']);
                if ($peninjauan && ! empty($peninjauan['idDosen'])) {
                    $dosen = $this->dosenModel->find($peninjauan['idDosen']);
                }
            }
        }

        $data = [
            'title'         => 'Dashboard Mahasiswa | SIPKM-DKKA',
            'pageHeader'    => 'Dashboard Mahasiswa',
            'role'          => $role,
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
        $role = session()->get('role') ?? 'Ketua Kelompok';
        $hasKelompok = ! empty($mhs['idKelompok']);

        $kelompok = null;
        $daftarAnggota = [];
        $kodeKelompok = 'PKM-UBL-2026-X89';

        if ($hasKelompok) {
            $kelompok = $this->kelompokModel->find($mhs['idKelompok']);
            $daftarAnggota = $this->mahasiswaModel->getAnggotaByKelompok($mhs['idKelompok']);
            $kodeKelompok = 'PKM-UBL-' . ($kelompok['tahunPeriode'] ?? '2026') . '-K' . $kelompok['idKelompok'];
        }

        // Ambil daftar mahasiswa yang belum punya kelompok (idKelompok IS NULL) untuk opsi invite
        $mahasiswaBebas = $this->mahasiswaModel->getMahasiswaBebas();

        $data = [
            'title'          => 'Data Kelompok PKM | SIPKM-DKKA',
            'pageHeader'     => 'Data Kelompok PKM',
            'role'           => $role,
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
     * Proses Pembentukan Kelompok Baru (Method Wajib: + buatKelompok())
     */
    public function buatKelompok()
    {
        $mhs = $this->getMahasiswaAktif();

        // Validasi: mahasiswa tidak boleh sudah punya kelompok
        if (! empty($mhs['idKelompok'])) {
            return redirect()->to(base_url('mahasiswa/kelompok'))->with('error', 'Anda sudah tergabung dalam suatu kelompok PKM.');
        }

        $rules = [
            'namaKelompok' => 'required|min_length[3]|max_length[100]',
            'tahunPeriode' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon isi nama kelompok dengan benar.');
        }

        $namaKelompok   = $this->request->getPost('namaKelompok');
        $tahunPeriode   = $this->request->getPost('tahunPeriode') ?? '2026';
        $anggotaDipilih = $this->request->getPost('anggota') ?? []; // Array of NIMs

        // Validasi ketersediaan anggota yang dipilih (WHERE idKelompok IS NULL)
        $nimValid = [];
        foreach ($anggotaDipilih as $nim) {
            $calon = $this->mahasiswaModel->findByNim($nim);
            if ($calon && empty($calon['idKelompok']) && $calon['nim'] !== $mhs['nim']) {
                $nimValid[] = $calon;
            }
        }

        // Total anggota = Ketua + Anggota dipilih
        $totalAnggota = 1 + count($nimValid);

        // Database Transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Simpan kelompok baru
        $kelompokData = [
            'namaKelompok'  => $namaKelompok,
            'tahunPeriode'  => $tahunPeriode,
            'nimKetua'      => $mhs['nim'],
            'jumlahAnggota' => $totalAnggota,
        ];
        $this->kelompokModel->insert($kelompokData);
        $idKelompok = $this->kelompokModel->getInsertID();

        // 2. Update ketua kelompok: isi idKelompok & ubah role menjadi 'Ketua Kelompok'
        $this->mahasiswaModel->update($mhs['idMahasiswa'], ['idKelompok' => $idKelompok]);
        $this->userModel->update($mhs['idUser'], ['role' => 'Ketua Kelompok']);

        // 3. Update anggota yang dipilih: isi idKelompok
        foreach ($nimValid as $calon) {
            $this->mahasiswaModel->update($calon['idMahasiswa'], ['idKelompok' => $idKelompok]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal membentuk kelompok. Silakan coba kembali.');
        }

        // Perbarui sesi aktif
        session()->set([
            'role'       => 'Ketua Kelompok',
            'idKelompok' => $idKelompok,
        ]);

        return redirect()->to(base_url('mahasiswa/kelompok'))->with('success', 'Kelompok PKM berhasil dibentuk! Status Anda kini adalah Ketua Kelompok.');
    }

    /**
     * Menambahkan Anggota Baru ke Kelompok (Method Wajib: + validasiAnggota())
     */
    public function tambahAnggota()
    {
        $mhs = $this->getMahasiswaAktif();
        $role = session()->get('role');

        if ($role !== 'Ketua Kelompok' || empty($mhs['idKelompok'])) {
            return redirect()->to(base_url('mahasiswa/kelompok'))->with('error', 'Hanya Ketua Kelompok yang berwenang menambahkan anggota.');
        }

        $nim = $this->request->getPost('nim');
        if (empty($nim)) {
            return redirect()->back()->with('error', 'Silakan pilih mahasiswa yang akan diundang.');
        }

        // Validasi anggota belum punya kelompok
        $calon = $this->mahasiswaModel->findByNim($nim);
        if (! $calon || ! empty($calon['idKelompok'])) {
            return redirect()->back()->with('error', 'Mahasiswa dengan NIM tersebut sudah memiliki kelompok atau tidak terdaftar.');
        }

        // Cek kuota anggota (maksimal 5 orang)
        $currentAnggota = $this->mahasiswaModel->getAnggotaByKelompok($mhs['idKelompok']);
        if (count($currentAnggota) >= 5) {
            return redirect()->back()->with('error', 'Kelompok sudah mencapai batas maksimal 5 mahasiswa.');
        }

        // Update mahasiswa
        $this->mahasiswaModel->update($calon['idMahasiswa'], ['idKelompok' => $mhs['idKelompok']]);

        // Update jumlah anggota di tabel kelompok_pkm
        $this->kelompokModel->update($mhs['idKelompok'], [
            'jumlahAnggota' => count($currentAnggota) + 1,
        ]);

        return redirect()->to(base_url('mahasiswa/kelompok'))->with('success', 'Mahasiswa ' . esc($calon['namaMahasiswa']) . ' berhasil ditambahkan ke dalam kelompok.');
    }

    /**
     * Formulir Input Proposal PKM (Method Wajib: + inputProposal())
     */
    public function proposal()
    {
        $mhs = $this->getMahasiswaAktif();
        $role = session()->get('role') ?? 'Ketua Kelompok';

        $kelompok = null;
        $proposal = null;
        $hasProposal = false;

        if (! empty($mhs['idKelompok'])) {
            $kelompok = $this->kelompokModel->find($mhs['idKelompok']);
            $proposal = $this->proposalModel->findByKelompokId($mhs['idKelompok']);
            if ($proposal) {
                $hasProposal = true;
            }
        }

        $data = [
            'title'       => 'Input Proposal PKM | SIPKM-DKKA',
            'pageHeader'  => 'Input Proposal PKM',
            'role'        => $role,
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
     * Memproses Pengajuan Proposal (Method Wajib: + kirimProposal() & + validasiTautan())
     */
    public function kirimProposal()
    {
        $mhs = $this->getMahasiswaAktif();
        $role = session()->get('role');

        if ($role !== 'Ketua Kelompok' || empty($mhs['idKelompok'])) {
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

        // Validasi tautan Google Drive / Google Docs (Method Wajib: + validasiTautan())
        if (! $this->validasiTautan($tautanGoogleDocs)) {
            return redirect()->back()->withInput()->with('error', 'Tautan berkas wajib diawali dengan https://docs.google.com/ atau https://drive.google.com/.');
        }

        $proposalLama = $this->proposalModel->findByKelompokId($mhs['idKelompok']);

        $proposalData = [
            'idKelompok'       => $mhs['idKelompok'],
            'judulProposal'    => $this->request->getPost('judulProposal'),
            'jenisPKM'         => $this->request->getPost('jenisPKM'),
            'tautanGoogleDocs' => $tautanGoogleDocs,
            'statusProposal'   => 'Diunggah',
            'tglUnggah'        => date('Y-m-d'),
        ];

        if ($proposalLama) {
            // Update jika proposal sudah pernah dibuat
            $this->proposalModel->update($proposalLama['idProposal'], $proposalData);
            $msg = 'Berkas proposal berhasil diperbarui!';
        } else {
            // Insert baru
            $this->proposalModel->insert($proposalData);
            $msg = 'Proposal PKM berhasil diajukan! Status usulan: Diunggah.';
        }

        return redirect()->to(base_url('mahasiswa/tracking'))->with('success', $msg);
    }

    /**
     * Validasi Format Tautan Google Drive / Docs (Method Wajib: + validasiTautan())
     */
    protected function validasiTautan(string $url): bool
    {
        return (bool) preg_match('/^https:\/\/(docs|drive)\.google\.com\/.+/i', $url);
    }

    /**
     * Halaman Tracking Status Proposal & 2 Card Catatan (Method Wajib: + lihatTrackingProposal())
     */
    public function tracking()
    {
        $mhs = $this->getMahasiswaAktif();
        $role = session()->get('role') ?? 'Ketua Kelompok';

        $kelompok = null;
        $proposal = null;
        $peninjauan = null;
        $dosen = null;
        $statusProposal = 'Diunggah';

        if (! empty($mhs['idKelompok'])) {
            $kelompok = $this->kelompokModel->find($mhs['idKelompok']);
            $proposal = $this->proposalModel->findByKelompokId($mhs['idKelompok']);

            if ($proposal) {
                // Status aktual dari database (atau simulasi jika ada query param ?status=)
                $statusParam = $this->request->getGet('status');
                $statusProposal = ! empty($statusParam) ? $statusParam : $proposal['statusProposal'];

                $peninjauan = $this->peninjauanModel->findByProposalId($proposal['idProposal']);
                if ($peninjauan && ! empty($peninjauan['idDosen'])) {
                    $dosen = $this->dosenModel->find($peninjauan['idDosen']);
                }
            }
        }

        // Default fallback peninjauan jika belum ada record
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

        // Default fallback dosen jika belum di-plotting
        if (! $dosen) {
            $dosen = [
                'namaDosen' => 'Belum Diplotting DKKA',
                'nipDosen'  => '-',
            ];
        }

        $data = [
            'title'          => 'Tracking Status Proposal | SIPKM-DKKA',
            'pageHeader'     => 'Tracking Status Proposal',
            'role'           => $role,
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
