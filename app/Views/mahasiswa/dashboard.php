<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success small py-2 px-3 mb-3 d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm">
      <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
      <div><?= session()->getFlashdata('success') ?></div>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger small py-2 px-3 mb-3 d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm">
      <i class="bi bi-exclamation-triangle-fill text-danger flex-shrink-0"></i>
      <div><?= session()->getFlashdata('error') ?></div>
    </div>
  <?php endif; ?>

  <!-- 1. Top Banner Mahasiswa -->
  <div class="banner-admin-navy mb-4 shadow-sm">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge px-3 py-1 rounded-pill fw-bold" style="background: #fbbf24; color: #112842; font-size: 0.78rem;">
            <i class="bi bi-person-fill me-1"></i> Mode: <?= esc($role) ?>
          </span>
          <span class="badge rounded-pill text-white border border-white-50 opacity-75" style="font-size: 0.78rem;">
            Tahun Akademik <?= esc($kelompok['tahunPeriode'] ?? '2026') ?>
          </span>
        </div>
        <h4 class="fw-bold text-white mb-1">Selamat Datang, <?= esc($mahasiswa['namaMahasiswa'] ?? 'Mahasiswa UBL') ?>!</h4>
        <small class="text-white-50" style="font-size: 0.85rem;">
          NIM: <strong><?= esc($mahasiswa['nim'] ?? '2412501001') ?></strong> &bull; 
          Program Studi: <strong><?= esc($mahasiswa['programStudi'] ?? 'Sistem Informasi') ?></strong> &bull; 
          Universitas Budi Luhur
        </small>
      </div>
      <div>
        <?php if ($hasKelompok): ?>
          <a href="<?= base_url('mahasiswa/tracking') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 fw-semibold" style="border-color: rgba(255,255,255,0.3); font-size: 0.82rem;">
            <i class="bi bi-activity me-1"></i> Tracking Status Proposal
          </a>
        <?php else: ?>
          <a href="<?= base_url('mahasiswa/kelompok') ?>" class="btn btn-warning text-dark btn-sm rounded-pill px-3 py-2 fw-bold" style="font-size: 0.82rem;">
            <i class="bi bi-plus-circle me-1"></i> Bentuk Kelompok PKM
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- 2. Row of 4 Stat Cards -->
  <div class="row g-3 mb-4">
    <!-- Stat 1: Status Kelompok (Deep Navy) -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-navy h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small text-white-50">Status Kelompok</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: #fbbf24; color: #112842;">
            <i class="bi bi-people fs-5"></i>
          </div>
        </div>
        <div>
          <?php if ($hasKelompok): ?>
            <div class="fw-bold text-white mb-1 text-truncate" style="font-size: 1.4rem; line-height: 1.2;">
              <?= esc($kelompok['namaKelompok']) ?>
            </div>
            <span class="pill-badge-blue mt-1">
              <i class="bi bi-check-circle-fill me-1"></i> Terdaftar
            </span>
          <?php else: ?>
            <div class="fw-bold text-white-50 mb-1" style="font-size: 1.3rem; line-height: 1.2;">
              Belum Ada Tim
            </div>
            <small class="text-white-50" style="font-size: 0.82rem;">Silakan bentuk / gabung kelompok</small>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Stat 2: Status Proposal PKM (Deep Navy) -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-navy h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small text-white-50">Status Proposal</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255,255,255,0.14); color: #ffffff;">
            <i class="bi bi-file-earmark-text fs-5"></i>
          </div>
        </div>
        <div>
          <?php if ($hasProposal): ?>
            <div class="fw-bold text-white mb-1" style="font-size: 1.4rem; line-height: 1.2;">
              <?= esc($proposal['jenisPKM']) ?>
            </div>
            <span class="pill-badge-blue mt-1">
              <?= esc($proposal['statusProposal']) ?>
            </span>
          <?php else: ?>
            <div class="fw-bold text-white-50 mb-1" style="font-size: 1.3rem; line-height: 1.2;">
              Belum Diunggah
            </div>
            <small class="text-white-50" style="font-size: 0.82rem;">Draft proposal kosong</small>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Stat 3: Jumlah Anggota Tim (Pastel Blue) -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-pastel h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small fw-semibold" style="color: #475569;">Jumlah Anggota</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; background: #ffffff; color: #112842;">
            <i class="bi bi-person-check fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold mb-1" style="font-size: 2.2rem; line-height: 1.1; color: #112842;">
            <?= $hasKelompok ? (int)($kelompok['jumlahAnggota'] ?? count($daftarAnggota)) : 0 ?>
          </div>
          <small class="fw-medium" style="font-size: 0.82rem; color: #475569;">
            Mahasiswa (Syarat: 3-5 Mhs)
          </small>
        </div>
      </div>
    </div>

    <!-- Stat 4: Dosen Peninjau (Pastel Blue) -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card-stat-pastel h-100 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="small fw-semibold" style="color: #475569;">Dosen Peninjau</span>
          <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; background: #ffffff; color: #112842;">
            <i class="bi bi-person-workspace fs-5"></i>
          </div>
        </div>
        <div>
          <div class="fw-bold mb-1 text-truncate" style="font-size: 1.25rem; line-height: 1.2; color: #112842;">
            <?= esc($dosen['namaDosen'] ?? 'Belum Diplot') ?>
          </div>
          <small class="fw-medium" style="font-size: 0.82rem; color: #475569;">
            <?= !empty($dosen) ? 'DKKA Terverifikasi' : 'Menunggu Plotting DKKA' ?>
          </small>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Kolom Kiri: Proposal Aktif & Quick Actions -->
    <div class="col-lg-8">
      <!-- Card Status Proposal Utama -->
      <div class="sipkm-card p-4 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <h6 class="fw-bold mb-0" style="color: #112842;">
            <i class="bi bi-file-earmark-check text-primary me-2"></i>Usulan Proposal PKM Aktif
          </h6>
          <?php if ($hasProposal): ?>
            <span class="pill-badge-blue">
              <?= esc($proposal['jenisPKM']) ?>
            </span>
          <?php endif; ?>
        </div>

        <?php if ($hasProposal): ?>
          <div class="p-3 rounded-3 mb-3 border" style="background: #f8fafc;">
            <h6 class="fw-bold text-dark mb-2"><?= esc($proposal['judulProposal']) ?></h6>
            <div class="d-flex flex-wrap gap-3 text-muted small mb-3">
              <span><i class="bi bi-calendar3 me-1"></i> Tgl Unggah: <strong><?= esc($proposal['tglUnggah']) ?></strong></span>
              <span><i class="bi bi-flag-fill me-1 text-warning"></i> Status: <strong><?= esc($proposal['statusProposal']) ?></strong></span>
              <span><i class="bi bi-person-fill me-1"></i> Ketua: <strong><?= esc($kelompok['nimKetua']) ?></strong></span>
            </div>

            <!-- Pratinjau Tautan Google Docs -->
            <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded border">
              <div class="d-flex align-items-center gap-2 text-truncate me-2">
                <i class="bi bi-google text-danger fs-5"></i>
                <span class="small text-truncate font-monospace text-muted"><?= esc($proposal['tautanGoogleDocs']) ?></span>
              </div>
              <a href="<?= esc($proposal['tautanGoogleDocs']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-nowrap">
                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Proposal
              </a>
            </div>
          </div>

          <!-- Aksi Cepat Terkait Proposal -->
          <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('mahasiswa/tracking') ?>" class="btn btn-primary-sipkm btn-sm rounded-pill px-3 py-2">
              <i class="bi bi-activity me-1"></i> Lihat Tracking &amp; Catatan Peninjau
            </a>
            <?php if ($role === 'Ketua Kelompok'): ?>
              <a href="<?= base_url('mahasiswa/proposal') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2">
                <i class="bi bi-pencil-square me-1"></i> Perbarui Tautan Proposal
              </a>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <!-- State Belum Ada Proposal -->
          <div class="text-center py-4">
            <div class="rounded-circle d-inline-flex p-3 mb-3" style="background: #fef3c7; color: #92400e;">
              <i class="bi bi-file-earmark-plus display-6"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1">Belum Ada Proposal PKM yang Diajukan</h6>
            <p class="text-muted small mb-3">Kelompokmu belum mengunggah tautan berkas proposal PKM ke sistem DKKA.</p>
            <?php if ($role === 'Ketua Kelompok'): ?>
              <a href="<?= base_url('mahasiswa/proposal') ?>" class="btn btn-primary-sipkm btn-sm rounded-pill px-4 py-2">
                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Mulai Input Proposal Sekarang
              </a>
            <?php else: ?>
              <div class="alert alert-info d-inline-block small mb-0 rounded-3">
                <i class="bi bi-info-circle me-1"></i> Pengunggahan proposal hanya dapat dilakukan oleh akun <strong>Ketua Kelompok</strong>.
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Card Anggota Tim PKM -->
      <div class="sipkm-card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
          <div>
            <h6 class="fw-bold mb-0" style="color: #112842;">
              <i class="bi bi-people text-primary me-2"></i>Susunan Anggota Kelompok
            </h6>
            <small class="text-muted"><?= esc($kelompok['namaKelompok'] ?? 'Belum ada kelompok') ?></small>
          </div>
          <a href="<?= base_url('mahasiswa/kelompok') ?>" class="small text-decoration-none fw-semibold" style="color: #112842;">
            Kelola Tim &rarr;
          </a>
        </div>

        <?php if ($hasKelompok): ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="small">
                <tr>
                  <th>No</th>
                  <th>NIM</th>
                  <th>Nama Mahasiswa</th>
                  <th>Program Studi</th>
                  <th>Peran</th>
                  <th>No. WhatsApp</th>
                </tr>
              </thead>
              <tbody class="small">
                <?php $no = 1; foreach ($daftarAnggota as $m): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td class="fw-semibold text-dark"><?= esc($m['nim']) ?></td>
                    <td><?= esc($m['namaMahasiswa']) ?></td>
                    <td><?= esc($m['programStudi']) ?></td>
                    <td>
                      <?php if ($m['nim'] === $kelompok['nimKetua']): ?>
                        <span class="badge px-3 py-1 rounded-pill fw-bold" style="background: #fbbf24; color: #112842;">
                          <i class="bi bi-star-fill me-1"></i> Ketua
                        </span>
                      <?php else: ?>
                        <span class="pill-badge-blue">Anggota</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php $wa = $m['noWhatsapp'] ?? $m['no_whatsapp'] ?? '-'; ?>
                      <?php if (! empty($wa) && $wa !== '-'): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', (string)$wa) ?>" target="_blank" class="text-success text-decoration-none fw-medium">
                          <i class="bi bi-whatsapp me-1"></i><?= esc($wa) ?>
                        </a>
                      <?php else: ?>
                        <span class="text-muted small">-</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-4">
            <p class="text-muted small mb-2">Kamu belum tergabung dalam kelompok PKM manapun.</p>
            <a href="<?= base_url('mahasiswa/kelompok') ?>" class="btn btn-warning text-dark btn-sm fw-bold rounded-pill px-3 py-2">
              <i class="bi bi-plus-circle me-1"></i> Buat Kelompok Baru
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Kolom Kanan: Panduan, Jadwal, & Info DKKA -->
    <div class="col-lg-4">
      <!-- Card Jadwal & Timeline Penting -->
      <div class="sipkm-card p-4 mb-4 shadow-sm">
        <h6 class="fw-bold mb-3" style="color: #112842;">
          <i class="bi bi-calendar-check text-warning me-2"></i>Timeline PKM UBL 2026
        </h6>
        <ul class="list-group list-group-flush small">
          <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">Batas Submit Proposal</div>
              <small class="text-muted">Melalui SIPKM-DKKA</small>
            </div>
            <span class="badge bg-danger-subtle text-danger">15 Des 2026</span>
          </li>
          <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">Peninjauan Dosen</div>
              <small class="text-muted">Administrasi &amp; Substansi</small>
            </div>
            <span class="badge bg-warning-subtle text-warning">16 - 22 Des 2026</span>
          </li>
          <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">Pengumuman Kelayakan</div>
              <small class="text-muted">Plafon Pendanaan DKKA</small>
            </div>
            <span class="badge bg-success-subtle text-success">28 Des 2026</span>
          </li>
        </ul>
      </div>

      <!-- Card Tautan Panduan 10 Bidang PKM -->
      <div class="sipkm-card p-4 shadow-sm">
        <h6 class="fw-bold mb-2" style="color: #112842;">
          <i class="bi bi-info-circle text-primary me-2"></i>10 Bidang PKM Belmawa
        </h6>
        <p class="text-muted small mb-3">Pastikan skema yang dipilih sesuai dengan fokus inovasi tim:</p>
        <?php
          $skemaPilihan = strtoupper(trim((string)($proposal['jenisPKM'] ?? $kelompok['jenisPKM'] ?? '')));
          $daftar10Bidang = [
            'PKM-RE'  => 'Riset Eksakta',
            'PKM-RSH' => 'Riset Sosial Humaniora',
            'PKM-K'   => 'Kewirausahaan',
            'PKM-PM'  => 'Pengabdian kepada Masyarakat',
            'PKM-PI'  => 'Penerapan IPTEK',
            'PKM-KC'  => 'Karsa Cipta',
            'PKM-KI'  => 'Karya Inovatif',
            'PKM-VGK' => 'Video Gagasan Konstruktif',
            'PKM-GFT' => 'Gagasan Futuristik Tertulis',
            'PKM-AI'  => 'Artikel Ilmiah',
          ];
        ?>
        <div class="d-flex flex-wrap gap-1 mb-3">
          <?php foreach ($daftar10Bidang as $kode => $nama): ?>
            <?php 
              $isAktif = (!empty($skemaPilihan) && (
                $skemaPilihan === $kode ||
                strpos($skemaPilihan, $kode) !== false
              ));
            ?>
            <span class="badge <?= $isAktif ? 'bg-primary text-white shadow-sm' : 'bg-light text-dark border' ?>" title="<?= esc($kode) ?> - <?= esc($nama) ?>">
              <?= esc($kode) ?>
            </span>
          <?php endforeach; ?>
        </div>
        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary btn-sm w-100 rounded-pill py-2">
          <i class="bi bi-trophy me-1"></i> Buka Informasi Lomba Lainnya
        </a>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
