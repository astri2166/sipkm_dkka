<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnifiedSipkmTables extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        // 1. Table: kelompok_pkm
        $this->forge->addField([
            'idKelompok' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'namaKelompok' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'tahunPeriode' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'nimKetua' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'jumlahAnggota' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
        ]);
        $this->forge->addKey('idKelompok', true);
        $this->forge->createTable('kelompok_pkm', true);

        // 2. Table: users
        $this->forge->addField([
            'id_user' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'id_kelompok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['SuperAdmin', 'Admin', 'Dosen', 'Ketua', 'Member'],
                'default'    => 'Member',
            ],
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'unique'     => true,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'unique'     => true,
            ],
            'program_studi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'status_akun' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Nonaktif'],
                'default'    => 'Aktif',
            ],
        ]);
        $this->forge->addKey('id_user', true);
        $this->forge->addForeignKey('id_kelompok', 'kelompok_pkm', 'idKelompok', 'CASCADE', 'SET NULL', 'fk_users_kelompok');
        $this->forge->createTable('users', true);

        // 3. Table: proposal_pkm
        $this->forge->addField([
            'idProposal' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'idKelompok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unique'     => true,
            ],
            'judulProposal' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'jenisPKM' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'tautanGoogleDocs' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'statusProposal' => [
                'type'       => 'ENUM',
                'constraint' => ['Diunggah', 'Sedang Ditinjau', 'Sudah Ditinjau', 'Layak', 'Tidak Layak'],
                'default'    => 'Diunggah',
            ],
            'tglUnggah' => [
                'type' => 'DATE',
            ],
        ]);
        $this->forge->addKey('idProposal', true);
        $this->forge->addForeignKey('idKelompok', 'kelompok_pkm', 'idKelompok', 'CASCADE', 'CASCADE', 'fk_proposal_kelompok');
        $this->forge->createTable('proposal_pkm', true);

        // 4. Table: peninjauan_proposal
        $this->forge->addField([
            'idPeninjauan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'idProposal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unique'     => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'catatanAdministrasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'catatanSubstansi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tautanRekapDrive' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'statusPenugasan' => [
                'type'       => 'ENUM',
                'constraint' => ['Belum Ditinjau', 'Selesai'],
                'default'    => 'Belum Ditinjau',
            ],
            'tglPenugasan' => [
                'type' => 'DATE',
            ],
            'tglSelesaiTinjau' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('idPeninjauan', true);
        $this->forge->addForeignKey('idProposal', 'proposal_pkm', 'idProposal', 'CASCADE', 'CASCADE', 'fk_peninjauan_proposal');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE', 'fk_peninjauan_users');
        $this->forge->createTable('peninjauan_proposal', true);

        // 5. Table: informasi_lomba
        $this->forge->addField([
            'idLomba' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'judulLomba' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'kategoriLomba' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'deskripsiLomba' => [
                'type' => 'TEXT',
            ],
            'timelineMulai' => [
                'type' => 'DATE',
            ],
            'timelineSelesai' => [
                'type' => 'DATE',
            ],
            'posterBanner' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
        $this->forge->addKey('idLomba', true);
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE', 'fk_lomba_users');
        $this->forge->createTable('informasi_lomba', true);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('informasi_lomba', true);
        $this->forge->dropTable('peninjauan_proposal', true);
        $this->forge->dropTable('proposal_pkm', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('kelompok_pkm', true);
        $this->db->enableForeignKeyChecks();
    }
}
