<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success small py-2 px-3 mb-3 d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm">
      <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
      <div><?= session()->getFlashdata('success') ?></div>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger small py-2 px-3 mb-3 d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm">
      <i class="bi bi-exclamation-triangle-fill text-danger flex-shrink-0"></i>
      <div><?= session()->getFlashdata('error') ?></div>
    </div>
  <?php endif; ?>

  <!-- 1. Top Banner Admin DKKA -->
  <div class="banner-admin-navy mb-4 shadow-sm">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <h4 class="fw-bold text-white mb-1">Selamat bertugas, <?= esc($userName ?? 'Rina Kartika, S.E.') ?></h4>
        <small class="text-white-50" style="font-size: 0.85rem;">
          Admin DKKA, Universitas Budi Luhur &bull; Tahun Akademik 2026
        </small>
      </div>
      <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1" style="border-color: rgba(255,255,255,0.3); font-size: 0.82rem;">
          <i class="bi bi-folder2-open me-1"></i> Folder Penilaian
        </a>
        <div class="d-inline-flex align-items-center gap-1 p-1 rounded-pill" style="background: rgba(255, 255, 255, 0.08);">
          <a href="<?= base_url('mahasiswa/dashboard') ?>" class="btn btn-sm text-white-50 border-0 rounded-pill px-3 py-1 text-decoration-none" style="font-size: 0.8rem;">Ketua</a>
          <a href="<?= base_url('mahasiswa/dashboard') ?>" class="btn btn-sm text-white-50 border-0 rounded-pill px-3 py-1 text-decoration-none" style="font-size: 0.8rem;">Anggota</a>
          <a href="<?= base_url('dosen/dashboard') ?>" class="btn btn-sm text-white-50 border-0 rounded-pill px-3 py-1 text-decoration-none" style="font-size: 0.8rem;">Dosen</a>
          <span class="badge rounded-pill px-3 py-1 fw-bold" style="background: #fbbf24; color: #112842; font-size: 0.8rem;">Admin</span>
          <a href="<?= base_url('superadmin/dashboard') ?>" class="btn btn-sm text-white-50 border-0 rounded-pill px-3 py-1 text-decoration-none" style="font-size: 0.8rem;">Super Admin</a>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Row of 4 Stat Cards -->
  <div class="row g-3 mb-4">
    <!-- Stat 1: Total proposal (Deep Navy) -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-navy h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small text-white-50">Total proposal</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: #fbbf24; color: #112842;">
            <i class="bi bi-file-earmark-text fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold text-white mb-1" style="font-size: 2.2rem; line-height: 1.1;">
            <?= (int)($stats['totalProposal'] ?? 0) ?>
          </div>
          <small class="text-white-50" style="font-size: 0.82rem;">
            <?= (int)($statsProposalStatus['menunggu'] ?? 0) ?> menunggu plotting
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 2: Kelompok PKM (Deep Navy) -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-navy h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small text-white-50">Kelompok PKM</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255,255,255,0.14); color: #ffffff;">
            <i class="bi bi-people fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold text-white mb-1" style="font-size: 2.2rem; line-height: 1.1;">
            <?= (int)($stats['totalKelompok'] ?? 0) ?>
          </div>
          <small class="text-white-50" style="font-size: 0.82rem;">
            <?= (int)($stats['totalMahasiswa'] ?? 0) ?> mahasiswa
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 3: Dosen peninjau (Pastel Blue) -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-pastel h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small fw-semibold" style="color: #475569;">Dosen peninjau</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; background: #ffffff; color: #112842;">
            <i class="bi bi-person fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold mb-1" style="font-size: 2.2rem; line-height: 1.1; color: #112842;">
            <?= (int)($stats['totalDosen'] ?? 0) ?>
          </div>
          <small class="fw-medium" style="font-size: 0.82rem; color: #475569;">
            <?= (int)($stats['totalPlotted'] ?? 0) ?> proposal sudah di-plot
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 4: Lomba aktif (Pastel Blue) -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-pastel h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small fw-semibold" style="color: #475569;">Lomba aktif</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; background: #ffffff; color: #112842;">
            <i class="bi bi-trophy fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold mb-1" style="font-size: 2.2rem; line-height: 1.1; color: #112842;">
            <?= (int)($stats['totalLomba'] ?? 0) ?>
          </div>
          <small class="fw-medium" style="font-size: 0.82rem; color: #475569;">
            Terbit di papan publik
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Middle Row: Status Proposal Donut Chart & Usulan per Bidang -->
  <div class="row g-3 mb-4">
    <!-- Middle Left: Status Proposal (Donut Chart) -->
    <div class="col-12 col-lg-5">
      <div class="sipkm-card p-4 h-100">
        <h6 class="fw-bold mb-3" style="color: #112842;">Status proposal</h6>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <?php
            $totProp = max(1, (int)($stats['totalProposal'] ?? 0));
            $sMenunggu = (int)($statsProposalStatus['menunggu'] ?? 0);
            $sDitinjau = (int)($statsProposalStatus['ditinjau'] ?? 0);
            $sLayak    = (int)($statsProposalStatus['layak'] ?? 0);
            $sRevisi   = (int)($statsProposalStatus['revisi'] ?? 0);

            // Perhitungan SVG Donut Chart (Radius = 40, Circumference = 2 * PI * 40 ~= 251.327)
            $C = 251.327;
            $segments = [
              ['key' => 'menunggu', 'val' => $sMenunggu, 'color' => '#fbbf24', 'label' => 'Menunggu plotting'],
              ['key' => 'ditinjau', 'val' => $sDitinjau, 'color' => '#3b82f6', 'label' => 'Sedang ditinjau'],
              ['key' => 'layak',    'val' => $sLayak,    'color' => '#112842', 'label' => 'Layak'],
              ['key' => 'revisi',   'val' => $sRevisi,   'color' => '#334155', 'label' => 'Perlu revisi'],
            ];

            $offset = 0;
            $svgSegments = [];
            foreach ($segments as $seg) {
              $segLen = ($seg['val'] / $totProp) * $C;
              $dashArray = sprintf('%.2f %.2f', $segLen, $C - $segLen);
              $dashOffset = sprintf('%.2f', -$offset);
              $svgSegments[] = [
                'color'      => $seg['color'],
                'dashArray'  => $dashArray,
                'dashOffset' => $dashOffset,
              ];
              $offset += $segLen;
            }
          ?>

          <!-- Donut SVG -->
          <div class="position-relative d-flex align-items-center justify-content-center" style="width: 140px; height: 140px;">
            <svg width="140" height="140" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
              <circle cx="50" cy="50" r="40" fill="transparent" stroke="#f1f5f9" stroke-width="14" />
              <?php foreach ($svgSegments as $ss): ?>
                <circle cx="50" cy="50" r="40" fill="transparent" stroke="<?= $ss['color'] ?>" stroke-width="14" stroke-dasharray="<?= $ss['dashArray'] ?>" stroke-dashoffset="<?= $ss['dashOffset'] ?>" />
              <?php endforeach; ?>
            </svg>
            <div class="position-absolute text-center" style="line-height: 1.1;">
              <div class="fw-bold" style="font-size: 1.55rem; color: #112842;"><?= (int)($stats['totalProposal'] ?? 0) ?></div>
              <small class="text-muted" style="font-size: 0.72rem;">proposal</small>
            </div>
          </div>

          <!-- Donut Legend List -->
          <div class="flex-grow-1 ps-2" style="font-size: 0.85rem;">
            <?php foreach ($segments as $seg): ?>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                  <span class="donut-legend-dot" style="background-color: <?= $seg['color'] ?>;"></span>
                  <span class="text-secondary"><?= esc($seg['label']) ?></span>
                </div>
                <span class="fw-bold" style="color: #112842;"><?= $seg['val'] ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Middle Right: Usulan per Bidang (Gradient Bars) -->
    <div class="col-12 col-lg-7">
      <div class="sipkm-card p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold mb-0" style="color: #112842;">Usulan per bidang</h6>
          <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold" style="font-size: 0.82rem; color: #112842;" data-bs-toggle="modal" data-bs-target="#modal10BidangPKM">
            Semua 10 Bidang &rarr;
          </button>
        </div>

        <?php
          // Urutkan skema berdasarkan total terbanyak
          $sebaranSorted = $sebaran10Bidang ?? [];
          usort($sebaranSorted, function ($a, $b) {
            return $b['total'] <=> $a['total'];
          });
          $topSkema = array_slice($sebaranSorted, 0, 5);
          $maxCount = 1;
          foreach ($topSkema as $ts) {
            if ($ts['total'] > $maxCount) {
              $maxCount = $ts['total'];
            }
          }
        ?>

        <div class="d-flex flex-column gap-3">
          <?php foreach ($topSkema as $sk): ?>
            <?php $barPct = max(8, round(($sk['total'] / $maxCount) * 100)); ?>
            <div>
              <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.84rem;">
                <span class="fw-semibold text-dark"><?= esc($sk['kode']) ?></span>
                <span class="fw-bold" style="color: #112842;"><?= $sk['total'] ?></span>
              </div>
              <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 50px;">
                <div class="progress-bar progress-bar-gradient" style="width: <?= $barPct ?>%;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Bottom 2x2 Grid Cards -->
  <div class="row g-3 mb-4">
    <!-- Bottom 1: Proposal PKM -->
    <div class="col-12 col-lg-6">
      <div class="sipkm-card p-4 h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h6 class="fw-bold mb-0" style="color: #112842;">Proposal PKM</h6>
              <small class="text-muted" style="font-size: 0.8rem;">
                <?= (int)($statsProposalStatus['menunggu'] ?? 0) ?> perlu plotting dosen
              </small>
            </div>
            <a href="<?= base_url('admin/proposal') ?>" class="small text-decoration-none fw-semibold" style="color: #112842; font-size: 0.82rem;">
              Kelola proposal &rarr;
            </a>
          </div>

          <!-- Proposal List -->
          <?php $proposals4 = array_slice($daftarProposalPlotting ?? [], 0, 4); ?>
          <?php if (!empty($proposals4)): ?>
            <?php foreach ($proposals4 as $p): ?>
              <?php $needsPlot = ($p['statusProposal'] === 'Diunggah' || empty($p['idDosen'])); ?>
              <div class="dash-list-item">
                <div class="overflow-hidden pe-2">
                  <div class="fw-semibold text-dark text-truncate" style="font-size: 0.88rem;">
                    <?= esc($p['namaKelompok'] ?: $p['judulProposal']) ?>
                  </div>
                  <small class="text-muted d-block" style="font-size: 0.78rem;">
                    <?= esc($p['jenisPKM']) ?> &bull; diunggah <?= esc(date('d M', strtotime($p['tglUnggah'] ?? 'now'))) ?>
                  </small>
                </div>
                <div class="flex-shrink-0">
                  <?php if ($needsPlot): ?>
                    <button type="button" class="pill-btn-plotting" data-bs-toggle="modal" data-bs-target="#modalPlottingDash_<?= $p['idProposal'] ?>">
                      Plotting
                    </button>
                  <?php else: ?>
                    <span class="pill-badge-blue">
                      <?= esc($p['statusProposal'] === 'Sedang Ditinjau' ? 'Ditinjau' : $p['statusProposal']) ?>
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="small text-muted py-3 mb-0 text-center">Belum ada usulan proposal yang masuk.</p>
          <?php endif; ?>
        </div>

        <div class="mt-3 pt-2">
          <a href="<?= base_url('admin/proposal') ?>" class="small text-muted text-decoration-none">
            +<?= max(0, count($daftarProposalPlotting ?? []) - 4) ?> lainnya
          </a>
        </div>
      </div>
    </div>

    <!-- Bottom 2: Dosen Peninjau -->
    <div class="col-12 col-lg-6">
      <div class="sipkm-card p-4 h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h6 class="fw-bold mb-0" style="color: #112842;">Dosen Peninjau</h6>
              <small class="text-muted" style="font-size: 0.8rem;">Beban tinjauan per dosen</small>
            </div>
            <a href="<?= base_url('admin/dosen') ?>" class="small text-decoration-none fw-semibold" style="color: #112842; font-size: 0.82rem;">
              Kelola dosen &rarr;
            </a>
          </div>

          <!-- Dosen List -->
          <?php $dosen4 = array_slice($daftarDosenWithStats ?? [], 0, 4); ?>
          <?php if (!empty($dosen4)): ?>
            <?php foreach ($dosen4 as $dos): ?>
              <?php $totalTugas = (int)(($dos['tugasPending'] ?? 0) + ($dos['tugasSelesai'] ?? 0)); ?>
              <div class="dash-list-item">
                <div class="overflow-hidden pe-2">
                  <div class="fw-semibold text-dark text-truncate" style="font-size: 0.88rem;">
                    <?= esc($dos['namaDosen']) ?>
                  </div>
                  <small class="text-muted d-block" style="font-size: 0.78rem;">
                    <?= (int)($dos['tugasPending'] ?? 0) ?> sedang ditinjau
                  </small>
                </div>
                <div class="flex-shrink-0">
                  <span class="pill-badge-blue">
                    <?= $totalTugas ?> proposal
                  </span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="small text-muted py-3 mb-0 text-center">Belum ada data dosen peninjau.</p>
          <?php endif; ?>
        </div>

        <div class="mt-3 pt-2">
          <a href="<?= base_url('admin/dosen') ?>" class="small text-muted text-decoration-none">
            +<?= max(0, count($daftarDosenWithStats ?? []) - 4) ?> lainnya
          </a>
        </div>
      </div>
    </div>

    <!-- Bottom 3: Kelompok PKM -->
    <div class="col-12 col-lg-6">
      <div class="sipkm-card p-4 h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h6 class="fw-bold mb-0" style="color: #112842;">Kelompok PKM</h6>
              <small class="text-muted" style="font-size: 0.8rem;">
                <?= (int)($stats['totalKelompok'] ?? 0) ?> tim, <?= (int)($stats['totalMahasiswa'] ?? 0) ?> mahasiswa
              </small>
            </div>
            <a href="<?= base_url('admin/kelompok') ?>" class="small text-decoration-none fw-semibold" style="color: #112842; font-size: 0.82rem;">
              Kelola kelompok &rarr;
            </a>
          </div>

          <!-- Kelompok List -->
          <?php $kelompok4 = array_slice($daftarKelompokRingkas ?? [], 0, 4); ?>
          <?php if (!empty($kelompok4)): ?>
            <?php foreach ($kelompok4 as $k): ?>
              <div class="dash-list-item">
                <div class="overflow-hidden pe-2">
                  <div class="fw-semibold text-dark text-truncate" style="font-size: 0.88rem;">
                    <?= esc($k['namaKelompok']) ?>
                  </div>
                  <small class="text-muted d-block" style="font-size: 0.78rem;">
                    <?= (int)($k['jumlahAnggota'] ?? 4) ?> anggota
                  </small>
                </div>
                <div class="flex-shrink-0">
                  <span class="pill-badge-blue">
                    <?= esc(!empty($k['jenisPKM']) ? $k['jenisPKM'] : 'PKM') ?>
                  </span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="small text-muted py-3 mb-0 text-center">Belum ada kelompok PKM terdaftar.</p>
          <?php endif; ?>
        </div>

        <div class="mt-3 pt-2">
          <a href="<?= base_url('admin/kelompok') ?>" class="small text-muted text-decoration-none">
            +<?= max(0, count($daftarKelompokRingkas ?? []) - 4) ?> lainnya
          </a>
        </div>
      </div>
    </div>

    <!-- Bottom 4: Informasi Lomba -->
    <div class="col-12 col-lg-6">
      <div class="sipkm-card p-4 h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h6 class="fw-bold mb-0" style="color: #112842;">Informasi Lomba</h6>
              <small class="text-muted" style="font-size: 0.8rem;">
                <?= (int)($stats['totalLomba'] ?? 0) ?> lomba aktif
              </small>
            </div>
            <a href="<?= base_url('admin/lomba') ?>" class="small text-decoration-none fw-semibold" style="color: #112842; font-size: 0.82rem;">
              Kelola lomba &rarr;
            </a>
          </div>

          <!-- Lomba List -->
          <?php $lomba4 = array_slice($daftarLombaTerbaru ?? [], 0, 4); ?>
          <?php if (!empty($lomba4)): ?>
            <?php foreach ($lomba4 as $l): ?>
              <div class="dash-list-item">
                <div class="overflow-hidden pe-2">
                  <div class="fw-semibold text-dark text-truncate" style="font-size: 0.88rem;">
                    <?= esc($l['judulLomba']) ?>
                  </div>
                  <small class="text-muted d-block" style="font-size: 0.78rem;">
                    Batas daftar <?= esc($l['timelineSelesai'] ?? '-') ?>
                  </small>
                </div>
                <div class="flex-shrink-0">
                  <span class="pill-badge-blue">
                    Aktif
                  </span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="small text-muted py-3 mb-0 text-center">Belum ada program lomba terbit.</p>
          <?php endif; ?>
        </div>

        <div class="mt-3 pt-2">
          <a href="<?= base_url('admin/lomba') ?>" class="small text-muted text-decoration-none">
            +<?= max(0, count($daftarLombaTerbaru ?? []) - 4) ?> lainnya
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center my-3">
    <small class="text-muted" style="font-size: 0.78rem;">
      Data terhubung secara real-time dengan basis data SIPKM-DKKA Universitas Budi Luhur.
    </small>
  </div>
