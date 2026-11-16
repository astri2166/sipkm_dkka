<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Banner Pengguna & Navigasi Cepat Role Preview -->
  <div class="sipkm-card p-4 mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0f294a 0%, #1e40af 100%); color: #fff;">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-1 rounded-pill">
          <i class="bi bi-person-badge-fill me-1"></i> Mode Mahasiswa: <?= esc($role) ?>
        </span>
        <h3 class="fw-bold mb-1">Selamat Datang, <?= esc($mahasiswa['namaMahasiswa'] ?? 'Mahasiswa UBL') ?>!</h3>
        <p class="mb-0 text-white-50">
          NIM: <strong><?= esc($mahasiswa['nim'] ?? '2412501001') ?></strong> &bull; 
          Program Studi: <strong><?= esc($mahasiswa['programStudi'] ?? 'Sistem Informasi') ?></strong> &bull; 
          Periode PKM: <strong><?= esc($kelompok['tahunPeriode'] ?? '2026') ?></strong>
        </p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <div class="btn-group shadow-sm">
          <a href="<?= base_url('mahasiswa/dashboard?role=Ketua+Kelompok') ?>" class="btn btn-sm <?= $role === 'Ketua Kelompok' ? 'btn-warning text-dark fw-bold' : 'btn-outline-light' ?>">
            <i class="bi bi-person-fill-gear"></i> Ketua
          </a>
          <a href="<?= base_url('mahasiswa/dashboard?role=Anggota+Kelompok') ?>" class="btn btn-sm <?= $role === 'Anggota Kelompok' ? 'btn-warning text-dark fw-bold' : 'btn-outline-light' ?>">
            <i class="bi bi-person-fill"></i> Anggota
          </a>
          <a href="<?= base_url('mahasiswa/dashboard?role=Ketua+Kelompok&has_kelompok=0') ?>" class="btn btn-sm <?= isset($_GET['has_kelompok']) && $_GET['has_kelompok'] === '0' ? 'btn-warning text-dark fw-bold' : 'btn-outline-light' ?>">
            <i class="bi bi-person-x"></i> Belum Kelompok
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- 4 Statistik Kartu Ringkasan -->
  <div class="row g-3 mb-4">
    <!-- Stat 1: Status Kelompok -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-primary border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Status Kelompok</small>
            <?php if ($hasKelompok): ?>
              <h5 class="fw-bold text-dark mb-0 mt-1"><?= esc($kelompok['namaKelompok']) ?></h5>
              <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">
                <i class="bi bi-check-circle-fill me-1"></i> Terdaftar
              </span>
            <?php else: ?>
              <h5 class="fw-bold text-danger mb-0 mt-1">Belum Ada Kelompok</h5>
              <span class="badge bg-danger-subtle text-danger border border-danger-subtle mt-1">
                <i class="bi bi-exclamation-circle-fill me-1"></i> idKelompok NULL
              </span>
            <?php endif; ?>
          </div>
          <div class="bg-primary-subtle text-primary p-3 rounded-circle">
            <i class="bi bi-people-fill fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 2: Status Proposal PKM -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-warning border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Status Proposal PKM</small>
            <?php if ($hasProposal): ?>
              <h5 class="fw-bold text-dark mb-0 mt-1"><?= esc($proposal['jenisPKM']) ?></h5>
              <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mt-1">
                <i class="bi bi-clock-history me-1"></i> <?= esc($proposal['statusProposal']) ?>
              </span>
            <?php else: ?>
              <h5 class="fw-bold text-muted mb-0 mt-1">Belum Diunggah</h5>
              <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle mt-1">Draft Kosong</span>
            <?php endif; ?>
          </div>
          <div class="bg-warning-subtle text-warning p-3 rounded-circle">
            <i class="bi bi-file-earmark-text-fill fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 3: Plafon & Anggota -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-info border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Jumlah Anggota Tim</small>
            <h5 class="fw-bold text-dark mb-0 mt-1"><?= $hasKelompok ? esc($kelompok['jumlahAnggota']) . ' Mahasiswa' : '0 Mahasiswa' ?></h5>
            <small class="text-muted">Pedoman: 3 - 5 Mahasiswa</small>
          </div>
          <div class="bg-info-subtle text-info p-3 rounded-circle">
            <i class="bi bi-person-check-fill fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 4: Dosen Peninjau Terplotting -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-success border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Dosen Peninjau</small>
            <h6 class="fw-bold text-dark mb-0 mt-1 text-truncate" style="max-width: 140px;">
              <?= esc($dosen['namaDosen'] ?? 'Belum Diplotting') ?>
            </h6>
            <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">
              <i class="bi bi-shield-check me-1"></i> DKKA Terverifikasi
            </span>
          </div>
          <div class="bg-success-subtle text-success p-3 rounded-circle">
            <i class="bi bi-person-workspace fs-4"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Kolom Kiri: Proposal Aktif & Quick Actions -->
    <div class="col-lg-8">
      <!-- Card Status Proposal Utama -->
      <div class="sipkm-card p-4 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-file-earmark-check text-primary me-2"></i>Usulan Proposal PKM Aktif
          </h5>
          <?php if ($hasProposal): ?>
            <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 rounded-pill">
              <?= esc($proposal['jenisPKM']) ?>
            </span>
          <?php endif; ?>
        </div>

        <?php if ($hasProposal): ?>
          <div class="p-3 bg-light rounded-3 mb-3 border">
            <h6 class="fw-bold text-dark mb-2"><?= esc($proposal['judulProposal']) ?></h6>
            <div class="d-flex flex-wrap gap-3 text-muted small mb-3">
              <span><i class="bi bi-calendar3 me-1"></i> Tgl Unggah: <strong><?= esc($proposal['tglUnggah']) ?></strong></span>
              <span><i class="bi bi-flag-fill me-1 text-warning"></i> Status: <strong><?= esc($proposal['statusProposal']) ?></strong></span>
              <span><i class="bi bi-person-fill me-1"></i> Ketua: <strong><?= esc($kelompok['nimKetua']) ?></strong></span>
            </div>

            <!-- Pratinjau Tautan Google Docs -->
            <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded border">
              <div class="d-flex align-items-center gap-2 text-truncate me-2">
                <i class="bi bi-google text-danger fs-5"></i>
                <span class="small text-truncate font-monospace text-muted"><?= esc($proposal['tautanGoogleDocs']) ?></span>
              </div>
              <a href="<?= esc($proposal['tautanGoogleDocs']) ?>" target="_blank" class="btn btn-sm btn-outline-primary text-nowrap">
                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Proposal
              </a>
            </div>
          </div>

          <!-- Aksi Cepat Terkait Proposal -->
          <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('mahasiswa/tracking') ?>" class="btn btn-primary-sipkm btn-sm">
              <i class="bi bi-activity me-1"></i> Lihat Tracking & Catatan Peninjau
            </a>
            <?php if ($role === 'Ketua Kelompok'): ?>
              <a href="<?= base_url('mahasiswa/proposal') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil-square me-1"></i> Perbarui Tautan Proposal
              </a>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <!-- State Belum Ada Proposal -->
          <div class="text-center py-4">
            <div class="bg-warning-subtle text-warning d-inline-flex p-3 rounded-circle mb-3">
              <i class="bi bi-file-earmark-plus display-6"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1">Belum Ada Proposal PKM yang Diajukan</h6>
            <p class="text-muted small mb-3">Kelompokmu belum mengunggah tautan berkas proposal PKM ke sistem DKKA.</p>
            <?php if ($role === 'Ketua Kelompok'): ?>
              <a href="<?= base_url('mahasiswa/proposal') ?>" class="btn btn-primary-sipkm btn-sm">
                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Mulai Input Proposal Sekarang
              </a>
            <?php else: ?>
              <div class="alert alert-info d-inline-block small mb-0">
                <i class="bi bi-info-circle me-1"></i> Pengunggahan proposal hanya dapat dilakukan oleh akun <strong>Ketua Kelompok</strong>.
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Card Anggota Tim PKM -->
      <div class="sipkm-card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <div>
            <h5 class="fw-bold text-dark mb-0">
              <i class="bi bi-people text-primary me-2"></i>Susunan Anggota Kelompok
            </h5>
            <small class="text-muted"><?= esc($kelompok['namaKelompok'] ?? 'Belum ada kelompok') ?></small>
          </div>
          <a href="<?= base_url('mahasiswa/kelompok') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-gear-fill me-1"></i> Kelola Tim
          </a>
        </div>

        <?php if ($hasKelompok): ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light small">
                <tr>
                  <th>No</th>
                  <th>NIM</th>
                  <th>Nama Mahasiswa</th>
                  <th>Program Studi</th>
                  <th>Peran</th>
                  <th>No. WhatsApp</th>
                </tr>
              </thead>
              <tbody class="small">
                <?php $no = 1; foreach ($daftarAnggota as $m): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td class="fw-semibold"><?= esc($m['nim']) ?></td>
                    <td><?= esc($m['namaMahasiswa']) ?></td>
                    <td><?= esc($m['programStudi']) ?></td>
                    <td>
                      <?php if ($m['nim'] === $kelompok['nimKetua']): ?>
                        <span class="badge bg-primary text-white"><i class="bi bi-star-fill me-1"></i> Ketua</span>
                      <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary">Anggota</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $m['noWhatsapp']) ?>" target="_blank" class="text-success text-decoration-none">
                        <i class="bi bi-whatsapp me-1"></i><?= esc($m['noWhatsapp']) ?>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-4">
            <p class="text-muted small mb-2">Kamu belum tergabung dalam kelompok PKM manapun.</p>
            <a href="<?= base_url('mahasiswa/kelompok') ?>" class="btn btn-warning text-dark btn-sm fw-bold">
              <i class="bi bi-plus-circle me-1"></i> Buat Kelompok Baru
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Kolom Kanan: Panduan, Jadwal, & Info DKKA -->
    <div class="col-lg-4">
      <!-- Card Jadwal & Timeline Penting -->
      <div class="sipkm-card p-4 mb-4 shadow-sm">
        <h6 class="fw-bold text-dark mb-3">
          <i class="bi bi-calendar-check text-warning me-2"></i>Timeline PKM UBL 2026
        </h6>
        <ul class="list-group list-group-flush small">
          <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">Batas Submit Proposal</div>
              <small class="text-muted">Melalui SIPKM-DKKA</small>
            </div>
            <span class="badge bg-danger-subtle text-danger">15 Des 2026</span>
          </li>
          <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">Peninjauan Dosen</div>
              <small class="text-muted">Administrasi & Substansi</small>
            </div>
            <span class="badge bg-warning-subtle text-warning">16 - 22 Des 2026</span>
          </li>
          <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">Pengumuman Kelayakan</div>
              <small class="text-muted">Plafon Pendanaan DKKA</small>
            </div>
            <span class="badge bg-success-subtle text-success">28 Des 2026</span>
          </li>
        </ul>
      </div>

      <!-- Card Tautan Panduan 10 Bidang PKM -->
      <div class="sipkm-card p-4 shadow-sm">
        <h6 class="fw-bold text-dark mb-2">
          <i class="bi bi-info-circle text-primary me-2"></i>10 Bidang PKM Kemdikbudristek
        </h6>
        <p class="text-muted small mb-3">Pastikan skema yang dipilih sesuai dengan inovasi tim:</p>
        <div class="d-flex flex-wrap gap-1 mb-3">
          <span class="badge bg-light text-dark border">PKM-RE</span>
          <span class="badge bg-light text-dark border">PKM-RSH</span>
          <span class="badge bg-light text-dark border">PKM-K</span>
          <span class="badge bg-light text-dark border">PKM-PM</span>
          <span class="badge bg-light text-dark border">PKM-PI</span>
          <span class="badge bg-primary text-white">PKM-KC</span>
          <span class="badge bg-light text-dark border">PKM-KI</span>
          <span class="badge bg-light text-dark border">PKM-VGK</span>
          <span class="badge bg-light text-dark border">PKM-GFT</span>
          <span class="badge bg-light text-dark border">PKM-AI</span>
        </div>
        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary btn-sm w-100">
          <i class="bi bi-trophy me-1"></i> Buka Informasi Lomba Lainnya
        </a>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
