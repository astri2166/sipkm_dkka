<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Navigasi Balik & Status Banner -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div class="d-flex align-items-center gap-2">
      <a href="<?= base_url('dosen/peninjauan') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penugasan
      </a>
      <h5 class="fw-bold text-dark mb-0">Evaluasi Proposal #<?= esc($tugas['idProposal']) ?></h5>
    </div>
    <div class="d-flex align-items-center gap-2">
      <?php if ($tugas['statusPenugasan'] === 'Selesai'): ?>
        <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 px-3 py-2 rounded-pill">
          <i class="bi bi-check-circle-fill me-1"></i> Penugasan Selesai (Rekap Terkirim)
        </span>
      <?php else: ?>
        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle fs-6 px-3 py-2 rounded-pill">
          <i class="bi bi-hourglass-split me-1"></i> Status: Belum Ditinjau
        </span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Card Rincian Proposal Mahasiswa -->
  <div class="sipkm-card p-4 shadow-sm mb-4 border-top border-primary border-4">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="badge bg-primary px-3 py-1 rounded-pill"><?= esc($tugas['jenisPKM']) ?></span>
          <span class="text-muted small">Periode <?= esc($tugas['tahunPeriode'] ?? '2026') ?></span>
          <span class="badge bg-secondary-subtle text-secondary small">Status Usulan: <?= esc($tugas['statusProposal']) ?></span>
        </div>
        <h4 class="fw-bold text-dark mb-2"><?= esc($tugas['judulProposal']) ?></h4>
        <div class="d-flex flex-wrap gap-3 text-muted small">
          <span><i class="bi bi-people-fill me-1 text-primary"></i> Kelompok: <strong><?= esc($tugas['namaKelompok']) ?></strong></span>
          <span><i class="bi bi-person-fill me-1"></i> Ketua Tim: <strong><?= esc($tugas['nimKetua']) ?></strong></span>
          <span><i class="bi bi-calendar3 me-1"></i> Tgl Ditugaskan: <strong><?= esc($tugas['tglPenugasan']) ?></strong></span>
        </div>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="<?= esc($tugas['tautanGoogleDocs']) ?>" target="_blank" class="btn btn-outline-danger btn-sm shadow-sm py-2 px-3 fw-semibold">
          <i class="bi bi-google me-1"></i> Buka Proposal di Google Docs
        </a>
      </div>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- BAGIAN 1: FORM TAHAP 1 PENILAIAN (CARD 1 & CARD 2)             -->
  <!-- ============================================================== -->
  <div class="sipkm-card p-4 shadow-sm mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom gap-2">
      <div>
        <h5 class="fw-bold text-dark mb-0">
          <span class="badge bg-primary me-2">Tahap 1</span>Isi Catatan Evaluasi (Card 1 & Card 2)
        </h5>
        <small class="text-muted">Buka berkas proposal di atas, lalu tuliskan rincian catatan koreksi yang akan terbaca langsung oleh mahasiswa.</small>
      </div>
      <div>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="isiContohCatatan()">
          <i class="bi bi-magic me-1"></i> Sisipkan Contoh Catatan Baku
        </button>
      </div>
    </div>

    <form class="sipkm-form" onsubmit="event.preventDefault(); showSipkmToast('Tahap 1: Catatan Card 1 & Card 2 berhasil disimpan! Status proposal menjadi: Sudah Ditinjau.', 'success'); document.getElementById('badgeTahap1Status').className='badge bg-success text-white'; document.getElementById('badgeTahap1Status').innerText='Tersimpan (Sudah Ditinjau)';">
      <div class="row g-4 mb-4">
        <!-- CARD 1: Catatan Administrasi -->
        <div class="col-lg-6">
          <div class="card-catatan-admin p-3 rounded-3 border h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-check-fill text-primary fs-5"></i>
                Card 1: Catatan Administrasi
              </label>
              <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" onclick="copySipkmText(this, document.getElementById('catatanAdminInput').value)">
                <i class="bi bi-clipboard me-1"></i> Salin
              </button>
            </div>
            <p class="small text-muted mb-2">Evaluasi format margin (4-3-3-3), jenis font, batas halaman, lembar pengesahan, & daftar pustaka.</p>
            <textarea class="form-control" rows="6" id="catatanAdminInput" required placeholder="Contoh:&#10;1. Margin kiri masih 3 cm, ubah menjadi 4 cm sesuai pedoman.&#10;2. Lembar pengesahan belum ditandatangani Dosen Pendamping."><?= esc($tugas['catatanAdministrasi'] ?? '') ?></textarea>
          </div>
        </div>

        <!-- CARD 2: Catatan Substansi -->
        <div class="col-lg-6">
          <div class="card-catatan-substansi p-3 rounded-3 border h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-journal-text text-warning fs-5"></i>
                Card 2: Catatan Substansi
              </label>
              <button type="button" class="btn btn-outline-warning text-dark btn-sm py-0 px-2" onclick="copySipkmText(this, document.getElementById('catatanSubstansiInput').value)">
                <i class="bi bi-clipboard me-1"></i> Salin
              </button>
            </div>
            <p class="small text-muted mb-2">Evaluasi metodologi penelitian, latar belakang urgensi masalah, novelty karya cipta, & rasionalitas anggaran.</p>
            <textarea class="form-control" rows="6" id="catatanSubstansiInput" required placeholder="Contoh:&#10;1. Latar belakang inovasi sangat baik dan selaras dengan tema SDG.&#10;2. Metodologi pengujian akurasi sensor perlu diperdalam pada Bab 3.&#10;3. Rincian anggaran biaya pos konsumsi melebihi 15% plafon."><?= esc($tugas['catatanSubstansi'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center pt-2">
        <span id="badgeTahap1Status" class="badge <?= !empty($tugas['catatanAdministrasi']) ? 'bg-success text-white' : 'bg-secondary' ?> py-2 px-3">
          <?= !empty($tugas['catatanAdministrasi']) ? 'Catatan Card 1 & 2 Sudah Terisi' : 'Tahap 1 Belum Disimpan' ?>
        </span>
        <button type="submit" class="btn btn-primary-sipkm btn-sm px-4" data-loading-text="Menyimpan Catatan...">
          <i class="bi bi-save2 me-1"></i> Simpan Catatan Evaluasi (Card 1 & 2)
        </button>
      </div>
    </form>
  </div>

  <!-- ============================================================== -->
  <!-- BAGIAN 2: TAHAP 2 REKAPITULASI PENILAIAN KE ADMIN DKKA         -->
  <!-- ============================================================== -->
  <div class="sipkm-card p-4 shadow-sm border-top border-warning border-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom gap-2">
      <div>
        <h5 class="fw-bold text-dark mb-0">
          <span class="badge bg-warning text-dark me-2">Tahap 2</span>Rekapitulasi Penilaian & Pengiriman ke DKKA
        </h5>
        <small class="text-muted">Unduh master template DKKA, masukkan nilai numerik, lalu kirimkan tautan Google Drive hasil rekap Anda.</small>
      </div>
      <div>
        <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-warning text-dark btn-sm fw-bold">
          <i class="bi bi-folder-symlink me-1"></i> 1. Buka Master Template DKKA (Google Drive)
        </a>
      </div>
    </div>

    <form class="sipkm-form" onsubmit="event.preventDefault(); showSipkmToast('Tahap 2 Selesai! Tautan rekap berhasil dikirim ke Admin DKKA. Status penugasan kini: Selesai.', 'success'); document.getElementById('badgeTahap2Status').className='badge bg-success text-white'; document.getElementById('badgeTahap2Status').innerText='Penugasan Selesai (100%)';">
      <div class="row g-3 align-items-end mb-3">
        <div class="col-md-9">
          <label class="form-label fw-semibold text-dark">
            Tautan Google Drive Hasil Rekapitulasi & Lembar Penilaian Dosen <span class="text-danger">*</span>
          </label>
          <div class="input-group">
            <span class="input-group-text bg-light text-success"><i class="bi bi-google"></i></span>
            <input type="url" class="form-control" id="tautanRekapDriveInput" placeholder="https://drive.google.com/drive/folders/..." required data-validate-drive="true" value="<?= esc($tugas['tautanRekapDrive'] ?? '') ?>">
          </div>
          <div class="form-text mt-1 text-muted">
            <i class="bi bi-shield-check text-success me-1"></i>
            Tautan folder Google Drive yang berisi lembar skor penilaian numerik dan berkas proposal beranotasi.
          </div>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-success w-100 btn-sipkm" data-loading-text="Mengirim Rekap...">
            <i class="bi bi-check2-circle me-1"></i> 2. Kirim & Selesaikan
          </button>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center pt-2 border-top">
        <span id="badgeTahap2Status" class="badge <?= $tugas['statusPenugasan'] === 'Selesai' ? 'bg-success text-white' : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' ?> py-2 px-3">
          <?= $tugas['statusPenugasan'] === 'Selesai' ? 'Penugasan Telah Diselesaikan' : 'Tahap 2 Belum Diselesaikan' ?>
        </span>
        <small class="text-muted">
          <i class="bi bi-info-circle me-1"></i> Setelah selesai, Admin DKKA dapat melihat hasil penilaian untuk sidang pleno kelayakan.
        </small>
      </div>
    </form>
  </div>
</div>

<script>
function isiContohCatatan() {
  document.getElementById('catatanAdminInput').value = "1. Margin kiri sudah 4 cm, namun daftar pustaka belum menggunakan format Harvard secara konsisten.\n2. Perbaiki penomoran halaman romawi pada lembar pengesahan.";
  document.getElementById('catatanSubstansiInput').value = "1. Latar belakang masalah sangat kuat dan relevan dengan SDG 11.\n2. Metodologi kalibrasi sensor MQ-135 perlu diperjelas pada Bab 3.\n3. Perbaiki rincian anggaran biaya pos operasional agar tidak melebihi 25% dari total plafon pendanaan.";
  showSipkmToast('Contoh catatan evaluasi berhasil disisipkan!', 'info');
}
</script>
<?= $this->endSection() ?>
