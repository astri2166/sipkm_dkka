<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'idMahasiswa';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idUser',
        'idKelompok',
        'nim',
        'namaMahasiswa',
        'programStudi',
        'noWhatsapp',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;

    protected $validationRules = [
        'idUser'        => 'required|is_natural_no_zero',
        'nim'           => 'required|max_length[20]|is_unique[mahasiswa.nim,idMahasiswa,{idMahasiswa}]',
        'namaMahasiswa' => 'required|max_length[100]',
        'programStudi'  => 'required|max_length[100]',
        'noWhatsapp'    => 'required|max_length[20]',
    ];

    public function findByUserId(int $idUser): ?array
    {
        return $this->where('idUser', $idUser)->first();
    }

    public function findByNim(string $nim): ?array
    {
        return $this->where('nim', $nim)->first();
    }

    /**
     * Cari mahasiswa yang belum punya kelompok (idKelompok IS NULL)
     */
    public function getMahasiswaBebas(): array
    {
        return $this->where('idKelompok', null)->findAll();
    }

    /**
     * Dapatkan semua anggota dalam satu kelompok
     */
    public function getAnggotaByKelompok(int $idKelompok): array
    {
        return $this->select('mahasiswa.*, user.email, user.role')
                    ->join('user', 'user.idUser = mahasiswa.idUser')
                    ->where('mahasiswa.idKelompok', $idKelompok)
                    ->findAll();
    }
}
