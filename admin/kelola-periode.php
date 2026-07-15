<?php
require_once '../includes/auth_check.php';
check_auth('admin');

$pageTitle = "Kelola Periode - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php';

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');

$msg = "";
$type = "";

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' && $is_admin) {
            $tahun_ajaran = trim($_POST['tahun_ajaran']);
            $semester = $_POST['semester'];
            $tanggal_mulai = $_POST['tanggal_mulai'];
            $tanggal_selesai = $_POST['tanggal_selesai'];
            $status = $_POST['status'] ?? 'nonaktif';

            if ($status === 'aktif') {
                // Set all other periods to nonaktif first
                $koneksi->exec("UPDATE periode SET status = 'nonaktif'");
            }

            $stmt = $koneksi->prepare("INSERT INTO periode (tahun_ajaran, semester, tanggal_mulai, tanggal_selesai, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$tahun_ajaran, $semester, $tanggal_mulai, $tanggal_selesai, $status]);
            log_activity($koneksi, 'Tambah Periode', "Menambahkan periode $tahun_ajaran - $semester", 'SUKSES');
            $msg = "Periode akademik baru berhasil ditambahkan!";
            $type = "success";
        } elseif ($_POST['action'] === 'edit' && $is_admin) {
            $id = $_POST['id'];
            $tahun_ajaran = trim($_POST['tahun_ajaran']);
            $semester = $_POST['semester'];
            $tanggal_mulai = $_POST['tanggal_mulai'];
            $tanggal_selesai = $_POST['tanggal_selesai'];

            $stmt = $koneksi->prepare("UPDATE periode SET tahun_ajaran = ?, semester = ?, tanggal_mulai = ?, tanggal_selesai = ? WHERE id = ?");
            $stmt->execute([$tahun_ajaran, $semester, $tanggal_mulai, $tanggal_selesai, $id]);
            log_activity($koneksi, 'Edit Periode', "Mengubah data periode ID $id", 'INFO');
            $msg = "Data periode akademik berhasil diperbarui!";
            $type = "success";
        } elseif ($_POST['action'] === 'activate' && $is_admin) {
            $id = $_POST['id'];
            // Transaction-like logic to ensure only one is active
            $koneksi->exec("UPDATE periode SET status = 'nonaktif'");
            $stmt = $koneksi->prepare("UPDATE periode SET status = 'aktif' WHERE id = ?");
            $stmt->execute([$id]);
            log_activity($koneksi, 'Aktivasi Periode', "Mengaktifkan periode ID $id", 'SUKSES');
            $msg = "Periode akademik berhasil diaktifkan!";
            $type = "success";
        } elseif ($_POST['action'] === 'delete' && $is_admin) {
            $id = $_POST['id'];
            
            // Check if active
            $stmt_chk = $koneksi->prepare("SELECT status FROM periode WHERE id = ?");
            $stmt_chk->execute([$id]);
            $status = $stmt_chk->fetchColumn();

            if ($status === 'aktif') {
                $msg = "Gagal menghapus! Periode aktif saat ini tidak dapat dihapus.";
                $type = "danger";
            } else {
                try {
                    $stmt = $koneksi->prepare("DELETE FROM periode WHERE id = ?");
                    $stmt->execute([$id]);
                    log_activity($koneksi, 'Hapus Periode', "Menghapus periode ID $id", 'BAHAYA');
                    $msg = "Periode akademik berhasil dihapus!";
                    $type = "success";
                } catch (PDOException $e) {
                    $msg = "Gagal menghapus! Periode ini sudah terhubung dengan data pendaftaran atau absensi.";
                    $type = "danger";
                }
            }
        }
    }
}

// Fetch all periods
$periods = $koneksi->query("SELECT * FROM periode ORDER BY tahun_ajaran DESC, semester DESC")->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Kelola Periode Akademik";
        $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i> Tambah Periode
                        </button>';
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if ($msg): ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 text-dark" role="alert">
                    <i class="fas fa-<?php echo $type == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h5 class="fw-bold mb-0 text-dark">Data Periode Akademik</h5>
                    <div class="position-relative" style="width: 250px;">
                        <input type="text" id="searchInput" class="form-control rounded-pill ps-4 pe-5" placeholder="Cari periode..." style="font-size: 0.85rem; border: 1px solid #ced4da;">
                        <i class="fas fa-search position-absolute text-muted" style="right: 18px; top: 50%; transform: translateY(-50%);"></i>
                    </div>
                </div>
                
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
                            <tr>
                                <th>Tahun Ajaran</th>
                                <th>Semester</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($periods)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada data periode akademik.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($periods as $row): ?>
                                    <tr>
                                        <td><span class="fw-bold text-dark"><?php echo htmlspecialchars($row->tahun_ajaran); ?></span></td>
                                        <td><span class="badge bg-light text-dark border px-3 text-capitalize"><?php echo htmlspecialchars($row->semester); ?></span></td>
                                        <td><?php echo date('d M Y', strtotime($row->tanggal_mulai)); ?></td>
                                        <td><?php echo date('d M Y', strtotime($row->tanggal_selesai)); ?></td>
                                        <td class="text-center">
                                            <?php if ($row->status === 'aktif'): ?>
                                                <span class="badge bg-success text-white px-3 rounded-pill">Aktif</span>
                                            <?php else: ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="action" value="activate">
                                                    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1">Set Aktif</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row->id; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger rounded-circle" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row->id; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade text-dark" id="editModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow rounded-4">
                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h5 class="fw-bold text-dark">Edit Periode Akademik</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <input type="hidden" name="action" value="edit">
                                                    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                    <div class="modal-body py-3">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Tahun Ajaran</label>
                                                            <input type="text" name="tahun_ajaran" class="form-control" value="<?php echo htmlspecialchars($row->tahun_ajaran); ?>" placeholder="Contoh: 2024/2025" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Semester</label>
                                                            <select name="semester" class="form-select" required>
                                                                <option value="ganjil" <?php echo $row->semester === 'ganjil' ? 'selected' : ''; ?>>Ganjil</option>
                                                                <option value="genap" <?php echo $row->semester === 'genap' ? 'selected' : ''; ?>>Genap</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Tanggal Mulai</label>
                                                            <input type="date" name="tanggal_mulai" class="form-control" value="<?php echo $row->tanggal_mulai; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Tanggal Selesai</label>
                                                            <input type="date" name="tanggal_selesai" class="form-control" value="<?php echo $row->tanggal_selesai; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-maroon rounded-pill px-4">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade text-dark" id="deleteModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow rounded-4">
                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h5 class="fw-bold text-dark">Hapus Periode</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                    <div class="modal-body py-3">
                                                        <p class="mb-0 text-muted">Apakah Anda yakin ingin menghapus periode akademik <strong><?php echo htmlspecialchars($row->tahun_ajaran); ?> (<?php echo htmlspecialchars($row->semester); ?>)</strong>?</p>
                                                        <small class="text-danger">* Tindakan ini tidak dapat dibatalkan.</small>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus Permanen</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade text-dark" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold text-dark">Tambah Periode Akademik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" placeholder="Contoh: 2024/2025" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Awal</label>
                        <select name="status" class="form-select">
                            <option value="nonaktif" selected>Non-Aktif</option>
                            <option value="aktif">Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon rounded-pill px-4">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });

    // Real-time search filter for academic periods
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
