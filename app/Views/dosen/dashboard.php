<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Banner Dosen Peninjau -->
  <div class="sipkm-card p-4 mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0f294a 0%, #1e3a8a 100%); color: #fff;">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-1 rounded-pill">
          <i class="bi bi-person-badge-fill me-1"></i> Dosen Peninjau Proposal PKM
        </span>
        <h3 class="fw-bold mb-1"><?= esc($dosen['namaDosen'] ?? 'Dr. Ahmad Fauzi, M.T.') ?></h3>
        <p class="mb-0 text-white-50">
          NIP: <strong><?= esc($dosen['nipDosen'] ?? '0315088501') ?></strong> &bull; 
          Email: <strong><?= esc($dosen['email'] ?? 'ahmad.fauzi@budiluhur.ac.id') ?></strong> &bull; 
          Periode Akademik: <strong>2026</strong>
        </p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-warning text-dark fw-bold shadow-sm">
          <i class="bi bi-google me-1"></i> Buka Master Template DKKA
        </a>
      </div>
    </div>
  </div>

  <!-- 4 Statistik Kartu Ringkasan Penugasan -->
  <div class="row g-3 mb-4">
    <!-- Stat 1: Total Proposal Ditugaskan -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-primary border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Total Penugasan</small>
            <h4 class="fw-bold text-dark mb-0 mt-1"><?= count($daftarTugas) ?> Proposal</h4>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1">Plotting DKKA</span>
          </div>
          <div class="bg-primary-subtle text-primary p-3 rounded-circle">
            <i class="bi bi-folder2-open fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 2: Belum Ditinjau -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-warning border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Belum Ditinjau</small>
            <h4 class="fw-bold text-warning mb-0 mt-1"><?= $countBelum ?> Proposal</h4>
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mt-1">
              <i class="bi bi-clock-history me-1"></i> Perlu Tindakan
            </span>
          </div>
          <div class="bg-warning-subtle text-warning p-3 rounded-circle">
            <i class="bi bi-hourglass-split fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 3: Selesai Ditinjau -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-success border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Selesai Ditinjau</small>
            <h4 class="fw-bold text-success mb-0 mt-1"><?= $countSelesai ?> Proposal</h4>
            <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">
              <i class="bi bi-check-circle-fill me-1"></i> Rekap Terkirim
            </span>
          </div>
          <div class="bg-success-subtle text-success p-3 rounded-circle">
            <i class="bi bi-clipboard-check-fill fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 4: Deadline Peninjauan -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="sipkm-card sipkm-card-hover p-3 h-100 shadow-sm border-start border-danger border-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Batas Akhir Penilaian</small>
            <h5 class="fw-bold text-danger mb-0 mt-1">22 Des 2026</h5>
            <small class="text-muted">Sisa 35 hari kerja</small>
          </div>
          <div class="bg-danger-subtle text-danger p-3 rounded-circle">
            <i class="bi bi-calendar-x-fill fs-4"></i>
          </div>
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
            <h5 class="fw-bold text-dark mb-0">
              <i class="bi bi-list-task text-primary me-2"></i>Daftar Proposal Menunggu Peninjauan
            </h5>
            <small class="text-muted">Proposal yang baru ditugaskan oleh Admin DKKA</small>
          </div>
          <a href="<?= base_url('dosen/peninjauan') ?>" class="btn btn-outline-primary btn-sm">
            Lihat Semua Penugasan <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>

        <?php
          $pendingTasks = array_filter($daftarTugas, fn($t) => $t['statusPenugasan'] === 'Belum Ditinjau');
        ?>

        <?php if (!empty($pendingTasks)): ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light small">
                <tr>
                  <th>No</th>
                  <th>Skema & Judul Usulan</th>
                  <th>Kelompok</th>
                  <th>Tgl Ditugaskan</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="small">
                <?php $no = 1; foreach ($pendingTasks as $item): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($item['jenisPKM']) ?></span>
                      <div class="fw-bold text-dark"><?= esc($item['judulProposal']) ?></div>
                    </td>
                    <td>
                      <div class="fw-semibold text-dark"><?= esc($item['namaKelompok']) ?></div>
                      <small class="text-muted">Ketua: <?= esc($item['nimKetua']) ?></small>
                    </td>
                    <td>
                      <span class="text-muted"><?= esc($item['tglPenugasan']) ?></span>
                    </td>
                    <td class="text-center">
                      <a href="<?= base_url('dosen/peninjauan/detail/' . $item['idPeninjauan']) ?>" class="btn btn-primary-sipkm btn-sm">
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
        <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
          <i class="bi bi-clock-history text-success me-2"></i>Histori Peninjauan Terakhir (Selesai)
        </h5>
        <div class="list-group list-group-flush small">
          <?php foreach ($daftarTugas as $item): if ($item['statusPenugasan'] === 'Selesai'): ?>
            <div class="list-group-item px-0 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
              <div class="me-2" style="max-width: 75%;">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge bg-success-subtle text-success border border-success-subtle">Selesai</span>
                  <span class="badge bg-secondary-subtle text-secondary"><?= esc($item['jenisPKM']) ?></span>
                  <small class="text-muted">Selesai: <?= esc($item['tglSelesaiTinjau']) ?></small>
                </div>
                <div class="fw-semibold text-dark"><?= esc($item['judulProposal']) ?></div>
                <small class="text-muted"><?= esc($item['namaKelompok']) ?> &bull; Tautan Rekap: <a href="<?= esc($item['tautanRekapDrive']) ?>" target="_blank" class="text-decoration-none">Google Drive</a></small>
              </div>
              <div>
                <a href="<?= base_url('dosen/peninjauan/detail/' . $item['idPeninjauan']) ?>" class="btn btn-outline-secondary btn-sm">
                  <i class="bi bi-eye me-1"></i> Rincian Nilai
                </a>
              </div>
            </div>
          <?php endif; endforeach; ?>
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
        <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-warning text-dark btn-sm w-100 fw-bold mb-2">
          <i class="bi bi-box-arrow-up-right me-1"></i> Buka Folder Master DKKA
        </a>
        <small class="text-muted d-block" style="font-size: 11px;">
          <i class="bi bi-info-circle me-1"></i> Link template dapat disesuaikan pada file konfigurasi <code>.env</code>.
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
