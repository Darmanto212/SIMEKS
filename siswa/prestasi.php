<?php require_once '../includes/auth_check.php';
check_auth('siswa');

$pageTitle = "Prestasi Saya - SIMEKS";
include '../config/koneksi.php'; 
include '../includes/header.php'; 

$user_id = $_SESSION['siswa_data']['user_id'];

// Fetch Real Achievements
$stmt = $koneksi->prepare("
    SELECT p.*, e.nama_eskul 
    FROM prestasi p 
    JOIN eskul e ON p.eskul_id = e.id 
    WHERE p.user_id = ? 
    ORDER BY p.tahun DESC, p.id DESC
");
$stmt->execute([$user_id]);
$prestasi = $stmt->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Prestasi & Penghargaan";
        include '../includes/siswa_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-maroon text-white position-relative overflow-hidden">
                        <div class="position-relative z-index-1">
                            <h4 class="fw-bold mb-2">Hall of Fame</h4>
                            <p class="mb-0 opacity-75">Koleksi seluruh kerja keras dan dedikasi kamu di SMAN 2 Sukatani.</p>
                        </div>
                        <i class="fas fa-trophy position-absolute end-0 bottom-0 mb-n3 me-n2 fs-1 opacity-25" style="transform: rotate(-15deg); font-size: 8rem !important;"></i>
                    </div>
                </div>

                <?php if (empty($prestasi)): ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/3112/3112946.png" width="100" class="mb-4 mx-auto opacity-50">
                            <h5 class="fw-bold text-muted">Belum ada prestasi tercatat</h5>
                            <p class="text-muted small">Ayo mulai aktif berkegiatan dan raih prestasi gemilang!</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($prestasi as $p): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover overflow-hidden">
                                <div class="bg-warning bg-opacity-10 p-4 text-center border-bottom border-warning border-opacity-10">
                                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-medal fs-3"></i>
                                    </div>
                                    <h6 class="fw-bold text-warning text-uppercase small mb-1"><?php echo htmlspecialchars($p->tingkat); ?></h6>
                                    <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($p->nama_prestasi); ?></h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light p-2 rounded-3 me-3">
                                            <i class="fas fa-running text-maroon"></i>
                                        </div>
                                        <div>
                                            <p class="small text-muted mb-0">Cabang Ekskul</p>
                                            <p class="small fw-bold mb-0"><?php echo htmlspecialchars($p->nama_eskul); ?></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-light p-2 rounded-3 me-3">
                                            <i class="fas fa-calendar-alt text-maroon"></i>
                                        </div>
                                        <div>
                                            <p class="small text-muted mb-0">Tahun Perolehan</p>
                                            <p class="small fw-bold mb-0"><?php echo htmlspecialchars($p->tahun); ?></p>
                                        </div>
                                    </div>
                                    <a href="get-sertifikat.php?id=<?php echo $p->id; ?>" target="_blank" class="btn btn-maroon w-100 rounded-pill fw-bold">
                                        <i class="fas fa-download me-2"></i> Unduh Sertifikat
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });
</script>

<?php include '../includes/footer.php'; ?>
