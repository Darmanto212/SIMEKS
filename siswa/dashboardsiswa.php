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
$announcements = $koneksi->query("SELECT * FROM pengumuman ORDER BY tanggal_terbit DESC LIMIT 4")->fetchAll();

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

        <div class="container-fluid px-4 py-3">
            <!-- Welcome Alert -->
            <div class="alert bg-white border-0 shadow-sm rounded-4 p-3 mb-3 animate__animated animate__fadeInDown text-dark">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <h4 class="fw-bold text-maroon mb-1">Halo, <?php echo $user['nama']; ?>! 👋</h4>
                        <p class="text-muted small mb-0">Selamat datang di sistem manajemen ekstrakurikuler. Jangan lupa cek jadwal latihan kamu minggu ini!</p>
                    </div>
                    <div class="col-md-2 text-end d-none d-md-block">
                        <img src="https://cdn-icons-png.flaticon.com/512/3220/3220551.png" width="60">
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row g-3 mb-3">
                <div class="col-md-4 col-4">
                    <div class="card border-0 shadow-sm rounded-4 p-2 px-3 h-100 transition-hover">
                        <div class="d-flex align-items-center stats-card-container">
                            <div class="bg-maroon bg-opacity-10 text-maroon p-2 rounded-3 me-3" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-running fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-muted extra-small mb-0 fw-bold">Ekskul Aktif</h6>
                                <h5 class="fw-bold mb-0"><?php echo $total_aktif; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-4">
                    <div class="card border-0 shadow-sm rounded-4 p-2 px-3 h-100 transition-hover">
                        <div class="d-flex align-items-center stats-card-container">
                            <div class="bg-success bg-opacity-10 text-success p-2 rounded-3 me-3" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-check fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-muted extra-small mb-0 fw-bold">Status Kehadiran</h6>
                                <h5 class="fw-bold mb-0"><?php echo $persen_hadir; ?>%</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-4">
                    <div class="card border-0 shadow-sm rounded-4 p-2 px-3 h-100 transition-hover">
                        <div class="d-flex align-items-center stats-card-container">
                            <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3 me-3" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-medal fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-muted extra-small mb-0 fw-bold">Prestasi Diraih</h6>
                                <h5 class="fw-bold mb-0"><?php echo $total_prestasi; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 1: Jadwal & Statistik Kehadiran -->
            <div class="row g-3 mb-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark">Jadwal Minggu Ini</h6>
                            <a href="#" class="text-maroon extra-small text-decoration-none fw-bold">Lihat Semua</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0 mb-0 table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 rounded-start py-2">Ekskul</th>
                                        <th class="border-0 py-2">Hari</th>
                                        <th class="border-0 py-2">Waktu</th>
                                        <th class="border-0 py-2">Lokasi</th>
                                        <th class="border-0 rounded-end py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($active_eskul)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4 small">Kamu belum terdaftar di ekskul manapun.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($active_eskul as $row): ?>
                                            <tr>
                                                <td class="py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-maroon bg-opacity-10 text-maroon p-1 rounded-2 me-2" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-running extra-small" style="font-size: 0.8rem;"></i>
                                                        </div>
                                                        <span class="fw-bold small text-dark"><?php echo htmlspecialchars($row->nama_eskul); ?></span>
                                                    </div>
                                                </td>
                                                <td class="py-2 small"><?php echo explode(", ", $row->jadwal)[0] ?? $row->jadwal; ?></td>
                                                <td class="py-2 small"><?php echo explode(", ", $row->jadwal)[1] ?? '-'; ?></td>
                                                <td class="py-2 small"><?php echo htmlspecialchars($row->lokasi); ?></td>
                                                <td class="py-2"><span class="badge bg-success-subtle text-success px-2 rounded-pill text-capitalize extra-small"><?php echo $row->status; ?></span></td>
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
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <h6 class="fw-bold mb-3 text-dark">Statistik Kehadiran</h6>
                        <div style="height: 120px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                        <div class="mt-2 text-center">
                            <span class="badge bg-success-subtle text-success rounded-pill px-2 extra-small">Hadir: <?php echo $real_hadir; ?></span>
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 extra-small">Absen: <?php echo $total_absen - $real_hadir; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Pengumuman Terbaru & Status Pendaftaran -->
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark">Pengumuman Terbaru</h6>
                            <a href="pengumuman.php" class="text-maroon extra-small text-decoration-none fw-bold">Lihat Semua</a>
                        </div>
                        
                        <?php if (empty($announcements)): ?>
                            <div class="text-center py-4 text-muted small">Belum ada pengumuman.</div>
                        <?php else: ?>
                            <?php foreach (array_slice($announcements, 0, 2) as $ann): ?>
                                <?php 
                                    $badge = 'bg-info';
                                    if ($ann->kategori == 'PENTING') $badge = 'bg-danger';
                                    elseif ($ann->kategori == 'UPDATE') $badge = 'bg-success';
                                    elseif ($ann->kategori == 'EVENT') $badge = 'bg-primary';
                                ?>
                                <div class="mb-2 pb-2 border-bottom">
                                    <span class="badge <?php echo $badge; ?> extra-small mb-1" style="font-size: 0.65rem;"><?php echo $ann->kategori; ?></span>
                                    <p class="mb-0 fw-bold small text-dark"><?php echo htmlspecialchars($ann->judul); ?></p>
                                    <p class="extra-small text-muted mb-0" style="font-size: 0.65rem;"><?php echo date('d M Y', strtotime($ann->tanggal_terbit)); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <a href="pengumuman.php" class="btn btn-outline-maroon w-100 rounded-pill extra-small fw-bold mt-2 py-1" style="font-size: 0.7rem;">Semua Informasi</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- Registration Status Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark">Status Pendaftaran</h6>
                            <i class="fas fa-file-invoice text-muted small"></i>
                        </div>
                        
                        <?php if (empty($all_registrations)): ?>
                            <div class="text-center py-4 text-muted small">Belum ada riwayat pendaftaran.</div>
                        <?php else: ?>
                            <div class="registration-timeline">
                                <?php foreach (array_slice($all_registrations, 0, 2) as $reg): ?>
                                    <div class="d-flex mb-2 pb-2 border-bottom">
                                        <div class="me-3">
                                            <?php if ($reg->status == 'menunggu'): ?>
                                                <div class="bg-warning bg-opacity-10 text-warning p-1 rounded-circle" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-clock extra-small" style="font-size: 0.75rem;"></i>
                                                </div>
                                            <?php elseif ($reg->status == 'diterima'): ?>
                                                <div class="bg-success bg-opacity-10 text-success p-1 rounded-circle" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-check extra-small" style="font-size: 0.75rem;"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="bg-danger bg-opacity-10 text-danger p-1 rounded-circle" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-times extra-small" style="font-size: 0.75rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold small text-dark"><?php echo htmlspecialchars($reg->nama_eskul); ?></p>
                                            <span class="extra-small text-capitalize <?php 
                                                if($reg->status == 'menunggu') echo 'text-warning';
                                                elseif($reg->status == 'diterima') echo 'text-success';
                                                else echo 'text-danger';
                                            ?>" style="font-size: 0.65rem;"><?php echo $reg->status; ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <a href="daftar-eskul.php" class="btn btn-light w-100 rounded-pill extra-small fw-bold mt-2 py-1" style="font-size: 0.7rem;">Cek Eskul Lain</a>
                    </div>
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