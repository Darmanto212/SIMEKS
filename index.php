<?php 
require_once 'config/koneksi.php';
$pageTitle = "Beranda - SIMEKS";
include 'includes/header.php'; 
?>

<section class="hero-section text-center">
    <div class="container position-relative z-2">
        <div class="row justify-content-center">
            <div class="col-lg-10 hero-content">
                <span class="badge bg-maroon text-white px-3 py-2 rounded-pill fw-bold mb-3 animate__animated animate__fadeInDown" style="letter-spacing: 1px;">
                    ✨ TAHUN AJARAN 2025/2026
                </span>
                <h1 class="fw-bold animate__animated animate__fadeInUp animate__fast">
                    Salurkan Bakat & Minatmu di <br>
                    <span class="text-warning">SMAN 2 SUKATANI</span>
                </h1>
                <p class="lead text-light mb-5 opacity-90 animate__animated animate__fadeInUp animate__delay-1s">
                    Bergabunglah dengan komunitas positif, raih prestasi gemilang, <br class="d-none d-md-block"> 
                    dan bentuk karakter kepemimpinan melalui ekstrakurikuler.
                </p>
                <div class="d-flex flex-sm-row flex-column justify-content-center align-items-center gap-3 animate__animated animate__fadeInUp animate__delay-1s">
                    <a href="login.php" class="btn btn-maroon btn-lg shadow-lg border-white px-5 rounded-pill">Daftar Sekarang</a>
                    <a href="#ekskul" class="btn btn-glass-light btn-lg px-5 rounded-pill">Lihat Ekskul</a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#525252ff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>


<section class="py-4 bg-light-section overflow-hidden">
    <div class="container py-3">
        <div class="row align-items-center" data-aos="fade-right">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="position-relative ps-lg-4">
                    <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                         alt="Tentang Sekolah" 
                         class="img-fluid rounded-4 shadow-lg w-100"
                         style="min-height: 300px; object-fit: cover;">
                         
                    <div class="bg-maroon rounded-3 position-absolute start-0 bottom-0 p-3 text-white shadow d-none d-md-block" style="max-width: 160px; transform: translate(-10px, 10px);">
                        <i class="fas fa-quote-left fs-4 mb-1"></i>
                        <p class="extra-small mb-0 fst-italic">"Berprestasi tiada henti, berkarakter pasti."</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                <h6 class="text-maroon fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1.5px;">Kenapa Memilih Kami?</h6>
                <h2 class="fw-bold mb-3 text-dark fs-2">Mengembangkan Potensi, <br>Membangun Masa Depan.</h2>
                <p class="text-muted small mb-4">SIMEKS SMAN 2 Sukatani dirancang untuk memudahkan siswa dalam mengakses informasi, mendaftar, dan memantau jadwal kegiatan ekstrakurikuler secara digital dan terintegrasi.</p>
                
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white d-flex align-items-center justify-content-center rounded-circle shadow text-maroon me-3" style="width: 40px; height: 40px;"><i class="fas fa-laptop-code"></i></div>
                            <span class="fw-bold text-dark fs-6">Sistem Digital</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white d-flex align-items-center justify-content-center rounded-circle shadow text-maroon me-3" style="width: 40px; height: 40px;"><i class="fas fa-user-clock"></i></div>
                            <span class="fw-bold text-dark fs-6">Real-Time Info</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white d-flex align-items-center justify-content-center rounded-circle shadow text-maroon me-3" style="width: 40px; height: 40px;"><i class="fas fa-shield-alt"></i></div>
                            <span class="fw-bold text-dark fs-6">Data Aman</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white d-flex align-items-center justify-content-center rounded-circle shadow text-maroon me-3" style="width: 40px; height: 40px;"><i class="fas fa-medal"></i></div>
                            <span class="fw-bold text-dark fs-6">Fokus Prestasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Fetch all active eskul for dynamic display
