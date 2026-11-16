<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Mahasiswa | SIPKM-DKKA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/sipkm-ui.css') ?>" rel="stylesheet">
</head>
<body class="d-flex align-items-center min-vh-100 py-4">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="sipkm-card p-4 p-md-5 shadow-sm animate-fade-up">
        <div class="mb-4">
          <span class="badge bg-primary-subtle text-primary px-3 py-2 mb-2">Registrasi Akun Mandiri</span>
          <h4 class="fw-bold mb-1">Pendaftaran Akun Mahasiswa PKM</h4>
          <p class="text-muted small">Akun baru otomatis terdaftar sebagai <strong>Anggota Kelompok</strong> dan dapat langsung membuat atau bergabung ke tim PKM.</p>
        </div>

        <form action="<?= base_url('login') ?>" method="get" class="sipkm-form" novalidate>
          <?= csrf_field() ?>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">NIM <span class="text-danger">*</span></label>
              <input type="text" name="nim" class="form-control" placeholder="Contoh: 2412501872" pattern="[0-9]{8,15}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Nama Lengkap Mahasiswa <span class="text-danger">*</span></label>
              <input type="text" name="namaMahasiswa" class="form-control" placeholder="Sesuai KTM" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Program Studi <span class="text-danger">*</span></label>
              <select name="programStudi" class="form-select" required>
                <option value="">-- Pilih Program Studi --</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Sistem Komputer">Sistem Komputer</option>
                <option value="Manajemen">Manajemen</option>
                <option value="Akuntansi">Akuntansi</option>
                <option value="Ilmu Komunikasi">Ilmu Komunikasi</option>
                <option value="Hubungan Internasional">Hubungan Internasional</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">No. WhatsApp Aktif <span class="text-danger">*</span></label>
              <input type="tel" name="noWhatsapp" class="form-control" placeholder="0812xxxxxxxx" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" class="form-control" placeholder="Username login" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Email Kampus / Aktif <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" placeholder="nim@student.budiluhur.ac.id" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary-sipkm btn-sipkm w-100 py-2 mt-4" data-loading-text="Mendaftarkan Akun...">
            <i class="bi bi-person-plus me-1"></i> Daftar Akun Mahasiswa
          </button>
        </form>

        <div class="text-center mt-4 small">
          Sudah memiliki akun? <a href="<?= base_url('login') ?>" class="fw-semibold text-decoration-none">Masuk di sini</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/js/sipkm-ui.js') ?>"></script>
</body>
</html>
