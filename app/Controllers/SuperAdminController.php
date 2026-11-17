<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AdminModel;

class SuperAdminController extends BaseController
{
    protected UserModel $userModel;
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->adminModel = new AdminModel();
    }

    /**
     * Helper mendapatkan data Super Admin aktif dari sesi login
     */
    private function getSuperAdminAktif(): array
    {
        $idUser = session()->get('idUser');
        $admin  = $this->adminModel->findByUserId((int) $idUser);

        if (! $admin) {
            $admin = [
                'idAdmin'   => 1,
                'idUser'    => $idUser ?? 1,
                'nipAdmin'  => '198801012015011001',
                'namaAdmin' => 'Budi Santoso, M.Kom.',
                'jabatan'   => 'Super Admin',
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
        $daftarAdmin = $this->adminModel->getAdminWithUser();

        $countAktif    = count(array_filter($daftarAdmin, fn($a) => $a['statusAkun'] === 'Aktif'));
        $countNonaktif = count(array_filter($daftarAdmin, fn($a) => $a['statusAkun'] !== 'Aktif'));

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
        $daftarAdmin = $this->adminModel->getAdminWithUser();

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
     * Tambah Akun Administrator DKKA Baru
     */
    public function tambahAdmin()
    {
        $rules = [
            'nipAdmin'  => 'required|min_length[5]|max_length[30]|is_unique[admin.nipAdmin]',
            'namaAdmin' => 'required|min_length[3]|max_length[100]',
            'jabatan'   => 'required|in_list[Admin DKKA,Super Admin]',
            'username'  => 'required|min_length[4]|max_length[50]|is_unique[user.username]',
            'email'     => 'required|valid_email|max_length[100]|is_unique[user.email]',
            'password'  => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi seluruh kolom formulir tambah admin dengan benar.');
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
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan administrator baru.');
        }

        return redirect()->to(base_url('superadmin/kelola-admin'))
                         ->with('success', 'Akun Administrator ' . esc($this->request->getPost('namaAdmin')) . ' berhasil didaftarkan!');
    }

    /**
     * Edit Data Akun Administrator
     */
    public function editAdmin(int $idAdmin)
    {
        $admin = $this->adminModel->find($idAdmin);
        if (! $admin) {
            return redirect()->back()->with('error', 'Data administrator tidak ditemukan.');
        }

        $user = $this->userModel->find($admin['idUser']);
        if (! $user) {
            return redirect()->back()->with('error', 'Data akun user tidak ditemukan.');
        }

        $rules = [
            'nipAdmin'  => "required|min_length[5]|max_length[30]|is_unique[admin.nipAdmin,idAdmin,{$idAdmin}]",
            'namaAdmin' => 'required|min_length[3]|max_length[100]',
            'jabatan'   => 'required|in_list[Admin DKKA,Super Admin]',
            'username'  => "required|min_length[4]|max_length[50]|is_unique[user.username,idUser,{$user['idUser']}]",
            'email'     => "required|valid_email|max_length[100]|is_unique[user.email,idUser,{$user['idUser']}]",
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon periksa kembali kolom data admin yang diubah.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Update data admin
        $this->adminModel->update($idAdmin, [
            'nipAdmin'  => trim($this->request->getPost('nipAdmin')),
            'namaAdmin' => trim($this->request->getPost('namaAdmin')),
            'jabatan'   => $this->request->getPost('jabatan'),
        ]);

        // Update data user
        $userData = [
            'username' => trim($this->request->getPost('username')),
            'email'    => trim($this->request->getPost('email')),
            'role'     => $this->request->getPost('jabatan'),
        ];

        $passwordBaru = $this->request->getPost('password');
        if (! empty($passwordBaru)) {
            $userData['password'] = password_hash($passwordBaru, PASSWORD_BCRYPT);
        }

        $this->userModel->update($user['idUser'], $userData);

        $db->transComplete();

        return redirect()->to(base_url('superadmin/kelola-admin'))
                         ->with('success', 'Data Administrator ' . esc($this->request->getPost('namaAdmin')) . ' berhasil diperbarui!');
    }

    /**
     * Mengubah Status Akun Admin (Aktif / Nonaktif)
     */
    public function toggleStatusAdmin(int $idUser)
    {
        $currentIdUser = session()->get('idUser');
        if ((int) $idUser === (int) $currentIdUser) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang aktif.');
        }

        $user = $this->userModel->find($idUser);
        if (! $user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $newStatus = ($user['statusAkun'] === 'Aktif') ? 'Nonaktif' : 'Aktif';
        $this->userModel->update($idUser, ['statusAkun' => $newStatus]);

        return redirect()->to(base_url('superadmin/kelola-admin'))
                         ->with('success', 'Status akun ' . esc($user['username']) . ' berhasil diubah menjadi: ' . $newStatus);
    }

    /**
     * Hapus Akun Administrator dari Tabel user & admin
     */
    public function hapusAdmin(int $idAdmin)
    {
        $admin = $this->adminModel->find($idAdmin);
        if (! $admin) {
            return redirect()->back()->with('error', 'Administrator tidak ditemukan.');
        }

        $currentIdUser = session()->get('idUser');
        if ((int) $admin['idUser'] === (int) $currentIdUser) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->adminModel->delete($idAdmin);
        $this->userModel->delete($admin['idUser']);

        $db->transComplete();

        return redirect()->to(base_url('superadmin/kelola-admin'))
                         ->with('success', 'Akun Administrator ' . esc($admin['namaAdmin']) . ' berhasil dihapus secara permanen.');
    }
}
