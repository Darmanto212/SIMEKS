<?php 
session_start();
$pageTitle = "Pilih Ekskul - SIMEKS";
include '../config/koneksi.php'; 

require_once '../includes/auth_check.php';
check_auth('siswa');

$user_id = $_SESSION['siswa_data']['user_id'];

// Handle Registration
if (isset($_GET['daftar'])) {
    $eskul_id = $_GET['daftar'];
    
    // Check quota
    $stmt = $koneksi->prepare("
        SELECT e.kuota, (SELECT COUNT(*) FROM pendaftaran WHERE eskul_id = e.id AND status = 'diterima') as terdaftar 
        FROM eskul e WHERE e.id = ?
    ");
    $stmt->execute([$eskul_id]);
    $quota_info = $stmt->fetch();

    if ($quota_info->terdaftar >= $quota_info->kuota) {
        $msg = "Maaf, kuota untuk ekskul ini sudah penuh!";
        $type = "danger";
    } else {
        // Check if already registered
        $stmt = $koneksi->prepare("SELECT id FROM pendaftaran WHERE user_id = ? AND eskul_id = ?");
        $stmt->execute([$user_id, $eskul_id]);
        
        if ($stmt->fetch()) {
            $msg = "Kamu sudah mendaftar di ekskul ini!";
            $type = "warning";
        } else {
            $stmt = $koneksi->prepare("INSERT INTO pendaftaran (user_id, eskul_id, tanggal_daftar, status) VALUES (?, ?, CURDATE(), 'menunggu')");
            $stmt->execute([$user_id, $eskul_id]);
            $msg = "Pendaftaran berhasil dikirim! Mohon tunggu persetujuan admin.";
            $type = "success";
        }
    }
}

// Fetch All Eskul with registration counts
$eskul_list = $koneksi->query("
    SELECT e.*, (SELECT COUNT(*) FROM pendaftaran WHERE eskul_id = e.id AND status = 'diterima') as terdaftar 
    FROM eskul e 
    WHERE e.status = 'aktif' 
    ORDER BY e.nama_eskul ASC
")->fetchAll();

// Fetch Student's Registrations for status lookup
$stmt = $koneksi->prepare("SELECT eskul_id, status FROM pendaftaran WHERE user_id = ?");
$stmt->execute([$user_id]);
$my_registrations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

include '../includes/header.php'; 
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Daftar Pilihan Ekstrakurikuler";
        include '../includes/siswa_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if (isset($msg)): ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert">
                    <i class="fas <?php echo $type == 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> me-2"></i>
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php foreach ($eskul_list as $row): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <img src="<?php echo str_starts_with($row->gambar, 'http') ? $row->gambar : '../assets/'.$row->gambar; ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($row->nama_eskul); ?></h5>
                                <p class="text-muted small mb-3"><?php echo htmlspecialchars($row->deskripsi); ?></p>
                                
                                <div class="d-flex flex-column gap-2 mb-4">
                                    <div class="small d-flex align-items-center text-muted">
                                        <i class="fas fa-user-tie text-maroon me-2" style="width: 20px;"></i>
                                        <span><?php echo htmlspecialchars($row->pembina); ?></span>
                                    </div>
                                    <div class="small d-flex align-items-center text-muted">
                                        <i class="fas fa-calendar-alt text-maroon me-2" style="width: 20px;"></i>
                                        <span><?php echo htmlspecialchars($row->jadwal); ?></span>
                                    </div>
                                    <div class="small d-flex align-items-center text-muted">
                                        <i class="fas fa-map-marker-alt text-maroon me-2" style="width: 20px;"></i>
                                        <span><?php echo htmlspecialchars($row->lokasi); ?></span>
                                    </div>
                                    <div class="small d-flex align-items-center text-muted">
                                        <i class="fas fa-users text-maroon me-2" style="width: 20px;"></i>
                                        <span>Kuota: <?php echo $row->terdaftar; ?> / <?php echo $row->kuota; ?></span>
                                    </div>
                                </div>

                                <?php if (isset($my_registrations[$row->id])): ?>
                                    <?php 
                                        $status = $my_registrations[$row->id];
                                        if ($status == 'menunggu') echo '<button class="btn btn-secondary w-100 rounded-pill py-2 disabled">Menunggu Persetujuan</button>';
                                        elseif ($status == 'diterima') echo '<button class="btn btn-success w-100 rounded-pill py-2 disabled"><i class="fas fa-check-circle me-2"></i>Sudah Terdaftar</button>';
                                        else echo '<a href="?daftar='.$row->id.'" class="btn btn-maroon w-100 rounded-pill py-2">Daftar Lagi</a>';
                                    ?>
                                <?php elseif ($row->terdaftar >= $row->kuota): ?>
                                    <button class="btn btn-danger w-100 rounded-pill py-2 disabled">Kuota Penuh</button>
                                <?php else: ?>
                                    <a href="?daftar=<?php echo $row->id; ?>" class="btn btn-maroon w-100 rounded-pill py-2 shadow-sm">
                                        <i class="fas fa-plus-circle me-2"></i>Gabung Sekarang
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
