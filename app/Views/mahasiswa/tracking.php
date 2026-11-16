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

  <!-- Header & Switcher Simulasi Status Proposal -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Tracking Status & Catatan Peninjauan Proposal</h4>
      <p class="text-muted small mb-0">Pantau proses evaluasi administrasi dan substansi dari Dosen Peninjau DKKA secara transparan.</p>
    </div>
    <!-- Status Switcher untuk Pengetesan Pratinjau Frontend -->
    <div class="btn-group btn-group-sm shadow-sm">
      <span class="btn btn-light disabled text-dark fw-bold border">Uji Status:</span>
      <a href="<?= base_url('mahasiswa/tracking?status=Diunggah') ?>" class="btn <?= $statusProposal === 'Diunggah' ? 'btn-primary' : 'btn-outline-secondary' ?>">
        Diunggah
      </a>
      <a href="<?= base_url('mahasiswa/tracking?status=Sedang+Ditinjau') ?>" class="btn <?= $statusProposal === 'Sedang Ditinjau' ? 'btn-primary' : 'btn-outline-secondary' ?>">
        Sedang Ditinjau
      </a>
      <a href="<?= base_url('mahasiswa/tracking?status=Sudah+Ditinjau') ?>" class="btn <?= $statusProposal === 'Sudah Ditinjau' ? 'btn-primary' : 'btn-outline-secondary' ?>">
        Sudah Ditinjau
      </a>
      <a href="<?= base_url('mahasiswa/tracking?status=Layak') ?>" class="btn <?= $statusProposal === 'Layak' ? 'btn-success text-white' : 'btn-outline-success' ?>">
        Layak (Didanai)
      </a>
      <a href="<?= base_url('mahasiswa/tracking?status=Tidak+Layak') ?>" class="btn <?= $statusProposal === 'Tidak Layak' ? 'btn-danger text-white' : 'btn-outline-danger' ?>">
        Tidak Layak
      </a>
    </div>
  </div>

  <!-- Card Utama: Stepper Horizontal Timeline -->
  <div class="sipkm-card p-4 shadow-sm mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 border-bottom gap-2">
      <div>
        <span class="badge bg-primary px-3 py-1 rounded-pill me-2"><?= esc($proposal['jenisPKM']) ?></span>
        <span class="fw-bold text-dark fs-6"><?= esc($kelompok['namaKelompok']) ?></span>
      </div>
      <div>
        <?php
          $badgeMap = [
            'Diunggah'        => 'bg-info-subtle text-info-emphasis border-info-subtle',
            'Sedang Ditinjau' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
            'Sudah Ditinjau'  => 'bg-primary-subtle text-primary border-primary-subtle',
            'Layak'           => 'bg-success-subtle text-success border-success-subtle',
            'Tidak Layak'     => 'bg-danger-subtle text-danger border-danger-subtle',
          ];
        ?>
        <span class="badge <?= $badgeMap[$statusProposal] ?? 'bg-secondary' ?> border fs-6 px-3 py-2 rounded-pill">
          Status Saat Ini: <strong><?= esc($statusProposal) ?></strong>
        </span>
      </div>
    </div>

    <!-- Stepper 4 Tahap Evaluasi -->
    <?php
      $stepIndex = 1;
      if ($statusProposal === 'Sedang Ditinjau') $stepIndex = 2;
      elseif ($statusProposal === 'Sudah Ditinjau') $stepIndex = 3;
      elseif ($statusProposal === 'Layak' || $statusProposal === 'Tidak Layak') $stepIndex = 4;
    ?>
    <div class="sipkm-stepper px-2">
      <!-- Step 1: Diunggah -->
      <div class="step-item <?= $stepIndex >= 1 ? 'completed' : 'pending' ?>">
        <div class="step-circle">
          <?php if ($stepIndex > 1): ?><i class="bi bi-check-lg"></i><?php else: ?>1<?php endif; ?>
        </div>
        <div class="step-title">1. Diunggah</div>
        <div class="step-subtitle"><?= esc($proposal['tglUnggah']) ?></div>
      </div>

      <!-- Step 2: Sedang Ditinjau -->
      <div class="step-item <?= $stepIndex > 2 ? 'completed' : ($stepIndex === 2 ? 'active' : 'pending') ?>">
        <div class="step-circle">
          <?php if ($stepIndex > 2): ?><i class="bi bi-check-lg"></i><?php else: ?>2<?php endif; ?>
        </div>
        <div class="step-title">2. Sedang Ditinjau</div>
        <div class="step-subtitle"><?= $stepIndex >= 2 ? esc($peninjauan['tglPenugasan']) : 'Menunggu Dosen' ?></div>
      </div>

      <!-- Step 3: Sudah Ditinjau -->
      <div class="step-item <?= $stepIndex > 3 ? 'completed' : ($stepIndex === 3 ? 'active' : 'pending') ?>">
        <div class="step-circle">
          <?php if ($stepIndex > 3): ?><i class="bi bi-check-lg"></i><?php else: ?>3<?php endif; ?>
        </div>
        <div class="step-title">3. Sudah Ditinjau</div>
        <div class="step-subtitle"><?= $stepIndex >= 3 ? esc($peninjauan['tglSelesaiTinjau'] ?? '14 Nov 2026') : 'Evaluasi Berkas' ?></div>
      </div>

      <!-- Step 4: Penetapan Kelayakan DKKA -->
      <div class="step-item <?= $statusProposal === 'Layak' ? 'completed' : ($statusProposal === 'Tidak Layak' ? 'rejected' : ($stepIndex === 4 ? 'active' : 'pending')) ?>">
        <div class="step-circle">
          <?php if ($statusProposal === 'Layak'): ?>
            <i class="bi bi-trophy-fill text-white"></i>
          <?php elseif ($statusProposal === 'Tidak Layak'): ?>
            <i class="bi bi-x-lg text-white"></i>
          <?php else: ?>
            4
          <?php endif; ?>
        </div>
        <div class="step-title">4. Penetapan DKKA</div>
        <div class="step-subtitle">
          <?php if ($statusProposal === 'Layak'): ?>
            <span class="text-success fw-bold">Layak Didanai</span>
          <?php elseif ($statusProposal === 'Tidak Layak'): ?>
            <span class="text-danger fw-bold">Tidak Lolos</span>
          <?php else: ?>
            Sidang Pleno
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Alert Ringkasan Proposal & Dosen Peninjau -->
    <div class="mt-4 p-3 bg-light rounded-3 border">
      <div class="row align-items-center">
        <div class="col-md-8">
          <small class="text-muted d-block">Judul Proposal:</small>
          <h6 class="fw-bold text-dark mb-1"><?= esc($proposal['judulProposal']) ?></h6>
          <div class="small text-muted">
            Dosen Peninjau: <strong><?= esc($dosen['namaDosen']) ?></strong> (NIP: <?= esc($dosen['nipDosen']) ?>)
          </div>
        </div>
        <div class="col-md-4 text-md-end mt-2 mt-md-0">
          <a href="<?= esc($proposal['tautanGoogleDocs']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-google me-1"></i> Buka Berkas Google Docs
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- 2 CARD CATATAN PENINJAUAN (WAJIB SESUAI SPESIFIKASI PROMPT) -->
  <div class="row g-4 mb-4">
    <!-- CARD 1: Catatan Administrasi -->
    <div class="col-lg-6">
      <div class="sipkm-card card-catatan-admin p-4 h-100 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <div class="d-flex align-items-center gap-2">
            <div class="bg-primary text-white p-2 rounded-3">
              <i class="bi bi-file-earmark-check-fill fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Card 1: Catatan Administrasi</h5>
              <small class="text-muted">Format, Margin, Lembar Pengesahan, & Sistematika</small>
            </div>
          </div>
          <?php if (!empty($peninjauan['catatanAdministrasi'])): ?>
            <button class="btn btn-outline-primary btn-sm" onclick="copySipkmText(this, document.getElementById('textCatatanAdmin').innerText)">
              <i class="bi bi-clipboard me-1"></i> Salin Catatan
            </button>
          <?php endif; ?>
        </div>

        <?php if (!empty($peninjauan['catatanAdministrasi']) && in_array($statusProposal, ['Sudah Ditinjau', 'Layak', 'Tidak Layak'])): ?>
          <div class="bg-white p-3 rounded-3 border mb-3">
            <pre class="mb-0 text-dark" id="textCatatanAdmin" style="white-space: pre-wrap; font-family: inherit; font-size: 0.92rem; line-height: 1.6;"><?= esc($peninjauan['catatanAdministrasi']) ?></pre>
          </div>
          <div class="d-flex align-items-center gap-2 small text-muted">
            <i class="bi bi-person-check-fill text-primary"></i>
            <span>Ditinjau oleh: <strong><?= esc($dosen['namaDosen']) ?></strong></span>
          </div>
        <?php else: ?>
          <div class="text-center py-4 text-muted">
            <i class="bi bi-hourglass-split display-6 text-secondary mb-2 d-block"></i>
            <span class="small">Catatan administrasi belum diisi atau masih dalam proses peninjauan oleh Dosen.</span>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- CARD 2: Catatan Substansi -->
    <div class="col-lg-6">
      <div class="sipkm-card card-catatan-substansi p-4 h-100 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <div class="d-flex align-items-center gap-2">
            <div class="bg-warning text-dark p-2 rounded-3">
              <i class="bi bi-journal-text fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Card 2: Catatan Substansi</h5>
              <small class="text-muted">Metodologi, Latar Belakang Masalah, & Plafon Anggaran</small>
            </div>
          </div>
          <?php if (!empty($peninjauan['catatanSubstansi'])): ?>
            <button class="btn btn-outline-warning text-dark btn-sm" onclick="copySipkmText(this, document.getElementById('textCatatanSubstansi').innerText)">
              <i class="bi bi-clipboard me-1"></i> Salin Catatan
            </button>
          <?php endif; ?>
        </div>

        <?php if (!empty($peninjauan['catatanSubstansi']) && in_array($statusProposal, ['Sudah Ditinjau', 'Layak', 'Tidak Layak'])): ?>
          <div class="bg-white p-3 rounded-3 border mb-3">
            <pre class="mb-0 text-dark" id="textCatatanSubstansi" style="white-space: pre-wrap; font-family: inherit; font-size: 0.92rem; line-height: 1.6;"><?= esc($peninjauan['catatanSubstansi']) ?></pre>
          </div>
          <div class="d-flex align-items-center gap-2 small text-muted">
            <i class="bi bi-person-check-fill text-warning"></i>
            <span>Ditinjau oleh: <strong><?= esc($dosen['namaDosen']) ?></strong></span>
          </div>
        <?php else: ?>
          <div class="text-center py-4 text-muted">
            <i class="bi bi-hourglass-split display-6 text-secondary mb-2 d-block"></i>
            <span class="small">Catatan substansi belum diisi atau masih dalam proses peninjauan oleh Dosen.</span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Petunjuk Tindak Lanjut Revisi -->
  <div class="sipkm-card p-4 shadow-sm border-start border-success border-4">
    <h5 class="fw-bold text-dark mb-2">
      <i class="bi bi-arrow-repeat text-success me-2"></i>Panduan Revisi Mandiri Mahasiswa
    </h5>
    <p class="text-muted small mb-3">
      Karena proposal disimpan dalam tautan Google Docs/Drive, tim kamu cukup melakukan perbaikan langsung pada dokumen tersebut:
    </p>
    <div class="row g-3 small">
      <div class="col-md-4">
        <div class="p-3 bg-light rounded-3 h-100">
          <div class="fw-bold text-primary mb-1">Langkah 1: Salin Catatan</div>
          <p class="text-muted mb-0">Gunakan tombol <strong>Salin Catatan</strong> pada Card 1 dan Card 2 di atas untuk mencatat daftar koreksi Dosen.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-light rounded-3 h-100">
          <div class="fw-bold text-primary mb-1">Langkah 2: Perbaiki di Google Docs</div>
          <p class="text-muted mb-0">Buka berkas proposal melalui tautan Google Docs dan sesuaikan format margin, sitasi, serta metodologi.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-light rounded-3 h-100">
          <div class="fw-bold text-primary mb-1">Langkah 3: Sinkronisasi Otomatis</div>
          <p class="text-muted mb-0">Perubahan tersimpan otomatis di awan sehingga Dosen & Admin DKKA dapat langsung melihat versi termutakhir.</p>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
