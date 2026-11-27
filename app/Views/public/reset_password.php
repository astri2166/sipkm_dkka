<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password | SIPKM-DKKA Universitas Budi Luhur</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo-budiluhur.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/sipkm-ui.css') ?>" rel="stylesheet">
</head>
<body class="d-flex align-items-center min-vh-100 py-4">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="sipkm-card p-4 p-md-5 shadow-sm animate-fade-up">
        <!-- Header -->
        <div class="text-center mb-4">
          <img src="<?= base_url('assets/img/logo-budiluhur.png') ?>" alt="Logo Universitas Budi Luhur" class="rounded-circle shadow-sm mb-3" style="width: 76px; height: 76px; object-fit: contain;">
          <h4 class="fw-bold mb-1">Reset Password Akun</h4>
          <p class="text-muted small mb-2">Layanan mandiri pemulihan kata sandi akun SIPKM-DKKA</p>
          
          <!-- Badge 5 Peran Akun -->
          <div class="d-flex justify-content-center flex-wrap gap-1 mt-2">
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle small">Super Admin</span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle small">Admin DKKA</span>
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle small">Dosen</span>
            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle small">Ketua PKM</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle small">Anggota</span>
          </div>
        </div>

        <!-- Alert Error Tunggal -->
        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger small py-2 px-3 mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <div><?= session()->getFlashdata('error') ?></div>
          </div>
        <?php endif; ?>

        <!-- Alert Error Validasi Multi -->
        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger small py-2 px-3 mb-3">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Mohon periksa kesalahan berikut:</div>
            <ul class="mb-0 ps-3">
              <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Alert Sukses -->
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success small py-2 px-3 mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <div><?= session()->getFlashdata('success') ?></div>
          </div>
        <?php endif; ?>

        <!-- Form Reset Password -->
        <form action="<?= base_url('reset-password') ?>" method="post" class="sipkm-form" novalidate>
          <?= csrf_field() ?>
          
          <div class="mb-3">
            <label class="form-label fw-medium small text-dark">
              Identitas Akun <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="bi bi-person-badge"></i></span>
              <input type="text" name="identity" id="resetIdentity" class="form-control" placeholder="Username / Email / NIM / NIP" value="<?= old('identity') ?>" required>
            </div>
            <div class="form-text small text-muted">
              Masukkan Username, Email terdaftar, NIM (Mahasiswa), atau NIP (Dosen/Admin).
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium small text-dark">
              Password Baru <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="bi bi-key-fill"></i></span>
              <input type="password" name="password_baru" id="resetPasswordBaru" class="form-control" placeholder="Minimal 6 karakter" required>
              <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('resetPasswordBaru', 'eyeIcon1')">
                <i class="bi bi-eye" id="eyeIcon1"></i>
              </button>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium small text-dark">
              Konfirmasi Password Baru <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock-fill"></i></span>
              <input type="password" name="konfirmasi_password" id="resetKonfirmasiPassword" class="form-control" placeholder="Ulangi password baru" required>
              <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('resetKonfirmasiPassword', 'eyeIcon2')">
                <i class="bi bi-eye" id="eyeIcon2"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-primary-sipkm btn-sipkm w-100 py-2 mt-2" data-loading-text="Mereset Password...">
            <i class="bi bi-check-circle-fill me-1"></i> Simpan Password Baru
          </button>
        </form>

        <!-- Pintasan Akun Contoh untuk Pengujian Cepat -->
        <div class="mt-4 pt-3 border-top">
          <small class="text-muted d-block mb-2">Pintasan Cepat Identitas Akun (Pengujian):</small>
          <div class="d-flex flex-wrap gap-1">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillResetIdentitas('superadmin')">Super Admin</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillResetIdentitas('admindkka')">Admin</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillResetIdentitas('dosen1')">Dosen</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillResetIdentitas('naufa')">Ketua PKM</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillResetIdentitas('ananta')">Anggota</button>
          </div>
        </div>

        <!-- Link Navigasi Kembali -->
        <div class="text-center mt-4 small">
          Sudah ingat kata sandi Anda? <a href="<?= base_url('login') ?>" class="fw-semibold text-decoration-none">Masuk ke Sistem</a><br>
          Belum punya akun mahasiswa? <a href="<?= base_url('register') ?>" class="fw-semibold text-decoration-none">Daftar Akun Baru</a><br>
          <a href="<?= base_url('/') ?>" class="text-muted text-decoration-none d-inline-block mt-2">&larr; Kembali ke Papan Informasi Lomba</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/sipkm-ui.js') ?>"></script>
<script>
function fillResetIdentitas(identitas) {
  document.getElementById('resetIdentity').value = identitas;
  document.getElementById('resetPasswordBaru').value = 'password123';
  document.getElementById('resetKonfirmasiPassword').value = 'password123';
  showSipkmToast(`Identitas "${identitas}" terisi! Password contoh disiapkan.`, 'primary');
}

function togglePasswordVisibility(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById(iconId);
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
  }
}
</script>
</body>
</html>
