<?php require_once '../includes/auth_check.php';
check_auth('admin');

$pageTitle = "Kelola Absensi - SIMEKS";
include '../config/koneksi.php'; 
include '../includes/header.php'; 

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$pembina_eskul_id = null;
if ($is_pembina) {
    $pembina_eskul_id = get_pembina_eskul_id($koneksi, $_SESSION['pembina_data']['user_id']);
}

// Fetch active eskul for dropdown based on role
if ($is_admin) {
    $eskul_list = $koneksi->query("SELECT * FROM eskul WHERE status = 'aktif' ORDER BY nama_eskul ASC")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_e = $koneksi->prepare("SELECT * FROM eskul WHERE id = ? AND status = 'aktif'");
        $stmt_e->execute([$pembina_eskul_id]);
        $eskul_list = $stmt_e->fetchAll();
    } else {
        $eskul_list = [];
    }
}

$selected_eskul = isset($_GET['eskul_id']) ? $_GET['eskul_id'] : '';
if (!$is_admin && $pembina_eskul_id) {
    $selected_eskul = $pembina_eskul_id;
}
$selected_date = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Handle Attendance Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_absensi'])) {
    $eskul_id = $_POST['eskul_id'];
    $tanggal = $_POST['tanggal'];
    $absensi_data = $_POST['status'] ?? []; // Array: [user_id => status]

    // Security check for Pembina
    if ($is_pembina && $eskul_id != $pembina_eskul_id) {
        header("Location: kelola-absensi.php");
        exit();
    }

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

// Handle Libur POST Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_libur'])) {
    $eskul_id = $_POST['eskul_id'];
    $tanggal = $_POST['tanggal'];
    
    // Security check for Pembina
    if ($is_pembina && $eskul_id != $pembina_eskul_id) {
        header("Location: kelola-absensi.php");
        exit();
    }

    if ($_POST['action_libur'] === 'set_libur') {
        $keterangan = $_POST['keterangan_libur'] ?? '';
        
        // Save to eskul_libur
        $stmt = $koneksi->prepare("INSERT INTO eskul_libur (eskul_id, tanggal, keterangan) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE keterangan = ?");
        $stmt->execute([$eskul_id, $tanggal, $keterangan, $keterangan]);
        
        // Delete any existing absensi on that day for this eskul
        $stmt = $koneksi->prepare("DELETE FROM absensi WHERE eskul_id = ? AND tanggal = ?");
        $stmt->execute([$eskul_id, $tanggal]);

        // Get eskul name
        $stmt_e = $koneksi->prepare("SELECT nama_eskul FROM eskul WHERE id = ?");
        $stmt_e->execute([$eskul_id]);
        $eskul_nama = $stmt_e->fetchColumn();

        // Send notification to all registered students of this eskul
        $stmt_s = $koneksi->prepare("SELECT user_id FROM pendaftaran WHERE eskul_id = ? AND status = 'diterima'");
        $stmt_s->execute([$eskul_id]);
        $students_to_notif = $stmt_s->fetchAll();

        foreach ($students_to_notif as $st) {
            send_notification($koneksi, $st->user_id, "Latihan Diliburkan ⚠️", "Latihan " . $eskul_nama . " pada tanggal " . date('d/m/Y', strtotime($tanggal)) . " ditiadakan. Keterangan: " . ($keterangan ?: 'Tidak ada keterangan.'), 'warning');
        }

        $success_msg = "Sesi latihan berhasil diliburkan dan notifikasi dikirim ke siswa!";
        log_activity($koneksi, 'Set Eskul Libur', "Diliburkan eskul ID $eskul_id tanggal $tanggal", 'PERINGATAN');
    } elseif ($_POST['action_libur'] === 'cancel_libur') {
        $stmt = $koneksi->prepare("DELETE FROM eskul_libur WHERE eskul_id = ? AND tanggal = ?");
        $stmt->execute([$eskul_id, $tanggal]);
        
        $success_msg = "Sesi latihan diaktifkan kembali!";
        log_activity($koneksi, 'Batal Eskul Libur', "Batal libur eskul ID $eskul_id tanggal $tanggal", 'INFO');
    }
}

