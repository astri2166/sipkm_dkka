<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Normalisasi nama role internal/eksternal ke format ENUM DB 5 tabel
     */
    public static function normalizeRole(?string $role): string
    {
        switch ($role) {
            case 'Super Admin':
            case 'SuperAdmin':
                return 'SuperAdmin';
            case 'Admin':
                return 'Admin';
            case 'Dosen Peninjau':
            case 'Dosen':
                return 'Dosen';
            case 'Ketua Kelompok':
            case 'Ketua':
                return 'Ketua';
            case 'Anggota Kelompok':
            case 'Member':
            case 'Anggota':
                return 'Member';
            default:
                return $role ?? '';
        }
    }

    /**
     * Memeriksa status login session dan hak akses peran (role-based access control)
     *
     * @param array|null $arguments Daftar role yang diizinkan (misal: ['SuperAdmin'] atau ['Ketua', 'Member'])
     * @return \CodeIgniter\HTTP\RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Periksa apakah pengguna sudah login
        if (! $session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Silakan masuk (login) terlebih dahulu untuk mengakses halaman ini.');
            return redirect()->to(base_url('login'));
        }

        // Fitur pendukung pengujian / preview: Jika ada parameter ?role= pada URL, sesuaikan role sesi aktif
        $roleParam = $request->getGet('role');
        if (! empty($roleParam)) {
            $normalizedParam = self::normalizeRole($roleParam);
            if (in_array($normalizedParam, ['SuperAdmin', 'Admin', 'Dosen', 'Ketua', 'Member'], true)) {
                $session->set('role', $normalizedParam);
            }
        }

        // 2. Periksa kecocokan role jika filter argument disertakan
        if (! empty($arguments)) {
            $userRole = self::normalizeRole($session->get('role'));
            $allowedRoles = array_map([self::class, 'normalizeRole'], $arguments);

            if (! in_array($userRole, $allowedRoles, true)) {
                // Khusus jika user role 'Admin' mencoba membuka route khusus Super Admin
                if ($userRole === 'Admin' && in_array('SuperAdmin', $allowedRoles, true)) {
                    $session->setFlashdata('error', 'Akses Ditolak: Halaman ini khusus Super Admin.');
                    return redirect()->to(base_url('admin/dashboard'));
                }

                // Khusus jika user role 'SuperAdmin' mencoba membuka route operasional Admin
                if ($userRole === 'SuperAdmin' && in_array('Admin', $allowedRoles, true)) {
                    $session->setFlashdata('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk membuka halaman tersebut.');
                    return redirect()->to(base_url('superadmin/dashboard'));
                }

                $session->setFlashdata('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka halaman tersebut.');

                // Arahkan kembali ke dashboard sesuai role yang sah
                switch ($userRole) {
                    case 'SuperAdmin':
                        return redirect()->to(base_url('superadmin/dashboard'));
                    case 'Admin':
                        return redirect()->to(base_url('admin/dashboard'));
                    case 'Dosen':
                        return redirect()->to(base_url('dosen/dashboard'));
                    case 'Ketua':
                    case 'Member':
                        return redirect()->to(base_url('mahasiswa/dashboard'));
                    default:
                        return redirect()->to(base_url('login'));
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah request
    }
}
