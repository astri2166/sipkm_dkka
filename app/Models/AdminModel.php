<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admin';
    protected $primaryKey       = 'idAdmin';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idUser',
        'nipAdmin',
        'namaAdmin',
        'jabatan',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;

    protected $validationRules = [
        'idUser'    => 'required|is_natural_no_zero',
        'nipAdmin'  => 'required|max_length[30]|is_unique[admin.nipAdmin,idAdmin,{idAdmin}]',
        'namaAdmin' => 'required|max_length[100]',
        'jabatan'   => 'required|max_length[50]',
    ];

    public function findByUserId(int $idUser): ?array
    {
        return $this->where('idUser', $idUser)->first();
    }

    public function getAdminWithUser(): array
    {
        return $this->select('admin.*, user.username, user.email, user.role, user.statusAkun')
                    ->join('user', 'user.idUser = admin.idUser')
                    ->findAll();
    }
}
