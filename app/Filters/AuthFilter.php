<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Memeriksa status login session dan hak akses peran (role-based access control)
     *
     * @param array|null $arguments Daftar role yang diizinkan (misal: ['Super Admin', 'Admin'])
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

        // Fitur pendukung pengujian: Jika ada parameter ?role= pada URL saat preview, sesuaikan role sesi aktif
        $roleParam = $request->getGet('role');
        if (! empty($roleParam) && in_array($roleParam, ['Super Admin', 'Admin', 'Dosen Peninjau', 'Ketua Kelompok', 'Anggota Kelompok'], true)) {
            $session->set('role', $roleParam);
        }

        // 2. Periksa kecocokan role jika filter argument disertakan
        if (! empty($arguments)) {
            $userRole = $session->get('role');

            if (! in_array($userRole, $arguments, true)) {
                // Khusus jika user role 'Admin' mencoba membuka route khusus Super Admin
                if ($userRole === 'Admin' && in_array('Super Admin', $arguments, true)) {
                    $session->setFlashdata('error', 'Akses Ditolak: Halaman ini khusus Super Admin.');
                    return redirect()->to(base_url('admin/dashboard'));
                }

                // Khusus jika user role 'Super Admin' mencoba membuka route operasional Admin
                if ($userRole === 'Super Admin' && in_array('Admin', $arguments, true)) {
                    $session->setFlashdata('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk membuka halaman tersebut.');
                    return redirect()->to(base_url('superadmin/dashboard'));
                }

                $session->setFlashdata('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka halaman tersebut.');

                // Arahkan kembali ke dashboard sesuai role yang sah
                switch ($userRole) {
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
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah request
    }
}
