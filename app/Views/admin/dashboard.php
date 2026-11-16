<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Banner Admin DKKA & Role Preview Switcher -->
  <div class="sipkm-card p-4 mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0f294a 0%, #1e3a8a 100%); color: #fff;">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">
            <i class="bi bi-shield-fill-check me-1"></i> Mode: <?= esc($role) ?>
          </span>
          <span class="badge bg-white text-dark opacity-75">Tahun Akademik 2026</span>
        </div>
        <h3 class="fw-bold mb-1">Pusat Kendali SIPKM-DKKA Universitas Budi Luhur</h3>
        <p class="mb-0 text-white-50">
          Selamat bertugas, <strong><?= esc($userName ?? 'Admin DKKA') ?></strong> &bull; Direktorat Kemahasiswaan dan Karir Alumni
        </p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <div class="btn-group shadow-sm">
          <a href="<?= base_url('admin/dashboard?role=Admin') ?>" class="btn btn-sm <?= $role === 'Admin' ? 'btn-warning text-dark fw-bold' : 'btn-outline-light' ?>">
            <i class="bi bi-person-badge"></i> Admin DKKA
          </a>
          <a href="<?= base_url('admin/dashboard?role=Super+Admin') ?>" class="btn btn-sm <?= $role === 'Super Admin' ? 'btn-warning text-dark fw-bold' : 'btn-outline-light' ?>">
            <i class="bi bi-shield-lock"></i> Super Admin
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- 4 Statistik Kartu Ringkasan Operasional -->
  <div class="row g-3 mb-4">
    <!-- Stat 1: Usulan Proposal PKM -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-primary border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Total Proposal PKM</small>
            <h4 class="fw-bold text-dark mb-0 mt-1"><?= esc($stats['totalProposal'] ?? 12) ?> Usulan</h4>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1">
              <?= esc($stats['proposalLayak'] ?? 4) ?> Dinyatakan Layak
            </span>
          </div>
          <div class="bg-primary-subtle text-primary p-3 rounded-circle">
            <i class="bi bi-file-earmark-text-fill fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 2: Kelompok Terdaftar -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-info border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Kelompok PKM</small>
            <h4 class="fw-bold text-dark mb-0 mt-1"><?= esc($stats['totalKelompok'] ?? 15) ?> Tim</h4>
            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle mt-1">
              <?= esc($stats['totalMahasiswa'] ?? 48) ?> Mahasiswa
            </span>
          </div>
          <div class="bg-info-subtle text-info p-3 rounded-circle">
            <i class="bi bi-people-fill fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 3: Dosen Peninjau & Plotting -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-warning border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Dosen Peninjau</small>
            <h4 class="fw-bold text-warning mb-0 mt-1"><?= esc($stats['totalDosen'] ?? 6) ?> Dosen</h4>
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mt-1">
              <?= esc($stats['needPlotting'] ?? 2) ?> Butuh Plotting
            </span>
          </div>
          <div class="bg-warning-subtle text-warning p-3 rounded-circle">
            <i class="bi bi-person-workspace fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 4: Informasi Lomba Publik -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-success border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Lomba Aktif</small>
            <h4 class="fw-bold text-success mb-0 mt-1"><?= esc($stats['totalLomba'] ?? 3) ?> Program</h4>
            <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">
              Papan Publik Terbit
            </span>
          </div>
          <div class="bg-success-subtle text-success p-3 rounded-circle">
            <i class="bi bi-trophy-fill fs-4"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <!-- Kolom Kiri: Proposal Butuh Plotting Dosen Segera -->
    <div class="col-lg-8">
      <div class="sipkm-card p-4 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <div>
            <h5 class="fw-bold text-dark mb-0">
              <i class="bi bi-exclamation-diamond-fill text-warning me-2"></i>Usulan Proposal Perlu Plotting Dosen
            </h5>
            <small class="text-muted">Proposal baru diunggah mahasiswa dan belum memiliki Dosen Peninjau</small>
          </div>
          <a href="<?= base_url('admin/proposal') ?>" class="btn btn-primary-sipkm btn-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Plotting Massal
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
              <tr>
                <th>No</th>
                <th>Skema & Judul Usulan</th>
                <th>Kelompok</th>
                <th>Tgl Unggah</th>
                <th class="text-center">Aksi Cepat</th>
              </tr>
            </thead>
            <tbody class="small">
              <tr>
                <td>1</td>
                <td>
                  <span class="badge bg-primary-subtle text-primary mb-1">PKM-KC</span>
                  <div class="fw-bold text-dark">Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI</div>
                </td>
                <td>
                  <div class="fw-semibold">Tim Inovasi Smart Campus</div>
                  <small class="text-muted">Ketua: 2412501001</small>
                </td>
                <td>2026-11-10</td>
                <td class="text-center">
                  <a href="<?= base_url('admin/proposal') ?>" class="btn btn-outline-primary btn-sm py-1">
                    <i class="bi bi-person-plus me-1"></i> Plotting
                  </a>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>
                  <span class="badge bg-primary-subtle text-primary mb-1">PKM-KC</span>
                  <div class="fw-bold text-dark">Aplikasi Deteksi Dini Retinopati Diabetik Menggunakan ResNet-50</div>
                </td>
                <td>
                  <div class="fw-semibold">Tim BioMedika UBL</div>
                  <small class="text-muted">Ketua: 2412501050</small>
                </td>
                <td>2026-11-12</td>
                <td class="text-center">
                  <a href="<?= base_url('admin/proposal') ?>" class="btn btn-outline-primary btn-sm py-1">
                    <i class="bi bi-person-plus me-1"></i> Plotting
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Ringkasan Sebaran 10 Bidang PKM Belmawa -->
      <div class="sipkm-card p-4 shadow-sm">
        <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
          <i class="bi bi-pie-chart-fill text-primary me-2"></i>Sebaran Usulan 10 Bidang PKM 2026
        </h5>
        <div class="row g-3 small">
          <div class="col-md-6">
            <div class="d-flex justify-content-between mb-1">
              <span>PKM-KC (Karsa Cipta)</span>
              <strong>5 Usulan (42%)</strong>
            </div>
            <div class="progress mb-3" style="height: 8px;">
              <div class="progress-bar bg-primary" style="width: 42%;"></div>
            </div>

            <div class="d-flex justify-content-between mb-1">
              <span>PKM-PM (Pengabdian Masyarakat)</span>
              <strong>3 Usulan (25%)</strong>
            </div>
            <div class="progress mb-3" style="height: 8px;">
              <div class="progress-bar bg-success" style="width: 25%;"></div>
            </div>

            <div class="d-flex justify-content-between mb-1">
              <span>PKM-RE (Riset Eksakta)</span>
              <strong>2 Usulan (17%)</strong>
            </div>
            <div class="progress mb-3" style="height: 8px;">
              <div class="progress-bar bg-info" style="width: 17%;"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex justify-content-between mb-1">
              <span>PKM-K (Kewirausahaan)</span>
              <strong>1 Usulan (8%)</strong>
            </div>
            <div class="progress mb-3" style="height: 8px;">
              <div class="progress-bar bg-warning" style="width: 8%;"></div>
            </div>

            <div class="d-flex justify-content-between mb-1">
              <span>PKM-RSH (Sosial Humaniora)</span>
              <strong>1 Usulan (8%)</strong>
            </div>
            <div class="progress mb-3" style="height: 8px;">
              <div class="progress-bar bg-secondary" style="width: 8%;"></div>
            </div>

            <div class="d-flex justify-content-between mb-1">
              <span>Bidang Lainnya (VGK, GFT, AI)</span>
              <strong>0 Usulan</strong>
            </div>
            <div class="progress mb-0" style="height: 8px;">
              <div class="progress-bar bg-light" style="width: 0%;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Pintasan Navigasi & Master Penilaian -->
    <div class="col-lg-4">
      <!-- Master Template Penilaian DKKA -->
      <div class="sipkm-card p-4 mb-4 shadow-sm border-start border-warning border-4">
        <h6 class="fw-bold text-dark mb-2">
          <i class="bi bi-google text-danger me-2"></i>Folder Master Penilaian DKKA
        </h6>
        <p class="text-muted small mb-3">
          Tautan resmi Google Drive DKKA tempat seluruh Dosen Peninjau mengunduh lembar form rubrik penilaian:
        </p>
        <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-warning text-dark btn-sm w-100 fw-bold mb-2">
          <i class="bi bi-box-arrow-up-right me-1"></i> Buka Folder Master Drive
        </a>
      </div>

      <!-- Menu Pintasan Cepat Admin -->
      <div class="sipkm-card p-4 shadow-sm">
        <h6 class="fw-bold text-dark mb-3">
          <i class="bi bi-grid-fill text-primary me-2"></i>Pintasan Pengelolaan
        </h6>
        <div class="d-grid gap-2">
          <a href="<?= base_url('admin/pengguna?tab=dosen') ?>" class="btn btn-outline-secondary btn-sm text-start py-2">
            <i class="bi bi-person-badge text-primary me-2"></i> Kelola Dosen Peninjau
          </a>
          <a href="<?= base_url('admin/kelompok') ?>" class="btn btn-outline-secondary btn-sm text-start py-2">
            <i class="bi bi-people text-info me-2"></i> Kelola Kelompok PKM
          </a>
          <a href="<?= base_url('admin/proposal') ?>" class="btn btn-outline-secondary btn-sm text-start py-2">
            <i class="bi bi-file-earmark-text text-warning me-2"></i> Kelola Proposal & Rekap
          </a>
          <a href="<?= base_url('admin/lomba') ?>" class="btn btn-outline-secondary btn-sm text-start py-2">
            <i class="bi bi-trophy text-success me-2"></i> Kelola Papan Informasi Lomba
          </a>
          <?php if ($role === 'Super Admin'): ?>
            <a href="<?= base_url('admin/pengguna?tab=admin') ?>" class="btn btn-outline-danger btn-sm text-start py-2">
              <i class="bi bi-shield-lock-fill me-2"></i> Kelola Akun Admin DKKA
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
