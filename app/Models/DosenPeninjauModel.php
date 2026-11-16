<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenPeninjauModel extends Model
{
    protected $table            = 'dosen_peninjau';
    protected $primaryKey       = 'idDosen';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idUser',
        'nipDosen',
        'namaDosen',
        'email',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;

    protected $validationRules = [
        'idUser'    => 'required|is_natural_no_zero',
        'nipDosen'  => 'required|max_length[30]|is_unique[dosen_peninjau.nipDosen,idDosen,{idDosen}]',
        'namaDosen' => 'required|max_length[100]',
        'email'     => 'required|valid_email|max_length[100]',
    ];

    public function findByUserId(int $idUser): ?array
    {
        return $this->where('idUser', $idUser)->first();
    }

    /**
     * Dapatkan daftar dosen beserta status akun user dan hitungan tugas
     */
    public function getDosenWithStats(): array
    {
        return $this->select('dosen_peninjau.*, user.statusAkun, user.username,
            (SELECT COUNT(*) FROM peninjauan_proposal WHERE peninjauan_proposal.idDosen = dosen_peninjau.idDosen AND peninjauan_proposal.statusPenugasan = "Selesai") as tugasSelesai,
            (SELECT COUNT(*) FROM peninjauan_proposal WHERE peninjauan_proposal.idDosen = dosen_peninjau.idDosen AND peninjauan_proposal.statusPenugasan = "Belum Ditinjau") as tugasPending')
            ->join('user', 'user.idUser = dosen_peninjau.idUser')
            ->findAll();
    }
}
