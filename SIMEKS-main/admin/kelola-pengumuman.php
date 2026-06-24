<?php 
session_start();
require_once '../includes/auth_check.php';
check_auth('admin');

$pageTitle = "Kelola Pengumuman - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $stmt = $koneksi->prepare("INSERT INTO pengumuman (judul, isi, kategori) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['judul'], $_POST['isi'], $_POST['kategori']]);
            
            // Broadcast to all students
            $students_res = $koneksi->query("SELECT id FROM users WHERE role = 'siswa'")->fetchAll();
            foreach ($students_res as $s_row) {
                send_notification($koneksi, $s_row->id, "Pengumuman Baru: " . $_POST['judul'], "Ada info baru kategori " . $_POST['kategori'], 'info');
            }
            
            log_activity($koneksi, 'Tambah Pengumuman', 'Judul: ' . $_POST['judul'], 'SUKSES');
        } elseif ($_POST['action'] === 'edit') {
            $stmt = $koneksi->prepare("UPDATE pengumuman SET judul=?, isi=?, kategori=? WHERE id=?");
            $stmt->execute([$_POST['judul'], $_POST['isi'], $_POST['kategori'], $_POST['id']]);
            log_activity($koneksi, 'Update Pengumuman', 'ID: ' . $_POST['id'], 'INFO');
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $koneksi->prepare("DELETE FROM pengumuman WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            log_activity($koneksi, 'Hapus Pengumuman', 'ID: ' . $_POST['id'], 'BAHAYA');
        }
        header("Location: kelola-pengumuman.php");
        exit();
    }
}

// Fetch all announcements
$pengumuman_list = $koneksi->query("SELECT * FROM pengumuman ORDER BY tanggal DESC")->fetchAll();

?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Kelola Pengumuman";
        $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i> Tambah Pengumuman
                        </button>';
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0">Tanggal</th>
                                <th class="py-3 border-0">Kategori</th>
                                <th class="py-3 border-0">Judul</th>
                                <th class="py-3 border-0">Isi</th>
                                <th class="py-3 border-0 text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pengumuman_list)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada pengumuman.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pengumuman_list as $row): ?>
                                    <tr>
                                        <td class="px-4 small text-muted"><?php echo date('d/m/Y H:i', strtotime($row->tanggal)); ?></td>
                                        <td>
                                            <?php 
                                            $badge = 'bg-info';
                                            if ($row->kategori == 'PENTING') $badge = 'bg-danger';
                                            elseif ($row->kategori == 'UPDATE') $badge = 'bg-success';
                                            elseif ($row->kategori == 'EVENT') $badge = 'bg-primary';
                                            ?>
                                            <span class="badge <?php echo $badge; ?> rounded-pill px-3"><?php echo $row->kategori; ?></span>
                                        </td>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row->judul); ?></td>
                                        <td class="text-muted small"><?php echo substr(htmlspecialchars($row->isi), 0, 50) . '...'; ?></td>
                                        <td class="text-end px-4">
                                            <button class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal<?php echo $row->id; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger rounded-circle" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal<?php echo $row->id; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-dark">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold">Edit Pengumuman</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kategori</label>
                                                            <select name="kategori" class="form-select" required>
                                                                <option value="INFO" <?php echo $row->kategori == 'INFO' ? 'selected' : ''; ?>>INFO</option>
                                                                <option value="PENTING" <?php echo $row->kategori == 'PENTING' ? 'selected' : ''; ?>>PENTING</option>
                                                                <option value="UPDATE" <?php echo $row->kategori == 'UPDATE' ? 'selected' : ''; ?>>UPDATE</option>
                                                                <option value="EVENT" <?php echo $row->kategori == 'EVENT' ? 'selected' : ''; ?>>EVENT</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Judul</label>
                                                            <input type="text" name="judul" class="form-control" value="<?php echo htmlspecialchars($row->judul); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Isi Pengumuman</label>
                                                            <textarea name="isi" class="form-control" rows="4" required><?php echo htmlspecialchars($row->isi); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-maroon px-4">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content text-dark text-center">
                                                <div class="modal-body p-4">
                                                    <i class="fas fa-exclamation-triangle text-danger fs-1 mb-3"></i>
                                                    <h5 class="fw-bold mb-3">Hapus Pengumuman?</h5>
                                                    <p class="text-muted small mb-4">Aksi ini tidak dapat dibatalkan.</p>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger w-100">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
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
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Pengumuman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="INFO">INFO</option>
                            <option value="PENTING">PENTING</option>
                            <option value="UPDATE">UPDATE</option>
                            <option value="EVENT">EVENT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" placeholder="Judul Pengumuman" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Pengumuman</label>
                        <textarea name="isi" class="form-control" rows="4" placeholder="Tulis pengumuman di sini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon px-4">Posting</button>
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
</script>

<?php include '../includes/footer.php'; ?>
