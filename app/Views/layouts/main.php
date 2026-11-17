<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'SIPKM-DKKA | Universitas Budi Luhur') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/sipkm-ui.css') ?>" rel="stylesheet">
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

      <div class="mb-3 px-2 py-2 rounded-3" style="background: rgba(255,255,255,0.08);">
        <small class="d-block text-warning fw-semibold"><?= esc($activeRole) ?></small>
        <span class="small text-white"><?= esc($userName ?? 'Pengguna Preview') ?></span>
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
              <i class="bi bi-speedometer2"></i> Dashboard Admin
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'dosen' ? 'active' : '' ?>" href="<?= base_url('admin/dosen') ?>">
              <i class="bi bi-person-badge"></i> Kelola Dosen Peninjau
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'kelompok' ? 'active' : '' ?>" href="<?= base_url('admin/kelompok') ?>">
              <i class="bi bi-people"></i> Kelola Kelompok PKM
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'proposal' ? 'active' : '' ?>" href="<?= base_url('admin/proposal') ?>">
              <i class="bi bi-file-earmark-text"></i> Kelola Proposal PKM
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $activeMenu === 'lomba' ? 'active' : '' ?>" href="<?= base_url('admin/lomba') ?>">
              <i class="bi bi-trophy"></i> Kelola Informasi Lomba
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
    <header class="bg-white border-bottom px-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h5 class="mb-0 fw-bold text-dark"><?= esc($pageHeader ?? 'Dashboard') ?></h5>
      <!-- Switcher Cepat Preview Role (Hanya untuk Fase UI/UX Preview) -->
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">Mode Preview UI</span>
        <div class="btn-group btn-group-sm">
          <a href="<?= base_url('mahasiswa/dashboard?role=Ketua+Kelompok') ?>" class="btn btn-outline-secondary">Ketua</a>
          <a href="<?= base_url('mahasiswa/dashboard?role=Anggota+Kelompok') ?>" class="btn btn-outline-secondary">Anggota</a>
          <a href="<?= base_url('dosen/dashboard') ?>" class="btn btn-outline-secondary">Dosen</a>
          <a href="<?= base_url('admin/dashboard?role=Admin') ?>" class="btn btn-outline-secondary">Admin</a>
          <a href="<?= base_url('superadmin/dashboard?role=Super+Admin') ?>" class="btn btn-outline-secondary">Super Admin</a>
        </div>
      </div>
    </header>

    <main class="p-4 flex-grow-1 animate-fade-up">
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/sipkm-ui.js') ?>"></script>
</body>
</html>
