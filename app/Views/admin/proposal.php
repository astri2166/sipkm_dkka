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

  <!-- Header & Tab Navigasi Proposal -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Pengelolaan Proposal & Sidang Pleno DKKA</h4>
      <p class="text-muted small mb-0">Plotting penugasan dosen peninjau (satuan & massal) serta penetapan status kelayakan pendanaan PKM.</p>
    </div>
    <!-- 2 Tab Utama Sesuai Spesifikasi Master Prompt -->
    <ul class="nav nav-pills shadow-sm p-1 bg-white rounded-3 border" id="proposalTabs" role="tablist">
      <li class="nav-item">
        <button class="nav-link active btn-sm fw-bold" id="tab-plotting-tab" data-bs-toggle="pill" data-bs-target="#tab-plotting" type="button" role="tab">
          <i class="bi bi-person-plus-fill me-1"></i> 1. Proposal & Plotting Dosen
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link btn-sm fw-bold" id="tab-rekap-tab" data-bs-toggle="pill" data-bs-target="#tab-rekap" type="button" role="tab">
          <i class="bi bi-award-fill me-1"></i> 2. Rekap & Penetapan Kelayakan
        </button>
      </li>
    </ul>
  </div>

  <div class="tab-content" id="proposalTabsContent">
    <!-- ============================================================== -->
    <!-- TAB 1: KELOLA PROPOSAL & PLOTTING DOSEN (SATUAN & MASSAL)     -->
    <!-- ============================================================== -->
    <div class="tab-pane fade show active" id="tab-plotting" role="tabpanel">
      <!-- Toolbar Aksi Massal & Filter -->
      <div class="sipkm-card p-3 mb-4 shadow-sm bg-light">
        <div class="row g-2 align-items-center">
          <div class="col-md-5">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
              <input type="text" id="searchProp" class="form-control" placeholder="Cari judul proposal, kelompok, atau NIM ketua..." onkeyup="filterProposal()">
            </div>
          </div>
          <div class="col-md-3">
            <select id="filterStatusProp" class="form-select form-select-sm" onchange="filterProposal()">
              <option value="">-- Semua Status --</option>
              <option value="Diunggah">Diunggah (Belum Diplotting)</option>
              <option value="Sedang Ditinjau">Sedang Ditinjau Dosen</option>
              <option value="Sudah Ditinjau">Sudah Ditinjau Dosen</option>
              <option value="Layak">Layak (Didanai)</option>
              <option value="Tidak Layak">Tidak Layak</option>
            </select>
          </div>
          <div class="col-md-4 text-md-end">
            <!-- Tombol Bulk Plotting Checklist -->
            <button class="btn btn-primary-sipkm btn-sm" id="btnBulkPlotting" type="button" onclick="openBulkPlottingModal()">
              <i class="bi bi-person-check-fill me-1"></i> Plotting Massal Terpilih (<span id="selectedCount">0</span>)
            </button>
          </div>
        </div>
      </div>

      <!-- Tabel Proposal -->
      <div class="sipkm-card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <span class="small text-muted" id="countPropText">Menampilkan <strong><?= count($daftarProposal) ?></strong> berkas proposal</span>
          <div class="form-check form-check-inline small mb-0">
            <input class="form-check-input" type="checkbox" id="checkAll" onchange="toggleCheckAll(this)">
            <label class="form-check-label fw-semibold" for="checkAll">Pilih Semua Proposal</label>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="tableProp">
            <thead class="table-light small">
              <tr>
                <th style="width: 40px;" class="text-center">#</th>
                <th>Skema & Judul Usulan</th>
                <th>Kelompok & Ketua</th>
                <th>Tautan Dokumen</th>
                <th>Dosen Peninjau Terplotting</th>
                <th>Status Proposal</th>
                <th class="text-center" style="width: 130px;">Aksi</th>
              </tr>
            </thead>
            <tbody class="small" id="tbodyProp">
              <?php if (empty($daftarProposal)): ?>
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Belum ada data usulan proposal yang masuk.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($daftarProposal as $p): ?>
                  <tr class="item-prop" data-id="<?= $p['idProposal'] ?>" data-judul="<?= esc($p['judulProposal']) ?>" data-status="<?= esc($p['statusProposal']) ?>" data-text="<?= esc(strtolower($p['judulProposal'] . ' ' . $p['namaKelompok'] . ' ' . $p['nimKetua'])) ?>">
                    <td class="text-center">
                      <input class="form-check-input prop-checkbox" type="checkbox" value="<?= $p['idProposal'] ?>" onchange="updateSelectedCount()">
                    </td>
                    <td>
                      <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($p['jenisPKM']) ?></span>
                      <div class="fw-bold text-dark"><?= esc($p['judulProposal']) ?></div>
                      <small class="text-muted">ID: <code>#<?= esc($p['idProposal']) ?></code> &bull; <?= esc($p['tglUnggah']) ?></small>
                    </td>
                    <td>
                      <div class="fw-semibold text-dark"><?= esc($p['namaKelompok']) ?></div>
                      <small class="text-muted">NIM Ketua: <?= esc($p['nimKetua']) ?></small>
                    </td>
                    <td>
                      <a href="<?= esc($p['tautanGoogleDocs']) ?>" target="_blank" class="btn btn-outline-danger btn-sm py-1 px-2" title="Buka Dokumen Google Docs">
                        <i class="bi bi-google me-1"></i> Buka Docs
                      </a>
                    </td>
                    <td>
                      <?php if (!empty($p['namaDosen'])): ?>
                        <div class="fw-semibold text-dark"><?= esc($p['namaDosen']) ?></div>
                        <small class="text-muted">NIP: <?= esc($p['nipDosen']) ?></small>
                      <?php else: ?>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                          <i class="bi bi-exclamation-circle me-1"></i> Belum Diplotting
                        </span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php
                        $bMap = [
                          'Diunggah'        => 'bg-info-subtle text-info-emphasis border-info-subtle',
                          'Sedang Ditinjau' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                          'Sudah Ditinjau'  => 'bg-primary-subtle text-primary border-primary-subtle',
                          'Layak'           => 'bg-success-subtle text-success border-success-subtle',
                          'Tidak Layak'     => 'bg-danger-subtle text-danger border-danger-subtle',
                        ];
                      ?>
                      <span class="badge <?= $bMap[$p['statusProposal']] ?? 'bg-secondary' ?> border">
                        <?= esc($p['statusProposal']) ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalPlottingSatuan_<?= $p['idProposal'] ?>" title="Plotting Satuan">
                        <i class="bi bi-person-plus me-1"></i> Plotting
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- TAB 2: HASIL REKAP & PENETAPAN KELAYAKAN AKHIR (PLENO DKKA)   -->
    <!-- ============================================================== -->
    <div class="tab-pane fade" id="tab-rekap" role="tabpanel">
      <div class="sipkm-card p-4 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
          <div>
            <h5 class="fw-bold text-dark mb-0">
              <i class="bi bi-award-fill text-warning me-2"></i>Sidang Pleno: Penetapan Kelayakan Usulan PKM
            </h5>
            <small class="text-muted">Periksa hasil penilaian Dosen Peninjau dan tetapkan status akhir: <strong>Layak (Didanai)</strong> atau <strong>Tidak Layak</strong></small>
          </div>
          <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-folder-check me-1"></i> Buka Master Rekap DKKA
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
              <tr>
                <th style="width: 40px;">No</th>
                <th>Skema & Judul Usulan</th>
                <th>Dosen Peninjau</th>
                <th>Hasil Rekap Penilaian</th>
                <th>Catatan Reviewer</th>
                <th>Status Saat Ini</th>
                <th class="text-center" style="width: 220px;">Penetapan Pleno DKKA</th>
              </tr>
            </thead>
            <tbody class="small">
              <?php 
                $rekapList = array_filter($daftarProposal, fn($p) => in_array($p['statusProposal'], ['Sudah Ditinjau', 'Layak', 'Tidak Layak']));
                if (empty($rekapList)): 
              ?>
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Belum ada berkas proposal yang telah selesai ditinjau oleh Dosen Peninjau.</td>
                </tr>
              <?php else: ?>
                <?php $no = 1; foreach ($rekapList as $prop): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td>
                      <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($prop['jenisPKM']) ?></span>
                      <div class="fw-bold text-dark"><?= esc($prop['judulProposal']) ?></div>
                      <small class="text-muted"><?= esc($prop['namaKelompok']) ?> &bull; NIM: <?= esc($prop['nimKetua']) ?></small>
                    </td>
                    <td>
                      <div class="fw-semibold text-dark"><?= esc($prop['namaDosen'] ?? 'Belum Ditugaskan') ?></div>
                      <small class="text-muted">Tgl Selesai: <?= esc($prop['tglSelesaiTinjau'] ?? '-') ?></small>
                    </td>
                    <td>
                      <?php $rekapLink = !empty($prop['tautanRekapDrive']) ? $prop['tautanRekapDrive'] : $masterTemplateUrl; ?>
                      <a href="<?= esc($rekapLink) ?>" target="_blank" class="btn btn-outline-success btn-sm py-1 px-2 fw-semibold">
                        <i class="bi bi-folder-check me-1"></i> Rekap Drive
                      </a>
                    </td>
                    <td>
                      <button type="button" class="btn btn-outline-secondary btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalCatatanDosen_<?= $prop['idProposal'] ?>">
                        <i class="bi bi-chat-left-text me-1"></i> Baca Catatan
                      </button>
                    </td>
                    <td>
                      <?php if ($prop['statusProposal'] === 'Layak'): ?>
                        <span class="badge bg-success text-white px-2 py-1"><i class="bi bi-check-circle me-1"></i> Layak (Didanai)</span>
                      <?php elseif ($prop['statusProposal'] === 'Tidak Layak'): ?>
                        <span class="badge bg-danger text-white px-2 py-1"><i class="bi bi-x-circle me-1"></i> Tidak Layak</span>
                      <?php else: ?>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Sudah Ditinjau</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <div class="d-flex gap-1 justify-content-center">
                        <form action="<?= base_url('admin/proposal/tetapkan-kelayakan') ?>" method="post" class="d-inline sipkm-form">
                          <?= csrf_field() ?>
                          <input type="hidden" name="idProposal" value="<?= $prop['idProposal'] ?>">
                          <input type="hidden" name="statusProposal" value="Layak">
                          <button type="submit" class="btn btn-success btn-sm py-1 px-2" onclick="return confirm('Tetapkan usulan proposal #<?= $prop['idProposal'] ?> sebagai LAYAK DIDANAI?');">
                            <i class="bi bi-check-lg me-1"></i> Layak
                          </button>
                        </form>
                        <form action="<?= base_url('admin/proposal/tetapkan-kelayakan') ?>" method="post" class="d-inline sipkm-form">
                          <?= csrf_field() ?>
                          <input type="hidden" name="idProposal" value="<?= $prop['idProposal'] ?>">
                          <input type="hidden" name="statusProposal" value="Tidak Layak">
                          <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Tetapkan usulan proposal #<?= $prop['idProposal'] ?> sebagai TIDAK LAYAK?');">
                            <i class="bi bi-x-lg me-1"></i> Tidak Layak
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================== -->
<!-- MODAL-MODAL DITEMPATKAN DI ROOT CONTAINER (BEBAS STACKING BUG) -->
<!-- ============================================================== -->