</div>

<!-- Modal 10 Bidang PKM Belmawa Lengkap -->
<div class="modal fade" id="modal10BidangPKM" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light">
        <h6 class="modal-title fw-bold" style="color: #112842;">
          <i class="bi bi-pie-chart-fill text-primary me-2"></i>Sebaran Usulan Seluruh 10 Bidang PKM 2026
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <?php foreach ($sebaran10Bidang ?? [] as $sb): ?>
            <div class="col-12 col-md-6">
              <div class="p-3 rounded-3 border" style="background: #f8fafc;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <div>
                    <span class="fw-bold text-dark"><?= esc($sb['kode']) ?></span>
                    <small class="text-muted d-block"><?= esc($sb['nama']) ?></small>
                  </div>
                  <div class="text-end">
                    <span class="fw-bold" style="color: #112842;"><?= $sb['total'] ?> usulan</span>
                    <small class="text-muted d-block">(<?= $sb['persentase'] ?>%)</small>
                  </div>
                </div>
                <div class="progress mt-2" style="height: 6px;">
                  <div class="progress-bar" style="width: <?= $sb['persentase'] ?>%; background-color: <?= esc($sb['color']) ?>;"></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer bg-light py-2">
        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
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
                <i class="bi bi-info-circle me-1"></i> Setelah disimpan, status usulan otomatis menjadi <strong>Sedang Ditinjau</strong> dan dosen peninjau terkait dapat langsung mengevaluasi proposal ini.
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary-sipkm btn-sm rounded-pill px-3" data-loading-text="Menugaskan...">
                <i class="bi bi-check2-circle me-1"></i> Simpan Penugasan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
