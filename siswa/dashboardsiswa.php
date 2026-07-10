<?php require_once '../includes/auth_check.php';
check_auth('siswa');

$pageTitle = "Dashboard Siswa - SIMEKS";
include '../config/koneksi.php'; 
include '../includes/header.php'; 

$user_id = $_SESSION['siswa_data']['user_id'];

// Fetch Student Info
$stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();

// Fetch Active Ekskul (Registrations)
$stmt = $koneksi->prepare("
    SELECT p.*, e.nama_eskul, e.jadwal, e.lokasi 
    FROM pendaftaran p
    JOIN eskul e ON p.eskul_id = e.id
    WHERE p.user_id = ? AND p.status = 'diterima'
");
$stmt->execute([$user_id]);
$active_eskul = $stmt->fetchAll();
$total_aktif = count($active_eskul);

// Fetch All Registrations (for status check)
$stmt = $koneksi->prepare("
    SELECT p.*, e.nama_eskul 
    FROM pendaftaran p
    JOIN eskul e ON p.eskul_id = e.id
    WHERE p.user_id = ?
    ORDER BY p.tanggal_daftar DESC
");
$stmt->execute([$user_id]);
$all_registrations = $stmt->fetchAll();

// Fetch Latest 4 Announcements
$announcements = $koneksi->query("SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 4")->fetchAll();

// Fetch Real Attendance Stats
$stmt = $koneksi->prepare("SELECT COUNT(*) FROM absensi WHERE user_id = ? AND status = 'hadir'");
$stmt->execute([$user_id]);
$real_hadir = $stmt->fetchColumn();

$stmt = $koneksi->prepare("SELECT COUNT(*) FROM absensi WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_absen = $stmt->fetchColumn();
$persen_hadir = ($total_absen > 0) ? round(($real_hadir / $total_absen) * 100) : 0;

// Fetch Real Achievement Count
$stmt = $koneksi->prepare("SELECT COUNT(*) FROM prestasi WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_prestasi = $stmt->fetchColumn();

// Mock data for UI if user not found (failsafe)
$user = [
    'nama' => $user_data->nama ?? 'Budi Santoso',
    'nisn' => $user_data->nisn ?? '1234567890',
    'kelas' => $user_data->kelas ?? 'XII MIPA 1'
];
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Dashboard Siswa";
        include '../includes/siswa_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <!-- Welcome Alert -->
            <div class="alert bg-white border-0 shadow-sm rounded-4 p-4 mb-4 animate__animated animate__fadeInDown">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h3 class="fw-bold text-maroon">Halo, <?php echo $user['nama']; ?>! 👋</h3>
                        <p class="text-muted mb-0">Selamat datang di sistem manajemen ekstrakurikuler. Jangan lupa cek jadwal latihan kamu minggu ini!</p>
                    </div>
                    <div class="col-md-3 text-end d-none d-md-block">
                        <img src="https://cdn-icons-png.flaticon.com/512/3220/3220551.png" width="100">
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100 transition-hover">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-maroon bg-opacity-10 text-maroon p-3 rounded-4 me-3">
                                <i class="fas fa-running fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0 fw-bold">Ekskul Aktif</h6>
                                <h4 class="fw-bold mb-0"><?php echo $total_aktif; ?></h4>
                            </div>
                        </div>
                        <p class="mb-0 small text-muted">
                            <?php 
                                if($total_aktif > 0) {
                                    $names = array_map(fn($e) => $e->nama_eskul, $active_eskul);
                                    echo htmlspecialchars(implode(", ", $names));
                                } else {
                                    echo "Belum ada ekskul aktif kamu ikuti";
                                }
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100 transition-hover">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 me-3">
                                <i class="fas fa-calendar-check fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0 fw-bold">Status Kehadiran</h6>
                                <h4 class="fw-bold mb-0"><?php echo $persen_hadir; ?>%</h4>
                            </div>
                        </div>
                        <p class="mb-0 small text-muted text-truncate"><?php echo $real_hadir; ?> dari <?php echo $total_absen; ?> pertemuan tercatat</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100 transition-hover">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4 me-3">
                                <i class="fas fa-medal fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0 fw-bold">Prestasi Diraih</h6>
                                <h4 class="fw-bold mb-0"><?php echo $total_prestasi; ?></h4>
                            </div>
                        </div>
                        <p class="mb-0 small text-muted text-truncate">Lihat detail di menu Prestasi</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Jadwal Minggu Ini</h5>
                            <a href="#" class="text-maroon small text-decoration-none fw-bold">Lihat Semua</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 rounded-start">Ekskul</th>
                                        <th class="border-0">Hari</th>
                                        <th class="border-0">Waktu</th>
                                        <th class="border-0">Lokasi</th>
                                        <th class="border-0 rounded-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($active_eskul)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Kamu belum terdaftar di ekskul manapun.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($active_eskul as $row): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-maroon bg-opacity-10 text-maroon p-2 rounded-3 me-3">
                                                            <i class="fas fa-running"></i>
                                                        </div>
                                                        <span class="fw-bold"><?php echo htmlspecialchars($row->nama_eskul); ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo explode(", ", $row->jadwal)[0] ?? $row->jadwal; ?></td>
                                                <td><?php echo explode(", ", $row->jadwal)[1] ?? '-'; ?></td>
                                                <td><?php echo htmlspecialchars($row->lokasi); ?></td>
                                                <td><span class="badge bg-success-subtle text-success px-3 rounded-pill text-capitalize"><?php echo $row->status; ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Attendance Chart Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-4">Statistik Kehadiran</h5>
                        <div style="height: 200px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                        <div class="mt-3 text-center">
                            <span class="badge bg-success-subtle text-success rounded-pill px-3">Hadir: <?php echo $real_hadir; ?></span>
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Absen: <?php echo $total_absen - $real_hadir; ?></span>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Pengumuman Terbaru</h5>
                            <a href="pengumuman.php" class="text-maroon small text-decoration-none fw-bold">Lihat Semua</a>
                        </div>
                        
                        <?php if (empty($announcements)): ?>
                            <div class="text-center py-4 text-muted small">Belum ada pengumuman.</div>
                        <?php else: ?>
                            <?php foreach ($announcements as $ann): ?>
                                <?php 
                                    $badge = 'bg-info';
                                    if ($ann->kategori == 'PENTING') $badge = 'bg-danger';
                                    elseif ($ann->kategori == 'UPDATE') $badge = 'bg-success';
                                    elseif ($ann->kategori == 'EVENT') $badge = 'bg-primary';
                                ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <span class="badge <?php echo $badge; ?> mb-2"><?php echo $ann->kategori; ?></span>
                                    <p class="mb-1 fw-bold small"><?php echo htmlspecialchars($ann->judul); ?></p>
                                    <p class="extra-small text-muted mb-0"><?php echo date('d M Y', strtotime($ann->tanggal)); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <a href="pengumuman.php" class="btn btn-outline-maroon w-100 rounded-pill small fw-bold mt-2">Semua Informasi</a>
                    </div>

                    <!-- Registration Status Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Status Pendaftaran</h5>
                            <i class="fas fa-file-invoice text-muted"></i>
                        </div>
                        
                        <?php if (empty($all_registrations)): ?>
                            <div class="text-center py-4 text-muted small">Belum ada riwayat pendaftaran.</div>
                        <?php else: ?>
                            <div class="registration-timeline">
                                <?php foreach (array_slice($all_registrations, 0, 3) as $reg): ?>
                                    <div class="d-flex mb-3 pb-2 border-bottom">
                                        <div class="me-3">
                                            <?php if ($reg->status == 'menunggu'): ?>
                                                <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                            <?php elseif ($reg->status == 'diterima'): ?>
                                                <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-circle">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold small"><?php echo htmlspecialchars($reg->nama_eskul); ?></p>
                                            <span class="extra-small text-capitalize <?php 
                                                if($reg->status == 'menunggu') echo 'text-warning';
                                                elseif($reg->status == 'diterima') echo 'text-success';
                                                else echo 'text-danger';
                                            ?>"><?php echo $reg->status; ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <a href="daftar-eskul.php" class="btn btn-light w-100 rounded-pill small fw-bold mt-2">Cek Eskul Lain</a>
                    </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const attCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Tidak Hadir'],
            datasets: [{
                data: [<?php echo $real_hadir; ?>, <?php echo ($total_absen - $real_hadir); ?>],
                backgroundColor: ['#198754', '#dc3545'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12 }
                    }
                }
            },
            maintainAspectRatio: false
        }
    });
</script>

<?php include '../includes/footer.php'; ?>