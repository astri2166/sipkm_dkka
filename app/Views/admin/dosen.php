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

  <!-- Header Kelola Dosen Peninjau -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Manajemen Dosen Peninjau PKM</h4>
      <p class="text-muted small mb-0">Kelola akun peninjau proposal, pantau rasio beban tugas, dan telusuri histori evaluasi.</p>
    </div>
    <div>
      <button class="btn btn-primary-sipkm btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahDosen">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Dosen Peninjau
      </button>
    </div>
  </div>

  <!-- Filter & Pencarian Dosen -->
  <div class="sipkm-card p-3 mb-4 shadow-sm bg-light">
    <div class="row g-2 align-items-center">
      <div class="col-md-6">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
          <input type="text" id="searchDosen" class="form-control" placeholder="Cari nama dosen, NIP, atau email..." onkeyup="filterDosenTable()">
        </div>
      </div>
      <div class="col-md-6 text-md-end text-muted small">
        Total Dosen Terdaftar: <strong><?= count($daftarDosen) ?></strong> Peninjau
      </div>
    </div>
  </div>

  <!-- Tabel Utama Dosen Peninjau -->
  <div class="sipkm-card p-4 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="tableDosen">
        <thead class="table-light small">
          <tr>
            <th style="width: 40px;">No</th>
            <th>NIP / NIDN</th>
            <th>Nama Dosen Peninjau</th>
            <th>Email Resmi</th>
            <th>Beban Penugasan</th>
            <th>Status Akun</th>
            <th class="text-center" style="width: 170px;">Aksi</th>
          </tr>
        </thead>
        <tbody class="small" id="tbodyDosen">
          <?php $no = 1; foreach ($daftarDosen as $dos): ?>
            <tr class="item-dosen" data-text="<?= esc(strtolower($dos['namaDosen'] . ' ' . $dos['nipDosen'] . ' ' . $dos['email'])) ?>">
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
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-primary px-2 py-1" title="Proposal Selesai Dinilai">
                    <i class="bi bi-check2 me-1"></i><?= esc($dos['tugasSelesai'] ?? 0) ?> Selesai
                  </span>
                  <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1" title="Proposal Menunggu Peninjauan">
                    <?= esc($dos['tugasPending'] ?? 0) ?> Pending
                  </span>
                </div>
              </td>
              <td>
                <span class="badge <?= ($dos['statusAkun'] ?? 'Aktif') === 'Aktif' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' ?>">
                  <i class="bi bi-check-circle me-1"></i> <?= esc($dos['statusAkun'] ?? 'Aktif') ?>
                </span>
              </td>
              <td class="text-center">
                <!-- 1. Tombol Histori 2 Tab Sesuai Spesifikasi -->
                <a href="<?= base_url('admin/dosen/detail/' . $dos['idDosen']) ?>" class="btn btn-outline-primary btn-sm py-1 px-2 me-1" title="Buka 2 Tab Histori Penugasan & Hasil">
                  <i class="bi bi-journal-text me-1"></i> Histori
                </a>

                <!-- 2. Tombol Edit Dosen -->
                <button class="btn btn-outline-secondary btn-sm py-1 px-2 me-1" data-bs-toggle="modal" data-bs-target="#modalEditDosen_<?= $dos['idDosen'] ?>" title="Edit Data Dosen">
                  <i class="bi bi-pencil"></i>
                </button>

                <!-- 3. Tombol Toggle Status -->
                <a href="<?= base_url('admin/dosen/toggle-status/' . $dos['idUser']) ?>" class="btn btn-outline-warning btn-sm py-1 px-2" title="Ubah Status Aktif/Nonaktif" onclick="return confirm('Apakah Anda yakin ingin mengubah status akun dosen <?= esc($dos['namaDosen']) ?>?');">
                  <i class="bi bi-power"></i>
                </a>

                <!-- Modal Edit Dosen Peninjau -->
                <div class="modal fade text-start" id="modalEditDosen_<?= $dos['idDosen'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                      <div class="modal-header bg-light">
                        <h6 class="modal-title fw-bold text-dark">
                          <i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Dosen Peninjau
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="<?= base_url('admin/dosen/edit/' . $dos['idDosen']) ?>" method="post" class="sipkm-form">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">NIP / NIDN Dosen <span class="text-danger">*</span></label>
                            <input type="text" name="nipDosen" class="form-control" value="<?= esc($dos['nipDosen']) ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                            <input type="text" name="namaDosen" class="form-control" value="<?= esc($dos['namaDosen']) ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label small fw-semibold">Email Resmi Universitas <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?= esc($dos['email']) ?>" required>
                          </div>
                          <div class="mb-2">
                            <label class="form-label small fw-semibold">Ubah Password (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                            <div class="form-text">Isi hanya jika dosen meminta reset password akun.</div>
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

  <!-- Modal Tambah Dosen Peninjau Baru -->
  <div class="modal fade" id="modalTambahDosen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-light">
          <h6 class="modal-title fw-bold text-dark">
            <i class="bi bi-person-plus text-primary me-2"></i>Tambah Akun Dosen Peninjau
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url('admin/dosen/tambah') ?>" method="post" class="sipkm-form">
          <?= csrf_field() ?>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label small fw-semibold">NIP / NIDN Dosen <span class="text-danger">*</span></label>
              <input type="text" name="nipDosen" class="form-control" placeholder="Contoh: 0315088501" required>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
              <input type="text" name="namaDosen" class="form-control" placeholder="Contoh: Dr. Ir. Hendra Wijaya, M.Sc." required>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Email Resmi Universitas <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" placeholder="hendra.wijaya@budiluhur.ac.id" required>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Password Default <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" value="password" required>
              <div class="form-text">Dosen dapat masuk menggunakan NIP/Email dan password ini.</div>
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
</div>

<script>
function filterDosenTable() {
  const q = document.getElementById('searchDosen').value.toLowerCase().trim();
  const rows = document.querySelectorAll('#tbodyDosen tr.item-dosen');
  rows.forEach(r => {
    const show = !q || r.dataset.text.includes(q);
    r.classList.toggle('d-none', !show);
  });
}
</script>
<?= $this->endSection() ?>
