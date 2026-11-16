<?php

namespace App\Models;

use CodeIgniter\Model;

class PeninjauanProposalModel extends Model
{
    protected $table            = 'peninjauan_proposal';
    protected $primaryKey       = 'idPeninjauan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idProposal',
        'idDosen',
        'catatanAdministrasi',
        'catatanSubstansi',
        'tautanRekapDrive',
        'statusPenugasan',
        'tglPenugasan',
        'tglSelesaiTinjau',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;

    protected $validationRules = [
        'idProposal'       => 'required|is_natural_no_zero|is_unique[peninjauan_proposal.idProposal,idPeninjauan,{idPeninjauan}]',
        'idDosen'          => 'required|is_natural_no_zero',
        'statusPenugasan'  => 'required|in_list[Belum Ditinjau,Selesai]',
        'tglPenugasan'     => 'required|valid_date[Y-m-d]',
        'tglSelesaiTinjau' => 'permit_empty|valid_date[Y-m-d]',
    ];

    public function findByProposalId(int $idProposal): ?array
    {
        return $this->where('idProposal', $idProposal)->first();
    }

    /**
     * Dapatkan daftar penugasan proposal yang ditugaskan ke dosen tertentu
     */
    public function getTugasByDosen(int $idDosen): array
    {
        return $this->select('peninjauan_proposal.*, proposal_pkm.judulProposal, proposal_pkm.jenisPKM,
                              proposal_pkm.tautanGoogleDocs, proposal_pkm.statusProposal,
                              kelompok_pkm.namaKelompok, kelompok_pkm.nimKetua, kelompok_pkm.tahunPeriode')
                    ->join('proposal_pkm', 'proposal_pkm.idProposal = peninjauan_proposal.idProposal')
                    ->join('kelompok_pkm', 'kelompok_pkm.idKelompok = proposal_pkm.idKelompok')
                    ->where('peninjauan_proposal.idDosen', $idDosen)
                    ->findAll();
    }
}