// Check Libur Status
$is_libur = false;
$libur_keterangan = "";
if ($selected_eskul) {
    $stmt_chk_libur = $koneksi->prepare("SELECT keterangan FROM eskul_libur WHERE eskul_id = ? AND tanggal = ?");
    $stmt_chk_libur->execute([$selected_eskul, $selected_date]);
    $libur_row = $stmt_chk_libur->fetch();
    if ($libur_row) {
        $is_libur = true;
        $libur_keterangan = $libur_row->keterangan;
    }
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
        $navTitle = $is_admin ? "Kelola Absensi" : "Absensi Ekskul Binaan";
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if (isset($success_msg)): ?>
                <div class="alert alert-success rounded-4 border-0 shadow-sm animate__animated animate__fadeIn text-dark">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-dark">Pilih Ekstrakurikuler</label>
                        <?php if ($is_admin): ?>
                            <select name="eskul_id" class="form-select rounded-3" required>
                                <option value="">-- Pilih Eskul --</option>
                                <?php foreach ($eskul_list as $e): ?>
                                    <option value="<?php echo $e->id; ?>" <?php echo $selected_eskul == $e->id ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($e->nama_eskul); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <select name="eskul_id" class="form-select rounded-3" disabled>
                                <?php foreach ($eskul_list as $e): ?>
                                    <option value="<?php echo $e->id; ?>" selected>
                                        <?php echo htmlspecialchars($e->nama_eskul); ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if (empty($eskul_list)): ?>
                                    <option value="">Belum Ditugaskan</option>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" name="eskul_id" value="<?php echo $selected_eskul; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Tanggal Kegiatan</label>
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
                <!-- Libur Status Banner or Panel -->
                <?php if ($is_libur): ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-danger bg-opacity-10 text-danger animate__animated animate__fadeIn">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h6 class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Sesi Latihan Diliburkan!</h6>
                                <p class="mb-0 small text-dark">Latihan pada tanggal <strong><?php echo date('d/m/Y', strtotime($selected_date)); ?></strong> telah diliburkan.</p>
                                <p class="mb-0 small text-muted mt-1">Keterangan: <?php echo htmlspecialchars($libur_keterangan); ?></p>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="action_libur" value="cancel_libur">
                                <input type="hidden" name="eskul_id" value="<?php echo $selected_eskul; ?>">
                                <input type="hidden" name="tanggal" value="<?php echo $selected_date; ?>">
                                <button type="submit" class="btn btn-danger rounded-pill btn-sm px-4 fw-bold">
                                    <i class="fas fa-calendar-check me-1"></i> Aktifkan Kembali Sesi
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Informasi Sesi Latihan</h6>
                                <small class="text-muted">Status: Aktif (Latihan berjalan sesuai jadwal)</small>
                            </div>
                            <button type="button" class="btn btn-outline-warning rounded-pill btn-sm px-4 fw-bold" data-bs-toggle="collapse" data-bs-target="#collapseLibur">
                                <i class="fas fa-calendar-times me-1"></i> Atur Libur Sesi Ini
                            </button>
                        </div>
                        <div class="collapse mt-3" id="collapseLibur">
                            <form method="POST" class="border-top pt-3 mt-3">
                                <input type="hidden" name="action_libur" value="set_libur">
                                <input type="hidden" name="eskul_id" value="<?php echo $selected_eskul; ?>">
                                <input type="hidden" name="tanggal" value="<?php echo $selected_date; ?>">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark">Keterangan / Alasan Libur</label>
                                    <input type="text" name="keterangan_libur" class="form-control rounded-3" placeholder="Contoh: Libur Hari Raya, Ujian Semester, Cuaca Buruk..." required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-warning rounded-pill btn-sm px-4 fw-bold text-dark">Simpan Status Libur</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!$is_libur): ?>
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
                                                <td class="px-4 fw-bold text-dark"><?php echo htmlspecialchars($s->nama); ?></td>
                                                <td class="text-dark"><?php echo htmlspecialchars($s->kelas); ?></td>
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
                <?php endif; // End of !$is_libur check ?>
            <?php endif; // End of $selected_eskul check ?>
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
