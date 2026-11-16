<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'idUser';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'role',
        'statusAkun',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'username'   => 'required|min_length[3]|max_length[50]|is_unique[user.username,idUser,{idUser}]',
        'email'      => 'required|valid_email|max_length[100]|is_unique[user.email,idUser,{idUser}]',
        'password'   => 'required|min_length[6]',
        'role'       => 'required|in_list[Super Admin,Admin,Dosen Peninjau,Ketua Kelompok,Anggota Kelompok]',
        'statusAkun' => 'required|in_list[Aktif,Nonaktif]',
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Username ini sudah terdaftar di sistem.',
        ],
        'email' => [
            'is_unique' => 'Email ini sudah terdaftar di sistem.',
        ],
    ];

    /**
     * Cari user berdasarkan username ATAU email
     */
    public function findByLogin(string $login): ?array
    {
        return $this->where('username', $login)
                    ->orWhere('email', $login)
                    ->first();
    }
}
