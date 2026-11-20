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
        'id_user',
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
        'id_user'          => 'required|is_natural_no_zero',
        'statusPenugasan'  => 'required|in_list[Belum Ditinjau,Selesai]',
        'tglPenugasan'     => 'required|valid_date[Y-m-d]',
        'tglSelesaiTinjau' => 'permit_empty|valid_date[Y-m-d]',
    ];

    public function findByProposalId(int $idProposal): ?array
    {
        $row = $this->select('peninjauan_proposal.*, peninjauan_proposal.id_user as idDosen')
                    ->where('idProposal', $idProposal)
                    ->first();
        return $row;
    }

    /**
     * Dapatkan daftar penugasan proposal yang ditugaskan ke dosen tertentu (berdasarkan id_user dosen)
     */
    public function getTugasByDosen(int $idDosen): array
    {
        return $this->select('peninjauan_proposal.*, peninjauan_proposal.id_user as idDosen,
                              proposal_pkm.judulProposal, proposal_pkm.jenisPKM,
                              proposal_pkm.tautanGoogleDocs, proposal_pkm.statusProposal,
                              kelompok_pkm.namaKelompok, kelompok_pkm.nimKetua, kelompok_pkm.tahunPeriode')
                    ->join('proposal_pkm', 'proposal_pkm.idProposal = peninjauan_proposal.idProposal')
                    ->join('kelompok_pkm', 'kelompok_pkm.idKelompok = proposal_pkm.idKelompok')
                    ->where('peninjauan_proposal.id_user', $idDosen)
                    ->findAll();
    }

    /**
     * Dapatkan detail penugasan proposal lengkap dengan rincian proposal & kelompok
     */
    public function getDetailPeninjauan(int $idPeninjauan): ?array
    {
        return $this->select('peninjauan_proposal.*, peninjauan_proposal.id_user as idDosen,
                              proposal_pkm.judulProposal, proposal_pkm.jenisPKM,
                              proposal_pkm.tautanGoogleDocs, proposal_pkm.statusProposal,
                              kelompok_pkm.namaKelompok, kelompok_pkm.nimKetua, kelompok_pkm.tahunPeriode,
                              users.nama_lengkap as namaDosen, users.nip as nipDosen')
                    ->join('proposal_pkm', 'proposal_pkm.idProposal = peninjauan_proposal.idProposal')
                    ->join('kelompok_pkm', 'kelompok_pkm.idKelompok = proposal_pkm.idKelompok')
                    ->join('users', "users.id_user = peninjauan_proposal.id_user AND users.role = 'Dosen'", 'left')
                    ->where('peninjauan_proposal.idPeninjauan', $idPeninjauan)
                    ->first();
    }
}
