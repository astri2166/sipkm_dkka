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

  <!-- 1. Top Banner Dosen Peninjau -->
  <div class="banner-admin-navy mb-4 shadow-sm">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge px-3 py-1 rounded-pill fw-bold" style="background: #fbbf24; color: #112842; font-size: 0.78rem;">
            <i class="bi bi-person-badge-fill me-1"></i> Dosen Peninjau PKM
          </span>
          <span class="badge rounded-pill text-white border border-white-50 opacity-75" style="font-size: 0.78rem;">
            Periode Akademik 2026
          </span>
        </div>
        <h4 class="fw-bold text-white mb-1">Selamat bertugas, <?= esc($dosen['namaDosen'] ?? 'Dr. Ahmad Fauzi, M.T.') ?></h4>
        <small class="text-white-50" style="font-size: 0.85rem;">
          NIP: <strong><?= esc($dosen['nipDosen'] ?? '0315088501') ?></strong> &bull; 
          Email: <strong><?= esc($dosen['email'] ?? 'ahmad.fauzi@budiluhur.ac.id') ?></strong> &bull; 
          Direktorat Kemahasiswaan &amp; Karir Alumni
        </small>
      </div>
      <div>
        <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 fw-semibold" style="border-color: rgba(255,255,255,0.3); font-size: 0.82rem;">
          <i class="bi bi-folder2-open me-1"></i> Folder Master Template DKKA
        </a>
      </div>
    </div>
  </div>

  <!-- 2. Row of 4 Stat Cards -->
  <div class="row g-3 mb-4">
    <!-- Stat 1: Total Proposal Ditugaskan -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-navy h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small text-white-50">Total Penugasan</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: #fbbf24; color: #112842;">
            <i class="bi bi-folder2-open fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold text-white mb-1" style="font-size: 2.2rem; line-height: 1.1;">
            <?= count($daftarTugas) ?>
          </div>
          <small class="text-white-50" style="font-size: 0.82rem;">
            Proposal di-plot oleh DKKA
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 2: Belum Ditinjau -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-navy h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small text-white-50">Belum Ditinjau</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255,255,255,0.14); color: #ffffff;">
            <i class="bi bi-hourglass-split fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold text-white mb-1" style="font-size: 2.2rem; line-height: 1.1;">
            <?= $countBelum ?>
          </div>
          <small class="text-white-50" style="font-size: 0.82rem;">
            Proposal perlu tindakan segera
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 3: Selesai Ditinjau -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-pastel h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small fw-semibold" style="color: #475569;">Selesai Ditinjau</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; background: #ffffff; color: #112842;">
            <i class="bi bi-clipboard-check-fill fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold mb-1" style="font-size: 2.2rem; line-height: 1.1; color: #112842;">
            <?= $countSelesai ?>
          </div>
          <small class="fw-medium" style="font-size: 0.82rem; color: #475569;">
            Rekap penilaian terkirim
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 4: Deadline Peninjauan -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-pastel h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small fw-semibold" style="color: #475569;">Batas Akhir Penilaian</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; background: #ffffff; color: #112842;">
            <i class="bi bi-calendar-check fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold mb-1" style="font-size: 1.6rem; line-height: 1.2; color: #112842;">
            22 Des 2026
          </div>
          <small class="fw-medium" style="font-size: 0.82rem; color: #475569;">
            Periode Evaluasi DKKA
          </small>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Kolom Kiri: Proposal yang Membutuhkan Evaluasi Segera -->
    <div class="col-lg-8">
      <div class="sipkm-card p-4 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <div>
            <h6 class="fw-bold mb-0" style="color: #112842;">
              <i class="bi bi-list-task text-primary me-2"></i>Daftar Proposal Menunggu Peninjauan
            </h6>
            <small class="text-muted">Proposal baru yang telah di-plot oleh Administrator DKKA</small>
          </div>
          <a href="<?= base_url('dosen/peninjauan') ?>" class="small text-decoration-none fw-semibold" style="color: #112842;">
            Lihat Semua Penugasan &rarr;
          </a>
        </div>

        <?php
          $pendingTasks = array_filter($daftarTugas, fn($t) => $t['statusPenugasan'] === 'Belum Ditinjau');
        ?>

        <?php if (!empty($pendingTasks)): ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="small">
                <tr>
                  <th style="width: 40px;">No</th>
                  <th>Skema & Judul Usulan</th>
                  <th>Kelompok</th>
                  <th>Tgl Ditugaskan</th>
                  <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
              </thead>
              <tbody class="small">
                <?php $no = 1; foreach ($pendingTasks as $item): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <span class="pill-badge-blue mb-1"><?= esc($item['jenisPKM']) ?></span>
                      <div class="fw-bold text-dark lh-sm mt-1"><?= esc($item['judulProposal']) ?></div>
                    </td>
                    <td>
                      <div class="fw-semibold text-dark"><?= esc($item['namaKelompok']) ?></div>
                      <small class="text-muted">Ketua: <?= esc($item['nimKetua']) ?></small>
                    </td>
                    <td>
                      <span class="text-muted"><?= esc($item['tglPenugasan']) ?></span>
                    </td>
                    <td class="text-center">
                      <a href="<?= base_url('dosen/peninjauan/detail/' . $item['idPeninjauan']) ?>" class="btn btn-primary-sipkm btn-sm rounded-pill px-3 py-1">
                        <i class="bi bi-pencil-square me-1"></i> Mulai Nilai
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-4 text-muted">
            <i class="bi bi-check2-all display-5 text-success mb-2 d-block"></i>
            <span class="fw-semibold text-dark d-block">Semua Penugasan Selesai!</span>
            <small>Tidak ada proposal yang berstatus belum ditinjau saat ini.</small>
          </div>
        <?php endif; ?>
      </div>

      <!-- Riwayat Proposal Selesai -->
      <div class="sipkm-card p-4 shadow-sm">
        <h6 class="fw-bold mb-3 pb-2 border-bottom" style="color: #112842;">
          <i class="bi bi-clock-history text-success me-2"></i>Histori Peninjauan Terakhir (Selesai)
        </h6>
        <div class="list-group list-group-flush small">
          <?php $foundSelesai = false; ?>
          <?php foreach ($daftarTugas as $item): if ($item['statusPenugasan'] === 'Selesai'): $foundSelesai = true; ?>
            <div class="list-group-item px-0 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
              <div class="me-2" style="max-width: 75%;">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge bg-success-subtle text-success border border-success-subtle">Selesai</span>
                  <span class="pill-badge-blue"><?= esc($item['jenisPKM']) ?></span>
                  <small class="text-muted">Selesai: <?= esc($item['tglSelesaiTinjau']) ?></small>
                </div>
                <div class="fw-semibold text-dark"><?= esc($item['judulProposal']) ?></div>
                <small class="text-muted"><?= esc($item['namaKelompok']) ?> &bull; Tautan Rekap: <a href="<?= esc($item['tautanRekapDrive']) ?>" target="_blank" class="text-decoration-none">Google Drive</a></small>
              </div>
              <div>
                <a href="<?= base_url('dosen/peninjauan/detail/' . $item['idPeninjauan']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                  <i class="bi bi-eye me-1"></i> Rincian Nilai
                </a>
              </div>
            </div>
          <?php endif; endforeach; ?>
          <?php if (!$foundSelesai): ?>
            <p class="text-muted small py-2 mb-0">Belum ada riwayat peninjauan proposal yang berstatus selesai.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Panduan Rubrik Penilaian DKKA -->
    <div class="col-lg-4">
      <!-- Card Link Master Template DKKA -->
      <div class="sipkm-card p-4 mb-4 shadow-sm border-start border-warning border-4">
        <h6 class="fw-bold text-dark mb-2">
          <i class="bi bi-folder-symlink text-warning me-2"></i>Master Template Penilaian DKKA
        </h6>
        <p class="text-muted small mb-3">
          Sesuai SOP DKKA, setiap Dosen Peninjau wajib mengunduh formulir penilaian numerik resmi dan mengunggah kembali salinan hasil rekap ke Google Drive:
        </p>
        <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-warning text-dark btn-sm w-100 fw-bold mb-2 rounded-pill py-2">
          <i class="bi bi-box-arrow-up-right me-1"></i> Buka Folder Master DKKA
        </a>
        <small class="text-muted d-block" style="font-size: 11px;">
          <i class="bi bi-info-circle me-1"></i> Link template dapat disesuaikan pada file konfigurasi sistem.
        </small>
      </div>

      <!-- Panduan 2 Card Penilaian -->
      <div class="sipkm-card p-4 shadow-sm">
        <h6 class="fw-bold text-dark mb-3">
          <i class="bi bi-ui-checks text-primary me-2"></i>Alur Penilaian 2 Tahap
        </h6>
        <div class="timeline-steps small">
          <div class="mb-3">
            <div class="fw-bold text-primary mb-1">Tahap 1: Evaluasi Card 1 & Card 2</div>
            <p class="text-muted mb-0">
              Buka berkas Google Docs proposal, lalu isi <strong>Card 1 (Administrasi)</strong> & <strong>Card 2 (Substansi)</strong>. Mahasiswa dapat melihat catatan ini secara <em>live</em>.
            </p>
          </div>
          <div class="mb-0">
            <div class="fw-bold text-success mb-1">Tahap 2: Input Link Rekap Drive</div>
            <p class="text-muted mb-0">
              Buka Master Template DKKA, input nilai dan upload lembar koreksi ke Google Drive, lalu salin tautan folder tersebut ke sistem untuk menyelesaikan penugasan.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
