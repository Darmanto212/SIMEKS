<?php 
require_once 'config/koneksi.php';
$pageTitle = "Beranda - SIMEKS";
include 'includes/header.php'; 
?>

<section class="hero-section" style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.65)), url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; display: flex; align-items: center; min-height: 50vh; padding-top: 140px !important; padding-bottom: 60px !important; position: relative;">
    <div class="container position-relative z-2">
        <div class="row align-items-center">
            <div class="col-lg-7 text-lg-start text-center hero-content">
                <span class="badge bg-maroon text-white px-3 py-2 rounded-pill fw-bold mb-3 animate__animated animate__fadeInDown" style="letter-spacing: 1px; font-size: 0.75rem;">
                    SMAN 2 SUKATANI
                </span>
                <h1 class="fw-bold animate__animated animate__fadeInUp animate__fast text-white mb-3" style="font-size: 2.2rem; line-height: 1.25; letter-spacing: -0.5px;">
                    Salurkan Bakat dan Minatmu <br>
                    <span class="text-warning">Bersama SIMEKS</span>
                </h1>
                <p class="lead text-light mb-4 opacity-90 animate__animated animate__fadeInUp animate__delay-1s" style="font-size: 0.95rem; line-height: 1.6; max-width: 620px; margin-left: 0; margin-right: 0;">
                    Temukan kegiatan ekstrakurikuler, lakukan pendaftaran, pantau kehadiran, dan dokumentasikan prestasi dalam satu sistem secara praktis dan digital.
                </p>
                <div class="d-flex flex-sm-row flex-column justify-content-lg-start justify-content-center align-items-center gap-3 animate__animated animate__fadeInUp animate__delay-1s">
                    <a href="register.php" class="btn btn-maroon btn-md px-4 py-2 rounded-pill fw-bold">Daftar Sekarang</a>
                    <a href="#ekskul" class="btn btn-glass-light btn-md px-4 py-2 rounded-pill fw-bold">Lihat Ekskul</a>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0 d-none d-lg-block text-center">
                <div class="position-relative p-2 bg-white bg-opacity-10 rounded-4 shadow-lg border border-white border-opacity-10 animate__animated animate__zoomIn">
                    <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=800&q=80" 
                         alt="Ilustrasi Kegiatan Ekskul" 
                         class="img-fluid rounded-3 shadow"
                         style="max-height: 240px; width: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>


<section id="tentang" class="py-4-compact bg-light-section overflow-hidden">
    <div class="container py-2">
        <div class="text-center mb-4" data-aos="fade-up">
            <h6 class="text-maroon fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 1.5px;">Tentang SIMEKS</h6>
            <h2 class="fw-bold text-dark mb-2" style="font-size: 1.8rem;">Mengapa Menggunakan SIMEKS?</h2>
            <p class="text-muted small mx-auto mb-0" style="max-width: 800px;">
                SIMEKS SMAN 2 Sukatani dirancang untuk memudahkan seluruh sivitas akademika dalam mengelola kegiatan ekstrakurikuler secara terintegrasi dan transparan.
            </p>
        </div>
        
        <div class="row g-3 mt-1" data-aos="fade-up" data-aos-delay="100">
            <div class="col-md-3 col-6">
                <div class="card h-100 border-0 shadow-premium p-3 rounded-4 text-center">
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-circle text-maroon mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-laptop-code fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Pendaftaran Digital</h6>
                    <p class="text-muted extra-small mb-0" style="font-size: 0.75rem; line-height: 1.4;">Layanan pendaftaran anggota baru secara daring yang praktis.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card h-100 border-0 shadow-premium p-3 rounded-4 text-center">
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-circle text-maroon mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-user-clock fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Informasi Real-Time</h6>
                    <p class="text-muted extra-small mb-0" style="font-size: 0.75rem; line-height: 1.4;">Akses jadwal latihan, pengumuman, dan absensi terkini.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card h-100 border-0 shadow-premium p-3 rounded-4 text-center">
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-circle text-maroon mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-shield-alt fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Data Terpusat</h6>
                    <p class="text-muted extra-small mb-0" style="font-size: 0.75rem; line-height: 1.4;">Seluruh riwayat partisipasi dan log tersimpan dengan aman.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card h-100 border-0 shadow-premium p-3 rounded-4 text-center">
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-circle text-maroon mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-medal fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Fokus Prestasi</h6>
                    <p class="text-muted extra-small mb-0" style="font-size: 0.75rem; line-height: 1.4;">Dokumentasi pencapaian eskul untuk portofolio siswa.</p>
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
        ORDER BY tanggal_terbit DESC 
        LIMIT 3
    ")->fetchAll();

    // Fetch Stats
    $total_siswa = $koneksi->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn();
    $total_prestasi = $koneksi->query("SELECT COUNT(*) FROM prestasi")->fetchColumn();
    ?>

