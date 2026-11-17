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

  <!-- Header Kelola Admin -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Manajemen Akun Administrator</h4>
      <p class="text-muted small mb-0">Kelola akun, hak akses jabatan, dan kredensial Administrator DKKA Universitas Budi Luhur.</p>
    </div>
    <div>
      <button class="btn btn-primary-sipkm btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Administrator Baru
      </button>
    </div>
  </div>

  <!-- Tabel Utama Administrator -->
  <div class="sipkm-card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <h5 class="fw-bold text-dark mb-0">
        <i class="bi bi-shield-lock-fill text-danger me-2"></i>Daftar Akun Administrator
      </h5>
      <span class="small text-muted">Total: <strong><?= count($daftarAdmin) ?></strong> Akun Terdaftar</span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th style="width: 40px;">No</th>
            <th>NIP Admin</th>
            <th>Nama Lengkap</th>
            <th>Jabatan / Role</th>
            <th>Username & Email</th>
            <th>Status Akun</th>
            <th class="text-center" style="width: 150px;">Aksi</th>
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
              <td>
                <span class="badge <?= $adm['jabatan'] === 'Super Admin' ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' ?>">
                  <?= esc($adm['jabatan']) ?>
                </span>
              </td>
              <td>
                <div><code><?= esc($adm['username']) ?></code></div>
                <small class="text-muted"><?= esc($adm['email']) ?></small>
              </td>
              <td>
                <span class="badge <?= $adm['statusAkun'] === 'Aktif' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' ?>">
                  <?= esc($adm['statusAkun']) ?>
                </span>
              </td>
              <td class="text-center">
                <!-- Tombol Edit Admin -->
                <button class="btn btn-outline-primary btn-sm py-1 px-2 me-1" data-bs-toggle="modal" data-bs-target="#modalEditAdmin_<?= $adm['idAdmin'] ?>" title="Edit Data Admin">
                  <i class="bi bi-pencil"></i>
                </button>

                <!-- Tombol Toggle Status -->
                <?php if ((int)$adm['idUser'] !== (int)session()->get('idUser')): ?>
                  <a href="<?= base_url('superadmin/admin/toggle-status/' . $adm['idUser']) ?>" class="btn btn-outline-warning btn-sm py-1 px-2 me-1" title="Aktifkan / Nonaktifkan Akun" onclick="return confirm('Ubah status akun administrator <?= esc($adm['namaAdmin']) ?>?');">
                    <i class="bi bi-power"></i>
                  </a>

                  <!-- Tombol Hapus Admin -->
                  <a href="<?= base_url('superadmin/admin/hapus/' . $adm['idAdmin']) ?>" class="btn btn-outline-danger btn-sm py-1 px-2" title="Hapus Administrator" onclick="return confirm('Apakah Anda yakin ingin menghapus akun <?= esc($adm['namaAdmin']) ?> secara permanen? Data user dan admin akan dihapus.');">
                    <i class="bi bi-trash"></i>
                  </a>
                <?php else: ?>
                  <span class="badge bg-secondary-subtle text-secondary small py-1 px-2">Akun Anda</span>
                <?php endif; ?>

                <!-- Modal Edit Admin -->
                <div class="modal fade text-start" id="modalEditAdmin_<?= $adm['idAdmin'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                      <div class="modal-header bg-light">
                        <h6 class="modal-title fw-bold text-dark">
                          <i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Administrator
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="<?= base_url('superadmin/admin/edit/' . $adm['idAdmin']) ?>" method="post" class="sipkm-form">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">NIP Admin <span class="text-danger">*</span></label>
                            <input type="text" name="nipAdmin" class="form-control" value="<?= esc($adm['nipAdmin']) ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                            <input type="text" name="namaAdmin" class="form-control" value="<?= esc($adm['namaAdmin']) ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Jabatan / Role <span class="text-danger">*</span></label>
                            <select name="jabatan" class="form-select" required>
                              <option value="Admin DKKA" <?= $adm['jabatan'] === 'Admin DKKA' ? 'selected' : '' ?>>Admin DKKA (Operasional)</option>
                              <option value="Super Admin" <?= $adm['jabatan'] === 'Super Admin' ? 'selected' : '' ?>>Super Admin</option>
                            </select>
                          </div>
                          <div class="row g-2 mb-3">
                            <div class="col-md-6">
                              <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
                              <input type="text" name="username" class="form-control" value="<?= esc($adm['username']) ?>" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label small fw-semibold">Email Resmi UBL <span class="text-danger">*</span></label>
                              <input type="email" name="email" class="form-control" value="<?= esc($adm['email']) ?>" required>
                            </div>
                          </div>
                          <div class="mb-2">
                            <label class="form-label small fw-semibold">Ganti Password (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                            <div class="form-text">Isi kolom ini hanya jika ingin mereset password administrator.</div>
                          </div>
                        </div>
                        <div class="modal-footer bg-light">
                          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menyimpan...">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Tambah Administrator Baru -->
  <div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-light">
          <h6 class="modal-title fw-bold text-dark">
            <i class="bi bi-person-plus text-danger me-2"></i>Tambah Akun Administrator DKKA
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url('superadmin/admin/tambah') ?>" method="post" class="sipkm-form">
          <?= csrf_field() ?>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label small fw-semibold">NIP Admin <span class="text-danger">*</span></label>
              <input type="text" name="nipAdmin" class="form-control" placeholder="Contoh: 199205142018032002" required>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
              <input type="text" name="namaAdmin" class="form-control" placeholder="Contoh: Siti Rahma, S.Kom." required>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Jabatan / Role <span class="text-danger">*</span></label>
              <select name="jabatan" class="form-select" required>
                <option value="Admin DKKA">Admin DKKA (Staf Pelaksana Operasional)</option>
                <option value="Super Admin">Super Admin</option>
              </select>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" placeholder="admin_dkka2" required>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Email Resmi UBL <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="admin@budiluhur.ac.id" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Password Default <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" value="password" required>
              <div class="form-text">Administrator dapat mengganti password setelah berhasil login.</div>
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
</div>
<?= $this->endSection() ?>
