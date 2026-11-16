<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | SIPKM-DKKA Universitas Budi Luhur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/sipkm-ui.css') ?>" rel="stylesheet">
</head>
<body class="d-flex align-items-center min-vh-100 py-4">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="sipkm-card p-4 p-md-5 shadow-sm animate-fade-up">
        <div class="text-center mb-4">
          <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-2">DKKA UNIVERSITAS BUDI LUHUR</span>
          <h4 class="fw-bold mb-1">Masuk ke SIPKM-DKKA</h4>
          <p class="text-muted small">Gunakan akun Mahasiswa, Dosen Peninjau, atau Admin</p>
        </div>

        <form action="<?= base_url('mahasiswa/dashboard') ?>" method="get" class="sipkm-form" novalidate>
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label fw-medium">Username atau Email</label>
            <input type="text" name="username" id="loginUsername" class="form-control" placeholder="Contoh: ketua_pkm1" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Password</label>
            <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••" required>
          </div>
          <button type="submit" class="btn btn-primary-sipkm btn-sipkm w-100 py-2 mt-2" data-loading-text="Memverifikasi Akun...">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
          </button>
        </form>

        <div class="mt-4 pt-3 border-top">
          <small class="text-muted d-block mb-2">Isi Otomatis Akun Dummy (Preview):</small>
          <div class="d-flex flex-wrap gap-1">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillDummy('superadmin')">Super Admin</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillDummy('admindkka')">Admin</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillDummy('dosen1')">Dosen</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillDummy('ketua_pkm1')">Ketua PKM</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillDummy('mhs_bebas1')">Mhs Baru</button>
          </div>
        </div>

        <div class="text-center mt-4 small">
          Belum punya akun mahasiswa? <a href="<?= base_url('register') ?>" class="fw-semibold text-decoration-none">Daftar Akun Mandiri</a><br>
          <a href="<?= base_url('/') ?>" class="text-muted text-decoration-none d-inline-block mt-2">&larr; Kembali ke Papan Informasi Lomba</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/js/sipkm-ui.js') ?>"></script>
<script>
function fillDummy(uname) {
  document.getElementById('loginUsername').value = uname;
  document.getElementById('loginPassword').value = 'password';
  showSipkmToast(`Akun dummy "${uname}" siap diuji!`, 'primary');
}
</script>
</body>
</html>
