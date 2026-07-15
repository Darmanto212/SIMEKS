<?php require_once '../includes/auth_check.php';
check_auth('admin');

// Only allow Admin Master to access this page
if (!isset($_SESSION['admin_data']) || $_SESSION['admin_data']['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Log Notifikasi - SIMEKS";
include '../config/koneksi.php'; 
include '../includes/header.php'; 

// Fetch all notifications (sent to anyone) for admin auditing
$stmt = $koneksi->query("
    SELECT n.*, u.nama as penerima 
    FROM notifikasi n
    JOIN users u ON n.user_id = u.id
    ORDER BY n.created_at DESC
");
$notifications = $stmt->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Log Notifikasi Sistem";
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Riwayat Pemberitahuan</h5>
                    <span class="badge bg-light text-muted fw-normal rounded-pill px-3"><?php echo count($notifications); ?> Total Terkirim</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Waktu</th>
                                <th>Penerima</th>
                                <th>Judul Notifikasi</th>
                                <th>Pesan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($notifications)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada notifikasi yang terkirim.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach (array_slice($notifications, 0, 5) as $n): ?>
                                    <tr>
                                        <td class="px-4 small text-muted"><?php echo date('d M Y, H:i', strtotime($n->created_at)); ?></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($n->penerima); ?></td>
                                        <td>
                                            <span class="badge <?php echo ($n->type == 'success' ? 'bg-success' : ($n->type == 'danger' ? 'bg-danger' : 'bg-info')); ?> bg-opacity-10 text-<?php echo ($n->type == 'success' ? 'success' : ($n->type == 'danger' ? 'danger' : 'info')); ?> px-2 py-1 rounded">
                                                <?php echo htmlspecialchars($n->judul); ?>
                                            </span>
                                        </td>
                                        <td class="small text-muted"><?php echo htmlspecialchars($n->pesan); ?></td>
                                        <td>
                                            <?php if ($n->is_read): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success fw-normal">Sudah Dibaca</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal">Belum Dibaca</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <?php if (count($notifications) > 5): ?>
                            <tbody class="collapse" id="collapseNotifications">
                                <?php foreach (array_slice($notifications, 5) as $n): ?>
                                    <tr>
                                        <td class="px-4 small text-muted"><?php echo date('d M Y, H:i', strtotime($n->created_at)); ?></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($n->penerima); ?></td>
                                        <td>
                                            <span class="badge <?php echo ($n->type == 'success' ? 'bg-success' : ($n->type == 'danger' ? 'bg-danger' : 'bg-info')); ?> bg-opacity-10 text-<?php echo ($n->type == 'success' ? 'success' : ($n->type == 'danger' ? 'danger' : 'info')); ?> px-2 py-1 rounded">
                                                <?php echo htmlspecialchars($n->judul); ?>
                                            </span>
                                        </td>
                                        <td class="small text-muted"><?php echo htmlspecialchars($n->pesan); ?></td>
                                        <td>
                                            <?php if ($n->is_read): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success fw-normal">Sudah Dibaca</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal">Belum Dibaca</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
                <?php if (count($notifications) > 5): ?>
                    <div class="card-footer bg-white border-top text-center p-3">
                        <button class="btn btn-outline-maroon rounded-pill px-4 btn-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNotifications" aria-expanded="false" aria-controls="collapseNotifications" onclick="this.innerHTML = this.innerHTML.trim() === 'Sembunyikan' ? 'Tampilkan Lebih Banyak' : 'Sembunyikan'">
                            Tampilkan Lebih Banyak
                        </button>
                    </div>
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
