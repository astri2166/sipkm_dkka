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

  <!-- Header & Tombol Tambah Lomba -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Pengelolaan Papan Informasi Lomba Publik</h4>
      <p class="text-muted small mb-0">Publikasikan informasi kompetisi eksternal dan program pembinaan PKM bagi mahasiswa UBL.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('/') ?>" target="_blank" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Papan Publik
      </a>
      <button class="btn btn-primary-sipkm btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahLomba">
        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Informasi Lomba
      </button>
    </div>
  </div>

  <!-- Tabel Informasi Lomba -->
  <div class="sipkm-card p-4 shadow-sm mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <h5 class="fw-bold text-dark mb-0">
        <i class="bi bi-trophy-fill text-warning me-2"></i>Daftar Lomba Terbit
      </h5>
      <span class="small text-muted">Total: <strong><?= count($daftarLomba) ?></strong> Program Kompetisi</span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th style="width: 50px;">No</th>
            <th style="width: 100px;">Poster Banner</th>
            <th>Judul & Kategori Lomba</th>
            <th>Deskripsi Program</th>
            <th>Timeline Pendaftaran</th>
            <th>Status</th>
            <th class="text-center" style="width: 130px;">Aksi</th>
          </tr>
        </thead>
        <tbody class="small">
          <?php $no = 1; foreach ($daftarLomba as $l): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td>
                <img src="<?= base_url('assets/uploads/poster/' . esc($l['posterBanner'])) ?>" alt="Poster" class="rounded shadow-sm" style="width: 80px; height: 50px; object-fit: cover;" onerror="this.src='https://placehold.co/120x80/0f294a/fff?text=Poster'">
              </td>
              <td>
                <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($l['kategoriLomba']) ?></span>
                <div class="fw-bold text-dark"><?= esc($l['judulLomba']) ?></div>
                <small class="text-muted">ID Lomba: <code>#<?= esc($l['idLomba']) ?></code></small>
              </td>
              <td>
                <div class="text-muted text-truncate" style="max-width: 280px;" title="<?= esc($l['deskripsiLomba']) ?>">
                  <?= esc($l['deskripsiLomba']) ?>
                </div>
              </td>
              <td>
                <div class="fw-semibold text-dark"><?= esc($l['timelineMulai']) ?></div>
                <small class="text-muted">s/d <?= esc($l['timelineSelesai']) ?></small>
              </td>
              <td>
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                  <i class="bi bi-broadcast me-1"></i> Aktif Terbit
                </span>
              </td>
              <td class="text-center">
                <a href="<?= base_url('admin/lomba/hapus/' . $l['idLomba']) ?>" class="btn btn-outline-danger btn-sm py-1 px-2" title="Hapus Informasi Lomba" onclick="return confirm('Apakah Anda yakin ingin menghapus informasi lomba <?= esc($l['judulLomba']) ?>?');">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Tambah Informasi Lomba Baru -->
  <div class="modal fade" id="modalTambahLomba" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-light">
          <h6 class="modal-title fw-bold text-dark">
            <i class="bi bi-trophy text-primary me-2"></i>Publikasikan Informasi Lomba Baru
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url('admin/lomba/tambah') ?>" method="post" enctype="multipart/form-data" class="sipkm-form">
          <?= csrf_field() ?>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label small fw-semibold">Judul Kegiatan / Lomba <span class="text-danger">*</span></label>
              <input type="text" name="judulLomba" class="form-control" placeholder="Contoh: Pagelaran Mahasiswa Nasional GEMASTIK XX 2027" required>
            </div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Kategori Bidang <span class="text-danger">*</span></label>
                <select name="kategoriLomba" class="form-select" required>
                  <option value="PKM & Karya Ilmiah">PKM & Karya Ilmiah</option>
                  <option value="Teknologi & IT">Teknologi & IT</option>
                  <option value="Kewirausahaan">Kewirausahaan</option>
                  <option value="Seni & Humaniora">Seni & Humaniora</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Poster Banner (JPG/PNG) <span class="text-danger">*</span></label>
                <input type="file" name="poster" class="form-control" accept="image/*">
              </div>
            </div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Tanggal Mulai Pendaftaran <span class="text-danger">*</span></label>
                <input type="date" name="timelineMulai" class="form-control" required value="2026-11-20">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Batas Akhir Pendaftaran <span class="text-danger">*</span></label>
                <input type="date" name="timelineSelesai" class="form-control" required value="2026-12-31">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Deskripsi & Syarat Pendaftaran <span class="text-danger">*</span></label>
              <textarea name="deskripsiLomba" class="form-control" rows="4" placeholder="Tuliskan syarat tim, tautan panduan teknis, dan tahapan seleksi..." required></textarea>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menerbitkan...">
              <i class="bi bi-broadcast me-1"></i> Terbitkan ke Publik
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
