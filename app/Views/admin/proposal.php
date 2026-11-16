<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
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
            </select>
          </div>
          <div class="col-md-4 text-md-end">
            <!-- Tombol Bulk Plotting Checklist -->
            <button class="btn btn-primary-sipkm btn-sm" id="btnBulkPlotting" data-bs-toggle="modal" data-bs-target="#modalBulkPlotting">
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
              <?php foreach ($daftarProposal as $p): ?>
                <tr class="item-prop" data-status="<?= esc($p['statusProposal']) ?>" data-text="<?= esc(strtolower($p['judulProposal'] . ' ' . $p['namaKelompok'] . ' ' . $p['nimKetua'])) ?>">
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
                    <button class="btn btn-outline-primary btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalPlottingSatuan_<?= $p['idProposal'] ?>" title="Plotting Satuan">
                      <i class="bi bi-person-plus me-1"></i> Plotting
                    </button>

                    <!-- Modal Plotting Satuan -->
                    <div class="modal fade text-start" id="modalPlottingSatuan_<?= $p['idProposal'] ?>" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                          <div class="modal-header bg-light">
                            <h6 class="modal-title fw-bold text-dark">
                              <i class="bi bi-person-workspace text-primary me-2"></i>Tugaskan Dosen Peninjau
                            </h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form class="sipkm-form" onsubmit="event.preventDefault(); showSipkmToast('Proposal #<?= $p['idProposal'] ?> berhasil di-plotting ke Dosen Peninjau! Status berubah menjadi: Sedang Ditinjau.', 'success'); bootstrap.Modal.getInstance(document.getElementById('modalPlottingSatuan_<?= $p['idProposal'] ?>')).hide();">
                            <div class="modal-body">
                              <div class="mb-2">
                                <label class="small text-muted">Judul Proposal:</label>
                                <div class="fw-bold small text-dark"><?= esc($p['judulProposal']) ?></div>
                              </div>
                              <div class="mb-3">
                                <label class="form-label small fw-semibold">Pilih Dosen Peninjau <span class="text-danger">*</span></label>
                                <select class="form-select" required>
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
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Bulk Plotting Massal -->
    <div class="modal fade" id="modalBulkPlotting" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-light">
            <h6 class="modal-title fw-bold text-dark">
              <i class="bi bi-people-fill text-primary me-2"></i>Plotting Massal ke Dosen Peninjau
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form class="sipkm-form" onsubmit="event.preventDefault(); showSipkmToast('Proposal terpilih berhasil di-plotting massal! Status usulan telah diperbarui.', 'success'); bootstrap.Modal.getInstance(document.getElementById('modalBulkPlotting')).hide();">
            <div class="modal-body">
              <p class="small text-muted">
                Anda memilih <strong id="modalSelectedCount">0</strong> proposal untuk ditugaskan secara serentak ke satu Dosen Peninjau.
              </p>
              <div class="mb-3">
                <label class="form-label small fw-semibold">Pilih Dosen Peninjau <span class="text-danger">*</span></label>
                <select class="form-select" required>
                  <option value="">-- Pilih Dosen Peninjau --</option>
                  <?php foreach ($daftarDosen as $dos): ?>
                    <option value="<?= esc($dos['idDosen']) ?>">
                      <?= esc($dos['namaDosen']) ?> (<?= esc($dos['nipDosen']) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary-sipkm btn-sm" data-loading-text="Menugaskan Massal...">
                <i class="bi bi-check2-all me-1"></i> Jalankan Plotting Massal
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- TAB 2: HASIL REKAP & PENETAPAN KELAYAKAN AKHIR (PLENO DKKA)   -->
    <!-- ============================================================== -->
    <div class="tab-pane fade" id="tab-rekap" role="tabpanel">
      <div class="sipkm-card p-4 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
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
                <th>No</th>
                <th>Skema & Judul Usulan</th>
                <th>Dosen Peninjau</th>
                <th>Hasil Rekap Dosen</th>
                <th>Catatan Card 1 & 2</th>
                <th>Status Saat Ini</th>
                <th class="text-center" style="width: 220px;">Penetapan Pleno DKKA</th>
              </tr>
            </thead>
            <tbody class="small">
              <?php $no = 1; foreach ($daftarProposal as $prop): if (in_array($prop['statusProposal'], ['Sudah Ditinjau', 'Layak', 'Tidak Layak'])): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($prop['jenisPKM']) ?></span>
                    <div class="fw-bold text-dark"><?= esc($prop['judulProposal']) ?></div>
                    <small class="text-muted"><?= esc($prop['namaKelompok']) ?></small>
                  </td>
                  <td>
                    <div class="fw-semibold text-dark"><?= esc($prop['namaDosen'] ?? 'Dr. Ahmad Fauzi, M.T.') ?></div>
                    <small class="text-muted">Selesai: 2026-11-14</small>
                  </td>
                  <td>
                    <a href="https://drive.google.com/drive/folders/1HasilRekapPenilaianDosenUBL2026" target="_blank" class="btn btn-outline-success btn-sm py-1 px-2 fw-semibold">
                      <i class="bi bi-folder-check me-1"></i> Rekap Drive
                    </a>
                  </td>
                  <td>
                    <button class="btn btn-outline-secondary btn-sm py-1 px-2" onclick="showSipkmToast('Catatan Dosen:\n1. Administrasi: Margin 4cm sesuai, perbaiki lembar pengesahan.\n2. Substansi: Metodologi IoT relevan SDG 11.', 'info')">
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
                    <div class="btn-group btn-group-sm">
                      <button class="btn btn-success btn-sm" onclick="showSipkmToast('Proposal #<?= $prop['idProposal'] ?> ditetapkan: LAYAK DIDANAI!', 'success')">
                        <i class="bi bi-check-lg me-1"></i> Layak
                      </button>
                      <button class="btn btn-outline-danger btn-sm" onclick="showSipkmToast('Proposal #<?= $prop['idProposal'] ?> ditetapkan: TIDAK LAYAK.', 'warning')">
                        <i class="bi bi-x-lg me-1"></i> Tidak Layak
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endif; endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function updateSelectedCount() {
  const selected = document.querySelectorAll('.prop-checkbox:checked').length;
  document.getElementById('selectedCount').innerText = selected;
  document.getElementById('modalSelectedCount').innerText = selected;
}

function toggleCheckAll(source) {
  document.querySelectorAll('.prop-checkbox').forEach(cb => {
    cb.checked = source.checked;
  });
  updateSelectedCount();
}

function filterProposal() {
  const q = document.getElementById('searchProp').value.toLowerCase().trim();
  const st = document.getElementById('filterStatusProp').value;
  const rows = document.querySelectorAll('#tbodyProp tr.item-prop');
  let count = 0;

  rows.forEach(r => {
    const matchText = !q || r.dataset.text.includes(q);
    const matchStatus = !st || r.dataset.status === st;
    const show = matchText && matchStatus;
    r.classList.toggle('d-none', !show);
    if (show) count++;
  });

  document.getElementById('countPropText').innerHTML = `Menampilkan <strong>${count}</strong> berkas proposal`;
}
</script>
<?= $this->endSection() ?>
