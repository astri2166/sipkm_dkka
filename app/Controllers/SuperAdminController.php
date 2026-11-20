<?php

namespace App\Controllers;

use App\Models\UserModel;

class SuperAdminController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Helper mendapatkan data Super Admin aktif dari sesi login / database
     */
    private function getSuperAdminAktif(): array
    {
        $idUser = session()->get('id_user') ?? session()->get('idUser');
        $admin = null;

        if ($idUser) {
            $admin = $this->userModel->findByUserId((int) $idUser);
        }

        if (! $admin) {
            $admin = $this->userModel->where('role', 'SuperAdmin')->first();
            $admin = $this->userModel->formatUserAliases($admin);
        }

        if (! $admin) {
            $admin = [
                'idAdmin'   => 1,
                'idUser'    => 1,
                'id_user'   => 1,
                'nipAdmin'  => '198801012015011001',
                'namaAdmin' => 'Budi Santoso, M.Kom.',
                'jabatan'   => 'Super Admin',
                'role'      => 'SuperAdmin',
            ];
        }

        return $admin;
    }

    /**
     * Dashboard Utama Super Admin
     */
    public function dashboard()
    {
        $superAdmin  = $this->getSuperAdminAktif();
        $daftarAdmin = $this->userModel->getAdminUsers();

        $countAktif    = count(array_filter($daftarAdmin, fn($a) => ($a['status_akun'] ?? $a['statusAkun']) === 'Aktif'));
        $countNonaktif = count(array_filter($daftarAdmin, fn($a) => ($a['status_akun'] ?? $a['statusAkun']) !== 'Aktif'));

        $data = [
            'title'         => 'Dashboard Super Admin | SIPKM-DKKA',
            'pageHeader'    => 'Dashboard Super Administrator',
            'role'          => 'Super Admin',
            'activeMenu'    => 'dashboard',
            'userName'      => $superAdmin['namaAdmin'],
            'superAdmin'    => $superAdmin,
            'daftarAdmin'   => $daftarAdmin,
            'countTotal'    => count($daftarAdmin),
            'countAktif'    => $countAktif,
            'countNonaktif' => $countNonaktif,
        ];

        return view('superadmin/dashboard', $data);
    }

    /**
     * Menu Utama: Kelola Akun Administrator DKKA
     */
    public function kelolaAdmin()
    {
        $superAdmin  = $this->getSuperAdminAktif();
        $daftarAdmin = $this->userModel->getAdminUsers();

        $data = [
            'title'       => 'Kelola Akun Admin | SIPKM-DKKA',
            'pageHeader'  => 'Manajemen Akun Administrator DKKA',
            'role'        => 'Super Admin',
            'activeMenu'  => 'admin',
            'userName'    => $superAdmin['namaAdmin'],
            'daftarAdmin' => $daftarAdmin,
        ];

        return view('superadmin/kelola_admin', $data);
    }

    /**
     * Tambah Akun Administrator Baru (1x INSERT langsung ke tabel users)
     */
    public function tambahAdmin()
    {
        $rules = [
            'nipAdmin'  => 'required|min_length[5]|max_length[30]|is_unique[users.nip]',
            'namaAdmin' => 'required|min_length[3]|max_length[100]',
            'jabatan'   => 'required',
            'username'  => 'required|min_length[4]|max_length[50]|is_unique[users.username]',
            'email'     => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi seluruh kolom formulir tambah admin dengan benar.');
        }

        $jabatan = trim($this->request->getPost('jabatan'));
        $roleBaru = (stripos($jabatan, 'super') !== false) ? 'SuperAdmin' : 'Admin';

        $userData = [
            'id_kelompok'   => null,
            'username'      => trim($this->request->getPost('username')),
            'email'         => trim($this->request->getPost('email')),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'nama_lengkap'  => trim($this->request->getPost('namaAdmin')),
            'role'          => $roleBaru,
            'nip'           => trim($this->request->getPost('nipAdmin')),
            'nim'           => null,
            'program_studi' => null,
            'jabatan'       => $jabatan,
            'status_akun'   => 'Aktif',
        ];

        $inserted = $this->userModel->insert($userData);

        if (! $inserted) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan administrator baru.');
        }

        return redirect()->to(base_url('superadmin/kelola-admin'))
                         ->with('success', 'Akun Administrator ' . esc($this->request->getPost('namaAdmin')) . ' berhasil didaftarkan!');
    }

    /**
     * Edit Data Akun Administrator langsung ke tabel users
     */
    public function editAdmin(int $idAdmin)
    {
        $user = $this->userModel->find($idAdmin);
        if (! $user) {
            return redirect()->back()->with('error', 'Data administrator tidak ditemukan.');
        }

        $rules = [
            'nipAdmin'  => "required|min_length[5]|max_length[30]|is_unique[users.nip,id_user,{$idAdmin}]",
            'namaAdmin' => 'required|min_length[3]|max_length[100]',
            'jabatan'   => 'required',
            'username'  => "required|min_length[4]|max_length[50]|is_unique[users.username,id_user,{$idAdmin}]",
            'email'     => "required|valid_email|max_length[100]|is_unique[users.email,id_user,{$idAdmin}]",
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon periksa kembali kolom data admin yang diubah.');
        }

        $jabatan = trim($this->request->getPost('jabatan'));
        $roleBaru = (stripos($jabatan, 'super') !== false) ? 'SuperAdmin' : 'Admin';

        $userData = [
            'username'     => trim($this->request->getPost('username')),
            'email'        => trim($this->request->getPost('email')),
            'nama_lengkap' => trim($this->request->getPost('namaAdmin')),
            'nip'          => trim($this->request->getPost('nipAdmin')),
            'jabatan'      => $jabatan,
            'role'         => $roleBaru,
        ];

        $passwordBaru = $this->request->getPost('password');
        if (! empty($passwordBaru)) {
            $userData['password'] = password_hash($passwordBaru, PASSWORD_BCRYPT);
        }

        $this->userModel->update($idAdmin, $userData);

        return redirect()->to(base_url('superadmin/kelola-admin'))
                         ->with('success', 'Data Administrator ' . esc($this->request->getPost('namaAdmin')) . ' berhasil diperbarui!');
    }

    /**
     * Mengubah Status Akun Admin (Aktif / Nonaktif)
     */
    public function toggleStatusAdmin(int $idUser)
    {
        $currentIdUser = session()->get('id_user') ?? session()->get('idUser');
        if ((int) $idUser === (int) $currentIdUser) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang aktif.');
        }

        $user = $this->userModel->find($idUser);
        if (! $user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $currentStatus = $user['status_akun'] ?? $user['statusAkun'] ?? 'Aktif';
        $newStatus = ($currentStatus === 'Aktif') ? 'Nonaktif' : 'Aktif';
        $this->userModel->update($idUser, ['status_akun' => $newStatus]);

        return redirect()->to(base_url('superadmin/kelola-admin'))
                         ->with('success', 'Status akun ' . esc($user['username']) . ' berhasil diubah menjadi: ' . $newStatus);
    }

    /**
     * Hapus Akun Administrator langsung dari tabel users
     */
    public function hapusAdmin(int $idAdmin)
    {
        $user = $this->userModel->find($idAdmin);
        if (! $user) {
            return redirect()->back()->with('error', 'Administrator tidak ditemukan.');
        }

        $currentIdUser = session()->get('id_user') ?? session()->get('idUser');
        if ((int) $user['id_user'] === (int) $currentIdUser) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $this->userModel->delete($idAdmin);

        return redirect()->to(base_url('superadmin/kelola-admin'))
                         ->with('success', 'Akun Administrator ' . esc($user['nama_lengkap']) . ' berhasil dihapus secara permanen.');
    }
}
