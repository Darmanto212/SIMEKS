<?php 
$pageTitle = "Detail Ekstrakurikuler - SIMEKS";
include 'config/koneksi.php';
include 'includes/header.php'; 

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $koneksi->prepare("
    SELECT e.*, (SELECT COUNT(*) FROM pendaftaran WHERE eskul_id = e.id AND status = 'diterima') as terdaftar 
    FROM eskul e WHERE e.id = ?
");
$stmt->execute([$id]);
$eskul = $stmt->fetch();

if (!$eskul) {
    header("Location: index.php");
    exit();
}
?>

<section class="py-5 bg-light-section min-vh-100">
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-maroon text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Ekskul</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInLeft">
                    <img src="<?php echo str_starts_with($eskul->gambar, 'http') ? $eskul->gambar : 'assets/uploads/eskul/'.$eskul->gambar; ?>" class="img-fluid" style="width: 100%; height: 400px; object-fit: cover;">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="fw-bold mb-0"><?php echo htmlspecialchars($eskul->nama_eskul); ?></h1>
                            <span class="badge bg-maroon-light text-maroon fs-6 px-3 py-2 rounded-pill">Aktif</span>
                        </div>
                        <p class="lead text-muted mb-5"><?php echo htmlspecialchars($eskul->deskripsi); ?></p>
                        
                        <div class="row g-4 text-dark">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="bg-white p-3 rounded-circle text-maroon shadow-sm me-3"><i class="fas fa-user-tie"></i></div>
                                    <div>
                                        <small class="text-muted d-block uppercase small fw-bold">Pembina</small>
                                        <span class="fw-bold"><?php echo htmlspecialchars($eskul->pembina); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="bg-white p-3 rounded-circle text-maroon shadow-sm me-3"><i class="fas fa-calendar-alt"></i></div>
                                    <div>
                                        <small class="text-muted d-block uppercase small fw-bold">Jadwal</small>
                                        <span class="fw-bold"><?php echo htmlspecialchars($eskul->jadwal); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="bg-white p-3 rounded-circle text-maroon shadow-sm me-3"><i class="fas fa-map-marker-alt"></i></div>
                                    <div>
                                        <small class="text-muted d-block uppercase small fw-bold">Lokasi</small>
                                        <span class="fw-bold"><?php echo htmlspecialchars($eskul->lokasi); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="bg-white p-3 rounded-circle text-maroon shadow-sm me-3"><i class="fas fa-users"></i></div>
                                    <div>
                                        <small class="text-muted d-block uppercase small fw-bold">Kuota Terisi</small>
                                        <span class="fw-bold"><?php echo $eskul->terdaftar; ?> / <?php echo $eskul->kuota; ?> Siswa</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 p-4 animate__animated animate__fadeInRight">
                    <h4 class="fw-bold mb-4">Tertarik bergabung?</h4>
                    <p class="text-muted mb-4">Silakan masuk ke akun siswa Anda atau daftar terlebih dahulu untuk mendaftar di kegiatan ekstrakurikuler ini.</p>
                    
                    <div class="d-grid gap-3">
                        <a href="login.php" class="btn btn-maroon btn-lg rounded-pill py-3 fw-bold">Masuk Sekarang</a>
                        <a href="register.php" class="btn btn-outline-maroon btn-lg rounded-pill py-3 fw-bold">Belum Punya Akun?</a>
                    </div>
                    
                    <hr class="my-5">
                    
                    <h5 class="fw-bold mb-3">Informasi Tambahan</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Gratis tanpa biaya pendaftaran.</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Terbuka untuk semua jenjang kelas.</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Sertifikat prestasi tersedia.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