<!-- 1. Modal Bulk Plotting Massal -->
<div class="modal fade" id="modalBulkPlotting" tabindex="-1" aria-labelledby="modalBulkPlottingTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light">
        <h6 class="modal-title fw-bold text-dark" id="modalBulkPlottingTitle">
          <i class="bi bi-people-fill text-primary me-2"></i>Plotting Massal ke Dosen Peninjau
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('admin/proposal/bulk-plotting') ?>" method="post" class="sipkm-form" id="formBulkPlotting">
        <?= csrf_field() ?>
        <input type="hidden" name="selectedProposals" id="bulkSelectedInput" value="">
        <div class="modal-body p-4">
          <!-- 1. PILIH PROPOSAL LANGSUNG DI DALAM MODAL -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label small fw-bold text-dark mb-0">
                <i class="bi bi-file-earmark-check text-primary me-1"></i> 1. Pilih Proposal yang Ingin Ditugaskan:
              </label>
              <div class="form-check form-check-inline small mb-0">
                <input class="form-check-input" type="checkbox" id="modalCheckAll" onchange="toggleModalCheckAll(this)">
                <label class="form-check-label fw-semibold text-primary" for="modalCheckAll" style="cursor: pointer;">
                  Pilih Semua Proposal (<?= count($daftarProposal) ?>)
                </label>
              </div>
            </div>

            <div class="border rounded-3 p-2 bg-light" style="max-height: 180px; overflow-y: auto;" id="modalProposalBox">
              <?php foreach ($daftarProposal as $p): ?>
                <div class="form-check p-2 rounded mb-1 bg-white border border-light-subtle d-flex align-items-start gap-2 hover-shadow-sm">
                  <input class="form-check-input modal-prop-check mt-1" type="checkbox" value="<?= $p['idProposal'] ?>" id="modal_p_<?= $p['idProposal'] ?>" onchange="syncFromModalCheck(this)">
                  <label class="form-check-label small flex-grow-1" for="modal_p_<?= $p['idProposal'] ?>" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <span class="badge bg-primary-subtle text-primary"><?= esc($p['jenisPKM']) ?> &bull; #<?= esc($p['idProposal']) ?></span>
                      <?php
                        $bMap = [
                          'Diunggah'        => 'bg-info-subtle text-info-emphasis border-info-subtle',
                          'Sedang Ditinjau' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                          'Sudah Ditinjau'  => 'bg-primary-subtle text-primary border-primary-subtle',
                          'Layak'           => 'bg-success-subtle text-success border-success-subtle',
                          'Tidak Layak'     => 'bg-danger-subtle text-danger border-danger-subtle',
                        ];
                      ?>
                      <span class="badge <?= $bMap[$p['statusProposal']] ?? 'bg-secondary' ?> border small"><?= esc($p['statusProposal']) ?></span>
                    </div>
                    <div class="fw-bold text-dark"><?= esc($p['judulProposal']) ?></div>
                    <div class="text-muted small">Kelompok: <?= esc($p['namaKelompok']) ?> &bull; Ketua: <?= esc($p['nimKetua']) ?></div>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- 2. PILIH DOSEN PENINJAU -->
          <div class="mb-3">
            <label class="form-label small fw-bold text-dark">
              <i class="bi bi-person-badge text-primary me-1"></i> 2. Pilih Dosen Peninjau Penerima Penugasan <span class="text-danger">*</span>
            </label>
            <select name="idDosen" id="bulkSelectDosen" class="form-select" required>
              <option value="">-- Pilih Dosen Peninjau --</option>
              <?php foreach ($daftarDosen as $dos): ?>
                <option value="<?= esc($dos['idDosen']) ?>">
                  <?= esc($dos['namaDosen']) ?> (<?= esc($dos['nipDosen']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- 3. STATUS BAR REAL-TIME -->
          <div id="bulkStatusBar" class="alert alert-light border py-2 px-3 small d-flex align-items-center gap-2 mb-0">
            <i class="bi bi-info-circle text-primary fs-6 flex-shrink-0" id="bulkStatusIcon"></i>
            <div id="bulkStatusText">Centang minimal 1 proposal pada daftar di atas untuk mengaktifkan tombol penugasan massal.</div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary-sipkm btn-sm" id="btnSubmitBulk" data-loading-text="Menugaskan Massal..." disabled>
            <i class="bi bi-check2-all me-1"></i> Jalankan Plotting Massal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 2. Modal-Modal Plotting Satuan per Proposal -->
<?php foreach ($daftarProposal as $p): ?>
  <div class="modal fade text-start" id="modalPlottingSatuan_<?= $p['idProposal'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-light">
          <h6 class="modal-title fw-bold text-dark">
            <i class="bi bi-person-workspace text-primary me-2"></i>Tugaskan Dosen Peninjau
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url('admin/proposal/plotting') ?>" method="post" class="sipkm-form">
          <?= csrf_field() ?>
          <input type="hidden" name="idProposal" value="<?= $p['idProposal'] ?>">
          <div class="modal-body">
            <div class="mb-2">
              <label class="small text-muted mb-0">Judul Usulan Proposal:</label>
              <div class="fw-bold small text-dark"><?= esc($p['judulProposal']) ?></div>
              <small class="text-muted">Skema: <?= esc($p['jenisPKM']) ?> &bull; Kelompok: <?= esc($p['namaKelompok']) ?></small>
            </div>
            <div class="mb-3 mt-2">
              <label class="form-label small fw-semibold">Pilih Dosen Peninjau <span class="text-danger">*</span></label>
              <select name="idDosen" class="form-select" required>
                <option value="">-- Pilih Dosen Peninjau --</option>
                <?php foreach ($daftarDosen as $dos): ?>
                  <option value="<?= esc($dos['idDosen']) ?>" <?= ($p['idDosen'] ?? 0) == $dos['idDosen'] ? 'selected' : '' ?>>
                    <?= esc($dos['namaDosen']) ?> (<?= esc($dos['nipDosen']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="alert alert-info small mb-0">
              <i class="bi bi-info-circle me-1"></i> Setelah disimpan, data penugasan akan masuk ke <code>peninjauan_proposal</code> dan status usulan otomatis menjadi <strong>Sedang Ditinjau</strong>.
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menugaskan...">
              <i class="bi bi-check2 me-1"></i> Simpan Penugasan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<!-- 3. Modal Catatan Penilaian Dosen (Tab 2) -->
<?php foreach ($daftarProposal as $prop): ?>
  <div class="modal fade" id="modalCatatanDosen_<?= $prop['idProposal'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-light">
          <h6 class="modal-title fw-bold text-dark">
            <i class="bi bi-chat-left-quote-fill text-primary me-2"></i>Catatan Peninjauan Dosen Reviewer
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 pb-2 border-bottom">
            <h6 class="fw-bold mb-1"><?= esc($prop['judulProposal']) ?></h6>
            <div class="small text-muted">
              Dosen Peninjau: <strong><?= esc($prop['namaDosen'] ?? 'Belum Ditugaskan') ?></strong> 
              &bull; Tanggal Selesai: <?= esc($prop['tglSelesaiTinjau'] ?? 'Dalam Proses') ?>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <div class="border rounded-3 p-3 bg-light h-100">
                <div class="fw-bold text-dark mb-2">
                  <i class="bi bi-card-checklist text-primary me-1"></i> Card 1: Format & Administrasi
                </div>
                <div class="small text-secondary" style="white-space: pre-line;">
                  <?= !empty($prop['catatanAdministrasi']) ? esc($prop['catatanAdministrasi']) : 'Belum ada catatan administrasi dari dosen peninjau.' ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="border rounded-3 p-3 bg-light h-100">
                <div class="fw-bold text-dark mb-2">
                  <i class="bi bi-lightbulb text-warning me-1"></i> Card 2: Urgensi & Substansi Usulan
                </div>
                <div class="small text-secondary" style="white-space: pre-line;">
                  <?= !empty($prop['catatanSubstansi']) ? esc($prop['catatanSubstansi']) : 'Belum ada catatan substansi dari dosen peninjau.' ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<script>
// Fungsi Sinkronisasi Checkbox Utama & Modal
function updateSelectedCount() {
  const checkedTableBoxes = document.querySelectorAll('.prop-checkbox:checked');
  const selectedIds = Array.from(checkedTableBoxes).map(cb => cb.value);
  const selected = selectedIds.length;
  
  // 1. Update toolbar badge counter
  const selectedCountEl = document.getElementById('selectedCount');
  if (selectedCountEl) selectedCountEl.innerText = selected;
  
  // 2. Update hidden input untuk POST form
  const hiddenInput = document.getElementById('bulkSelectedInput');
  if (hiddenInput) hiddenInput.value = selectedIds.join(',');

  // 3. Sinkronisasi state checkbox di dalam modal
  document.querySelectorAll('.modal-prop-check').forEach(mcb => {
    mcb.checked = selectedIds.includes(mcb.value);
  });

  // 4. Update status master checkbox "Pilih Semua" di modal & tabel
  const allTableBoxes = document.querySelectorAll('.prop-checkbox');
  const checkAllTable = document.getElementById('checkAll');
  if (checkAllTable) {
    checkAllTable.checked = (allTableBoxes.length > 0 && selected === allTableBoxes.length);
  }
  const modalCheckAll = document.getElementById('modalCheckAll');
  if (modalCheckAll) {
    modalCheckAll.checked = (allTableBoxes.length > 0 && selected === allTableBoxes.length);
  }

  // 5. Update Status Bar & Tombol Submit di dalam Modal
  const statusBar = document.getElementById('bulkStatusBar');
  const statusIcon = document.getElementById('bulkStatusIcon');
  const statusText = document.getElementById('bulkStatusText');
  const submitBtn = document.getElementById('btnSubmitBulk');

  if (selected > 0) {
    if (statusBar) {
      statusBar.className = 'alert alert-success-subtle border border-success-subtle py-2 px-3 small text-success-emphasis d-flex align-items-center gap-2 mb-0';
    }
    if (statusIcon) {
      statusIcon.className = 'bi bi-check-circle-fill text-success fs-6 flex-shrink-0';
    }
    if (statusText) {
      statusText.innerHTML = `Siap menugaskan <strong>${selected} berkas proposal</strong> ke Dosen Peninjau terpilih.`;
    }
    if (submitBtn) {
      submitBtn.removeAttribute('disabled');
    }
  } else {
    if (statusBar) {
      statusBar.className = 'alert alert-light border py-2 px-3 small text-muted d-flex align-items-center gap-2 mb-0';
    }
    if (statusIcon) {
      statusIcon.className = 'bi bi-info-circle text-primary fs-6 flex-shrink-0';
    }
    if (statusText) {
      statusText.innerText = 'Centang minimal 1 proposal pada daftar di atas untuk mengaktifkan tombol penugasan massal.';
    }
    if (submitBtn) {
      submitBtn.setAttribute('disabled', 'disabled');
    }
  }
}

// Handler saat checkbox di dalam modal diklik
function syncFromModalCheck(source) {
  const tableBox = document.querySelector(`.prop-checkbox[value="${source.value}"]`);
  if (tableBox) {
    tableBox.checked = source.checked;
  }
  updateSelectedCount();
}

// Handler toggle "Pilih Semua" dari dalam modal
function toggleModalCheckAll(source) {
  document.querySelectorAll('.prop-checkbox').forEach(cb => {
    cb.checked = source.checked;
  });
  updateSelectedCount();
}

// Handler toggle "Pilih Semua" dari tabel
function toggleCheckAll(source) {
  document.querySelectorAll('.prop-checkbox').forEach(cb => {
    cb.checked = source.checked;
  });
  updateSelectedCount();
}

// Buka Modal Plotting Massal
function openBulkPlottingModal() {
  updateSelectedCount();
  const modalEl = document.getElementById('modalBulkPlotting');
  if (modalEl) {
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  }
}

// Filter tabel proposal
function filterProposal() {
  const q = document.getElementById('searchProp').value.toLowerCase().trim();
  const st = document.getElementById('filterStatusProp').value;
  const rows = document.querySelectorAll('#tbodyProp tr.item-prop');
  let count = 0;

  rows.forEach(r => {
    const matchText = !q || (r.dataset.text && r.dataset.text.includes(q));
    const matchStatus = !st || r.dataset.status === st;
    const show = matchText && matchStatus;
    r.classList.toggle('d-none', !show);
    if (show) count++;
  });

  const countText = document.getElementById('countPropText');
  if (countText) {
    countText.innerHTML = `Menampilkan <strong>${count}</strong> berkas proposal`;
  }
}

// Inisialisasi awal saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
  updateSelectedCount();
});
</script>
<?= $this->endSection() ?>
