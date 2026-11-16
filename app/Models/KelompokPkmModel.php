<?php

namespace App\Models;

use CodeIgniter\Model;

class KelompokPkmModel extends Model
{
    protected $table            = 'kelompok_pkm';
    protected $primaryKey       = 'idKelompok';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'namaKelompok',
        'tahunPeriode',
        'nimKetua',
        'jumlahAnggota',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;

    protected $validationRules = [
        'namaKelompok'  => 'required|max_length[100]',
        'tahunPeriode'  => 'required|max_length[10]',
        'nimKetua'      => 'required|max_length[20]',
        'jumlahAnggota' => 'required|is_natural_no_zero',
    ];

    /**
     * Dapatkan kelompok beserta nama ketua dan proposal
     */
    public function getKelompokWithDetails(string $periode = ''): array
    {
        $builder = $this->select('kelompok_pkm.*, mahasiswa.namaMahasiswa as namaKetua, proposal_pkm.idProposal, proposal_pkm.judulProposal, proposal_pkm.jenisPKM, proposal_pkm.statusProposal')
                        ->join('mahasiswa', 'mahasiswa.nim = kelompok_pkm.nimKetua', 'left')
                        ->join('proposal_pkm', 'proposal_pkm.idKelompok = kelompok_pkm.idKelompok', 'left');

        if (!empty($periode)) {
            $builder->where('kelompok_pkm.tahunPeriode', $periode);
        }

        return $builder->findAll();
    }
}
