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
            'role'     => 'required|in_list[Ketua,Member,Ketua Kelompok,Anggota Kelompok]',
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
            'role' => [
                'required' => 'Silakan pilih peran akun (Ketua Kelompok atau Anggota Kelompok).',
                'in_list'  => 'Pilihan peran akun tidak valid.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaLengkap = trim((string) ($this->request->getPost('nama_lengkap') ?? $this->request->getPost('namaMahasiswa') ?? ''));
        $prodi       = trim((string) ($this->request->getPost('program_studi') ?? $this->request->getPost('programStudi') ?? ''));
        $roleInput   = trim((string) $this->request->getPost('role'));
        $roleDb      = ($roleInput === 'Ketua' || $roleInput === 'Ketua Kelompok') ? 'Ketua' : 'Member';

        if (empty($namaLengkap)) {
            return redirect()->back()->withInput()->with('error', 'Nama lengkap mahasiswa wajib diisi.');
        }

        // Cukup 1x INSERT ke tabel users: role sesuai pilihan ('Ketua' atau 'Member'), id_kelompok = NULL, status_akun = 'Aktif'
        $userData = [
            'id_kelompok'   => null,
            'username'      => trim($this->request->getPost('username')),
            'email'         => trim($this->request->getPost('email')),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'nama_lengkap'  => $namaLengkap,
            'role'          => $roleDb,
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
     * Menampilkan formulir reset / lupa password (GET)
     * Dapat diakses oleh seluruh peran akun: SuperAdmin, Admin, Dosen, Ketua, dan Member
     */
    public function resetPassword()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('public/reset_password');
    }

    /**
     * Memproses reset password akun (POST)
     * Mengizinkan pencarian via Username, Email, NIM, atau NIP
     */
    public function prosesResetPassword()
    {
        $rules = [
            'identity'            => 'required',
            'password_baru'       => 'required|min_length[6]',
            'konfirmasi_password' => 'required|matches[password_baru]',
        ];

        $messages = [
            'identity' => [
                'required' => 'Silakan masukkan Username, Email, NIM, atau NIP akun Anda.',
            ],
            'password_baru' => [
                'required'   => 'Password baru wajib diisi.',
                'min_length' => 'Password baru minimal terdiri dari 6 karakter.',
            ],
            'konfirmasi_password' => [
                'required' => 'Konfirmasi password baru wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok dengan password baru.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $identity = trim((string) $this->request->getPost('identity'));
        $user     = $this->userModel->findByLoginOrIdentifier($identity);

        if (! $user) {
            return redirect()->back()->withInput()->with('error', 'Akun dengan identitas "' . esc($identity) . '" tidak ditemukan dalam sistem SIPKM-DKKA.');
        }

        if (($user['status_akun'] ?? $user['statusAkun']) !== 'Aktif') {
            return redirect()->back()->withInput()->with('error', 'Akun tersebut saat ini berstatus nonaktif. Silakan hubungi Administrator DKKA.');
        }

        $passwordBaru = (string) $this->request->getPost('password_baru');
        $this->userModel->resetUserPassword((int) $user['id_user'], $passwordBaru);

        $roleLabel = $user['role'];
        if ($roleLabel === 'Member') $roleLabel = 'Anggota Kelompok';
        if ($roleLabel === 'Ketua')  $roleLabel = 'Ketua Kelompok';

        return redirect()->to(base_url('login'))
                         ->with('success', 'Password akun ' . esc($user['nama_lengkap']) . ' (' . esc($roleLabel) . ') berhasil direset! Silakan masuk menggunakan password baru.');
    }

    /**
     * Memproses ganti password untuk pengguna yang sedang login (POST)
     */
    public function ubahPasswordLogin()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $idUser = (int) session()->get('id_user');

        $rules = [
            'password_lama'       => 'required',
            'password_baru'       => 'required|min_length[6]',
            'konfirmasi_password' => 'required|matches[password_baru]',
        ];

        $messages = [
            'password_lama' => [
                'required' => 'Password lama wajib diisi untuk verifikasi keamanan.',
            ],
            'password_baru' => [
                'required'   => 'Password baru wajib diisi.',
                'min_length' => 'Password baru minimal terdiri dari 6 karakter.',
            ],
            'konfirmasi_password' => [
                'required' => 'Konfirmasi password baru wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok dengan password baru.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $user = $this->userModel->findByUserId($idUser);
        if (! $user) {
            return redirect()->back()->with('error', 'Data akun tidak ditemukan.');
        }

        $passwordLama = (string) $this->request->getPost('password_lama');
        if (! password_verify($passwordLama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        $passwordBaru = (string) $this->request->getPost('password_baru');
        $this->userModel->resetUserPassword($idUser, $passwordBaru);

        return redirect()->back()->with('success', 'Password akun Anda berhasil diperbarui!');
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
