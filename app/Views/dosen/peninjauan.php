<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Header & Aksi Cepat Master Template -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Daftar Penugasan Peninjauan Proposal PKM</h4>
      <p class="text-muted small mb-0">Evaluasi administrasi, substansi, dan rekapitulasi penilaian proposal mahasiswa Universitas Budi Luhur.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?= esc($masterTemplateUrl) ?>" target="_blank" class="btn btn-warning text-dark btn-sm fw-bold shadow-sm">
        <i class="bi bi-folder-symlink me-1"></i> Buka Master Template DKKA (Google Drive)
      </a>
    </div>
  </div>

  <!-- Filter & Search Card -->
  <div class="sipkm-card p-3 mb-4 shadow-sm">
    <div class="row g-3 align-items-center">
      <!-- Search Input -->
      <div class="col-md-5">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
          <input type="text" id="searchTugas" class="form-control border-start-0" placeholder="Cari judul proposal, kelompok, atau NIM ketua..." onkeyup="filterTugasTable()">
        </div>
      </div>
      <!-- Filter Status Penugasan -->
      <div class="col-md-4">
        <select id="filterStatus" class="form-select form-select-sm" onchange="filterTugasTable()">
          <option value="">-- Semua Status Penugasan --</option>
          <option value="Belum Ditinjau" <?= ($statusFilter === 'Belum Ditinjau') ? 'selected' : '' ?>>Belum Ditinjau (Perlu Tindakan)</option>
          <option value="Selesai" <?= ($statusFilter === 'Selesai') ? 'selected' : '' ?>>Selesai (Sudah Dinilai)</option>
        </select>
      </div>
      <!-- Filter Skema PKM -->
      <div class="col-md-3">
        <select id="filterSkema" class="form-select form-select-sm" onchange="filterTugasTable()">
          <option value="">-- Semua Skema PKM --</option>
          <option value="PKM-KC">PKM-KC (Karsa Cipta)</option>
          <option value="PKM-RE">PKM-RE (Riset Eksakta)</option>
          <option value="PKM-K">PKM-K (Kewirausahaan)</option>
          <option value="PKM-PM">PKM-PM (Pengabdian)</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Skeleton Shimmer Placeholder saat Filter -->
  <div id="skeletonTugas" class="d-none">
    <div class="sipkm-card p-4 shadow-sm mb-4">
      <div class="skeleton-shimmer skeleton-line mb-3" style="width: 40%; height: 24px;"></div>
      <div class="skeleton-shimmer skeleton-line mb-2" style="height: 40px;"></div>
      <div class="skeleton-shimmer skeleton-line mb-2" style="height: 40px;"></div>
      <div class="skeleton-shimmer skeleton-line" style="height: 40px;"></div>
    </div>
  </div>

  <!-- Tabel Utama Daftar Tugas Proposal -->
  <div id="contentTugas" class="sipkm-card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <span class="small text-muted" id="countTugasText">Menampilkan <strong><?= count($daftarTugas) ?></strong> proposal ditugaskan</span>
      <div class="small text-muted">Periode Akademik: <strong>2026</strong></div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="tablePenugasan">
        <thead class="table-light small">
          <tr>
            <th style="width: 50px;">No</th>
            <th>Skema & Judul Usulan Proposal</th>
            <th>Tim Pengusul</th>
            <th>Tgl Tugas</th>
            <th>Status Penugasan</th>
            <th>Status Proposal</th>
            <th>Berkas Link</th>
            <th class="text-center" style="width: 140px;">Aksi</th>
          </tr>
        </thead>
        <tbody class="small" id="tbodyTugas">
          <?php $no = 1; foreach ($daftarTugas as $item): ?>
            <tr class="item-row" data-status="<?= esc($item['statusPenugasan']) ?>" data-skema="<?= esc($item['jenisPKM']) ?>" data-text="<?= esc(strtolower($item['judulProposal'] . ' ' . $item['namaKelompok'] . ' ' . $item['nimKetua'])) ?>">
              <td class="text-center"><?= $no++ ?></td>
              <td>
                <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($item['jenisPKM']) ?></span>
                <div class="fw-bold text-dark"><?= esc($item['judulProposal']) ?></div>
                <small class="text-muted">ID Proposal: <code>#<?= esc($item['idProposal']) ?></code></small>
              </td>
              <td>
                <div class="fw-semibold text-dark"><?= esc($item['namaKelompok']) ?></div>
                <small class="text-muted">Ketua: <?= esc($item['nimKetua']) ?></small>
              </td>
              <td>
                <span class="text-muted d-block"><?= esc($item['tglPenugasan']) ?></span>
                <?php if ($item['tglSelesaiTinjau']): ?>
                  <small class="text-success"><i class="bi bi-check2"></i> <?= esc($item['tglSelesaiTinjau']) ?></small>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($item['statusPenugasan'] === 'Selesai'): ?>
                  <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                    <i class="bi bi-check-circle-fill me-1"></i> Selesai
                  </span>
                <?php else: ?>
                  <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                    <i class="bi bi-clock me-1"></i> Belum Ditinjau
                  </span>
                <?php endif; ?>
              </td>
              <td>
                <?php
                  $badgeProposal = [
                    'Diunggah'        => 'bg-info-subtle text-info-emphasis',
                    'Sedang Ditinjau' => 'bg-warning-subtle text-warning-emphasis',
                    'Sudah Ditinjau'  => 'bg-primary-subtle text-primary',
                    'Layak'           => 'bg-success-subtle text-success',
                    'Tidak Layak'     => 'bg-danger-subtle text-danger',
                  ];
                ?>
                <span class="badge <?= $badgeProposal[$item['statusProposal']] ?? 'bg-secondary' ?> border">
                  <?= esc($item['statusProposal']) ?>
                </span>
              </td>
              <td>
                <div class="d-flex flex-column gap-1">
                  <a href="<?= esc($item['tautanGoogleDocs']) ?>" target="_blank" class="small text-decoration-none text-danger" title="Buka Berkas Google Docs Proposal">
                    <i class="bi bi-google me-1"></i>Proposal
                  </a>
                  <?php if (!empty($item['tautanRekapDrive'])): ?>
                    <a href="<?= esc($item['tautanRekapDrive']) ?>" target="_blank" class="small text-decoration-none text-success" title="Buka Tautan Rekap Dosen">
                      <i class="bi bi-folder-check me-1"></i>Rekap
                    </a>
                  <?php else: ?>
                    <span class="text-muted" style="font-size: 11px;">(Belum ada rekap)</span>
                  <?php endif; ?>
                </div>
              </td>
              <td class="text-center">
                <a href="<?= base_url('dosen/peninjauan/detail/' . $item['idPeninjauan']) ?>" class="btn <?= $item['statusPenugasan'] === 'Selesai' ? 'btn-outline-secondary' : 'btn-primary-sipkm' ?> btn-sm">
                  <?php if ($item['statusPenugasan'] === 'Selesai'): ?>
                    <i class="bi bi-eye me-1"></i> Rincian
                  <?php else: ?>
                    <i class="bi bi-pencil-square me-1"></i> Evaluasi
                  <?php endif; ?>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Empty State -->
    <div id="emptyTugas" class="text-center py-5 d-none">
      <i class="bi bi-search display-6 text-muted mb-2 d-block"></i>
      <h6 class="fw-bold text-dark">Tidak ada proposal yang sesuai kriteria filter</h6>
      <small class="text-muted">Coba ubah kata kunci pencarian atau reset filter status/skema.</small>
    </div>
  </div>
</div>

<script>
function filterTugasTable() {
  const query = document.getElementById('searchTugas').value.toLowerCase().trim();
  const status = document.getElementById('filterStatus').value;
  const skema = document.getElementById('filterSkema').value;
  const rows = document.querySelectorAll('#tbodyTugas tr.item-row');
  let visibleCount = 0;

  rows.forEach(row => {
    const textMatch = !query || row.dataset.text.includes(query);
    const statusMatch = !status || row.dataset.status === status;
    const skemaMatch = !skema || row.dataset.skema === skema;
    const show = textMatch && statusMatch && skemaMatch;

    row.classList.toggle('d-none', !show);
    if (show) visibleCount++;
  });

  document.getElementById('emptyTugas').classList.toggle('d-none', visibleCount > 0);
  document.getElementById('countTugasText').innerHTML = `Menampilkan <strong>${visibleCount}</strong> proposal ditugaskan`;
}
</script>
<?= $this->endSection() ?>
