<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Mahasiswa | SIPKM-DKKA</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo-budiluhur.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/sipkm-ui.css') ?>" rel="stylesheet">
</head>
<body class="d-flex align-items-center min-vh-100 py-4">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="sipkm-card p-4 p-md-5 shadow-sm animate-fade-up">
        <div class="d-flex align-items-center gap-3 mb-4">
          <img src="<?= base_url('assets/img/logo-budiluhur.png') ?>" alt="Logo Universitas Budi Luhur" class="rounded-circle shadow-sm flex-shrink-0" style="width: 58px; height: 58px; object-fit: contain;">
          <div>
            <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-1">Registrasi Akun Mandiri</span>
            <h4 class="fw-bold mb-0">Pendaftaran Akun Mahasiswa PKM</h4>
            <small class="text-muted">Universitas Budi Luhur &bull; Direktorat Kemahasiswaan</small>
          </div>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger small py-2 px-3 mb-3">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Mohon perbaiki data berikut:</div>
            <ul class="mb-0 ps-3">
              <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger small py-2 px-3 mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <div><?= session()->getFlashdata('error') ?></div>
          </div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="post" class="sipkm-form" novalidate>
          <?= csrf_field() ?>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">NIM <span class="text-danger">*</span></label>
              <input type="text" name="nim" class="form-control" placeholder="Contoh: 2412501872" pattern="[0-9]{8,15}" value="<?= old('nim') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Nama Lengkap Mahasiswa <span class="text-danger">*</span></label>
              <input type="text" name="namaMahasiswa" class="form-control" placeholder="Sesuai KTM" value="<?= old('namaMahasiswa') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Daftar Sebagai (Peran Akun) <span class="text-danger">*</span></label>
              <select name="role" class="form-select" required>
                <option value="">-- Pilih Peran Akun --</option>
                <option value="Ketua" <?= in_array(old('role'), ['Ketua', 'Ketua Kelompok']) ? 'selected' : '' ?>>Ketua Kelompok</option>
                <option value="Member" <?= in_array(old('role'), ['Member', 'Anggota Kelompok', 'Anggota']) ? 'selected' : '' ?>>Anggota Kelompok</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Program Studi <span class="text-danger">*</span></label>
              <select name="programStudi" class="form-select" required>
                <option value="">-- Pilih Program Studi --</option>
                <option value="Teknik Informatika" <?= old('programStudi') === 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                <option value="Sistem Komputer" <?= old('programStudi') === 'Sistem Komputer' ? 'selected' : '' ?>>Sistem Komputer</option>
                <option value="Sistem Informasi" <?= old('programStudi') === 'Sistem Informasi' ? 'selected' : '' ?>>Sistem Informasi</option>
                <option value="Ilmu Komunikasi" <?= old('programStudi') === 'Ilmu Komunikasi' ? 'selected' : '' ?>>Ilmu Komunikasi</option>
                <option value="Design Komunikasi Visual" <?= old('programStudi') === 'Design Komunikasi Visual' ? 'selected' : '' ?>>Design Komunikasi Visual</option>
                <option value="Hubungan Internasional" <?= old('programStudi') === 'Hubungan Internasional' ? 'selected' : '' ?>>Hubungan Internasional</option>
                <option value="Kriminologi" <?= old('programStudi') === 'Kriminologi' ? 'selected' : '' ?>>Kriminologi</option>
                <option value="Manajemen" <?= old('programStudi') === 'Manajemen' ? 'selected' : '' ?>>Manajemen</option>
                <option value="Akuntansi" <?= old('programStudi') === 'Akuntansi' ? 'selected' : '' ?>>Akuntansi</option>
                <option value="International Program" <?= old('programStudi') === 'International Program' ? 'selected' : '' ?>>International Program</option>
                <option value="Manajemen Bencana" <?= old('programStudi') === 'Manajemen Bencana' ? 'selected' : '' ?>>Manajemen Bencana</option>
                <option value="Arsitektur" <?= old('programStudi') === 'Arsitektur' ? 'selected' : '' ?>>Arsitektur</option>
                <option value="Teknik Elektro" <?= old('programStudi') === 'Teknik Elektro' ? 'selected' : '' ?>>Teknik Elektro</option>
                <option value="Pariwisata" <?= old('programStudi') === 'Pariwisata' ? 'selected' : '' ?>>Pariwisata</option>
                <option value="Sekretari" <?= old('programStudi') === 'Sekretari' ? 'selected' : '' ?>>Sekretari</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">No. WhatsApp Aktif <span class="text-danger">*</span></label>
              <input type="tel" name="noWhatsapp" class="form-control" placeholder="0812xxxxxxxx" value="<?= old('noWhatsapp') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Email Kampus / Aktif <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" placeholder="nim@student.budiluhur.ac.id" value="<?= old('email') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" class="form-control" placeholder="Username login" value="<?= old('username') ?>" required>
            </div>
            <div class="col-md-6">
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
