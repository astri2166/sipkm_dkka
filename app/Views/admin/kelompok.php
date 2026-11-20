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

  <!-- Header Pengelolaan Kelompok -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
      <h4 class="fw-bold text-dark mb-1">Pengelolaan Kelompok PKM</h4>
      <p class="text-muted small mb-0">Pantau dan kelola seluruh tim mahasiswa yang terdaftar pada program PKM Universitas Budi Luhur.</p>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="sipkm-card p-3 mb-4 shadow-sm bg-light">
    <div class="row g-2 align-items-center">
      <div class="col-md-5">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
          <input type="text" id="searchKelompok" class="form-control" placeholder="Cari nama kelompok, NIM ketua, atau prodi..." onkeyup="filterKelompok()">
        </div>
      </div>
      <div class="col-md-3">
        <select id="filterPeriode" class="form-select form-select-sm" onchange="filterKelompok()">
          <option value="">-- Semua Periode --</option>
          <option value="2026" selected>Tahun Akademik 2026</option>
          <option value="2025">Tahun Akademik 2025</option>
        </select>
      </div>
      <div class="col-md-4">
        <select id="filterSkemaKelompok" class="form-select form-select-sm" onchange="filterKelompok()">
          <option value="">-- Semua Skema PKM --</option>
          <option value="PKM-RE">PKM-RE (Riset Eksakta)</option>
          <option value="PKM-RSH">PKM-RSH (Riset Sosial Humaniora)</option>
          <option value="PKM-PM">PKM-PM (Pengabdian kepada Masyarakat)</option>
          <option value="PKM-PI">PKM-PI (Penerapan IPTEK)</option>
          <option value="PKM-K">PKM-K (Kewirausahaan)</option>
          <option value="PKM-KC">PKM-KC (Karsa Cipta)</option>
          <option value="PKM-KI">PKM-KI (Karya Inovatif)</option>
          <option value="PKM-VGK">PKM-VGK (Video Gagasan Konstruktif)</option>
          <option value="PKM-AI">PKM-AI (Artikel Ilmiah)</option>
          <option value="PKM-GFT">PKM-GFT (Gagasan Futuristik Tertulis)</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Tabel Kelompok PKM -->
  <div class="sipkm-card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <span class="small text-muted" id="countKelompokText">Menampilkan <strong><?= count($daftarKelompok) ?></strong> kelompok terdaftar</span>
      <div class="small text-muted">Syarat Anggota: <strong>3 - 5 Mahasiswa</strong></div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="tableKelompok">
        <thead class="table-light small">
          <tr>
            <th style="width: 50px;">No</th>
            <th>Nama Kelompok PKM</th>
            <th>Periode</th>
            <th>Ketua Kelompok (NIM)</th>
            <th>Total Anggota</th>
            <th>Judul Usulan Proposal</th>
            <th class="text-center" style="width: 150px;">Aksi</th>
          </tr>
        </thead>
        <tbody class="small" id="tbodyKelompok">
          <?php $no = 1; foreach ($daftarKelompok as $k): ?>
            <tr class="item-kelompok" data-periode="<?= esc($k['tahunPeriode']) ?>" data-skema="<?= esc($k['jenisPKM'] ?? '') ?>" data-text="<?= esc(strtolower($k['namaKelompok'] . ' ' . $k['nimKetua'] . ' ' . $k['namaKetua'])) ?>">
              <td class="text-center"><?= $no++ ?></td>
              <td>
                <div class="fw-bold text-dark"><?= esc($k['namaKelompok']) ?></div>
                <small class="text-muted">ID: <code>#<?= esc($k['idKelompok']) ?></code></small>
              </td>
              <td><span class="badge bg-secondary-subtle text-secondary"><?= esc($k['tahunPeriode']) ?></span></td>
              <td>
                <div class="fw-semibold text-dark"><?= esc($k['namaKetua']) ?></div>
                <code class="text-primary"><?= esc($k['nimKetua']) ?></code>
              </td>
              <td>
                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                  <i class="bi bi-people-fill me-1"></i><?= esc($k['jumlahAnggota']) ?> Orang
                </span>
              </td>
              <td>
                <?php if (!empty($k['judulProposal'])): ?>
                  <span class="badge bg-primary-subtle text-primary mb-1"><?= esc($k['jenisPKM']) ?></span>
                  <div class="small text-truncate" style="max-width: 250px;" title="<?= esc($k['judulProposal']) ?>">
                    <?= esc($k['judulProposal']) ?>
                  </div>
                <?php else: ?>
                  <span class="text-muted fst-italic">Belum mengunggah</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <!-- Tombol Lihat Anggota -->
                <button class="btn btn-outline-primary btn-sm py-1 px-2 me-1" data-bs-toggle="modal" data-bs-target="#modalAnggota_<?= $k['idKelompok'] ?>" title="Lihat Anggota Tim">
                  <i class="bi bi-person-lines-fill"></i>
                </button>
                <a href="<?= base_url('admin/kelompok/hapus/' . $k['idKelompok']) ?>" class="btn btn-outline-danger btn-sm py-1 px-2" title="Bubarkan Kelompok" onclick="return confirm('Apakah Anda yakin ingin membubarkan kelompok <?= esc($k['namaKelompok']) ?>?');">
                  <i class="bi bi-trash"></i>
                </a>

                <!-- Modal Lihat Anggota Tim -->
                <div class="modal fade" id="modalAnggota_<?= $k['idKelompok'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                      <div class="modal-header bg-light">
                        <h6 class="modal-title fw-bold text-dark">
                          <i class="bi bi-people-fill text-primary me-2"></i>Susunan Anggota: <?= esc($k['namaKelompok']) ?>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body p-0">
                        <div class="table-responsive">
                          <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                              <tr>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Program Studi</th>
                                <th>Peran</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($k['anggota'] as $ang): ?>
                                <tr>
                                  <td class="fw-bold font-monospace"><?= esc($ang['nim']) ?></td>
                                  <td><?= esc($ang['namaMahasiswa']) ?></td>
                                  <td><?= esc($ang['programStudi']) ?></td>
                                  <td>
                                    <?php if ($ang['nim'] === $k['nimKetua']): ?>
                                      <span class="badge bg-primary">Ketua</span>
                                    <?php else: ?>
                                      <span class="badge bg-secondary-subtle text-secondary">Anggota</span>
                                    <?php endif; ?>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                      </div>
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

<script>
function filterKelompok() {
  const query = document.getElementById('searchKelompok').value.toLowerCase().trim();
  const periode = document.getElementById('filterPeriode').value;
  const skema = document.getElementById('filterSkemaKelompok').value;
  const rows = document.querySelectorAll('#tbodyKelompok tr.item-kelompok');
  let count = 0;

  rows.forEach(r => {
    const matchText = !query || r.dataset.text.includes(query);
    const matchPeriode = !periode || r.dataset.periode === periode;
    const matchSkema = !skema || r.dataset.skema === skema;
    const show = matchText && matchPeriode && matchSkema;
    r.classList.toggle('d-none', !show);
    if (show) count++;
  });

  document.getElementById('countKelompokText').innerHTML = `Menampilkan <strong>${count}</strong> kelompok terdaftar`;
}
</script>
<?= $this->endSection() ?>
