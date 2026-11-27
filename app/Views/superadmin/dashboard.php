<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success small py-2 px-3 mb-3 d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm">
      <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
      <div><?= session()->getFlashdata('success') ?></div>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger small py-2 px-3 mb-3 d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm">
      <i class="bi bi-exclamation-triangle-fill text-danger flex-shrink-0"></i>
      <div><?= session()->getFlashdata('error') ?></div>
    </div>
  <?php endif; ?>

  <!-- 1. Top Banner Super Admin -->
  <div class="banner-admin-navy mb-4 shadow-sm">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge px-3 py-1 rounded-pill fw-bold" style="background: #fbbf24; color: #112842; font-size: 0.78rem;">
            <i class="bi bi-shield-lock-fill me-1"></i> Super Administrator
          </span>
          <span class="badge rounded-pill text-white border border-white-50 opacity-75" style="font-size: 0.78rem;">
            Otoritas Tingkat Tertinggi
          </span>
        </div>
        <h4 class="fw-bold text-white mb-1">Pusat Otorisasi Akun Administrator SIPKM-DKKA</h4>
        <small class="text-white-50" style="font-size: 0.85rem;">
          Selamat datang, <strong><?= esc($userName) ?></strong> &bull; Anda memiliki hak penuh mengelola kredensial dan staf operasional DKKA.
        </small>
      </div>
      <div>
        <a href="<?= base_url('superadmin/kelola-admin') ?>" class="btn btn-warning text-dark btn-sm rounded-pill px-4 py-2 fw-bold" style="font-size: 0.82rem;">
          <i class="bi bi-people-fill me-1"></i> Buka Menu Kelola Admin
        </a>
      </div>
    </div>
  </div>

  <!-- 2. Row of 3 Stat Cards -->
  <div class="row g-3 mb-4">
    <!-- Stat 1: Total Admin -->
    <div class="col-12 col-md-4">
      <div class="card-stat-navy h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small text-white-50">Total Administrator</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: #fbbf24; color: #112842;">
            <i class="bi bi-shield-shaded fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold text-white mb-1" style="font-size: 2.2rem; line-height: 1.1;">
            <?= $countTotal ?>
          </div>
          <small class="text-white-50" style="font-size: 0.82rem;">
            Akun admin terdaftar di sistem
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 2: Admin Aktif -->
    <div class="col-12 col-md-4">
      <div class="card-stat-pastel h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small fw-semibold" style="color: #475569;">Administrator Aktif</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; background: #ffffff; color: #112842;">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold mb-1" style="font-size: 2.2rem; line-height: 1.1; color: #112842;">
            <?= $countAktif ?>
          </div>
          <small class="fw-medium" style="font-size: 0.82rem; color: #475569;">
            Dapat mengakses sistem operasional
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 3: Admin Nonaktif -->
    <div class="col-12 col-md-4">
      <div class="card-stat-pastel h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small fw-semibold" style="color: #475569;">Administrator Nonaktif</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; background: #ffffff; color: #112842;">
            <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold mb-1" style="font-size: 2.2rem; line-height: 1.1; color: #112842;">
            <?= $countNonaktif ?>
          </div>
          <small class="fw-medium" style="font-size: 0.82rem; color: #475569;">
            Akses ke dalam sistem diblokir
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Preview Cepat Daftar Admin -->
  <div class="sipkm-card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <div>
        <h6 class="fw-bold mb-0" style="color: #112842;">
          <i class="bi bi-shield-lock text-primary me-2"></i>Daftar Administrator Terdaftar
        </h6>
        <small class="text-muted">Ringkasan administrator yang memiliki hak operasional PKM</small>
      </div>
      <a href="<?= base_url('superadmin/kelola-admin') ?>" class="small text-decoration-none fw-semibold" style="color: #112842;">
        Kelola Lengkap &rarr;
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="small">
          <tr>
            <th style="width: 40px;">No</th>
            <th>NIP</th>
            <th>Nama Administrator</th>
            <th>Jabatan</th>
            <th>Username</th>
            <th>Email</th>
            <th>Status Akun</th>
          </tr>
        </thead>
        <tbody class="small">
          <?php $no = 1; foreach ($daftarAdmin as $adm): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td class="fw-bold font-monospace text-dark"><?= esc($adm['nipAdmin']) ?></td>
              <td class="fw-semibold text-dark"><?= esc($adm['namaAdmin']) ?></td>
              <td>
                <span class="badge <?= $adm['jabatan'] === 'Super Admin' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' ?>">
                  <?= esc($adm['jabatan']) ?>
                </span>
              </td>
              <td><code><?= esc($adm['username']) ?></code></td>
              <td class="text-muted"><?= esc($adm['email']) ?></td>
              <td>
                <span class="badge <?= $adm['statusAkun'] === 'Aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                  <?= esc($adm['statusAkun']) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