<!-- Statistics Counter Section -->
<section class="py-3-compact bg-maroon text-white overflow-hidden" style="border-bottom: 3px solid var(--maroon-dark);">
    <div class="container">
        <div class="row g-3 text-center align-items-center">
            <div class="col-6 col-md-3" data-aos="fade-up">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-users fs-4 text-white"></i>
                    <div class="text-start">
                        <h4 class="fw-bold mb-0 text-white fs-5"><span class="counter-value" data-target="<?php echo $total_siswa; ?>">0</span></h4>
                        <p class="mb-0 extra-small text-white-50 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Siswa</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-running fs-4 text-white"></i>
                    <div class="text-start">
                        <h4 class="fw-bold mb-0 text-white fs-5"><span class="counter-value" data-target="<?php echo $total_eskul_count; ?>">0</span></h4>
                        <p class="mb-0 extra-small text-white-50 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Ekskul</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-trophy fs-4 text-white"></i>
                    <div class="text-start">
                        <h4 class="fw-bold mb-0 text-white fs-5"><span class="counter-value" data-target="<?php echo $total_prestasi; ?>">0</span></h4>
                        <p class="mb-0 extra-small text-white-50 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Prestasi</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-award fs-4 text-white"></i>
                    <div class="text-start">
                        <h4 class="fw-bold mb-0 text-white fs-5"><span class="counter-value" data-target="25">0</span></h4>
                        <p class="mb-0 extra-small text-white-50 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Pembina</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="ekskul" class="py-4-compact bg-white overflow-hidden">
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div class="text-start">
                <h6 class="text-maroon fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1.5px; margin: 0;">Daftar Ekstrakurikuler</h6>
                <h2 class="fw-bold text-dark fs-4 mb-0">Pilih Bakat dan Minatmu</h2>
            </div>
            <?php if ($total_eskul_count > 8): ?>
                <div>
                    <button id="btnShowMore" class="btn btn-outline-maroon btn-sm px-4 py-2 rounded-pill fw-bold" style="font-size: 0.8rem;">
                        Lihat Semua (<?php echo $total_eskul_count - 8; ?>+)
                    </button>
                </div>
            <?php endif; ?>
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
                                <img src="<?php echo $image_src; ?>" class="img-fluid" style="height: 140px; width: 100%; object-fit: cover;" alt="<?php echo htmlspecialchars($row->nama_eskul); ?>">
                            </div>
                            <div class="card-body p-3 d-flex flex-column text-start">
                                <h6 class="fw-bold mb-1 text-truncate text-dark" title="<?php echo htmlspecialchars($row->nama_eskul); ?>"><?php echo htmlspecialchars($row->nama_eskul); ?></h6>
                                <p class="text-muted extra-small mb-3 text-truncate-2" style="font-size: 0.75rem; line-height: 1.4; min-height: 35px;"><?php echo htmlspecialchars($row->deskripsi); ?></p>
                                <a href="detail-eskul.php?id=<?php echo $row->id; ?>" class="btn btn-maroon w-100 py-1-5 small rounded-pill mt-auto" style="font-size: 0.8rem;">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>


