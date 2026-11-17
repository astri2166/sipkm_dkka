<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\DosenPeninjauModel;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;
    protected MahasiswaModel $mahasiswaModel;
    protected DosenPeninjauModel $dosenModel;
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->userModel      = new UserModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->dosenModel     = new DosenPeninjauModel();
        $this->adminModel     = new AdminModel();
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

        $loginInput = $this->request->getPost('username');
        $password   = $this->request->getPost('password');

        // Cari user berdasarkan username ATAU email
        $user = $this->userModel->findByLogin($loginInput);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username/Email atau password yang Anda masukkan salah.');
        }

        // Cek status akun
        if ($user['statusAkun'] !== 'Aktif') {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi Administrator DKKA.');
        }

        // Ambil data detail sesuai role
        $sessionData = [
            'idUser'     => $user['idUser'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ];

        if (in_array($user['role'], ['Ketua Kelompok', 'Anggota Kelompok'])) {
            $mhs = $this->mahasiswaModel->findByUserId($user['idUser']);
            if ($mhs) {
                $sessionData['idMahasiswa']   = $mhs['idMahasiswa'];
                $sessionData['idKelompok']    = $mhs['idKelompok'];
                $sessionData['nim']           = $mhs['nim'];
                $sessionData['namaMahasiswa'] = $mhs['namaMahasiswa'];
                $sessionData['programStudi']  = $mhs['programStudi'];
                $sessionData['noWhatsapp']    = $mhs['noWhatsapp'];
            }
        } elseif ($user['role'] === 'Dosen Peninjau') {
            $dos = $this->dosenModel->findByUserId($user['idUser']);
            if ($dos) {
                $sessionData['idDosen']   = $dos['idDosen'];
                $sessionData['nipDosen']  = $dos['nipDosen'];
                $sessionData['namaDosen'] = $dos['namaDosen'];
            }
        } elseif (in_array($user['role'], ['Super Admin', 'Admin'])) {
            $adm = $this->adminModel->findByUserId($user['idUser']);
            if ($adm) {
                $sessionData['idAdmin']   = $adm['idAdmin'];
                $sessionData['nipAdmin']  = $adm['nipAdmin'];
                $sessionData['namaAdmin'] = $adm['namaAdmin'];
                $sessionData['jabatan']   = $adm['jabatan'];
            }
        }

        session()->set($sessionData);
        session()->setFlashdata('success', 'Selamat datang kembali, ' . esc($sessionData['namaMahasiswa'] ?? $sessionData['namaDosen'] ?? $sessionData['namaAdmin'] ?? $user['username']) . '!');

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
     */
    public function prosesRegister()
    {
        $rules = [
            'nim'           => 'required|max_length[20]|is_unique[mahasiswa.nim]',
            'namaMahasiswa' => 'required|max_length[100]',
            'programStudi'  => 'required|max_length[100]',
            'noWhatsapp'    => 'required|max_length[20]',
            'username'      => 'required|min_length[3]|max_length[50]|is_unique[user.username]',
            'email'         => 'required|valid_email|max_length[100]|is_unique[user.email]',
            'password'      => 'required|min_length[6]',
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

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Simpan ke tabel user (role default: Anggota Kelompok, statusAkun: Aktif)
        $userData = [
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'       => 'Anggota Kelompok',
            'statusAkun' => 'Aktif',
        ];
        $this->userModel->insert($userData);
        $idUser = $this->userModel->getInsertID();

        // 2. Simpan ke tabel mahasiswa (idKelompok awal bernilai NULL)
        $mhsData = [
            'idUser'        => $idUser,
            'idKelompok'    => null,
            'nim'           => $this->request->getPost('nim'),
            'namaMahasiswa' => $this->request->getPost('namaMahasiswa'),
            'programStudi'  => $this->request->getPost('programStudi'),
            'noWhatsapp'    => $this->request->getPost('noWhatsapp'),
        ];
        $this->mahasiswaModel->insert($mhsData);

        $db->transComplete();

        if ($db->transStatus() === false) {
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
            case 'Super Admin':
                return redirect()->to(base_url('superadmin/dashboard'));
            case 'Admin':
                return redirect()->to(base_url('admin/dashboard'));
            case 'Dosen Peninjau':
                return redirect()->to(base_url('dosen/dashboard'));
            case 'Ketua Kelompok':
            case 'Anggota Kelompok':
                return redirect()->to(base_url('mahasiswa/dashboard'));
            default:
                return redirect()->to(base_url('login'));
        }
    }
}
