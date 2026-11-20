<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan halaman login (GET)
     */
    public function login()
    {
        // Jika sudah login, redirect otomatis ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('public/login');
    }

    /**
     * Memproses verifikasi kredensial login (POST)
     */
    public function verifikasiLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan isi username/email dan password.');
        }

        $loginInput = trim($this->request->getPost('username'));
        $password   = $this->request->getPost('password');

        // Cari user di tabel users berdasarkan username ATAU email
        $user = $this->userModel->findByLogin($loginInput);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username/Email atau password yang Anda masukkan salah.');
        }

        // Cek status akun
        if (($user['status_akun'] ?? $user['statusAkun']) !== 'Aktif') {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi Administrator DKKA.');
        }

        // Simpan data user ke dalam session dengan kompatibilitas alias penuh
        $sessionData = [
            'id_user'       => (int) $user['id_user'],
            'idUser'        => (int) $user['id_user'],
            'username'      => $user['username'],
            'email'         => $user['email'],
            'nama_lengkap'  => $user['nama_lengkap'],
            'namaUser'      => $user['nama_lengkap'],
            'role'          => $user['role'],
            'id_kelompok'   => $user['id_kelompok'] ? (int) $user['id_kelompok'] : null,
            'idKelompok'    => $user['id_kelompok'] ? (int) $user['id_kelompok'] : null,
            'nim'           => $user['nim'] ?? null,
            'nip'           => $user['nip'] ?? null,
            'program_studi' => $user['program_studi'] ?? null,
            'programStudi'  => $user['program_studi'] ?? null,
            'jabatan'       => $user['jabatan'] ?? null,
            'isLoggedIn'    => true,

            // Alias kompatibilitas frontend view (agar seluruh layout & view lama 100% normal)
            'idMahasiswa'   => (int) $user['id_user'],
            'idDosen'       => (int) $user['id_user'],
            'idAdmin'       => (int) $user['id_user'],
            'namaMahasiswa' => $user['nama_lengkap'],
            'namaDosen'     => $user['nama_lengkap'],
            'namaAdmin'     => $user['nama_lengkap'],
            'nipAdmin'      => $user['nip'] ?? null,
            'nipDosen'      => $user['nip'] ?? null,
        ];

        session()->set($sessionData);
        session()->setFlashdata('success', 'Selamat datang kembali, ' . esc($user['nama_lengkap'] ?: $user['username']) . '!');

        return $this->redirectByRole($user['role']);
    }

    /**
     * Menampilkan formulir pendaftaran mahasiswa mandiri (GET)
     */
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('public/register');
    }

    /**
     * Memproses registrasi mahasiswa baru (POST)
     * Langsung 1x INSERT ke tabel users tanpa no_whatsapp
     */
    public function prosesRegister()
    {
        $rules = [
            'nim'      => 'required|max_length[20]|is_unique[users.nim]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'    => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        $messages = [
            'nim' => [
                'is_unique' => 'NIM tersebut sudah terdaftar di sistem.',
            ],
            'username' => [
                'is_unique' => 'Username ini sudah dipakai oleh pengguna lain.',
            ],
            'email' => [
                'is_unique' => 'Email ini sudah terdaftar di sistem.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaLengkap = trim((string) ($this->request->getPost('nama_lengkap') ?? $this->request->getPost('namaMahasiswa') ?? ''));
        $prodi = trim((string) ($this->request->getPost('program_studi') ?? $this->request->getPost('programStudi') ?? ''));

        if (empty($namaLengkap)) {
            return redirect()->back()->withInput()->with('error', 'Nama lengkap mahasiswa wajib diisi.');
        }

        // Cukup 1x INSERT ke tabel users: role = 'Member', id_kelompok = NULL, status_akun = 'Aktif'
        $userData = [
            'id_kelompok'   => null,
            'username'      => trim($this->request->getPost('username')),
            'email'         => trim($this->request->getPost('email')),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'nama_lengkap'  => $namaLengkap,
            'role'          => 'Member',
            'nim'           => trim($this->request->getPost('nim')),
            'nip'           => null,
            'program_studi' => $prodi,
            'jabatan'       => null,
            'status_akun'   => 'Aktif',
        ];

        $inserted = $this->userModel->insert($userData);

        if (! $inserted) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mendaftarkan akun. Silakan coba lagi.');
        }

        session()->setFlashdata('success', 'Registrasi berhasil! Silakan masuk menggunakan username dan password Anda.');
        return redirect()->to(base_url('login'));
    }

    /**
     * Logout pengguna dan hancurkan sesi
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda telah berhasil keluar dari sistem SIPKM-DKKA.');
    }

    /**
     * Helper redirect dashboard sesuai role
     */
    private function redirectByRole(string $role)
    {
        switch ($role) {
            case 'SuperAdmin':
            case 'Super Admin':
                return redirect()->to(base_url('superadmin/dashboard'));
            case 'Admin':
                return redirect()->to(base_url('admin/dashboard'));
            case 'Dosen':
            case 'Dosen Peninjau':
                return redirect()->to(base_url('dosen/dashboard'));
            case 'Ketua':
            case 'Member':
            case 'Ketua Kelompok':
            case 'Anggota Kelompok':
                return redirect()->to(base_url('mahasiswa/dashboard'));
            default:
                return redirect()->to(base_url('login'));
        }
    }
}
