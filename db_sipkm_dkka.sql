-- ====================================================================
-- SISTEM INFORMASI PENGELOLAAN PKM & PAPAN INFORMASI LOMBA (SIPKM-DKKA)
-- UNIVERSITAS BUDI LUHUR
-- Skema Database Revisi Dosen Pembimbing: 5 Tabel Inti (Single Table users)
-- Database: db_sipkm_dkka
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------------------
-- 1. Tabel kelompok_pkm
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `kelompok_pkm`;
CREATE TABLE `kelompok_pkm` (
  `idKelompok` INT(11) NOT NULL AUTO_INCREMENT,
  `namaKelompok` VARCHAR(100) NOT NULL,
  `tahunPeriode` VARCHAR(10) NOT NULL,
  `nimKetua` VARCHAR(20) NOT NULL,
  `jumlahAnggota` INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idKelompok`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------
-- 2. Tabel users (Peleburan tabel user, admin, dosen_peninjau, mahasiswa)
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `id_kelompok` INT(11) DEFAULT NULL,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role` ENUM('SuperAdmin','Admin','Dosen','Ketua','Member') NOT NULL DEFAULT 'Member',
  `nim` VARCHAR(20) DEFAULT NULL,
  `nip` VARCHAR(30) DEFAULT NULL,
  `program_studi` VARCHAR(100) DEFAULT NULL,
  `jabatan` VARCHAR(50) DEFAULT NULL,
  `status_akun` ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `idx_users_username` (`username`),
  UNIQUE KEY `idx_users_email` (`email`),
  UNIQUE KEY `idx_users_nim` (`nim`),
  UNIQUE KEY `idx_users_nip` (`nip`),
  KEY `fk_users_kelompok` (`id_kelompok`),
  CONSTRAINT `fk_users_kelompok` FOREIGN KEY (`id_kelompok`) REFERENCES `kelompok_pkm` (`idKelompok`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------
-- 3. Tabel proposal_pkm
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `proposal_pkm`;
CREATE TABLE `proposal_pkm` (
  `idProposal` INT(11) NOT NULL AUTO_INCREMENT,
  `idKelompok` INT(11) NOT NULL,
  `judulProposal` VARCHAR(255) NOT NULL,
  `jenisPKM` VARCHAR(50) NOT NULL,
  `tautanGoogleDocs` VARCHAR(255) NOT NULL,
  `statusProposal` ENUM('Diunggah','Sedang Ditinjau','Sudah Ditinjau','Layak','Tidak Layak') NOT NULL DEFAULT 'Diunggah',
  `tglUnggah` DATE NOT NULL,
  PRIMARY KEY (`idProposal`),
  UNIQUE KEY `idx_proposal_kelompok` (`idKelompok`),
  CONSTRAINT `fk_proposal_kelompok` FOREIGN KEY (`idKelompok`) REFERENCES `kelompok_pkm` (`idKelompok`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------
-- 4. Tabel peninjauan_proposal
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `peninjauan_proposal`;
CREATE TABLE `peninjauan_proposal` (
  `idPeninjauan` INT(11) NOT NULL AUTO_INCREMENT,
  `idProposal` INT(11) NOT NULL,
  `id_user` INT(11) NOT NULL,
  `catatanAdministrasi` TEXT DEFAULT NULL,
  `catatanSubstansi` TEXT DEFAULT NULL,
  `tautanRekapDrive` VARCHAR(255) DEFAULT NULL,
  `statusPenugasan` ENUM('Belum Ditinjau','Selesai') NOT NULL DEFAULT 'Belum Ditinjau',
  `tglPenugasan` DATE NOT NULL,
  `tglSelesaiTinjau` DATE DEFAULT NULL,
  PRIMARY KEY (`idPeninjauan`),
  UNIQUE KEY `idx_peninjauan_proposal` (`idProposal`),
  KEY `fk_peninjauan_users` (`id_user`),
  CONSTRAINT `fk_peninjauan_proposal` FOREIGN KEY (`idProposal`) REFERENCES `proposal_pkm` (`idProposal`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_peninjauan_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------------------
-- 5. Tabel informasi_lomba
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `informasi_lomba`;
CREATE TABLE `informasi_lomba` (
  `idLomba` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `judulLomba` VARCHAR(200) NOT NULL,
  `kategoriLomba` VARCHAR(100) NOT NULL,
  `deskripsiLomba` TEXT NOT NULL,
  `timelineMulai` DATE NOT NULL,
  `timelineSelesai` DATE NOT NULL,
  `posterBanner` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idLomba`),
  KEY `fk_lomba_users` (`id_user`),
  CONSTRAINT `fk_lomba_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Hapus tabel lama jika masih tersisa
DROP TABLE IF EXISTS `admin`;
DROP TABLE IF EXISTS `dosen_peninjau`;
DROP TABLE IF EXISTS `mahasiswa`;
DROP TABLE IF EXISTS `user`;

SET FOREIGN_KEY_CHECKS = 1;

-- ====================================================================
-- DATA SEEDER AWAL (5 ROLE LENGKAP)
-- Password Default: password123 ($2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
-- ====================================================================

-- 1. Data Kelompok PKM
INSERT INTO `kelompok_pkm` (`idKelompok`, `namaKelompok`, `tahunPeriode`, `nimKetua`, `jumlahAnggota`) VALUES
(1, 'Tim Inovasi Smart Campus UBL', '2026', '2412501001', 2),
(2, 'Tim GreenTech UBL', '2026', '2412501873', 1),
(3, 'Tim Edukasi Inklusif UBL', '2026', '2474294831', 1);

-- 2. Data Pengguna (users: SuperAdmin, Admin, Dosen, Ketua, Member)
INSERT INTO `users` (`id_user`, `id_kelompok`, `username`, `email`, `password`, `nama_lengkap`, `role`, `nim`, `nip`, `program_studi`, `jabatan`, `status_akun`) VALUES
-- Super Admin
(1, NULL, 'superadmin', 'superadmin.dkka@budiluhur.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso, M.Kom.', 'SuperAdmin', NULL, '198801012015011001', NULL, 'Super Admin', 'Aktif'),
-- Admin DKKA
(2, NULL, 'admindkka', 'admin.dkka@budiluhur.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rina Kartika, S.E.', 'Admin', NULL, '199205142018032002', NULL, 'Admin DKKA', 'Aktif'),
-- Dosen Peninjau
(3, NULL, 'dosen1', 'ahmad.fauzi@budiluhur.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Ahmad Fauzi, M.T.', 'Dosen', NULL, '0315088501', NULL, NULL, 'Aktif'),
(4, NULL, 'dosen2', 'siti.rahmawati@budiluhur.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Rahmawati, S.Kom., M.Cs.', 'Dosen', NULL, '0322118902', NULL, NULL, 'Aktif'),
-- Mahasiswa Ketua Kelompok 1
(5, 1, 'ketua_pkm1', '2412501001@student.budiluhur.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rizky Pratama', 'Ketua', '2412501001', NULL, 'Sistem Informasi', NULL, 'Aktif'),
-- Mahasiswa Member Kelompok 1
(6, 1, 'anggota_pkm1', '2412501002@student.budiluhur.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nadia Putri', 'Member', '2412501002', NULL, 'Teknik Informatika', NULL, 'Aktif'),
-- Mahasiswa Bebas (Belum punya kelompok)
(7, NULL, 'mhs_bebas1', '2412501872@student.budiluhur.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mahasiswa Bebas 1', 'Member', '2412501872', NULL, 'Sistem Informasi', NULL, 'Aktif'),
-- Mahasiswa Ketua Kelompok 2
(8, 2, 'mhs_bebas2', '2412501873@student.budiluhur.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dimas Aditya Saputra', 'Ketua', '2412501873', NULL, 'Sistem Komputer', NULL, 'Aktif'),
-- Mahasiswa Ketua Kelompok 3
(9, 3, 'astriananta', '2474294831@student.budiluhur.ac.id', '$2y$10$3us4e9DCHKCo0EmubyG29e.71hVwE4iNNJ6G6h9LOJGZKqhE7T8su', 'Astri Ananta Kartini', 'Ketua', '2474294831', NULL, 'Sistem Informasi', NULL, 'Aktif');

-- 3. Data Proposal PKM
INSERT INTO `proposal_pkm` (`idProposal`, `idKelompok`, `judulProposal`, `jenisPKM`, `tautanGoogleDocs`, `statusProposal`, `tglUnggah`) VALUES
(1, 1, 'Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI', 'PKM-KC', 'https://docs.google.com/document/d/1ExampleSmartCampusUBL2026/edit', 'Sudah Ditinjau', '2026-11-10'),
(2, 2, 'Rancang Bangun Prototipe Pengering Sampah Otomatis Berbasis Panel Surya', 'PKM-PI', 'https://docs.google.com/document/d/2ExampleSolarDryerUBL2026/edit', 'Diunggah', '2026-11-12');

-- 4. Data Peninjauan Proposal (id_user merujuk ke Dosen Peninjau di users.id_user = 3)
INSERT INTO `peninjauan_proposal` (`idPeninjauan`, `idProposal`, `id_user`, `catatanAdministrasi`, `catatanSubstansi`, `tautanRekapDrive`, `statusPenugasan`, `tglPenugasan`, `tglSelesaiTinjau`) VALUES
(1, 1, 3, '1. Margin kiri sudah 4 cm, daftar pustaka konsisten.\n2. Lembar pengesahan lengkap.', '1. Latar belakang urgensi inovasi relevan dan sangat kuat.\n2. Metodologi pengujian akurasi sensor telah diperjelas.', 'https://drive.google.com/drive/folders/1HasilRekapPenilaianDosenUBL2026', 'Belum Ditinjau', '2026-11-17', '2026-11-16');

-- 5. Data Informasi Lomba (id_user merujuk ke Admin DKKA di users.id_user = 2 / 1)
INSERT INTO `informasi_lomba` (`idLomba`, `id_user`, `judulLomba`, `kategoriLomba`, `deskripsiLomba`, `timelineMulai`, `timelineSelesai`, `posterBanner`) VALUES
(1, 2, 'Program Kreativitas Mahasiswa (PKM) Internal UBL 2026', 'PKM & Karya Ilmiah', 'Seleksi pendanaan dan pembinaan proposal PKM 10 bidang tingkat Universitas Budi Luhur menuju PIMNAS 2026. Wajib membentuk kelompok 3-5 mahasiswa lintas angkatan.', '2026-11-01', '2026-12-15', 'poster-pkm-2026.jpg'),
(2, 2, 'GEMASTIK XIX — Divisi Pengembangan Perangkat Lunak', 'Teknologi & IT', 'Pagelaran Mahasiswa Nasional Bidang Teknologi Informasi dan Komunikasi. Daftarkan tim terbaikmu melalui DKKA Universitas Budi Luhur.', '2026-11-10', '2026-12-20', 'poster-gemastik-2026.jpg'),
(3, 2, 'Kompetisi Bisnis Mahasiswa Indonesia (KBMI) 2026', 'Kewirausahaan', 'Ajang hibah modal usaha dan pendampingan bisnis bagi mahasiswa aktif yang memiliki rintisan usaha inovatif.', '2026-11-15', '2027-01-10', 'poster-kbmi-2026.jpg'),
(4, 1, 'LOMBA PPBL', 'Seni & Humaniora', 'SBCJC Sbcs csu hjcbd vsufieufbbv siuf vh', '2026-11-20', '2027-01-07', '1794986704_c3b4fb65b21abd954c83.jpg');
