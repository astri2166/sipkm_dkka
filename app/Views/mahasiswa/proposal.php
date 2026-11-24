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

  <!-- Header Halaman -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Pengajuan & Pengunggahan Proposal PKM</h4>
      <p class="text-muted small mb-0">Input rincian skema, judul, dan tautan berkas proposal Google Drive/Docs resmi tim kamu.</p>
    </div>
  </div>

  <?php if ($role !== 'Ketua Kelompok'): ?>
    <!-- ============================================================== -->
    <!-- PERINGATAN: ANGGOTA TIDAK BERHAK INPUT PROPOSAL                -->
    <!-- ============================================================== -->
    <div class="sipkm-card p-5 text-center shadow-sm border-top border-warning border-4 mb-4">
      <div class="bg-warning-subtle text-warning d-inline-flex p-3 rounded-circle mb-3">
        <i class="bi bi-shield-lock-fill display-5"></i>
      </div>
      <h4 class="fw-bold text-dark mb-2">Akses Terbatas: Khusus Ketua Kelompok</h4>
      <p class="text-muted mx-auto" style="max-width: 600px;">
        Sesuai dengan pedoman operasional SIPKM-DKKA, formulir pengajuan dan pembaruan berkas proposal hanya dapat dilakukan oleh <strong>Ketua Kelompok</strong>.
      </p>
      <div class="alert alert-info d-inline-block text-start small mb-4">
        <i class="bi bi-info-circle me-1"></i> Anda saat ini masuk dengan peran <strong>Anggota Kelompok</strong>. Anda tetap dapat memantau status kemajuan dan catatan peninjau melalui menu <strong>Tracking Status</strong>.
      </div>
      <div>
        <a href="<?= base_url('mahasiswa/tracking') ?>" class="btn btn-primary-sipkm btn-sm me-2">
          <i class="bi bi-activity me-1"></i> Buka Tracking Status Proposal
        </a>
        <a href="<?= base_url('mahasiswa/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
      </div>
    </div>

  <?php else: ?>
    <!-- ============================================================== -->
    <!-- FORMULIR INPUT PROPOSAL (KHUSUS KETUA KELOMPOK)                -->
    <!-- ============================================================== -->
    <div class="row g-4">
      <!-- Kolom Formulir Utama -->
      <div class="col-lg-8">
        <div class="sipkm-card p-4 shadow-sm border-top border-primary border-4">
          <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h5 class="fw-bold text-dark mb-0">
              <i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>Formulir Tautan Proposal PKM
            </h5>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
              Periode <?= esc($kelompok['tahunPeriode'] ?? '2026') ?>
            </span>
          </div>

          <form action="<?= base_url('mahasiswa/proposal/simpan') ?>" method="post" class="sipkm-form">
            <?= csrf_field() ?>

            <!-- Informasi Tim (Readonly) -->
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Nama Kelompok PKM:</label>
                <input type="text" class="form-control bg-light" value="<?= esc($kelompok['namaKelompok'] ?? 'Belum ada kelompok') ?>" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Ketua Tim (NIM):</label>
                <input type="text" class="form-control bg-light font-monospace" value="<?= esc($kelompok['nimKetua'] ?? $mahasiswa['nim']) ?> - <?= esc($mahasiswa['namaMahasiswa']) ?>" readonly>
              </div>
            </div>

            <!-- 1. Pilihan Skema / Jenis PKM -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">
                Bidang / Skema PKM <span class="text-danger">*</span>
              </label>
              <select class="form-select" required name="jenisPKM" id="jenisPKM">
                <option value="">-- Pilih 1 dari 10 Skema PKM Belmawa --</option>
                <option value="PKM-RE" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-RE' ? 'selected' : '' ?>>PKM-RE (Riset Eksakta)</option>
                <option value="PKM-RSH" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-RSH' ? 'selected' : '' ?>>PKM-RSH (Riset Sosial Humaniora)</option>
                <option value="PKM-PM" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-PM' ? 'selected' : '' ?>>PKM-PM (Pengabdian kepada Masyarakat)</option>
                <option value="PKM-PI" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-PI' ? 'selected' : '' ?>>PKM-PI (Penerapan IPTEK)</option>
                <option value="PKM-K" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-K' ? 'selected' : '' ?>>PKM-K (Kewirausahaan)</option>
                <option value="PKM-KC" <?= ($proposal['jenisPKM'] ?? 'PKM-KC') === 'PKM-KC' ? 'selected' : '' ?>>PKM-KC (Karsa Cipta)</option>
                <option value="PKM-KI" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-KI' ? 'selected' : '' ?>>PKM-KI (Karya Inovatif)</option>
                <option value="PKM-VGK" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-VGK' ? 'selected' : '' ?>>PKM-VGK (Video Gagasan Konstruktif)</option>
                <option value="PKM-AI" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-AI' ? 'selected' : '' ?>>PKM-AI (Artikel Ilmiah)</option>
                <option value="PKM-GFT" <?= ($proposal['jenisPKM'] ?? '') === 'PKM-GFT' ? 'selected' : '' ?>>PKM-GFT (Gagasan Futuristik Tertulis)</option>
              </select>
            </div>

            <!-- 2. Judul Proposal -->
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label fw-semibold text-dark mb-0">
                  Judul Proposal PKM <span class="text-danger">*</span>
                </label>
                <small class="text-muted" id="charCount">0 / 250 karakter</small>
              </div>
              <textarea name="judulProposal" class="form-control" rows="3" required placeholder="Contoh: Sistem Deteksi Dini Kualitas Udara Kampus Berbasis IoT dan Edge AI" id="judulProposal" maxlength="250" oninput="document.getElementById('charCount').textContent = `${this.value.length} / 250 karakter`;"><?= esc($proposal['judulProposal'] ?? '') ?></textarea>
              <div class="form-text">Judul harus padat, jelas, menarik, dan tidak menggunakan singkatan yang tidak baku.</div>
            </div>

            <!-- 3. Tautan Google Docs / Google Drive (Dengan Validasi Real-time) -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">
                Tautan Berkas Proposal (Google Docs / Google Drive) <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light text-danger">
                  <i class="bi bi-google"></i>
                </span>
                <input type="url" name="tautanGoogleDocs" class="form-control" placeholder="https://docs.google.com/document/d/... atau https://drive.google.com/..." required data-validate-drive="true" id="tautanGoogleDocs" value="<?= esc($proposal['tautanGoogleDocs'] ?? '') ?>">
              </div>
              <div class="form-text mt-1 text-muted" id="driveHelp">
                <i class="bi bi-shield-check text-success me-1"></i>
                Wajib diawali dengan <code>https://docs.google.com/</code> atau <code>https://drive.google.com/</code>.
              </div>
            </div>

            <!-- Petunjuk Pengaturan Akses Dokumen -->
            <div class="alert alert-warning small mb-4">
              <div class="d-flex gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-warning flex-shrink-0"></i>
                <div>
                  <strong>Penting: Pengaturan Akses Google Docs</strong>
                  <p class="mb-0">
                    Pastikan tautan Google Docs telah diatur ke mode <strong>"Anyone with the link can view / Siapa saja yang memiliki link dapat melihat"</strong> agar Dosen Peninjau dapat memeriksa berkas proposalmu.
                  </p>
                </div>
              </div>
            </div>

            <!-- Pakta Integritas / Pernyataan Orisinalitas -->
            <div class="form-check mb-4 p-3 bg-light rounded-3 border">
              <input class="form-check-input ms-0 me-2" type="checkbox" id="checkPakta" required>
              <label class="form-check-label small fw-medium" for="checkPakta">
                Saya menyatakan dengan sungguh-sungguh bahwa proposal ini adalah karya orisinal tim kami, belum pernah didanai oleh sumber lain, dan tidak mengandung unsur plagiarisme.
              </label>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
              <a href="<?= base_url('mahasiswa/dashboard') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary-sipkm px-4" data-loading-text="Mengunggah Berkas Proposal...">
                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan & Ajukan Proposal
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Kolom Info & Riwayat Usulan -->
      <div class="col-lg-4">
        <!-- Card Status Proposal Tersimpan -->
        <div class="sipkm-card p-4 shadow-sm mb-4">
          <h6 class="fw-bold text-dark mb-3">
            <i class="bi bi-info-circle-fill text-primary me-2"></i>Status Berkas Saat Ini
          </h6>

          <?php if ($hasProposal): ?>
            <div class="p-3 bg-light rounded-3 border mb-3">
              <div class="d-flex justify-content-between mb-2">
                <small class="text-muted">Skema:</small>
                <span class="badge bg-primary"><?= esc($proposal['jenisPKM']) ?></span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <small class="text-muted">Status:</small>
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                  <?= esc($proposal['statusProposal']) ?>
                </span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <small class="text-muted">Tgl Unggah:</small>
                <span class="small fw-semibold"><?= esc($proposal['tglUnggah']) ?></span>
              </div>
              <div class="mt-2 pt-2 border-top text-truncate">
                <small class="text-muted d-block mb-1">Tautan Aktif:</small>
                <a href="<?= esc($proposal['tautanGoogleDocs']) ?>" target="_blank" class="small text-decoration-none">
                  <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Tab Baru
                </a>
              </div>
            </div>
            <a href="<?= base_url('mahasiswa/tracking') ?>" class="btn btn-outline-primary btn-sm w-100">
              <i class="bi bi-activity me-1"></i> Cek Tracking Peninjauan
            </a>
          <?php else: ?>
            <p class="text-muted small">Belum ada proposal yang diajukan oleh kelompok ini.</p>
          <?php endif; ?>
        </div>

        <!-- Card Format Template Penulisan DKKA -->
        <div class="sipkm-card p-4 shadow-sm">
          <h6 class="fw-bold text-dark mb-2">
            <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Ketentuan Penulisan
          </h6>
          <ul class="list-unstyled small text-muted mb-0">
            <li class="mb-2"><i class="bi bi-check2 text-success me-1"></i> Font Times New Roman 12 pt, spasi 1.15.</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-1"></i> Margin Kiri 4 cm, Kanan/Atas/Bawah 3 cm.</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-1"></i> Maksimal 10 halaman (inti proposal).</li>
            <li><i class="bi bi-check2 text-success me-1"></i> Lembar pengesahan menggunakan format baku UBL.</li>
          </ul>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