<section id="prestasi" class="py-4-compact bg-light-section overflow-hidden">
    <div class="container py-2">
        <div class="text-center mb-4" data-aos="fade-up">
            <h6 class="text-maroon fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1.5px;">Prestasi Terbaru</h6>
            <h2 class="fw-bold text-dark mb-0 fs-5">Kebanggaan Sekolah Kami</h2>
        </div>

        <div class="row g-3" data-aos="fade-up" data-aos-delay="100">
            <?php if (empty($latest_prestasi)): ?>
                <div class="col-12 text-center text-muted">Belum ada data prestasi terbaru.</div>
            <?php else: ?>
                <?php foreach ($latest_prestasi as $prestasi): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-premium rounded-4 p-3 h-100 animate__animated animate__fadeInUp">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-gold-light p-2 rounded-circle text-warning me-2.5 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                                    <i class="fas fa-trophy fs-6"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><?php echo htmlspecialchars($prestasi->nama_prestasi); ?></h6>
                                    <span class="badge bg-maroon-light text-maroon rounded-pill small px-2 py-0.5" style="font-size: 0.65rem;"><?php echo htmlspecialchars($prestasi->nama_eskul ?? 'Sekolah'); ?></span>
                                </div>
                            </div>
                            <p class="text-muted extra-small mb-0" style="font-size: 0.8rem; line-height: 1.4;"><?php echo htmlspecialchars($prestasi->deskripsi); ?></p>
                            <div class="mt-2 pt-2 border-top text-muted extra-small" style="font-size: 0.75rem;">
                                <i class="far fa-calendar-alt me-1"></i> Tahun <?php echo $prestasi->tahun; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="pengumuman" class="py-4-compact bg-white overflow-hidden">
    <div class="container py-2">
        <div class="text-center mb-4" data-aos="fade-up">
            <h6 class="text-maroon fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1.5px;">Pusat Informasi</h6>
            <h2 class="fw-bold text-dark mb-0 fs-5">Pengumuman Terbaru</h2>
        </div>

        <div class="row g-3" data-aos="fade-up" data-aos-delay="100">
            <?php if (empty($latest_pengumuman)): ?>
                <div class="col-12 text-center text-muted">Belum ada pengumuman terbaru.</div>
            <?php else: ?>
                <?php foreach ($latest_pengumuman as $info): ?>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-premium rounded-4 overflow-hidden">
                            <div class="card-body p-3 text-start">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-maroon-light text-maroon rounded-pill small px-2 py-0.5" style="font-size: 0.65rem;"><?php echo $info->kategori; ?></span>
                                    <small class="text-muted extra-small" style="font-size: 0.7rem;"><i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($info->tanggal_terbit)); ?></small>
                                </div>
                                <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.9rem;"><?php echo htmlspecialchars($info->judul); ?></h6>
                                <p class="text-muted extra-small mb-0" style="font-size: 0.8rem; line-height: 1.4;"><?php echo nl2br(htmlspecialchars(substr($info->isi, 0, 120))) . (strlen($info->isi) > 120 ? '...' : ''); ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-3 pt-0 text-start">
                                <a href="login.php" class="text-maroon fw-bold text-decoration-none extra-small" style="font-size: 0.75rem;">Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-3 bg-maroon text-white overflow-hidden" style="border-top: 3px solid var(--maroon-dark); border-bottom: 1px solid var(--maroon-dark);">
    <div class="container" data-aos="zoom-in">
        <div class="row align-items-center text-lg-start text-center py-2">
            <div class="col-lg-8">
                <h4 class="fw-bold mb-1 text-white fs-5">Siap Menjadi Bagian Dari Kami?</h4>
                <p class="mb-0 text-white-50 extra-small" style="font-size: 0.85rem;">Jangan ragu untuk memulai langkah pertamamu. Pendaftaran mudah dan cepat secara digital.</p>
            </div>
            <div class="col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                <a href="register.php" class="btn btn-light text-maroon btn-md fw-bold px-4 py-2 rounded-pill shadow-sm">Buat Akun Siswa</a>
            </div>
        </div>
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