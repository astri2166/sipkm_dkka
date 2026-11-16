<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Header Switcher Preview State -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Manajemen Kelompok PKM</h4>
      <p class="text-muted small mb-0">Kelola susunan anggota tim, kode registrasi kelompok, dan verifikasi anggota.</p>
    </div>
    <!-- Switcher Simulasi Pengujian -->
    <div class="btn-group btn-group-sm shadow-sm">
      <a href="<?= base_url('mahasiswa/kelompok?has_kelompok=1&role=Ketua+Kelompok') ?>" class="btn <?= $hasKelompok && $role === 'Ketua Kelompok' ? 'btn-primary' : 'btn-outline-primary' ?>">
        <i class="bi bi-person-check-fill me-1"></i> Simulasi: Sudah Berkelompok (Ketua)
      </a>
      <a href="<?= base_url('mahasiswa/kelompok?has_kelompok=1&role=Anggota+Kelompok') ?>" class="btn <?= $hasKelompok && $role === 'Anggota Kelompok' ? 'btn-primary' : 'btn-outline-primary' ?>">
        <i class="bi bi-person-fill me-1"></i> Simulasi: Anggota Kelompok
      </a>
      <a href="<?= base_url('mahasiswa/kelompok?has_kelompok=0') ?>" class="btn <?= !$hasKelompok ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning text-dark' ?>">
        <i class="bi bi-person-plus-fill me-1"></i> Simulasi: Belum Kelompok (Form Buat)
      </a>
    </div>
  </div>

  <?php if ($hasKelompok): ?>
    <!-- ============================================================== -->
    <!-- KONDISI A: MAHASISWA SUDAH TERGABUNG DALAM KELOMPOK            -->
    <!-- ============================================================== -->
    <div class="row g-4">
      <!-- Kolom Kiri: Rincian Kelompok & Kode Tim -->
      <div class="col-lg-4">
        <div class="sipkm-card p-4 shadow-sm mb-4 border-top border-primary border-4">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <span class="badge bg-primary text-white px-3 py-1 rounded-pill">
              Periode <?= esc($kelompok['tahunPeriode']) ?>
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle">
              <i class="bi bi-lock-fill me-1"></i> Terkunci
            </span>
          </div>

          <h5 class="fw-bold text-dark mb-1"><?= esc($kelompok['namaKelompok']) ?></h5>
          <p class="text-muted small mb-3">ID Kelompok Database: <code>#<?= esc($kelompok['idKelompok']) ?></code></p>

          <hr class="my-3">

          <!-- Kotak Kode Kelompok untuk Dibagikan ke Anggota -->
          <div class="mb-3">
            <label class="form-label small fw-semibold text-muted">Kode Registrasi Kelompok:</label>
            <div class="d-flex align-items-center gap-2">
              <div class="code-box flex-grow-1 text-center text-primary" id="kodeKelompokText">
                <?= esc($kodeKelompok) ?>
              </div>
              <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copySipkmText(this, '<?= esc($kodeKelompok) ?>')" title="Salin Kode Kelompok">
                <i class="bi bi-clipboard"></i>
              </button>
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 11px;">
              <i class="bi bi-info-circle me-1"></i> Bagikan kode ini kepada calon anggota agar dapat bergabung.
            </small>
          </div>

          <!-- Rincian Singkat -->
          <div class="bg-light p-3 rounded-3 small">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">NIM Ketua Tim:</span>
              <span class="fw-bold font-monospace"><?= esc($kelompok['nimKetua']) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Total Anggota:</span>
              <span class="fw-bold"><?= esc($kelompok['jumlahAnggota']) ?> / 5 Mahasiswa</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Status Validasi:</span>
              <span class="badge bg-success text-white">Memenuhi Syarat</span>
            </div>
          </div>
        </div>

        <!-- Alert Status Lock (Sesuai Aturan Bisnis) -->
        <div class="alert alert-secondary small shadow-sm">
          <h6 class="alert-heading fw-bold fs-6 mb-1">
            <i class="bi bi-shield-lock-fill text-warning me-1"></i> Kebijakan Kunci Kelompok
          </h6>
          <p class="mb-0 text-muted">
            Anggota kelompok yang telah terdaftar akan otomatis terkunci (<code>idKelompok</code> terkunci). Perubahan anggota hanya dapat dilakukan sebelum proposal resmi diajukan.
          </p>
        </div>
      </div>

      <!-- Kolom Kanan: Daftar Anggota Tim -->
      <div class="col-lg-8">
        <div class="sipkm-card p-4 shadow-sm">
          <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom gap-2">
            <div>
              <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-people-fill text-primary me-2"></i>Daftar Mahasiswa Terdaftar
              </h5>
              <small class="text-muted">Kelompok <?= esc($kelompok['namaKelompok']) ?></small>
            </div>
            <?php if ($role === 'Ketua Kelompok' && count($daftarAnggota) < 5): ?>
              <button class="btn btn-primary-sipkm btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">
                <i class="bi bi-person-plus-fill me-1"></i> Tambah Anggota (Undang NIM)
              </button>
            <?php endif; ?>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light small">
                <tr>
                  <th>No</th>
                  <th>NIM</th>
                  <th>Nama Lengkap</th>
                  <th>Program Studi</th>
                  <th>No. WhatsApp</th>
                  <th>Peran</th>
                  <?php if ($role === 'Ketua Kelompok'): ?>
                    <th class="text-center">Aksi</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody class="small">
                <?php $no = 1; foreach ($daftarAnggota as $m): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td class="fw-bold font-monospace"><?= esc($m['nim']) ?></td>
                    <td>
                      <div class="fw-semibold text-dark"><?= esc($m['namaMahasiswa']) ?></div>
                      <small class="text-muted"><?= esc($m['email'] ?? $m['nim'] . '@student.budiluhur.ac.id') ?></small>
                    </td>
                    <td><?= esc($m['programStudi']) ?></td>
                    <td>
                      <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $m['noWhatsapp']) ?>" target="_blank" class="text-success text-decoration-none fw-medium">
                        <i class="bi bi-whatsapp me-1"></i><?= esc($m['noWhatsapp']) ?>
                      </a>
                    </td>
                    <td>
                      <?php if ($m['nim'] === $kelompok['nimKetua']): ?>
                        <span class="badge bg-primary px-2 py-1"><i class="bi bi-star-fill me-1"></i> Ketua Tim</span>
                      <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Anggota</span>
                      <?php endif; ?>
                    </td>
                    <?php if ($role === 'Ketua Kelompok'): ?>
                      <td class="text-center">
                        <?php if ($m['nim'] !== $kelompok['nimKetua']): ?>
                          <button class="btn btn-outline-danger btn-sm py-0 px-2" onclick="showSipkmToast('Simulasi: Anggota <?= esc($m['namaMahasiswa']) ?> dapat dikeluarkan selama proposal belum terkunci.', 'warning')">
                            <i class="bi bi-trash"></i>
                          </button>
                        <?php else: ?>
                          <span class="text-muted" style="font-size: 11px;">(Ketua)</span>
                        <?php endif; ?>
                      </td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="mt-4 p-3 bg-light rounded-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="small text-muted">
              <i class="bi bi-check-circle-fill text-success me-1"></i>
              Komposisi tim: <strong><?= count($daftarAnggota) ?> Mahasiswa</strong>. Minimal 3 orang dan maksimal 5 orang untuk mendaftar PKM.
            </div>
            <?php if ($role === 'Ketua Kelompok'): ?>
              <a href="<?= base_url('mahasiswa/proposal') ?>" class="btn btn-success btn-sm">
                <i class="bi bi-arrow-right-circle me-1"></i> Lanjut Input Proposal PKM
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Tambah Anggota (Undang dari Mahasiswa Bebas) -->
    <div class="modal fade" id="modalTambahAnggota" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-light">
            <h6 class="modal-title fw-bold text-dark">
              <i class="bi bi-person-plus text-primary me-2"></i>Undang Anggota ke Kelompok
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form class="sipkm-form" onsubmit="event.preventDefault(); showSipkmToast('Simulasi: Mahasiswa berhasil ditambahkan ke kelompok!', 'success'); bootstrap.Modal.getInstance(document.getElementById('modalTambahAnggota')).hide();">
            <div class="modal-body">
              <p class="text-muted small">
                Pilih mahasiswa yang saat ini berstatus <strong>belum memiliki kelompok</strong> (<code>idKelompok IS NULL</code>):
              </p>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Pilih Mahasiswa Bebas:</label>
                <select class="form-select" required>
                  <option value="">-- Pilih Mahasiswa --</option>
                  <?php foreach ($mahasiswaBebas as $mb): ?>
                    <option value="<?= esc($mb['nim']) ?>">
                      <?= esc($mb['nim']) ?> - <?= esc($mb['namaMahasiswa']) ?> (<?= esc($mb['programStudi']) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="alert alert-info small mb-0">
                <i class="bi bi-info-circle me-1"></i> Mahasiswa yang sudah masuk kelompok otomatis tidak akan muncul di daftar pilihan ini.
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menambahkan...">
                <i class="bi bi-check-lg me-1"></i> Tambahkan Anggota
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php else: ?>
    <!-- ============================================================== -->
    <!-- KONDISI B: MAHASISWA BELUM MEMILIKI KELOMPOK (idKelompok NULL) -->
    <!-- ============================================================== -->
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="sipkm-card p-4 shadow-sm border-top border-warning border-4">
          <div class="text-center mb-4">
            <div class="bg-warning-subtle text-warning d-inline-flex p-3 rounded-circle mb-2">
              <i class="bi bi-people display-6"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Formulir Pembentukan Kelompok PKM Baru</h4>
            <p class="text-muted small">
              Status akun Anda saat ini: <code>idKelompok = NULL</code>. Silakan bentuk kelompok baru atau minta Ketua Kelompok mengundang NIM Anda.
            </p>
          </div>

          <form class="sipkm-form" onsubmit="event.preventDefault(); showSipkmToast('Simulasi: Kelompok baru berhasil dibentuk! Role Anda otomatis berubah menjadi Ketua Kelompok.', 'success'); setTimeout(() => window.location.href='<?= base_url('mahasiswa/kelompok?has_kelompok=1&role=Ketua+Kelompok') ?>', 1200);">
            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Nama Kelompok PKM <span class="text-danger">*</span></label>
              <input type="text" class="form-control" placeholder="Contoh: Tim Inovasi Smart Campus UBL" required>
              <div class="form-text">Gunakan nama tim yang representatif dan sesuai tema inovasi.</div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold text-dark">Tahun Periode <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="2026" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold text-dark">NIM Ketua (Akun Anda) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="<?= esc($mahasiswa['nim'] ?? '2412501872') ?>" readonly>
                <div class="form-text">Pembuat kelompok akan otomatis ditetapkan sebagai <strong>Ketua Kelompok</strong>.</div>
              </div>
            </div>

            <!-- Pilih Anggota Bebas -->
            <div class="mb-4">
              <label class="form-label fw-semibold text-dark d-flex justify-content-between align-items-center">
                <span>Undang Anggota Tim (Minimal 2 orang tambahan) <span class="text-danger">*</span></span>
                <span class="badge bg-info-subtle text-info-emphasis">Filter: idKelompok = NULL</span>
              </label>
              <div class="border rounded-3 p-3 bg-light">
                <p class="small text-muted mb-2">Daftar mahasiswa yang belum tergabung dalam kelompok manapun:</p>
                <?php foreach ($mahasiswaBebas as $mb): ?>
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="<?= esc($mb['nim']) ?>" id="mhs_<?= esc($mb['nim']) ?>">
                    <label class="form-check-label small" for="mhs_<?= esc($mb['nim']) ?>">
                      <strong><?= esc($mb['namaMahasiswa']) ?></strong> (NIM: <?= esc($mb['nim']) ?>) &bull; <?= esc($mb['programStudi']) ?>
                    </label>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
              <a href="<?= base_url('mahasiswa/dashboard') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
              </a>
              <button type="submit" class="btn btn-primary-sipkm px-4" data-loading-text="Mendaftarkan Kelompok...">
                <i class="bi bi-check-circle-fill me-1"></i> Daftarkan Kelompok Sekarang
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
