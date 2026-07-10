<?php
include_once '../includes/auth_check.php';
include_once '../config/koneksi.php';

check_auth('siswa');

$user_id = $_SESSION['user_id'];

// CSRF Token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 1. Fetch active period
$stmt_period = $koneksi->query("SELECT * FROM periode WHERE status = 'aktif' LIMIT 1");
$active_period = $stmt_period->fetch();

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_eskul'])) {
    // CSRF verification
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die("Validasi CSRF gagal.");
    }

    $eskul_id = filter_var($_POST['eskul_id'] ?? null, FILTER_VALIDATE_INT);
    
    if (!$eskul_id) {
        $msg = "ID Ekstrakurikuler tidak valid!";
        $type = "danger";
    } elseif (!$active_period) {
        $msg = "Pendaftaran gagal. Tidak ada periode akademik yang aktif saat ini!";
        $type = "danger";
    } else {
        // Fetch and lock eskul + validate active pembina
        $stmt_eskul = $koneksi->prepare("
            SELECT e.*, u.id AS pembina_user_id, u.status AS pembina_status, u.nama AS pembina_nama
            FROM eskul e
            JOIN users u ON e.pembina_id = u.id
            WHERE e.id = ? AND e.status = 'aktif' AND u.status = 'aktif' AND u.role = 'pembina' AND e.deleted_at IS NULL AND u.deleted_at IS NULL
        ");
        $stmt_eskul->execute([$eskul_id]);
        $eskul = $stmt_eskul->fetch();

        if (!$eskul) {
            $msg = "Ekstrakurikuler tidak aktif atau tidak memiliki pembina aktif saat ini.";
            $type = "danger";
        } else {
            $koneksi->beginTransaction();
            try {
                // Check double registration in this period
                $stmt_dup = $koneksi->prepare("SELECT id FROM pendaftaran WHERE user_id = ? AND eskul_id = ? AND periode_id = ? FOR UPDATE");
                $stmt_dup->execute([$user_id, $eskul_id, $active_period->id]);
                $already_registered = $stmt_dup->fetch();

                if ($already_registered) {
                    $msg = "Kamu sudah mendaftar di ekstrakurikuler ini untuk periode sekarang!";
                    $type = "warning";
                    $koneksi->rollBack();
                } else {
                    // Check quota
                    $stmt_quota = $koneksi->prepare("SELECT COUNT(*) FROM pendaftaran WHERE eskul_id = ? AND periode_id = ? AND status = 'diterima' FOR UPDATE");
                    $stmt_quota->execute([$eskul_id, $active_period->id]);
                    $current_registered = $stmt_quota->fetchColumn();

                    if ($current_registered >= $eskul->kuota) {
                        $msg = "Maaf, kuota untuk ekstrakurikuler ini sudah penuh!";
                        $type = "danger";
                        $koneksi->rollBack();
                    } else {
                        // Insert registration
                        $stmt_insert = $koneksi->prepare("INSERT INTO pendaftaran (user_id, eskul_id, periode_id, status) VALUES (?, ?, ?, 'menunggu')");
                        $stmt_insert->execute([$user_id, $eskul_id, $active_period->id]);
                        $pendaftaran_id = $koneksi->lastInsertId();

                        // Send notification to the pembina
                        $event_key = 'pendaftaran_baru_' . $pendaftaran_id;
                        $notif_title = "Pendaftaran Baru 📝";
                        $notif_msg = "Siswa " . $_SESSION['nama'] . " mendaftar di ekstrakurikuler " . $eskul->nama;
                        
                        $stmt_notif = $koneksi->prepare("
                            INSERT INTO notifikasi (user_id, judul, pesan, reference_type, reference_id, event_key, type) 
                            VALUES (?, ?, ?, 'pendaftaran', ?, ?, 'info')
                        ");
                        $stmt_notif->execute([$eskul->pembina_user_id, $notif_title, $notif_msg, $pendaftaran_id, $event_key]);

                        // Record activity log
                        log_activity($koneksi, 'Pendaftaran Eskul', 'Siswa mendaftar ke eskul ' . $eskul->nama . ' (ID Pendaftaran: ' . $pendaftaran_id . ')', 'INFO');

                        $koneksi->commit();
                        $msg = "Pendaftaran berhasil dikirim! Mohon tunggu persetujuan pembina.";
                        $type = "success";
                    }
                }
            } catch (Exception $e) {
                $koneksi->rollBack();
                error_log("Student registration failed: " . $e->getMessage());
                $msg = "Terjadi kesalahan sistem saat memproses pendaftaran.";
                $type = "danger";
            }
        }
    }
}

// Fetch All Eskul with active pembina
$period_id_val = $active_period->id ?? 0;
$eskul_list = $koneksi->query("
    SELECT e.*, u.nama AS pembina_nama,
           (SELECT COUNT(*) FROM pendaftaran WHERE eskul_id = e.id AND status = 'diterima' AND periode_id = $period_id_val) as terdaftar 
    FROM eskul e 
    JOIN users u ON e.pembina_id = u.id
    WHERE e.status = 'aktif' 
      AND u.role = 'pembina' 
      AND u.status = 'aktif'
      AND e.deleted_at IS NULL
      AND u.deleted_at IS NULL
    ORDER BY e.nama ASC
")->fetchAll();

// Fetch Student's Registrations for status lookup in current active period
$stmt = $koneksi->prepare("SELECT eskul_id, status FROM pendaftaran WHERE user_id = ? AND periode_id = ?");
$stmt->execute([$user_id, $period_id_val]);
$my_registrations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$pageTitle = "Pilih Eskul - SIMEKS";
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php foreach ($eskul_list as $row): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <img src="<?php echo str_starts_with($row->gambar, 'http') ? $row->gambar : '../assets/uploads/eskul/'.$row->gambar; ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($row->nama); ?></h5>
                                <p class="text-muted small mb-3"><?php echo htmlspecialchars($row->deskripsi); ?></p>
                                
                                <div class="d-flex flex-column gap-2 mb-4">
                                    <div class="small d-flex align-items-center text-muted">
                                        <i class="fas fa-user-tie text-maroon me-2" style="width: 20px;"></i>
                                        <span><?php echo htmlspecialchars($row->pembina_nama); ?></span>
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

                                <?php if (!$active_period): ?>
                                    <button class="btn btn-secondary w-100 rounded-pill py-2 disabled">Periode Nonaktif</button>
                                <?php elseif (isset($my_registrations[$row->id])): ?>
                                    <?php 
                                        $status = $my_registrations[$row->id];
                                        if ($status == 'menunggu') echo '<button class="btn btn-secondary w-100 rounded-pill py-2 disabled">Menunggu Persetujuan</button>';
                                        elseif ($status == 'diterima') echo '<button class="btn btn-success w-100 rounded-pill py-2 disabled"><i class="fas fa-check-circle me-2"></i>Sudah Terdaftar</button>';
                                        else {
                                            ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="eskul_id" value="<?php echo $row->id; ?>">
                                                <button type="submit" name="register_eskul" class="btn btn-maroon w-100 rounded-pill py-2">Daftar Lagi</button>
                                            </form>
                                            <?php
                                        }
                                    ?>
                                <?php elseif ($row->terdaftar >= $row->kuota): ?>
                                    <button class="btn btn-danger w-100 rounded-pill py-2 disabled">Kuota Penuh</button>
                                <?php else: ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="eskul_id" value="<?php echo $row->id; ?>">
                                        <button type="submit" name="register_eskul" class="btn btn-maroon w-100 rounded-pill py-2 shadow-sm">
                                            <i class="fas fa-plus-circle me-2"></i>Gabung Sekarang
                                        </button>
                                    </form>
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
