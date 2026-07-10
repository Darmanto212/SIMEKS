<?php require_once '../includes/auth_check.php';
check_auth('siswa');

$pageTitle = "Riwayat Kehadiran - SIMEKS";
include '../config/koneksi.php'; 
include '../includes/header.php'; 

$user_id = $_SESSION['siswa_data']['user_id'];

// Fetch Attendance History
$stmt = $koneksi->prepare("
    SELECT a.*, e.nama_eskul 
    FROM absensi a
    JOIN eskul e ON a.eskul_id = e.id
    WHERE a.user_id = ?
    ORDER BY a.tanggal DESC
");
$stmt->execute([$user_id]);
$attendance_history = $stmt->fetchAll();

// Calculate Stats
$stmt = $koneksi->prepare("SELECT COUNT(*) FROM absensi WHERE user_id = ? AND status = 'hadir'");
$stmt->execute([$user_id]);
$total_hadir = $stmt->fetchColumn();

$stmt = $koneksi->prepare("SELECT COUNT(*) FROM absensi WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_pertemuan = $stmt->fetchColumn();

$persentase = ($total_pertemuan > 0) ? round(($total_hadir / $total_pertemuan) * 100) : 0;
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Riwayat Kehadiran";
        include '../includes/siswa_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <!-- Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 transition-hover bg-maroon text-white">
                        <h6 class="opacity-75 small fw-bold text-uppercase mb-1">Total Kehadiran</h6>
                        <h2 class="fw-bold mb-0"><?php echo $total_hadir; ?> <small class="fs-6 opacity-75">Hari</small></h2>
                        <div class="progress mt-3 bg-white bg-opacity-25" style="height: 6px;">
                            <div class="progress-bar bg-white" style="width: <?php echo $persentase; ?>%"></div>
                        </div>
                        <p class="mt-2 mb-0 small opacity-75"><?php echo $persentase; ?>% dari total pertemuan</p>
                    </div>
                </div>
                <!-- Other status counts can be added here -->
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="p-4 border-bottom bg-white">
                    <h5 class="fw-bold mb-0">Log Absensi Mingguan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Tanggal</th>
                                <th>Ekstrakurikuler</th>
                                <th>Status</th>
                                <th class="px-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($attendance_history)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data absensi tercatat.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($attendance_history as $row): ?>
                                    <tr>
                                        <td class="px-4"><?php echo date('d M Y', strtotime($row->tanggal)); ?></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($row->nama_eskul); ?></td>
                                        <td>
                                            <?php 
                                                $badge = 'bg-secondary';
                                                if($row->status == 'hadir') $badge = 'bg-success';
                                                elseif($row->status == 'izin') $badge = 'bg-warning';
                                                elseif($row->status == 'sakit') $badge = 'bg-info';
                                                elseif($row->status == 'alpa') $badge = 'bg-danger';
                                            ?>
                                            <span class="badge <?php echo $badge; ?> rounded-pill px-3 text-capitalize"><?php echo $row->status; ?></span>
                                        </td>
                                        <td class="px-4 text-muted small"><?php echo htmlspecialchars($row->keterangan ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
