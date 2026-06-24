<?php
session_start();
require_once '../includes/auth_check.php';
check_auth('admin');
$pageTitle = "Dashboard - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php';

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$pembina_eskul_id = null;
$pembina_eskul_name = "";
if ($is_pembina) {
    $pembina_eskul_id = get_pembina_eskul_id($koneksi, $_SESSION['pembina_data']['user_id']);
    if ($pembina_eskul_id) {
        $stmt_name = $koneksi->prepare("SELECT nama_eskul FROM eskul WHERE id = ?");
        $stmt_name->execute([$pembina_eskul_id]);
        $pembina_eskul_name = $stmt_name->fetchColumn();
    }
}

// Fetch Statistics
if ($is_admin) {
    $total_siswa = $koneksi->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn();
    $total_eskul = $koneksi->query("SELECT COUNT(*) FROM eskul")->fetchColumn();
    $pendaftar_baru = $koneksi->query("SELECT COUNT(*) FROM pendaftaran WHERE status = 'menunggu'")->fetchColumn();
    $total_prestasi = $koneksi->query("SELECT COUNT(*) FROM prestasi")->fetchColumn();
} else {
    if ($pembina_eskul_id) {
        $stmt_ts = $koneksi->prepare("SELECT COUNT(*) FROM pendaftaran WHERE eskul_id = ? AND status = 'diterima'");
        $stmt_ts->execute([$pembina_eskul_id]);
        $total_siswa = $stmt_ts->fetchColumn();

        $pendaftar_baru_stmt = $koneksi->prepare("SELECT COUNT(*) FROM pendaftaran WHERE eskul_id = ? AND status = 'menunggu'");
        $pendaftar_baru_stmt->execute([$pembina_eskul_id]);
        $pendaftar_baru = $pendaftar_baru_stmt->fetchColumn();

        $total_prestasi_stmt = $koneksi->prepare("SELECT COUNT(*) FROM prestasi WHERE eskul_id = ?");
        $total_prestasi_stmt->execute([$pembina_eskul_id]);
        $total_prestasi = $total_prestasi_stmt->fetchColumn();
    } else {
        $total_siswa = 0;
        $pendaftar_baru = 0;
        $total_prestasi = 0;
    }
    $total_eskul = 1;
}

// Fetch Latest Registrations
if ($is_admin) {
    $latest_pendaftaran = $koneksi->query("
        SELECT p.*, u.nama, e.nama_eskul 
        FROM pendaftaran p
        JOIN users u ON p.user_id = u.id
        JOIN eskul e ON p.eskul_id = e.id
        ORDER BY p.tanggal_daftar DESC
        LIMIT 5
    ")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_lp = $koneksi->prepare("
            SELECT p.*, u.nama, e.nama_eskul 
            FROM pendaftaran p
            JOIN users u ON p.user_id = u.id
            JOIN eskul e ON p.eskul_id = e.id
            WHERE p.eskul_id = ?
            ORDER BY p.tanggal_daftar DESC
            LIMIT 5
        ");
        $stmt_lp->execute([$pembina_eskul_id]);
        $latest_pendaftaran = $stmt_lp->fetchAll();
    } else {
        $latest_pendaftaran = [];
    }
}

// Fetch Enrollment Trends (Last 7 days)
if ($is_admin) {
    $enrollment_query = $koneksi->query("
        SELECT DATE(tanggal_daftar) as date, COUNT(*) as count 
        FROM pendaftaran 
        WHERE tanggal_daftar >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(tanggal_daftar)
        ORDER BY date ASC
    ")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_eq = $koneksi->prepare("
            SELECT DATE(tanggal_daftar) as date, COUNT(*) as count 
            FROM pendaftaran 
            WHERE eskul_id = ? AND tanggal_daftar >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(tanggal_daftar)
            ORDER BY date ASC
        ");
        $stmt_eq->execute([$pembina_eskul_id]);
        $enrollment_query = $stmt_eq->fetchAll();
    } else {
        $enrollment_query = [];
    }
}

$trend_labels = [];
$trend_data = [];
foreach ($enrollment_query as $res) {
    $trend_labels[] = date('d M', strtotime($res->date));
    $trend_data[] = $res->count;
}

