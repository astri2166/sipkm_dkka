<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'SIPKM-DKKA | Universitas Budi Luhur') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/sipkm-ui.css?v=' . time()) ?>" rel="stylesheet">
  <style>
    /* Jaminan Mutlak: Modal Selalu di Atas Backdrop & Interaktif */
    .modal {
      z-index: 1065 !important;
    }
    .modal-backdrop {
      z-index: 1050 !important;
    }
    .modal-dialog {
      z-index: 1066 !important;
      pointer-events: auto !important;
    }
    .modal-content {
      pointer-events: auto !important;
    }
    main {
      transform: none !important;
    }
    body.modal-open main {
      transform: none !important;
    }
  </style>
</head>
<body>
<?php
  $activeRole = $role ?? 'Ketua Kelompok';
  $activeMenu = $activeMenu ?? 'dashboard';
?>
<div class="d-flex">
  <!-- Sidebar Dinamis Berdasarkan Role -->
  <aside class="sipkm-sidebar p-3 d-flex flex-column justify-content-between">
    <div>
      <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom border-secondary">
        <div class="bg-warning text-dark fw-bold rounded-3 px-2 py-1">UBL</div>
        <div>
          <div class="fw-bold text-white lh-1">SIPKM-DKKA</div>
          <small class="text-light opacity-75" style="font-size: 11px;">Direktorat Kemahasiswaan</small>
        </div>
      </div>

      <div class="mb-3 px-3 py-2 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.08);">
        <div class="overflow-hidden">
          <small class="d-block text-warning fw-semibold" style="font-size: 11px;"><?= esc($activeRole) ?></small>
          <span class="small text-white text-truncate d-block"><?= esc($userName ?? 'Pengguna Preview') ?></span>
        </div>
        <button type="button" class="btn btn-sm text-light p-0 border-0 opacity-75 ms-2 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modalGantiPasswordGlobal" title="Ganti Password">
          <i class="bi bi-key fs-6"></i>
        </button>
      </div>

      <ul class="nav flex-column">
        <?php if ($activeRole === 'Super Admin'): ?>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('superadmin/dashboard') ?>">
              <i class="bi bi-speedometer2"></i> Dashboard Super Admin
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'admin' ? 'active' : '' ?>" href="<?= base_url('superadmin/kelola-admin') ?>">
              <i class="bi bi-shield-lock"></i> Kelola Admin
            </a>
          </li>

        <?php elseif ($activeRole === 'Admin'): ?>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'dosen' ? 'active' : '' ?>" href="<?= base_url('admin/dosen') ?>">
              <i class="bi bi-person-badge"></i> Dosen Peninjau
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'kelompok' ? 'active' : '' ?>" href="<?= base_url('admin/kelompok') ?>">
              <i class="bi bi-people"></i> Kelompok PKM
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'proposal' ? 'active' : '' ?>" href="<?= base_url('admin/proposal') ?>">
              <i class="bi bi-file-earmark-text"></i> Proposal PKM
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'lomba' ? 'active' : '' ?>" href="<?= base_url('admin/lomba') ?>">
              <i class="bi bi-trophy"></i> Informasi Lomba
            </a>
          </li>

        <?php elseif ($activeRole === 'Dosen Peninjau'): ?>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dosen/dashboard') ?>">
              <i class="bi bi-speedometer2"></i> Dashboard Dosen
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'peninjauan' ? 'active' : '' ?>" href="<?= base_url('dosen/peninjauan') ?>">
              <i class="bi bi-clipboard-check"></i> Peninjauan Proposal
            </a>
          </li>

        <?php else: /* Ketua Kelompok / Anggota Kelompok */ ?>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('mahasiswa/dashboard') ?>">
              <i class="bi bi-speedometer2"></i> Dashboard Mahasiswa
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'kelompok' ? 'active' : '' ?>" href="<?= base_url('mahasiswa/kelompok') ?>">
              <i class="bi bi-people-fill"></i> Data Kelompok PKM
            </a>
          </li>
          <?php if ($activeRole === 'Ketua Kelompok'): ?>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'proposal' ? 'active' : '' ?>" href="<?= base_url('mahasiswa/proposal') ?>">
              <i class="bi bi-cloud-upload"></i> Input Proposal PKM
            </a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'tracking' ? 'active' : '' ?>" href="<?= base_url('mahasiswa/tracking') ?>">
              <i class="bi bi-activity"></i> Tracking Status Proposal
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="pt-3 border-top border-secondary">
      <a href="<?= base_url('/') ?>" class="nav-link text-warning">
        <i class="bi bi-globe"></i> Papan Lomba Publik
      </a>
      <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
        <i class="bi bi-box-arrow-left"></i> Keluar (Logout)
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="flex-grow-1 d-flex flex-column">
    <?php if (($activeMenu ?? '') !== 'dashboard'): ?>
    <header class="border-bottom px-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2" style="background: var(--sipkm-card-bg); border-color: var(--sipkm-card-border) !important;">
      <h5 class="mb-0 fw-bold" style="color: var(--sipkm-primary);"><?= esc($pageHeader ?? 'Dashboard') ?></h5>
      <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalGantiPasswordGlobal" title="Ubah Password Akun Anda">
          <i class="bi bi-key-fill me-1"></i> Ganti Password
        </button>
      </div>
    </header>
    <?php endif; ?>

    <main class="p-4 flex-grow-1">
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</div>

<!-- Modal Global Ganti Password untuk Pengguna yang Sedang Login -->
<div class="modal fade" id="modalGantiPasswordGlobal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light">
        <h6 class="modal-title fw-bold text-dark">
          <i class="bi bi-shield-lock-fill text-primary me-2"></i>Ganti Password Akun
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('ubah-password') ?>" method="post" class="sipkm-form" novalidate>
        <?= csrf_field() ?>
        <div class="modal-body">
          <p class="small text-muted mb-3">
            Perbarui kata sandi akun Anda untuk menjaga keamanan akses ke dalam sistem SIPKM-DKKA.
          </p>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Password Saat Ini (Lama) <span class="text-danger">*</span></label>
            <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password lama" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Password Baru <span class="text-danger">*</span></label>
            <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
            <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password baru" required>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menyimpan...">
            <i class="bi bi-check2-circle me-1"></i> Simpan Password Baru
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->renderSection('modals') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/sipkm-ui.js?v=' . time()) ?>"></script>
<script>
  // Pindahkan semua elemen modal langsung ke <body> agar terbebas 100% dari batasan CSS container manapun
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.modal').forEach(el => {
      if (el.parentElement !== document.body) {
        document.body.appendChild(el);
      }
    });
  });
</script>
</body>
</html>
