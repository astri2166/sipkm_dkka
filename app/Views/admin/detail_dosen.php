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

  <!-- Header & Navigasi Kembali -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div class="d-flex align-items-center gap-2">
      <a href="<?= base_url('admin/pengguna?tab=dosen') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Dosen
      </a>
      <h5 class="fw-bold text-dark mb-0">Rincian & Histori Dosen Peninjau</h5>
    </div>
    <div>
      <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
        NIP: <?= esc($dosen['nipDosen']) ?>
      </span>
    </div>
  </div>

  <!-- Profil Ringkas Dosen Peninjau -->
  <div class="sipkm-card p-4 shadow-sm mb-4 border-top border-primary border-4">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="d-flex align-items-center gap-3">
          <div class="bg-primary text-white p-3 rounded-circle fs-3">
            <i class="bi bi-person-workspace"></i>
          </div>
          <div>
            <h4 class="fw-bold text-dark mb-1"><?= esc($dosen['namaDosen']) ?></h4>
            <p class="text-muted small mb-0">
              Email: <strong><?= esc($dosen['email']) ?></strong> &bull; 
              Status: <span class="badge bg-success-subtle text-success">Aktif</span> &bull; 
              Fakultas Teknologi Informasi
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <div class="d-flex justify-content-lg-end gap-3">
          <div class="text-center p-2 bg-light rounded-3 border">
            <span class="small text-muted d-block">Total Tugas</span>
            <strong class="fs-5 text-dark"><?= count($tugasAdminKeDosen) ?></strong>
          </div>
          <div class="text-center p-2 bg-light rounded-3 border">
            <span class="small text-muted d-block">Telah Selesai</span>
            <strong class="fs-5 text-success"><?= count($hasilDosenKeAdmin) ?></strong>
          </div>
          <div class="text-center p-2 bg-light rounded-3 border">
            <span class="small text-muted d-block">Pending</span>
            <strong class="fs-5 text-warning"><?= count($tugasAdminKeDosen) - count($hasilDosenKeAdmin) ?></strong>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Tahun & Tanggal (Sesuai Master Prompt) -->
  <div class="sipkm-card p-3 mb-4 shadow-sm bg-light">
    <div class="row g-2 align-items-center">
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-0">Filter Periode:</label>
        <select class="form-select form-select-sm" id="filterTahun">
          <option value="2026">Tahun Akademik 2026</option>
          <option value="2025">Tahun Akademik 2025</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label small fw-semibold mb-0">Rentang Tanggal Penugasan:</label>
        <div class="input-group input-group-sm">
          <input type="date" class="form-control" value="2026-11-01">
          <span class="input-group-text">s/d</span>
          <input type="date" class="form-control" value="2026-12-31">
        </div>
      </div>
      <div class="col-md-5 text-md-end mt-3 mt-md-0 pt-md-3">
        <button class="btn btn-outline-primary btn-sm" onclick="showSipkmToast('Filter tanggal diterapkan pada histori.', 'info')">
          <i class="bi bi-funnel me-1"></i> Terapkan Filter
        </button>
      </div>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- 2 TAB HISTORI (WAJIB SESUAI SPESIFIKASI PROMPT)                -->
  <!-- ============================================================== -->
  <div class="sipkm-card shadow-sm mb-4">
    <div class="border-bottom px-4 pt-3">
      <ul class="nav nav-tabs border-0" id="historiDosenTab" role="tablist">
        <li class="nav-item">
          <button class="nav-link active fw-bold text-dark border-bottom-0" id="tab-pengajuan-tab" data-bs-toggle="tab" data-bs-target="#tab-pengajuan" type="button" role="tab">
            <i class="bi bi-arrow-up-right-circle text-primary me-2"></i>Tab 1: Pengajuan Peninjauan (Admin &rarr; Dosen)
            <span class="badge bg-primary ms-1"><?= count($tugasAdminKeDosen) ?></span>
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold text-dark border-bottom-0" id="tab-hasil-tab" data-bs-toggle="tab" data-bs-target="#tab-hasil" type="button" role="tab">
            <i class="bi bi-check2-circle text-success me-2"></i>Tab 2: Hasil Peninjauan (Dosen &rarr; Admin)
            <span class="badge bg-success ms-1"><?= count($hasilDosenKeAdmin) ?></span>
          </button>
        </li>
      </ul>
    </div>

    <div class="tab-content p-4" id="historiDosenTabContent">
      <!-- TAB 1: PENGAJUAN PENINJAUAN (ADMIN KE DOSEN) -->
      <div class="tab-pane fade show active" id="tab-pengajuan" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold text-dark mb-0">Daftar Seluruh Proposal yang Di-plotting Admin ke Dosen Ini</h6>
          <small class="text-muted">Mencakup proposal yang sedang berjalan maupun yang telah selesai</small>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
              <tr>
                <th>No</th>
                <th>Skema & Judul Usulan</th>
                <th>Kelompok Pengusul</th>
                <th>Tautan Google Docs</th>
                <th>Tgl Penugasan</th>
                <th>Status Penugasan</th>
              </tr>
            </thead>
            <tbody class="small">
              <?php $no = 1; foreach ($tugasAdminKeDosen as $item): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($item['jenisPKM']) ?></span>
                    <div class="fw-bold text-dark"><?= esc($item['judulProposal']) ?></div>
                  </td>
                  <td>
                    <div class="fw-semibold text-dark"><?= esc($item['namaKelompok']) ?></div>
                    <small class="text-muted">Ketua: <?= esc($item['nimKetua']) ?></small>
                  </td>
                  <td>
                    <a href="<?= esc($item['tautanGoogleDocs']) ?>" target="_blank" class="btn btn-outline-danger btn-sm py-1 px-2">
                      <i class="bi bi-google me-1"></i> Buka Docs
                    </a>
                  </td>
                  <td><?= esc($item['tglPenugasan']) ?></td>
                  <td>
                    <?php if ($item['statusPenugasan'] === 'Selesai'): ?>
                      <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-check-circle me-1"></i> Selesai
                      </span>
                    <?php else: ?>
                      <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                        <i class="bi bi-clock me-1"></i> Belum Ditinjau
                      </span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TAB 2: HASIL PENINJAUAN (DOSEN KE ADMIN) -->
      <div class="tab-pane fade" id="tab-hasil" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold text-dark mb-0">Hasil Evaluasi & Berkas Rekap yang Diserahkan Dosen</h6>
          <small class="text-muted">Proposal yang telah selesai ditinjau dan siap untuk sidang pleno kelayakan DKKA</small>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
              <tr>
                <th>No</th>
                <th>Skema & Judul Usulan</th>
                <th>Tgl Selesai</th>
                <th>Tautan Rekap Drive</th>
                <th>Pratinjau Card 1 & Card 2</th>
                <th class="text-center">Status Kelayakan</th>
              </tr>
            </thead>
            <tbody class="small">
              <?php $no = 1; foreach ($hasilDosenKeAdmin as $h): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($h['jenisPKM']) ?></span>
                    <div class="fw-bold text-dark"><?= esc($h['judulProposal']) ?></div>
                    <small class="text-muted"><?= esc($h['namaKelompok']) ?></small>
                  </td>
                  <td>
                    <span class="text-success fw-semibold"><i class="bi bi-calendar-check me-1"></i><?= esc($h['tglSelesaiTinjau']) ?></span>
                  </td>
                  <td>
                    <?php if (!empty($h['tautanRekapDrive'])): ?>
                      <a href="<?= esc($h['tautanRekapDrive']) ?>" target="_blank" class="btn btn-outline-success btn-sm py-1 px-2 fw-semibold">
                        <i class="bi bi-folder-check me-1"></i> Buka Rekap Drive
                      </a>
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <!-- Tombol Modal Pratinjau Card 1 & 2 -->
                    <button class="btn btn-outline-primary btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalCatatan_<?= $h['idPeninjauan'] ?>">
                      <i class="bi bi-eye me-1"></i> Baca Catatan Dosen
                    </button>

                    <!-- Modal Pratinjau Catatan Dosen -->
                    <div class="modal fade" id="modalCatatan_<?= $h['idPeninjauan'] ?>" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                          <div class="modal-header bg-light">
                            <h6 class="modal-title fw-bold text-dark">
                              <i class="bi bi-clipboard-check text-primary me-2"></i>Catatan Evaluasi Dosen: <?= esc($h['judulProposal']) ?>
                            </h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <!-- Card 1 -->
                            <div class="card-catatan-admin p-3 rounded-3 border mb-3">
                              <h6 class="fw-bold text-primary mb-1"><i class="bi bi-file-earmark-check-fill me-1"></i> Card 1: Catatan Administrasi</h6>
                              <pre class="mb-0 text-dark small" style="white-space: pre-wrap; font-family: inherit;"><?= esc($h['catatanAdministrasi']) ?></pre>
                            </div>
                            <!-- Card 2 -->
                            <div class="card-catatan-substansi p-3 rounded-3 border">
                              <h6 class="fw-bold text-warning mb-1"><i class="bi bi-journal-text me-1"></i> Card 2: Catatan Substansi</h6>
                              <pre class="mb-0 text-dark small" style="white-space: pre-wrap; font-family: inherit;"><?= esc($h['catatanSubstansi']) ?></pre>
                            </div>
                          </div>
                          <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                      <?= esc($h['statusProposal']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
