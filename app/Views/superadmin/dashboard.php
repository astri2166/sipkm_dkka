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

  <!-- Banner Super Admin -->
  <div class="sipkm-card p-4 mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: #fff;">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <span class="badge bg-danger text-white fw-bold mb-2 px-3 py-1 rounded-pill">
          <i class="bi bi-shield-lock-fill me-1"></i> Peran Khusus: Super Administrator
        </span>
        <h3 class="fw-bold mb-1">Pusat Otorisasi Akun Administrator SIPKM-DKKA</h3>
        <p class="mb-0 text-white-50">
          Selamat datang, <strong><?= esc($userName) ?></strong>. Anda memiliki wewenang eksklusif untuk mengelola akun staf dan administrator sistem.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="<?= base_url('superadmin/kelola-admin') ?>" class="btn btn-warning text-dark fw-bold shadow-sm">
          <i class="bi bi-people-fill me-1"></i> Buka Menu Kelola Admin
        </a>
      </div>
    </div>
  </div>

  <!-- 3 Statistik Ringkasan Akun Administrator -->
  <div class="row g-3 mb-4">
    <!-- Stat 1: Total Admin -->
    <div class="col-12 col-md-4">
      <div class="sipkm-card sipkm-card-hover p-4 shadow-sm border-start border-primary border-4 h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Total Administrator</small>
            <h3 class="fw-bold text-dark mb-0 mt-1"><?= $countTotal ?> Akun</h3>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-2">Terdaftar di Sistem</span>
          </div>
          <div class="bg-primary-subtle text-primary p-3 rounded-circle">
            <i class="bi bi-shield-shaded fs-3"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 2: Admin Aktif -->
    <div class="col-12 col-md-4">
      <div class="sipkm-card sipkm-card-hover p-4 shadow-sm border-start border-success border-4 h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Administrator Aktif</small>
            <h3 class="fw-bold text-success mb-0 mt-1"><?= $countAktif ?> Akun</h3>
            <span class="badge bg-success-subtle text-success border border-success-subtle mt-2">Dapat Akses Sistem</span>
          </div>
          <div class="bg-success-subtle text-success p-3 rounded-circle">
            <i class="bi bi-check-circle-fill fs-3"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stat 3: Admin Nonaktif -->
    <div class="col-12 col-md-4">
      <div class="sipkm-card sipkm-card-hover p-4 shadow-sm border-start border-danger border-4 h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted fw-semibold d-block">Administrator Nonaktif</small>
            <h3 class="fw-bold text-danger mb-0 mt-1"><?= $countNonaktif ?> Akun</h3>
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle mt-2">Akses Diblokir</span>
          </div>
          <div class="bg-danger-subtle text-danger p-3 rounded-circle">
            <i class="bi bi-x-circle-fill fs-3"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Preview Cepat Daftar Admin -->
  <div class="sipkm-card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <div>
        <h5 class="fw-bold text-dark mb-0">
          <i class="bi bi-shield-lock text-primary me-2"></i>Daftar Administrator Terdaftar
        </h5>
        <small class="text-muted">Ringkasan administrator yang memiliki hak operasional PKM</small>
      </div>
      <a href="<?= base_url('superadmin/kelola-admin') ?>" class="btn btn-primary-sipkm btn-sm">
        <i class="bi bi-gear-fill me-1"></i> Kelola Lengkap
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th>No</th>
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
              <td class="fw-bold font-monospace"><?= esc($adm['nipAdmin']) ?></td>
              <td class="fw-semibold text-dark"><?= esc($adm['namaAdmin']) ?></td>
              <td>
                <span class="badge <?= $adm['jabatan'] === 'Super Admin' ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' ?>">
                  <?= esc($adm['jabatan']) ?>
                </span>
              </td>
              <td><code><?= esc($adm['username']) ?></code></td>
              <td class="text-muted"><?= esc($adm['email']) ?></td>
              <td>
                <span class="badge <?= $adm['statusAkun'] === 'Aktif' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' ?>">
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