$all_eskul = $koneksi->query("SELECT * FROM eskul WHERE status = 'aktif' ORDER BY nama_eskul ASC")->fetchAll();
$total_eskul_count = count($all_eskul);

    // Fetch Latest 3 achievements
    $latest_prestasi = $koneksi->query("
        SELECT p.*, e.nama_eskul 
        FROM prestasi p 
        LEFT JOIN eskul e ON p.eskul_id = e.id 
        ORDER BY p.tahun DESC, p.id DESC 
        LIMIT 3
    ")->fetchAll();

    // Fetch Latest 3 announcements
    $latest_pengumuman = $koneksi->query("
        SELECT * FROM pengumuman 
        ORDER BY tanggal DESC 
        LIMIT 3
    ")->fetchAll();

    // Fetch Stats
    $total_siswa = $koneksi->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn();
    $total_prestasi = $koneksi->query("SELECT COUNT(*) FROM prestasi")->fetchColumn();
    ?>

<!-- Statistics Counter Section -->
<section class="py-5 bg-maroon text-white overflow-hidden">
    <div class="container py-3">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-aos="fade-up">
                <div class="counter-item">
                    <i class="fas fa-users fs-1 mb-3 text-white"></i>
                    <h2 class="display-5 fw-bold mb-1 text-white"><span class="counter-value" data-target="<?php echo $total_siswa; ?>">0</span></h2>
                    <p class="mb-0 small text-uppercase fw-bold text-white-50">Siswa Terdaftar</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="counter-item">
                    <i class="fas fa-running fs-1 mb-3 text-white"></i>
                    <h2 class="display-5 fw-bold mb-1 text-white"><span class="counter-value" data-target="<?php echo $total_eskul_count; ?>">0</span></h2>
                    <p class="mb-0 small text-uppercase fw-bold text-white-50">Ekstrakurikuler</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="counter-item">
                    <i class="fas fa-trophy fs-1 mb-3 text-white"></i>
                    <h2 class="display-5 fw-bold mb-1 text-white"><span class="counter-value" data-target="<?php echo $total_prestasi; ?>">0</span></h2>
                    <p class="mb-0 small text-uppercase fw-bold text-white-50">Total Prestasi</p>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="counter-item">
                    <i class="fas fa-award fs-1 mb-3 text-white"></i>
                    <h2 class="display-5 fw-bold mb-1 text-white"><span class="counter-value" data-target="25">0</span></h2>
                    <p class="mb-0 small text-uppercase fw-bold text-white-50">Pembina Ahli</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="ekskul" class="py-5 bg-white overflow-hidden">
    <div class="container py-4">
        <div class="row justify-content-center mb-5" data-aos="fade-up">
            <div class="col-lg-8 text-center">
                <div class="section-title">
                    <h6 class="text-maroon fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1.5px;">Daftar Ekstrakurikuler</h6>
                    <h2 class="fw-bold mb-0 text-dark fs-2">Pilih Bakat dan Minatmu</h2>
                </div>
            </div>
        </div>

        <div class="row g-3" id="eskulContainer" data-aos="fade-up" data-aos-delay="100">
            <?php if (empty($all_eskul)): ?>
                <div class="col-12 text-center text-muted">Belum ada data ekstrakurikuler.</div>
            <?php else: ?>
                <?php foreach ($all_eskul as $index => $row): ?>
                    <div class="col-6 col-md-4 col-lg-3 eskul-item <?php echo $index >= 8 ? 'd-none' : ''; ?>" data-nama="<?php echo strtolower(htmlspecialchars($row->nama_eskul)); ?>">
                        <div class="card card-custom h-100 shadow-premium border-0">
                            <?php 
                                $image_src = $row->gambar;
                                if (!str_starts_with($image_src, 'http')) {
                                    $image_src = 'assets/uploads/eskul/' . $image_src;
                                }
                            ?>
                            <div class="position-relative overflow-hidden">
                                <img src="<?php echo $image_src; ?>" class="img-fluid" style="height: 180px; width: 100%; object-fit: cover;" alt="<?php echo htmlspecialchars($row->nama_eskul); ?>">
                            </div>
                            <div class="card-body p-3 text-center d-flex flex-column">
                                <h5 class="fw-bold mb-2 text-truncate" title="<?php echo htmlspecialchars($row->nama_eskul); ?>"><?php echo htmlspecialchars($row->nama_eskul); ?></h5>
                                <p class="text-muted small mb-3 text-truncate-2"><?php echo htmlspecialchars($row->deskripsi); ?></p>
                                <a href="detail-eskul.php?id=<?php echo $row->id; ?>" class="btn btn-maroon w-100 py-2 small rounded-pill mt-auto">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($total_eskul_count > 8): ?>
            <div class="text-center mt-4">
                <button id="btnShowMore" class="btn btn-outline-maroon px-4 rounded-pill">
                    Lihat Lebih Banyak (<?php echo $total_eskul_count - 8; ?>+)
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>


<section id="prestasi" class="py-5 bg-light-section overflow-hidden">
    <div class="container py-4">
        <div class="row justify-content-center mb-5" data-aos="fade-up">
            <div class="col-lg-8 text-center">
                <div class="section-title">
                    <h6 class="text-maroon fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1.5px;">Prestasi Terbaru</h6>
                    <h2 class="fw-bold mb-0 text-dark fs-2">Kebanggaan Sekolah Kami</h2>
                </div>
            </div>
        </div>

        <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
            <?php if (empty($latest_prestasi)): ?>
                <div class="col-12 text-center text-muted">Belum ada data prestasi terbaru.</div>
            <?php else: ?>
                <?php foreach ($latest_prestasi as $prestasi): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-premium rounded-4 p-4 h-100 animate__animated animate__fadeInUp">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-gold-light p-3 rounded-circle text-warning me-3">
                                    <i class="fas fa-trophy fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($prestasi->nama_prestasi); ?></h5>
                                    <span class="badge bg-maroon-light text-maroon rounded-pill small px-3"><?php echo htmlspecialchars($prestasi->nama_eskul ?? 'Sekolah'); ?></span>
                                </div>
                            </div>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($prestasi->deskripsi); ?></p>
                            <div class="mt-3 pt-3 border-top text-muted small">
                                <i class="far fa-calendar-alt me-1"></i> Tahun <?php echo $prestasi->tahun; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="pengumuman" class="py-5 bg-white overflow-hidden">
    <div class="container py-4">
        <div class="row justify-content-center mb-5" data-aos="fade-up">
            <div class="col-lg-8 text-center">
                <div class="section-title">
                    <h6 class="text-maroon fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1.5px;">Pusat Informasi</h6>
                    <h2 class="fw-bold mb-0 text-dark fs-2">Pengumuman Terbaru</h2>
                </div>
            </div>
        </div>

        <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
            <?php if (empty($latest_pengumuman)): ?>
                <div class="col-12 text-center text-muted">Belum ada pengumuman terbaru.</div>
            <?php else: ?>
                <?php foreach ($latest_pengumuman as $info): ?>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-premium rounded-4 overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-maroon-light text-maroon rounded-pill"><?php echo $info->kategori; ?></span>
                                    <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($info->tanggal)); ?></small>
                                </div>
                                <h4 class="fw-bold mb-3"><?php echo htmlspecialchars($info->judul); ?></h4>
                                <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars(substr($info->isi, 0, 160))) . (strlen($info->isi) > 160 ? '...' : ''); ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                <a href="login.php" class="text-maroon fw-bold text-decoration-none small">Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-5 text-white text-center overflow-hidden" style="background: linear-gradient(135deg, var(--maroon), var(--maroon-dark));">
    <div class="container py-3" data-aos="zoom-in">
        <h2 class="fw-bold mb-3 text-white">Siap Menjadi Bagian Dari Kami?</h2>
        <p class="lead mb-4 text-white">Jangan ragu untuk memulai langkah pertamamu. Pendaftaran mudah dan cepat.</p>
        <a href="register.php" class="btn btn-light text-maroon btn-lg fw-bold px-5 shadow rounded-pill">Buat Akun Siswa</a>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnShowMore = document.getElementById('btnShowMore');
        if (btnShowMore) {
            btnShowMore.addEventListener('click', function() {
                const hiddenItems = document.querySelectorAll('.eskul-item.d-none');
                hiddenItems.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.remove('d-none');
                        item.classList.add('animate__animated', 'animate__fadeInUp');
                    }, index * 100);
                });
                this.parentElement.remove();
            });
        }
    });
</script>

<?php include 'includes/footer.php'; ?>