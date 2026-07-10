<?php require_once '../includes/auth_check.php';
check_auth('siswa');

$user_id = $_SESSION['siswa_data']['user_id'];
$pageTitle = "Notifikasi - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php';

// Mark all as read if requested
if (isset($_GET['read_all'])) {
    $stmt = $koneksi->prepare("UPDATE notifikasi SET is_read = 1 WHERE user_id = ?");
    $stmt->execute([$user_id]);
}
// Fetch Notifications
$stmt = $koneksi->prepare("SELECT * FROM notifikasi WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Pusat Notifikasi";
        include '../includes/siswa_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Semua Pemberitahuan</h5>
                    <span class="badge bg-light text-muted fw-normal rounded-pill px-3"><?php echo count($notifications); ?> Total</span>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (empty($notifications)): ?>
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-bell-slash fs-1 mb-3 opacity-25"></i>
                            <p>Belum ada notifikasi untuk Anda.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $n): ?>
                            <div class="list-group-item list-group-item-action border-0 p-4 border-bottom position-relative <?php echo $n->is_read ? 'bg-white' : 'bg-light bg-opacity-50'; ?>">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0 text-maroon">
                                        <i class="fas fa-circle small me-2" style="font-size: 8px;"></i>
                                        <?php echo htmlspecialchars($n->judul); ?>
                                    </h6>
                                    <small class="text-muted"><?php echo date('d M, H:i', strtotime($n->created_at)); ?></small>
                                </div>
                                <p class="mb-0 text-muted small"><?php echo htmlspecialchars($n->pesan); ?></p>
                                <?php if (!$n->is_read): ?>
                                    <span class="position-absolute top-0 end-0 mt-3 me-3">
                                        <span class="badge rounded-pill bg-danger">Baru</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
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
