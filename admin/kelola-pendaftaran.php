<?php
include_once '../includes/auth_check.php';
include_once '../config/koneksi.php';

// Access control: only admin and pembina can view this page
check_auth('admin');

$is_admin = ($_SESSION['role'] === 'admin');
$is_pembina = ($_SESSION['role'] === 'pembina');

// CSRF Token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle Approval/Rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die("Validasi CSRF gagal.");
    }

    // 2. Business Rule: Admin is read-only and cannot process pendaftaran
    if ($is_admin) {
        $msg = "Akses ditolak. Administrator hanya memiliki hak akses untuk memonitor pendaftaran.";
        $type = "danger";
    } else {
        $registration_id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? '';
        $alasan_penolakan = trim($_POST['catatan'] ?? '');

        if (!$registration_id || !in_array($status, ['diterima', 'ditolak'])) {
            $msg = "Parameter pendaftaran tidak valid!";
            $type = "danger";
        } else {
            // Validate: reason must be filled if status is ditolak
            if ($status === 'ditolak' && empty($alasan_penolakan)) {
                $msg = "Alasan penolakan wajib diisi jika status ditolak!";
                $type = "danger";
            } else {
                $koneksi->beginTransaction();
                try {
                    // Fetch pendaftaran and lock it
                    $stmt = $koneksi->prepare("SELECT * FROM pendaftaran WHERE id = ? FOR UPDATE");
                    $stmt->execute([$registration_id]);
                    $pendaftaran_row = $stmt->fetch();

                    if (!$pendaftaran_row) {
                        $msg = "Pendaftaran tidak ditemukan!";
                        $type = "danger";
                        $koneksi->rollBack();
                    } elseif ($pendaftaran_row->status !== 'menunggu') {
                        $msg = "Pendaftaran ini sudah diproses sebelumnya dan tidak dapat diubah!";
                        $type = "danger";
                        $koneksi->rollBack();
                    } else {
                        // Fetch and lock eskul to validate pembina and check quota
                        $stmt_eskul = $koneksi->prepare("SELECT * FROM eskul WHERE id = ? FOR UPDATE");
                        $stmt_eskul->execute([$pendaftaran_row->eskul_id]);
                        $eskul = $stmt_eskul->fetch();

                        if (!$eskul || $eskul->pembina_id != $_SESSION['user_id']) {
                            $msg = "Akses ditolak. Anda bukan pembina untuk ekstrakurikuler ini!";
                            $type = "danger";
                            $koneksi->rollBack();
                        } else {
                            if ($status === 'diterima') {
                                // Count current accepted members in this period
                                $stmt_count = $koneksi->prepare("
                                    SELECT COUNT(*) 
                                    FROM pendaftaran 
                                    WHERE eskul_id = ? AND periode_id = ? AND status = 'diterima' FOR UPDATE
                                ");
                                $stmt_count->execute([$pendaftaran_row->eskul_id, $pendaftaran_row->periode_id]);
                                $current_count = $stmt_count->fetchColumn();

                                if ($current_count >= $eskul->kuota) {
                                    $msg = "Pendaftaran gagal disetujui karena kuota untuk ekstrakurikuler " . htmlspecialchars($eskul->nama) . " sudah penuh!";
                                    $type = "danger";
                                    $koneksi->rollBack();
                                    goto after_process;
                                }
                            }

                            // Update registration status
                            $stmt_update = $koneksi->prepare("
                                UPDATE pendaftaran 
                                SET status = ?, alasan_penolakan = ?, diproses_oleh = ?, diproses_pada = CURRENT_TIMESTAMP 
                                WHERE id = ?
                            ");
                            $stmt_update->execute([
                                $status,
                                ($status === 'ditolak' ? $alasan_penolakan : null),
                                $_SESSION['user_id'],
                                $registration_id
                            ]);

                            // Send one notification to the student (event_key unique constraint prevents duplicates)
                            $event_key = 'pendaftaran_status_' . $registration_id;
                            $notif_title = ($status === 'diterima') ? "Pendaftaran Diterima! ✅" : "Pendaftaran Ditolak ❌";
                            $notif_msg = ($status === 'diterima')
                                ? "Selamat! Pendaftaran kamu di eskul " . $eskul->nama . " telah disetujui."
                                : "Maaf, pendaftaran kamu di eskul " . $eskul->nama . " belum dapat diterima. Alasan: " . $alasan_penolakan;

                            $stmt_notif = $koneksi->prepare("
                                INSERT INTO notifikasi (user_id, judul, pesan, reference_type, reference_id, event_key, type) 
                                VALUES (?, ?, ?, 'pendaftaran', ?, ?, ?)
                            ");
                            $stmt_notif->execute([
                                $pendaftaran_row->user_id,
                                $notif_title,
                                $notif_msg,
                                $registration_id,
                                $event_key,
                                ($status === 'diterima' ? 'success' : 'danger')
                            ]);

                            // Record activity log
                            log_activity($koneksi, 'Proses Pendaftaran', "Pendaftaran ID $registration_id diproses menjadi $status oleh Pembina", 'INFO');

                            $koneksi->commit();
                            $msg = "Pendaftaran berhasil diproses!";
                            $type = "success";
                        }
                    }
                } catch (Exception $e) {
                    $koneksi->rollBack();
                    error_log("Pembina approval transaction failed: " . $e->getMessage());
                    $msg = "Terjadi kesalahan sistem saat memproses pendaftaran.";
                    $type = "danger";
                }
            }
        }
    }
}
after_process:

// Fetch Pending & Latest Registrations based on role
if ($is_admin) {
    $pendaftaran = $koneksi->query("
        SELECT p.*, u.nama, u.kelas, e.nama AS nama_eskul, v.nama AS nama_verifikator
        FROM pendaftaran p
        JOIN users u ON p.user_id = u.id
        JOIN eskul e ON p.eskul_id = e.id
        LEFT JOIN users v ON p.diproses_oleh = v.id
        ORDER BY p.status = 'menunggu' DESC, p.created_at DESC
    ")->fetchAll();
} else {
    $stmt_p = $koneksi->prepare("
        SELECT p.*, u.nama, u.kelas, e.nama AS nama_eskul, v.nama AS nama_verifikator
        FROM pendaftaran p
        JOIN users u ON p.user_id = u.id
        JOIN eskul e ON p.eskul_id = e.id
        LEFT JOIN users v ON p.diproses_oleh = v.id
        WHERE e.pembina_id = ?
        ORDER BY p.status = 'menunggu' DESC, p.created_at DESC
    ");
    $stmt_p->execute([$_SESSION['user_id']]);
    $pendaftaran = $stmt_p->fetchAll();
}

$pageTitle = "Kelola Pendaftaran - SIMEKS";
include '../includes/header.php'; 
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = $is_admin ? "Kelola Pendaftaran Siswa" : "Verifikasi Pendaftaran Ekskul Binaan";
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if (isset($msg)): ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 text-dark" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h5 class="fw-bold mb-0 text-dark">Daftar Pendaftaran</h5>
                    <div class="position-relative" style="width: 250px;">
                        <input type="text" id="searchInput" class="form-control rounded-pill ps-4 pe-5" placeholder="Cari pendaftaran..." style="font-size: 0.85rem; border: 1px solid #ced4da;">
                        <i class="fas fa-search position-absolute text-muted" style="right: 18px; top: 50%; transform: translateY(-50%);"></i>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
                            <tr>
                                <th>Siswa</th>
                                <th>Ekstrakurikuler</th>
                                <th>Tanggal Daftar</th>
                                <th>Status saat ini</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pendaftaran)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pendaftaran.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pendaftaran as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row->nama); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($row->kelas); ?></div>
                                        </td>
                                        <td><span class="badge bg-maroon-light px-3 rounded-pill"><?php echo htmlspecialchars($row->nama_eskul); ?></span></td>
                                        <td class="text-dark"><?php echo date('d M Y', strtotime($row->created_at)); ?></td>
                                        <td>
                                            <?php 
                                                $badge = 'bg-warning-subtle text-warning';
                                                if ($row->status == 'diterima') $badge = 'bg-success-subtle text-success';
                                                if ($row->status == 'ditolak') $badge = 'bg-danger-subtle text-danger';
                                            ?>
                                            <span class="badge <?php echo $badge; ?> px-3 rounded-pill text-capitalize"><?php echo $row->status; ?></span>
                                        </td>
                                        <td class="text-center">
                                             <?php if ($row->status == 'menunggu'): ?>
                                                 <?php if ($is_pembina): ?>
                                                     <div class="d-flex justify-content-center gap-1">
                                                         <form method="POST" style="display:inline;">
                                                             <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                             <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                             <input type="hidden" name="status" value="diterima">
                                                             <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" onclick="return confirm('Terima pendaftaran ini?')">
                                                                 <i class="fas fa-check me-1"></i> Terima
                                                             </button>
                                                         </form>
                                                         
                                                         <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                                                 data-bs-toggle="modal" 
                                                                 data-bs-target="#rejectModal<?php echo $row->id; ?>">
                                                             <i class="fas fa-times me-1"></i> Tolak
                                                         </button>
                                                     </div>

                                                     <!-- Reject Modal -->
                                                     <div class="modal fade" id="rejectModal<?php echo $row->id; ?>" tabindex="-1" aria-hidden="true">
                                                         <div class="modal-dialog modal-dialog-centered">
                                                             <div class="modal-content rounded-4 border-0 shadow text-dark text-start">
                                                                 <form method="POST">
                                                                     <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                                     <div class="modal-header border-0 pb-0">
                                                                         <h5 class="modal-title fw-bold">Alasan Penolakan</h5>
                                                                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                     </div>
                                                                     <div class="modal-body">
                                                                         <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                                         <input type="hidden" name="status" value="ditolak">
                                                                         <div class="mb-3">
                                                                             <label class="form-label small fw-bold">Berikan alasan pendaftaran ditolak:</label>
                                                                             <textarea name="catatan" class="form-control rounded-3" rows="3" placeholder="Contoh: Kuota penuh, Persyaratan tidak lengkap, dll." required></textarea>
                                                                         </div>
                                                                     </div>
                                                                     <div class="modal-footer border-0 pt-0">
                                                                         <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                                         <button type="submit" class="btn btn-danger rounded-pill px-4">Kirim Penolakan</button>
                                                                     </div>
                                                                 </form>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 <?php else: ?>
                                                     <span class="text-muted small fst-italic">Menunggu verifikasi Pembina</span>
                                                 <?php endif; ?>
                                             <?php else: ?>
                                                 <div class="d-flex flex-column align-items-center text-dark">
                                                     <span class="text-muted small fst-italic">Diproses oleh: <?php echo htmlspecialchars($row->nama_verifikator ?? 'Sistem'); ?></span>
                                                     <span class="small fw-bold"><?php echo $row->status == 'diterima' ? 'Diterima ✅' : 'Ditolak ❌'; ?></span>
                                                     <?php if($row->alasan_penolakan): ?>
                                                        <span class="extra-small text-muted text-center" style="max-width: 150px;">"<?php echo htmlspecialchars($row->alasan_penolakan); ?>"</span>
                                                     <?php endif; ?>
                                                 </div>
                                             <?php endif; ?>
                                        </td>
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

    // Real-time search filter for registrations
    document.getElementById("searchInput").addEventListener("input", function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll("tbody tr:not(.no-result-row)");
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(query)) {
                row.style.setProperty("display", "", "important");
                visibleCount++;
            } else {
                row.style.setProperty("display", "none", "important");
            }
        });

        // Handle no results display
        let noResultRow = document.querySelector(".no-result-row");
        if (visibleCount === 0 && query !== "") {
            if (!noResultRow) {
                const tbody = document.querySelector("tbody");
                noResultRow = document.createElement("tr");
                noResultRow.className = "no-result-row";
                noResultRow.innerHTML = `<td colspan="6" class="text-center py-5 text-muted">Tidak ditemukan hasil pencarian untuk "${this.value}"</td>`;
                tbody.appendChild(noResultRow);
            } else {
                noResultRow.style.setProperty("display", "", "important");
                noResultRow.querySelector("td").textContent = `Tidak ditemukan hasil pencarian untuk "${this.value}"`;
            }
        } else if (noResultRow) {
            noResultRow.style.setProperty("display", "none", "important");
        }
    });
</script>

<?php include '../includes/footer.php'; ?>
