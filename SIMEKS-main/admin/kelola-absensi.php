<?php 
session_start();
require_once '../includes/auth_check.php';
check_auth('admin');

$pageTitle = "Kelola Absensi - SIMEKS";
include '../config/koneksi.php'; 
include '../includes/header.php'; 

// Fetch all eskul for dropdown
$eskul_list = $koneksi->query("SELECT * FROM eskul WHERE status = 'aktif' ORDER BY nama_eskul ASC")->fetchAll();

$selected_eskul = isset($_GET['eskul_id']) ? $_GET['eskul_id'] : '';
$selected_date = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Handle Attendance Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_absensi'])) {
    $eskul_id = $_POST['eskul_id'];
    $tanggal = $_POST['tanggal'];
    $absensi_data = $_POST['status']; // Array: [user_id => status]

    foreach ($absensi_data as $user_id => $status) {
        $ket = $_POST['keterangan'][$user_id] ?? '';
        
        // Check if already exists for this day
        $stmt = $koneksi->prepare("SELECT id FROM absensi WHERE user_id = ? AND eskul_id = ? AND tanggal = ?");
        $stmt->execute([$user_id, $eskul_id, $tanggal]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $koneksi->prepare("UPDATE absensi SET status = ?, keterangan = ? WHERE id = ?");
            $stmt->execute([$status, $ket, $existing->id]);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO absensi (user_id, eskul_id, tanggal, status, keterangan) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $eskul_id, $tanggal, $status, $ket]);
        }
    }
    $success_msg = "Absensi berhasil disimpan!";
    log_activity($koneksi, 'Input Absensi', "Input absensi ekskul ID $eskul_id tanggal $tanggal", 'INFO');
}

// Fetch Students in Selected Eskul
$students = [];
if ($selected_eskul) {
    $stmt = $koneksi->prepare("
        SELECT u.id, u.nama, u.kelas, a.status as current_status, a.keterangan as current_ket
        FROM pendaftaran p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN absensi a ON u.id = a.user_id AND a.eskul_id = ? AND a.tanggal = ?
        WHERE p.eskul_id = ? AND p.status = 'diterima'
        ORDER BY u.nama ASC
    ");
    $stmt->execute([$selected_eskul, $selected_date, $selected_eskul]);
    $students = $stmt->fetchAll();
}
?>

<div class="d-flex" id="wrapper">
    <?php include 'sidebar.php'; ?>
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Kelola Absensi";
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if (isset($success_msg)): ?>
                <div class="alert alert-success rounded-4 border-0 shadow-sm animate__animated animate__fadeIn">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Pilih Ekstrakurikuler</label>
                        <select name="eskul_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Eskul --</option>
                            <?php foreach ($eskul_list as $e): ?>
                                <option value="<?php echo $e->id; ?>" <?php echo $selected_eskul == $e->id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($e->nama_eskul); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" class="form-control rounded-3" value="<?php echo $selected_date; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-maroon w-100 rounded-pill">
                            <i class="fas fa-search me-2"></i> Tampilkan Siswa
                        </button>
                    </div>
                </form>
            </div>

            <?php if ($selected_eskul): ?>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <form method="POST">
                        <input type="hidden" name="eskul_id" value="<?php echo $selected_eskul; ?>">
                        <input type="hidden" name="tanggal" value="<?php echo $selected_date; ?>">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4">Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Status Kehadiran</th>
                                        <th class="px-4">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($students)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">Belum ada siswa terdaftar di ekskul ini.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($students as $s): ?>
                                            <tr>
                                                <td class="px-4 fw-bold"><?php echo htmlspecialchars($s->nama); ?></td>
                                                <td><?php echo htmlspecialchars($s->kelas); ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <input type="radio" class="btn-check" name="status[<?php echo $s->id; ?>]" id="h_<?php echo $s->id; ?>" value="hadir" <?php echo ($s->current_status == 'hadir' || !$s->current_status) ? 'checked' : ''; ?>>
                                                        <label class="btn btn-outline-success btn-sm rounded-start-pill px-3" for="h_<?php echo $s->id; ?>">H</label>

                                                        <input type="radio" class="btn-check" name="status[<?php echo $s->id; ?>]" id="i_<?php echo $s->id; ?>" value="izin" <?php echo ($s->current_status == 'izin') ? 'checked' : ''; ?>>
                                                        <label class="btn btn-outline-warning btn-sm px-3" for="i_<?php echo $s->id; ?>">I</label>

                                                        <input type="radio" class="btn-check" name="status[<?php echo $s->id; ?>]" id="s_<?php echo $s->id; ?>" value="sakit" <?php echo ($s->current_status == 'sakit') ? 'checked' : ''; ?>>
                                                        <label class="btn btn-outline-info btn-sm px-3" for="s_<?php echo $s->id; ?>">S</label>

                                                        <input type="radio" class="btn-check" name="status[<?php echo $s->id; ?>]" id="a_<?php echo $s->id; ?>" value="alpa" <?php echo ($s->current_status == 'alpa') ? 'checked' : ''; ?>>
                                                        <label class="btn btn-outline-danger btn-sm rounded-end-pill px-3" for="a_<?php echo $s->id; ?>">A</label>
                                                    </div>
                                                </td>
                                                <td class="px-4">
                                                    <input type="text" name="keterangan[<?php echo $s->id; ?>]" class="form-control form-control-sm rounded-pill" placeholder="Catatan..." value="<?php echo htmlspecialchars($s->current_ket ?? ''); ?>">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (!empty($students)): ?>
                            <div class="p-4 bg-light text-end">
                                <button type="submit" name="submit_absensi" class="btn btn-maroon px-5 rounded-pill fw-bold">
                                    <i class="fas fa-save me-2"></i> Simpan Semua Absensi
                                </button>
                            </div>
                        <?php endif; ?>
                    </form>
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
