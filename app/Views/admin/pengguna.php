<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Header & Tab Navigasi Pengguna -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Manajemen Pengguna Sistem</h4>
      <p class="text-muted small mb-0">Kelola akun Dosen Peninjau dan Administrator DKKA Universitas Budi Luhur.</p>
    </div>
    <div class="btn-group btn-group-sm shadow-sm">
      <a href="<?= base_url('admin/pengguna?tab=dosen') ?>" class="btn <?= $activeTab === 'dosen' ? 'btn-primary' : 'btn-outline-primary' ?>">
        <i class="bi bi-person-badge me-1"></i> Dosen Peninjau
      </a>
      <a href="<?= base_url('admin/pengguna?tab=admin') ?>" class="btn <?= $activeTab === 'admin' ? 'btn-primary' : 'btn-outline-primary' ?>">
        <i class="bi bi-shield-lock me-1"></i> Admin DKKA <?= $role === 'Super Admin' ? '' : '<span class="badge bg-secondary ms-1">Super Admin Only</span>' ?>
      </a>
    </div>
  </div>

  <?php if ($activeTab === 'admin' && $role !== 'Super Admin'): ?>
    <!-- Peringatan Akses Tab Admin Terkunci untuk Admin Biasa -->
    <div class="sipkm-card p-5 text-center shadow-sm border-top border-danger border-4">
      <div class="bg-danger-subtle text-danger d-inline-flex p-3 rounded-circle mb-3">
        <i class="bi bi-shield-lock-fill display-5"></i>
      </div>
      <h4 class="fw-bold text-dark mb-2">Akses Terbatas: Khusus Super Admin</h4>
      <p class="text-muted mx-auto" style="max-width: 550px;">
        Sesuai hak otorisasi role, manajemen akun Administrator DKKA hanya dapat dilakukan oleh <strong>Super Admin</strong>.
      </p>
      <div class="mt-3">
        <a href="<?= base_url('admin/pengguna?tab=dosen') ?>" class="btn btn-outline-primary btn-sm me-2">
          <i class="bi bi-person-badge me-1"></i> Kelola Dosen Peninjau Saja
        </a>
        <a href="<?= base_url('admin/dashboard?role=Super+Admin') ?>" class="btn btn-warning text-dark btn-sm fw-bold">
          <i class="bi bi-arrow-repeat me-1"></i> Simulasi Switch ke Super Admin
        </a>
      </div>
    </div>

  <?php elseif ($activeTab === 'admin'): ?>
    <!-- ============================================================== -->
    <!-- TAB 1: KELOLA ADMIN DKKA (KHUSUS SUPER ADMIN)                  -->
    <!-- ============================================================== -->
    <div class="sipkm-card p-4 shadow-sm">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom gap-2">
        <div>
          <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-shield-lock-fill text-danger me-2"></i>Daftar Administrator DKKA
          </h5>
          <small class="text-muted">Kelola akun dan otorisasi staf Direktorat Kemahasiswaan</small>
        </div>
        <button class="btn btn-primary-sipkm btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
          <i class="bi bi-person-plus-fill me-1"></i> Tambah Admin DKKA
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light small">
            <tr>
              <th>No</th>
              <th>NIP Admin</th>
              <th>Nama Administrator</th>
              <th>Jabatan</th>
              <th>Username / Email</th>
              <th>Status Akun</th>
              <th class="text-center" style="width: 140px;">Aksi</th>
            </tr>
          </thead>
          <tbody class="small">
            <?php $no = 1; foreach ($daftarAdmin as $adm): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td class="fw-bold font-monospace"><?= esc($adm['nipAdmin']) ?></td>
                <td>
                  <div class="fw-semibold text-dark"><?= esc($adm['namaAdmin']) ?></div>
                </td>
                <td><span class="badge bg-secondary-subtle text-secondary"><?= esc($adm['jabatan']) ?></span></td>
                <td>
                  <div><code><?= esc($adm['username']) ?></code></div>
                  <small class="text-muted"><?= esc($adm['email']) ?></small>
                </td>
                <td>
                  <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="bi bi-check-circle me-1"></i> <?= esc($adm['statusAkun']) ?>
                  </span>
                </td>
                <td class="text-center">
                  <button class="btn btn-outline-secondary btn-sm py-0 px-2 me-1" onclick="showSipkmToast('Simulasi: Edit data Admin <?= esc($adm['namaAdmin']) ?>', 'info')">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <?php if ($adm['jabatan'] !== 'Super Admin'): ?>
                    <button class="btn btn-outline-danger btn-sm py-0 px-2" onclick="showSipkmToast('Simulasi: Nonaktifkan Admin <?= esc($adm['namaAdmin']) ?>', 'warning')">
                      <i class="bi bi-trash"></i>
                    </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Tambah Admin DKKA -->
    <div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-light">
            <h6 class="modal-title fw-bold text-dark">
              <i class="bi bi-person-plus text-danger me-2"></i>Tambah Akun Administrator DKKA
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form class="sipkm-form" onsubmit="event.preventDefault(); showSipkmToast('Akun Admin DKKA baru berhasil didaftarkan!', 'success'); bootstrap.Modal.getInstance(document.getElementById('modalTambahAdmin')).hide();">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label small fw-semibold">NIP Admin <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Contoh: 199205142018032002" required>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Contoh: Siti Rahma, S.Kom." required>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Jabatan <span class="text-danger">*</span></label>
                <select class="form-select" required>
                  <option value="Admin DKKA">Admin DKKA (Staf Pelaksana)</option>
                  <option value="Super Admin">Super Admin</option>
                </select>
              </div>
              <div class="row g-2 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" placeholder="admin_dkka2" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Email Resmi UBL <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" placeholder="admin@budiluhur.ac.id" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Password Default <span class="text-danger">*</span></label>
                <input type="password" class="form-control" value="password" required>
                <div class="form-text">Pengguna wajib mengganti password saat login perdana.</div>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menyimpan...">
                <i class="bi bi-save me-1"></i> Simpan Admin
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php else: ?>
    <!-- ============================================================== -->
    <!-- TAB 2: KELOLA DOSEN PENINJAU (ADMIN DKKA & SUPER ADMIN)        -->
    <!-- ============================================================== -->
    <div class="sipkm-card p-4 shadow-sm">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom gap-2">
        <div>
          <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-person-badge-fill text-primary me-2"></i>Daftar Dosen Peninjau PKM
          </h5>
          <small class="text-muted">Kelola akun peninjau proposal beserta rekapitulasi beban tugas penilaian</small>
        </div>
        <button class="btn btn-primary-sipkm btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahDosen">
          <i class="bi bi-person-plus-fill me-1"></i> Tambah Dosen Peninjau
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light small">
            <tr>
              <th>No</th>
              <th>NIP Dosen</th>
              <th>Nama Dosen Peninjau</th>
              <th>Email Resmi</th>
              <th>Beban Penugasan</th>
              <th>Status Akun</th>
              <th class="text-center" style="width: 160px;">Aksi</th>
            </tr>
          </thead>
          <tbody class="small">
            <?php $no = 1; foreach ($daftarDosen as $dos): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td class="fw-bold font-monospace"><?= esc($dos['nipDosen']) ?></td>
                <td>
                  <div class="fw-semibold text-dark"><?= esc($dos['namaDosen']) ?></div>
                </td>
                <td>
                  <a href="mailto:<?= esc($dos['email']) ?>" class="text-decoration-none text-muted">
                    <i class="bi bi-envelope me-1"></i><?= esc($dos['email']) ?>
                  </a>
                </td>
                <td>
                  <!-- Badge Rasio Tugas vs Selesai Sesuai Master Prompt -->
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-2 py-1" title="Proposal Selesai">
                      <i class="bi bi-check2 me-1"></i><?= esc($dos['tugasSelesai'] ?? 2) ?> Selesai
                    </span>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1" title="Proposal Belum Selesai">
                      <?= esc($dos['tugasPending'] ?? 1) ?> Pending
                    </span>
                  </div>
                </td>
                <td>
                  <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="bi bi-check-circle me-1"></i> <?= esc($dos['statusAkun'] ?? 'Aktif') ?>
                  </span>
                </td>
                <td class="text-center">
                  <!-- Tombol Buka 2 Tab Histori Dosen -->
                  <a href="<?= base_url('admin/dosen/detail/' . $dos['idDosen']) ?>" class="btn btn-outline-primary btn-sm py-1 px-2" title="Lihat 2 Tab Histori Penugasan & Hasil">
                    <i class="bi bi-journal-text me-1"></i> Histori
                  </a>
                  <button class="btn btn-outline-danger btn-sm py-1 px-2" onclick="showSipkmToast('Simulasi: Nonaktifkan dosen <?= esc($dos['namaDosen']) ?>', 'warning')">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Tambah Dosen Peninjau -->
    <div class="modal fade" id="modalTambahDosen" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-light">
            <h6 class="modal-title fw-bold text-dark">
              <i class="bi bi-person-plus text-primary me-2"></i>Tambah Akun Dosen Peninjau
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form class="sipkm-form" onsubmit="event.preventDefault(); showSipkmToast('Dosen Peninjau baru berhasil didaftarkan!', 'success'); bootstrap.Modal.getInstance(document.getElementById('modalTambahDosen')).hide();">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label small fw-semibold">NIP / NIDN Dosen <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Contoh: 0315088501" required>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Contoh: Dr. Ir. Hendra Wijaya, M.Sc." required>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Email Resmi Universitas <span class="text-danger">*</span></label>
                <input type="email" class="form-control" placeholder="hendra.wijaya@budiluhur.ac.id" required>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Password Default <span class="text-danger">*</span></label>
                <input type="password" class="form-control" value="password" required>
                <div class="form-text">Dosen dapat masuk menggunakan email dan password default ini.</div>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menyimpan...">
                <i class="bi bi-save me-1"></i> Simpan Dosen
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
