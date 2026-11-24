<?php

namespace App\Models;

use CodeIgniter\Model;

class ProposalPkmModel extends Model
{
    protected $table            = 'proposal_pkm';
    protected $primaryKey       = 'idProposal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idKelompok',
        'judulProposal',
        'jenisPKM',
        'tautanGoogleDocs',
        'statusProposal',
        'tglUnggah',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;

    protected $validationRules = [
        'idKelompok'       => 'required|is_natural_no_zero|is_unique[proposal_pkm.idKelompok,idProposal,{idProposal}]',
        'judulProposal'    => 'required|max_length[255]',
        'jenisPKM'         => 'required|max_length[50]',
        'tautanGoogleDocs' => 'required|valid_url|max_length[255]',
        'statusProposal'   => 'required|in_list[Diunggah,Sedang Ditinjau,Sudah Ditinjau,Layak,Tidak Layak]',
        'tglUnggah'        => 'required|valid_date[Y-m-d]',
    ];

    public function findByKelompokId(int $idKelompok): ?array
    {
        return $this->where('idKelompok', $idKelompok)->first();
    }

    /**
     * Dapatkan daftar proposal lengkap dengan nama kelompok, ketua, dan dosen peninjau
     */
    public function getProposalWithPlotting(): array
    {
        return $this->select('proposal_pkm.*, kelompok_pkm.namaKelompok, kelompok_pkm.nimKetua,
                              peninjauan_proposal.idPeninjauan, peninjauan_proposal.id_user as idDosen, peninjauan_proposal.id_user,
                              peninjauan_proposal.statusPenugasan, peninjauan_proposal.tglPenugasan, peninjauan_proposal.tglSelesaiTinjau,
                              peninjauan_proposal.catatanAdministrasi, peninjauan_proposal.catatanSubstansi, peninjauan_proposal.tautanRekapDrive,
                              users.nama_lengkap as namaDosen, users.nip as nipDosen')
                    ->join('kelompok_pkm', 'kelompok_pkm.idKelompok = proposal_pkm.idKelompok')
                    ->join('peninjauan_proposal', 'peninjauan_proposal.idProposal = proposal_pkm.idProposal', 'left')
                    ->join('users', "users.id_user = peninjauan_proposal.id_user AND users.role = 'Dosen'", 'left')
                    ->findAll();
    }

    /**
     * Dapatkan daftar proposal untuk dashboard admin (maks. 10 usulan, diutamakan yang perlu plotting)
     */
    public function getProposalDashboard(int $limit = 10): array
    {
        return $this->select('proposal_pkm.*, kelompok_pkm.namaKelompok, kelompok_pkm.nimKetua,
                              users_ketua.nama_lengkap as namaKetua,
                              peninjauan_proposal.idPeninjauan, peninjauan_proposal.id_user as idDosen,
                              peninjauan_proposal.statusPenugasan,
                              dosen.nama_lengkap as namaDosen, dosen.nip as nipDosen')
                    ->join('kelompok_pkm', 'kelompok_pkm.idKelompok = proposal_pkm.idKelompok')
                    ->join('users as users_ketua', 'users_ketua.nim = kelompok_pkm.nimKetua', 'left')
                    ->join('peninjauan_proposal', 'peninjauan_proposal.idProposal = proposal_pkm.idProposal', 'left')
                    ->join('users as dosen', "dosen.id_user = peninjauan_proposal.id_user AND dosen.role = 'Dosen'", 'left')
                    ->orderBy("CASE WHEN proposal_pkm.statusProposal = 'Diunggah' THEN 0 ELSE 1 END", 'ASC', false)
                    ->orderBy('proposal_pkm.idProposal', 'DESC')
                    ->findAll($limit);
    }
}
