<?php

namespace App\Models;

use CodeIgniter\Model;

class InformasiLombaModel extends Model
{
    protected $table            = 'informasi_lomba';
    protected $primaryKey       = 'idLomba';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'judulLomba',
        'kategoriLomba',
        'deskripsiLomba',
        'timelineMulai',
        'timelineSelesai',
        'posterBanner',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;

    protected $validationRules = [
        'id_user'         => 'required|is_natural_no_zero',
        'judulLomba'      => 'required|max_length[200]',
        'kategoriLomba'   => 'required|max_length[100]',
        'deskripsiLomba'  => 'required',
        'timelineMulai'   => 'required|valid_date[Y-m-d]',
        'timelineSelesai' => 'required|valid_date[Y-m-d]',
        'posterBanner'    => 'required|max_length[255]',
    ];

    /**
     * Dapatkan daftar lomba aktif (urut berdasarkan timeline mulai terbaru)
     */
    public function getActiveLomba(): array
    {
        return $this->select('informasi_lomba.*, informasi_lomba.id_user as idAdmin, users.nama_lengkap as namaAdmin')
                    ->join('users', 'users.id_user = informasi_lomba.id_user', 'left')
                    ->orderBy('timelineMulai', 'DESC')
                    ->findAll();
    }
}
