<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success small py-2 px-3 mb-3 d-flex align-items-center gap-2">
      <i class="bi bi-check-circle-fill flex-shrink-0"></i>
      <div><?= session()->getFlashdata('success') ?></div>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger small py-2 px-3 mb-3 d-flex align-items-center gap-2">
      <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
      <div><?= session()->getFlashdata('error') ?></div>
    </div>
  <?php endif; ?>

  <!-- Banner Admin DKKA -->
  <div class="sipkm-card p-4 mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0f294a 0%, #1e3a8a 100%); color: #fff;">
    <div class="row align-items-center">
      <div class="col-12">
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
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
          <div>
            <h5 class="fw-bold text-dark mb-0">
              <i class="bi bi-exclamation-diamond-fill text-warning me-2"></i>Usulan Proposal Perlu Plotting Dosen
            </h5>
            <small class="text-muted">Daftar usulan proposal mahasiswa (maksimal 10 usulan) &bull; Prioritas butuh plotting Dosen Peninjau</small>
          </div>
          <a href="<?= base_url('admin/proposal') ?>" class="btn btn-primary-sipkm btn-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Plotting Massal
          </a>
        </div>

        <!-- Kolom Pencarian Cepat Proposal -->
        <div class="row g-2 mb-3 align-items-center">
          <div class="col-sm-7 col-md-8">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
              <input type="text" id="searchProposalDashboard" class="form-control" placeholder="Cari judul proposal, kelompok, NIM ketua, atau skema..." onkeyup="filterProposalDashboard()">
            </div>
          </div>
          <div class="col-sm-5 col-md-4 text-sm-end">
            <span class="small text-muted" id="countProposalDashboard">
              Menampilkan <strong><?= count($daftarProposalPlotting ?? []) ?></strong> usulan
            </span>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="tableProposalDashboard">
            <thead class="table-light small">
              <tr>
                <th style="width: 45px;">No</th>
                <th>Skema & Judul Usulan</th>
                <th>Kelompok Pengusul</th>
                <th>Tgl Unggah</th>
                <th class="text-center" style="width: 130px;">Aksi Cepat</th>
              </tr>
            </thead>
            <tbody class="small" id="tbodyProposalDashboard">
              <?php if (! empty($daftarProposalPlotting)): ?>
                <?php $no = 1; foreach ($daftarProposalPlotting as $p): ?>
                  <?php
                    $isDiunggah = ($p['statusProposal'] === 'Diunggah' || empty($p['idDosen']));
                    $searchData = strtolower($p['judulProposal'] . ' ' . $p['namaKelompok'] . ' ' . $p['nimKetua'] . ' ' . ($p['namaKetua'] ?? '') . ' ' . $p['jenisPKM']);
                  ?>
                  <tr class="item-proposal-dashboard" data-text="<?= esc($searchData) ?>">
                    <td><?= $no++ ?></td>
                    <td>
                      <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($p['jenisPKM']) ?></span>
                      <div class="fw-bold text-dark lh-sm"><?= esc($p['judulProposal']) ?></div>
                      <small class="text-muted font-monospace">ID: #<?= esc($p['idProposal']) ?></small>
                    </td>
                    <td>
                      <div class="fw-semibold text-dark"><?= esc($p['namaKelompok']) ?></div>
                      <small class="text-muted">Ketua: <?= esc($p['nimKetua']) ?><?= !empty($p['namaKetua']) ? ' (' . esc($p['namaKetua']) . ')' : '' ?></small>
                    </td>
                    <td><?= esc($p['tglUnggah']) ?></td>
                    <td class="text-center">
                      <?php if ($isDiunggah): ?>
                        <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalPlottingDash_<?= $p['idProposal'] ?>" title="Tugaskan Dosen Peninjau">
                          <i class="bi bi-person-plus me-1"></i> Plotting
                        </button>
                      <?php else: ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-block py-1 px-2">
                          <i class="bi bi-check-circle me-1"></i><?= esc($p['namaDosen'] ?? 'Sudah Diplotting') ?>
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <tr id="noMatchProposalDashboard" style="display: none;">
                  <td colspan="5" class="text-center py-4 text-muted">
                    <i class="bi bi-search me-1"></i> Tidak ditemukan proposal yang cocok dengan kata kunci pencarian.
                  </td>
                </tr>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center py-4 text-muted">
                    Belum ada kelompok yang mengajukan usulan proposal PKM.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Ringkasan Sebaran 10 Bidang PKM Belmawa -->
      <div class="sipkm-card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
          <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-pie-chart-fill text-primary me-2"></i>Sebaran Usulan 10 Bidang PKM 2026
          </h5>
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
            Total: <?= (int)($stats['totalProposal'] ?? 0) ?> Usulan Masuk
          </span>
        </div>
        <div class="row g-3 small">
          <?php
            $sebaranList = $sebaran10Bidang ?? [];
            $half = ceil(count($sebaranList) / 2);
            $leftCol = array_slice($sebaranList, 0, $half);
            $rightCol = array_slice($sebaranList, $half);
          ?>
          <div class="col-md-6">
            <?php foreach ($leftCol as $item): ?>
              <div class="d-flex justify-content-between mb-1">
                <span><strong><?= esc($item['kode']) ?></strong> <span class="text-muted">(<?= esc($item['nama']) ?>)</span></span>
                <strong><?= $item['total'] ?> Usulan (<?= $item['persentase'] ?>%)</strong>
              </div>
              <div class="progress mb-3" style="height: 8px;">
                <div class="progress-bar" style="width: <?= $item['persentase'] ?>%; background-color: <?= esc($item['color']) ?>;"></div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="col-md-6">
            <?php foreach ($rightCol as $item): ?>
              <div class="d-flex justify-content-between mb-1">
                <span><strong><?= esc($item['kode']) ?></strong> <span class="text-muted">(<?= esc($item['nama']) ?>)</span></span>
                <strong><?= $item['total'] ?> Usulan (<?= $item['persentase'] ?>%)</strong>
              </div>
              <div class="progress mb-3" style="height: 8px;">
                <div class="progress-bar" style="width: <?= $item['persentase'] ?>%; background-color: <?= esc($item['color']) ?>;"></div>
              </div>
            <?php endforeach; ?>
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

      <!-- Widget Lomba Terbit Terkini -->
      <div class="sipkm-card p-4 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <h6 class="fw-bold text-dark mb-0">
            <i class="bi bi-trophy-fill text-warning me-2"></i>Lomba Terbit di Papan Publik
          </h6>
          <a href="<?= base_url('admin/lomba') ?>" class="small text-primary text-decoration-none fw-semibold">Kelola &rarr;</a>
        </div>
        <div class="d-flex flex-column gap-2">
          <?php if (!empty($daftarLombaTerbaru)): foreach ($daftarLombaTerbaru as $lt): ?>
            <div class="d-flex align-items-center gap-2 p-2 rounded bg-light border border-light-subtle">
              <img src="<?= base_url('assets/uploads/poster/' . esc($lt['posterBanner'])) ?>" alt="Poster" class="rounded shadow-sm" style="width: 52px; height: 35px; object-fit: cover;" onerror="this.src='https://placehold.co/80x50/0f294a/fff?text=Poster'">
              <div class="overflow-hidden flex-grow-1">
                <div class="fw-bold small text-dark text-truncate"><?= esc($lt['judulLomba']) ?></div>
                <small class="text-muted" style="font-size: 11px;"><?= esc($lt['kategoriLomba']) ?> &bull; s/d <?= esc($lt['timelineSelesai']) ?></small>
              </div>
            </div>
          <?php endforeach; else: ?>
            <small class="text-muted">Belum ada lomba terbit.</small>
          <?php endif; ?>
        </div>
      </div>

      <!-- Menu Pintasan Cepat Admin -->
      <div class="sipkm-card p-4 shadow-sm">
        <h6 class="fw-bold text-dark mb-3">
          <i class="bi bi-grid-fill text-primary me-2"></i>Pintasan Pengelolaan
        </h6>
        <div class="d-grid gap-2">
          <a href="<?= base_url('admin/dosen') ?>" class="btn btn-outline-secondary btn-sm text-start py-2">
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
            <a href="<?= base_url('superadmin/kelola-admin') ?>" class="btn btn-outline-danger btn-sm text-start py-2">
              <i class="bi bi-shield-lock-fill me-2"></i> Kelola Akun Admin DKKA
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Plotting Dosen Satuan dari Dashboard -->
<?php if (! empty($daftarProposalPlotting)): ?>
  <?php foreach ($daftarProposalPlotting as $p): ?>
    <div class="modal fade text-start" id="modalPlottingDash_<?= $p['idProposal'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-light">
            <h6 class="modal-title fw-bold text-dark">
              <i class="bi bi-person-workspace text-primary me-2"></i>Tugaskan Dosen Peninjau
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="<?= base_url('admin/proposal/plotting') ?>" method="post" class="sipkm-form">
            <?= csrf_field() ?>
            <input type="hidden" name="idProposal" value="<?= $p['idProposal'] ?>">
            <input type="hidden" name="redirect_to" value="<?= base_url('admin/dashboard') ?>">
            <div class="modal-body">
              <div class="mb-2">
                <label class="small text-muted mb-0">Judul Usulan Proposal:</label>
                <div class="fw-bold small text-dark"><?= esc($p['judulProposal']) ?></div>
                <small class="text-muted">Skema: <?= esc($p['jenisPKM']) ?> &bull; Kelompok: <?= esc($p['namaKelompok']) ?></small>
              </div>
              <div class="mb-3 mt-2">
                <label class="form-label small fw-semibold">Pilih Dosen Peninjau <span class="text-danger">*</span></label>
                <select name="idDosen" class="form-select" required>
                  <option value="">-- Pilih Dosen Peninjau --</option>
                  <?php foreach ($daftarDosen as $dos): ?>
                    <option value="<?= esc($dos['idDosen']) ?>" <?= ($p['idDosen'] ?? 0) == $dos['idDosen'] ? 'selected' : '' ?>>
                      <?= esc($dos['namaDosen']) ?> (<?= esc($dos['nipDosen']) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="alert alert-info small mb-0">
                <i class="bi bi-info-circle me-1"></i> Setelah disimpan, data penugasan akan masuk ke sistem peninjauan dan status usulan otomatis menjadi <strong>Sedang Ditinjau</strong>.
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menugaskan...">
                <i class="bi bi-check2-circle me-1"></i> Simpan Penugasan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<script>
function filterProposalDashboard() {
  const query = document.getElementById('searchProposalDashboard').value.toLowerCase().trim();
  const rows = document.querySelectorAll('#tbodyProposalDashboard tr.item-proposal-dashboard');
  let visibleCount = 0;

  rows.forEach(row => {
    const text = row.getAttribute('data-text') || '';
    if (!query || text.includes(query)) {
      row.style.display = '';
      visibleCount++;
    } else {
      row.style.display = 'none';
    }
  });

  const noMatch = document.getElementById('noMatchProposalDashboard');
  if (noMatch) {
    noMatch.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
  }

  const countEl = document.getElementById('countProposalDashboard');
  if (countEl) {
    countEl.innerHTML = `Menampilkan <strong>${visibleCount}</strong> dari ${rows.length} usulan`;
  }
}
</script>
<?= $this->endSection() ?>
