<?php 
session_start();
require_once '../includes/auth_check.php';
check_auth('siswa');

$pageTitle = "Pengumuman Sekolah - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

$user_id = $_SESSION['siswa_data']['user_id'];

// Fetch all announcements
$pengumuman = $koneksi->query("SELECT * FROM pengumuman ORDER BY tanggal DESC")->fetchAll();

?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Pengumuman Sekolah";
        include '../includes/siswa_nav.php'; 
        ?>

        <div class="container p-4">
            <?php if (empty($pengumuman)): ?>
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/3220/3220551.png" width="150" class="mb-4 opacity-50">
                    <h5 class="text-muted border-0">Belum ada pengumuman saat ini.</h5>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($pengumuman as $row): ?>
                        <?php 
                            $badge = 'bg-info';
                            $icon = 'info-circle';
                            if ($row->kategori == 'PENTING') { $badge = 'bg-danger'; $icon = 'exclamation-circle'; }
                            elseif ($row->kategori == 'UPDATE') { $badge = 'bg-success'; $icon = 'check-circle'; }
                            elseif ($row->kategori == 'EVENT') { $badge = 'bg-primary'; $icon = 'calendar-star'; }
                        ?>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 p-4 animate__animated animate__fadeInUp">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                                    <div class="d-flex align-items-center mb-2 mb-md-0">
                                        <div class="bg-opacity-10 <?php echo $badge; ?> p-3 rounded-circle me-3">
                                            <i class="fas fa-<?php echo $icon; ?> fs-4"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($row->judul); ?></h5>
                                            <span class="badge <?php echo $badge; ?> rounded-pill small"><?php echo $row->kategori; ?></span>
                                        </div>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="far fa-clock me-1"></i> <?php echo date('d M Y, H:i', strtotime($row->tanggal)); ?>
                                    </div>
                                </div>
                                <div class="ps-md-5 ms-md-2 mt-2">
                                    <p class="text-secondary" style="white-space: pre-line;"><?php echo htmlspecialchars($row->isi); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
