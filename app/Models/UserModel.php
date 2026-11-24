<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_kelompok',
        'username',
        'email',
        'password',
        'nama_lengkap',
        'role',
        'nim',
        'nip',
        'program_studi',
        'jabatan',
        'status_akun',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'username'     => 'required|min_length[3]|max_length[50]|is_unique[users.username,id_user,{id_user}]',
        'email'        => 'required|valid_email|max_length[100]|is_unique[users.email,id_user,{id_user}]',
        'password'     => 'permit_empty|min_length[6]',
        'role'         => 'required|in_list[SuperAdmin,Admin,Dosen,Ketua,Member]',
        'status_akun'  => 'required|in_list[Aktif,Nonaktif]',
        'nama_lengkap' => 'required|max_length[100]',
        'nim'          => 'permit_empty|max_length[20]|is_unique[users.nim,id_user,{id_user}]',
        'nip'          => 'permit_empty|max_length[30]|is_unique[users.nip,id_user,{id_user}]',
        'program_studi'=> 'permit_empty|max_length[100]',
        'jabatan'      => 'permit_empty|max_length[50]',
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Username ini sudah terdaftar di sistem.',
        ],
        'email' => [
            'is_unique' => 'Email ini sudah terdaftar di sistem.',
        ],
        'nim' => [
            'is_unique' => 'NIM ini sudah terdaftar di sistem.',
        ],
        'nip' => [
            'is_unique' => 'NIP ini sudah terdaftar di sistem.',
        ],
    ];

    /**
     * Helper untuk memetakan alias kompatibilitas view
     */
    public function formatUserAliases(?array $user): ?array
    {
        if (! $user) {
            return null;
        }

        $id = $user['id_user'] ?? $user['idUser'] ?? null;
        $nama = $user['nama_lengkap'] ?? $user['namaMahasiswa'] ?? $user['namaDosen'] ?? $user['namaAdmin'] ?? '';
        $nim = $user['nim'] ?? '';
        $nip = $user['nip'] ?? '';
        $prodi = $user['program_studi'] ?? $user['programStudi'] ?? '';
        $jabatan = $user['jabatan'] ?? '';
        $status = $user['status_akun'] ?? $user['statusAkun'] ?? 'Aktif';
        $idKelompok = $user['id_kelompok'] ?? $user['idKelompok'] ?? null;

        return array_merge($user, [
            'idUser'        => $id,
            'idMahasiswa'   => $id,
            'idDosen'       => $id,
            'idAdmin'       => $id,
            'namaMahasiswa' => $nama,
            'namaDosen'     => $nama,
            'namaAdmin'     => $nama,
            'nipAdmin'      => $nip,
            'nipDosen'      => $nip,
            'programStudi'  => $prodi,
            'statusAkun'    => $status,
            'idKelompok'    => $idKelompok,
            'noWhatsapp'    => $user['no_whatsapp'] ?? $user['noWhatsapp'] ?? '-',
        ]);
    }

    /**
     * Cari user berdasarkan username ATAU email
     */
    public function findByLogin(string $login): ?array
    {
        $user = $this->where('username', $login)
                     ->orWhere('email', $login)
                     ->first();

        return $this->formatUserAliases($user);
    }

    /**
     * Cari user berdasarkan id_user
     */
    public function findByUserId(int $idUser): ?array
    {
        $user = $this->where('id_user', $idUser)->first();
        return $this->formatUserAliases($user);
    }

    /**
     * Cari mahasiswa berdasarkan NIM
     */
    public function findByNim(string $nim): ?array
    {
        $user = $this->where('nim', $nim)->first();
        return $this->formatUserAliases($user);
    }

    /**
     * Dapatkan daftar akun Admin & SuperAdmin
     */
    public function getAdminUsers(): array
    {
        $rows = $this->select('users.*, users.id_user as idAdmin, users.id_user as idUser, users.nama_lengkap as namaAdmin, users.nip as nipAdmin, users.status_akun as statusAkun')
                     ->whereIn('role', ['Admin', 'SuperAdmin'])
                     ->findAll();

        return array_map([$this, 'formatUserAliases'], $rows);
    }

    /**
     * Dapatkan daftar Dosen Peninjau dengan statistik penugasan
     */
    public function getDosenWithStats(): array
    {
        $rows = $this->select("users.*, users.id_user as idDosen, users.id_user as idUser, users.nama_lengkap as namaDosen, users.nip as nipDosen, users.status_akun as statusAkun,
            (SELECT COUNT(*) FROM peninjauan_proposal WHERE peninjauan_proposal.id_user = users.id_user AND peninjauan_proposal.statusPenugasan = 'Selesai') as tugasSelesai,
            (SELECT COUNT(*) FROM peninjauan_proposal WHERE peninjauan_proposal.id_user = users.id_user AND peninjauan_proposal.statusPenugasan = 'Belum Ditinjau') as tugasPending")
            ->where('role', 'Dosen')
            ->findAll();

        return array_map([$this, 'formatUserAliases'], $rows);
    }

    /**
     * Dapatkan seluruh Dosen Peninjau aktif untuk dropdown plotting
     */
    public function getAllDosen(): array
    {
        $rows = $this->select('users.*, users.id_user as idDosen, users.id_user as idUser, users.nama_lengkap as namaDosen, users.nip as nipDosen, users.status_akun as statusAkun')
                     ->where('role', 'Dosen')
                     ->where('status_akun', 'Aktif')
                     ->findAll();

        return array_map([$this, 'formatUserAliases'], $rows);
    }

    /**
     * Mahasiswa yang belum tergabung kelompok (id_kelompok IS NULL)
     */
    public function getMahasiswaBebas(): array
    {
        $rows = $this->select('users.*, users.id_user as idMahasiswa, users.id_user as idUser, users.nama_lengkap as namaMahasiswa, users.program_studi as programStudi, users.status_akun as statusAkun')
                     ->where('id_kelompok', null)
                     ->whereIn('role', ['Member', 'Ketua'])
                     ->where('status_akun', 'Aktif')
                     ->where('nim IS NOT NULL', null, false)
                     ->findAll();

        return array_map([$this, 'formatUserAliases'], $rows);
    }

    /**
     * Seluruh anggota dalam kelompok tertentu
     */
    public function getAnggotaByKelompok(int $idKelompok): array
    {
        $rows = $this->select('users.*, users.id_user as idMahasiswa, users.id_user as idUser, users.nama_lengkap as namaMahasiswa, users.program_studi as programStudi, users.status_akun as statusAkun')
                     ->where('id_kelompok', $idKelompok)
                     ->findAll();

        return array_map([$this, 'formatUserAliases'], $rows);
    }
}
