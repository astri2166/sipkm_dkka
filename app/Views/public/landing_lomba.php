<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Papan Informasi Lomba & PKM | DKKA Universitas Budi Luhur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/sipkm-ui.css') ?>" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm" style="background-color: var(--sipkm-primary);">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= base_url('/') ?>">
      <span class="badge bg-warning text-dark">DKKA</span> SIPKM Universitas Budi Luhur
    </a>
    <div class="d-flex gap-2">
      <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-sipkm btn-sm px-3">Masuk</a>
      <a href="<?= base_url('register') ?>" class="btn btn-warning text-dark fw-semibold btn-sipkm btn-sm px-3">Daftar Mahasiswa</a>
    </div>
  </div>
</nav>

<section class="py-5 text-white" style="background: linear-gradient(135deg, #0f294a 0%, #1e40af 100%);">
  <div class="container py-2">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill">Direktorat Kemahasiswaan dan Karir Alumni</span>
        <h1 class="display-6 fw-bold mb-3">Papan Informasi Lomba & Pengelolaan Proposal PKM</h1>
        <p class="lead opacity-75 mb-4">Temukan kompetisi bergengsi tingkat nasional maupun internal kampus, bentuk tim PKM, dan pantau hasil peninjauan dosen secara transparan.</p>
        <div class="d-flex flex-wrap gap-2">
          <a href="<?= base_url('register') ?>" class="btn btn-warning text-dark fw-bold btn-sipkm px-4 py-2">Mulai Ajukan Proposal PKM</a>
          <a href="<?= base_url('mahasiswa/dashboard') ?>" class="btn btn-outline-light btn-sipkm px-4 py-2">Pratinjau Dashboard Sistem</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container my-5">
  <!-- Filter & Pencarian -->
  <div class="sipkm-card p-3 mb-4 shadow-sm">
    <div class="row g-3 align-items-center">
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
          <input type="text" id="searchLomba" class="form-control border-start-0" placeholder="Cari judul lomba atau kata kunci..." onkeyup="filterLomba()">
        </div>
      </div>
      <div class="col-md-4">
        <select id="filterKategori" class="form-select" onchange="filterLomba()">
          <option value="">Semua Kategori Lomba</option>
          <option value="PKM & Karya Ilmiah">PKM & Karya Ilmiah</option>
          <option value="Teknologi & IT">Teknologi & IT</option>
          <option value="Kewirausahaan">Kewirausahaan</option>
        </select>
      </div>
      <div class="col-md-2 text-md-end">
        <span class="text-muted small" id="countLomba"><?= count($daftarLomba) ?> Lomba Aktif</span>
      </div>
    </div>
  </div>

  <!-- Skeleton Shimmer Placeholder (Muncul saat filter/pencarian) -->
  <div id="lombaSkeleton" class="row g-4 d-none">
    <?php for ($i = 0; $i < 3; $i++): ?>
    <div class="col-md-4">
      <div class="sipkm-card overflow-hidden">
        <div class="skeleton-shimmer skeleton-poster"></div>
        <div class="p-4">
          <div class="skeleton-shimmer skeleton-badge mb-3"></div>
          <div class="skeleton-shimmer skeleton-line w-75"></div>
          <div class="skeleton-shimmer skeleton-line w-50"></div>
        </div>
      </div>
    </div>
    <?php endfor; ?>
  </div>

  <!-- Grid Kartu Informasi Lomba -->
  <div id="lombaGrid" class="row g-4">
    <?php foreach ($daftarLomba as $lomba): ?>
    <div class="col-md-4 lomba-item" data-judul="<?= strtolower(esc($lomba['judulLomba'])) ?>" data-kategori="<?= esc($lomba['kategoriLomba']) ?>">
      <div class="sipkm-card sipkm-card-hover h-100 d-flex flex-column overflow-hidden">
        <div class="poster-wrapper skeleton-shimmer" style="height: 200px; overflow: hidden;">
          <img src="<?= base_url('assets/uploads/poster/' . esc($lomba['posterBanner'])) ?>"
               alt="<?= esc($lomba['judulLomba']) ?>"
               class="w-100 h-100 object-fit-cover lazy-img">
        </div>
        <div class="p-4 d-flex flex-column flex-grow-1">
          <div class="mb-2">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3"><?= esc($lomba['kategoriLomba']) ?></span>
          </div>
          <h5 class="fw-bold mb-2"><?= esc($lomba['judulLomba']) ?></h5>
          <p class="text-muted small mb-3 flex-grow-1"><?= esc(substr($lomba['deskripsiLomba'], 0, 115)) ?>...</p>
          <div class="d-flex align-items-center text-muted small mb-3">
            <i class="bi bi-calendar-event me-2 text-primary"></i>
            <?= date('d M Y', strtotime($lomba['timelineMulai'])) ?> – <?= date('d M Y', strtotime($lomba['timelineSelesai'])) ?>
          </div>
          <button type="button"
                  class="btn btn-primary-sipkm btn-sipkm w-100"
                  data-bs-toggle="modal"
                  data-bs-target="#modalDetailLomba"
                  onclick='openDetailLomba(<?= json_encode($lomba, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
            Lihat Detail & Ketentuan
          </button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Modal Detail Informasi Lomba -->
<div class="modal fade" id="modalDetailLomba" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <span id="modalKategori" class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2"></span>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <h4 id="modalJudul" class="fw-bold mb-2"></h4>
        <p id="modalTimeline" class="text-muted small mb-3"></p>
        <hr>
        <p id="modalDeskripsi" class="mb-0" style="white-space: pre-line;"></p>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-light btn-sipkm" data-bs-dismiss="modal">Tutup</button>
        <a href="<?= base_url('login') ?>" class="btn btn-primary-sipkm btn-sipkm">Login untuk Ajukan Tim</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/sipkm-ui.js') ?>"></script>
<script>
function openDetailLomba(data) {
  document.getElementById('modalKategori').textContent = data.kategoriLomba;
  document.getElementById('modalJudul').textContent = data.judulLomba;
  document.getElementById('modalTimeline').innerHTML = `<i class="bi bi-calendar-range me-1"></i> Periode: <strong>${data.timelineMulai}</strong> s/d <strong>${data.timelineSelesai}</strong>`;
  document.getElementById('modalDeskripsi').textContent = data.deskripsiLomba;
}

function filterLomba() {
  const keyword = document.getElementById('searchLomba').value.toLowerCase();
  const kategori = document.getElementById('filterKategori').value;
  triggerSkeletonTransition('lombaSkeleton', 'lombaGrid', 300);

  let visible = 0;
  document.querySelectorAll('.lomba-item').forEach((card) => {
    const matchJudul = card.dataset.judul.includes(keyword);
    const matchKat = !kategori || card.dataset.kategori === kategori;
    const show = matchJudul && matchKat;
    card.classList.toggle('d-none', !show);
    if (show) visible++;
  });
  document.getElementById('countLomba').textContent = `${visible} Lomba Aktif`;
}
</script>
</body>
</html>