// Fetch Popular Ekskul (Top 5)
$popular_query = [];
if ($is_admin) {
    $popular_query = $koneksi->query("
        SELECT e.nama_eskul, COUNT(p.id) as total 
        FROM eskul e 
        LEFT JOIN pendaftaran p ON e.id = p.eskul_id AND p.status = 'diterima'
        GROUP BY e.id 
        ORDER BY total DESC 
        LIMIT 5
    ")->fetchAll();
}

$eskul_labels = [];
$eskul_data = [];
foreach ($popular_query as $res) {
    $eskul_labels[] = $res->nama_eskul;
    $eskul_data[] = $res->total;
}

// Fetch Latest Logs
$latest_logs = [];
if ($is_admin) {
    $latest_logs = $koneksi->query("
        SELECT * FROM logs 
        ORDER BY tanggal DESC 
        LIMIT 5
    ")->fetchAll();
}

$admin = [
    'nama' => $_SESSION['admin_data']['nama'] ?? $_SESSION['pembina_data']['nama'] ?? 'Pengguna',
    'role' => $is_admin ? 'Admin Master' : 'Pembina Ekskul'
];
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php
        $navTitle = $is_admin ? "Admin Overview" : "Pembina Dashboard";
        include '../includes/admin_nav.php';
        ?>

        <div class="container-fluid p-4">
            <!-- Welcome Alert -->
            <div class="alert bg-white border-0 shadow-sm rounded-4 p-4 mb-4 animate__animated animate__fadeInDown text-dark">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h3 class="fw-bold text-maroon">Selamat Datang, <?php echo htmlspecialchars($admin['nama']); ?>! 🚀</h3>
                        <p class="text-muted mb-0">
                            <?php if ($is_admin): ?>
                                Kelola informasi kegiatan ekstrakurikuler SMAN 2 Sukatani melalui dashboard ini secara terpadu.
                            <?php else: ?>
                                Kelola data kegiatan ekstrakurikuler <strong><?php echo htmlspecialchars($pembina_eskul_name ?: 'Belum Ditugaskan'); ?></strong> secara terpadu.
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-3 text-end d-none d-md-block">
                        <img src="https://cdn-icons-png.flaticon.com/512/2345/2345152.png" width="100">
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3">
                                <i class="fas fa-users fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0 font-weight-bold"><?php echo $is_admin ? 'Total Siswa' : 'Total Anggota'; ?></h6>
                                <h4 class="fw-bold mb-0"><?php echo number_format($total_siswa); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 me-3">
                                <i class="fas fa-running fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0 font-weight-bold"><?php echo $is_admin ? 'Total Ekskul' : 'Eskul Binaan'; ?></h6>
                                <h4 class="fw-bold mb-0 <?php echo !$is_admin ? 'fs-6' : ''; ?>"><?php echo $is_admin ? $total_eskul : htmlspecialchars($pembina_eskul_name ?: 'Belum Ditugaskan'); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4 me-3">
                                <i class="fas fa-file-invoice fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0 font-weight-bold">Pendaftar Baru</h6>
                                <h4 class="fw-bold mb-0"><?php echo $pendaftar_baru; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-4 me-3">
                                <i class="fas fa-trophy fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0 font-weight-bold">Total Prestasi</h6>
                                <h4 class="fw-bold mb-0"><?php echo $total_prestasi; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="<?php echo $is_admin ? 'col-lg-7' : 'col-lg-12'; ?>">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="fw-bold mb-4">Tren Pendaftaran (7 Hari Terakhir)</h5>
                        <canvas id="trendChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
                <?php if ($is_admin): ?>
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="fw-bold mb-4">Ekskul Terpopuler</h5>
                        <canvas id="popularChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="row g-4">
                <div class="<?php echo $is_admin ? 'col-lg-8' : 'col-lg-12'; ?>">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Pendaftaran Terbaru</h5>
                            <a href="kelola-pendaftaran.php" class="text-maroon small text-decoration-none fw-bold">Lihat Semua</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0 mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 rounded-start">Nama Siswa</th>
                                        <th class="border-0">Ekskul</th>
                                        <th class="border-0">Tanggal</th>
                                        <th class="border-0 rounded-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($latest_pendaftaran)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada pendaftaran terbaru.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($latest_pendaftaran as $row): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row->nama); ?>&background=random" class="rounded-circle me-3" width="30">
                                                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($row->nama); ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($row->nama_eskul); ?></td>
                                                <td><?php echo date('d M Y', strtotime($row->tanggal_daftar)); ?></td>
                                                <td>
                                                    <?php
                                                    $badge_class = 'bg-warning-subtle text-warning';
                                                    if ($row->status == 'diterima') $badge_class = 'bg-success-subtle text-success';
                                                    if ($row->status == 'ditolak') $badge_class = 'bg-danger-subtle text-danger';
                                                    ?>
                                                    <span class="badge <?php echo $badge_class; ?> px-3 rounded-pill text-capitalize"><?php echo $row->status; ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if ($is_admin): ?>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-4">Log Aktivitas Terbaru</h5>
                        <?php if (empty($latest_logs)): ?>
                            <div class="text-center py-3 text-muted small">Belum ada aktivitas.</div>
                        <?php else: ?>
                            <?php foreach ($latest_logs as $log): ?>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 me-3 <?php
                                                                    if ($log->tipe == 'SUKSES') echo 'text-success';
                                                                    elseif ($log->tipe == 'BAHAYA') echo 'text-danger';
                                                                    elseif ($log->tipe == 'PERINGATAN') echo 'text-warning';
                                                                    else echo 'text-primary';
                                                                    ?>">
                                        <i class="fas <?php
                                                        if ($log->tipe == 'SUKSES') echo 'fa-circle-check';
                                                        elseif ($log->tipe == 'BAHAYA') echo 'fa-triangle-exclamation';
                                                        elseif ($log->tipe == 'PERINGATAN') echo 'fa-shield-halved';
                                                        else echo 'fa-info-circle';
                                                        ?>"></i>
                                    </div>
                                    <div class="text-dark">
                                        <p class="mb-0 small fw-bold"><?php echo htmlspecialchars($log->aktivitas); ?></p>
                                        <p class="extra-small text-muted mb-0"><?php echo date('H:i', strtotime($log->tanggal)); ?> - <?php echo htmlspecialchars($log->keterangan); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <a href="logs.php" class="btn btn-outline-maroon w-100 rounded-pill small fw-bold mt-2">Log Selengkapnya</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($trend_labels); ?>,
            datasets: [{
                label: 'Jumlah Pendaftaran',
                data: <?php echo json_encode($trend_data); ?>,
                borderColor: '#800000',
                backgroundColor: 'rgba(128, 0, 0, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#800000'
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    <?php if ($is_admin): ?>
    // Popular Chart
    const popularCtx = document.getElementById('popularChart').getContext('2d');
    new Chart(popularCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($eskul_labels); ?>,
            datasets: [{
                label: 'Jumlah Siswa',
                data: <?php echo json_encode($eskul_data); ?>,
                backgroundColor: [
                    '#800000', '#A52A2A', '#D2691E', '#CD5C5C', '#E9967A'
                ],
                borderRadius: 8
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    <?php endif; ?>

    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });
</script>

<?php include '../includes/footer.php'; ?>