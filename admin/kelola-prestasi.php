<?php
require_once '../includes/auth_check.php';
check_auth('admin');
$pageTitle = "Kelola Prestasi - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

// Handle CRUD logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $stmt = $koneksi->prepare("INSERT INTO prestasi (user_id, eskul_id, nama_prestasi, tahun, deskripsi) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['user_id'], $_POST['eskul_id'], $_POST['nama_prestasi'], $_POST['tahun'], $_POST['deskripsi']]);
        } elseif ($_POST['action'] === 'edit') {
            $stmt = $koneksi->prepare("UPDATE prestasi SET user_id=?, eskul_id=?, nama_prestasi=?, tahun=?, deskripsi=? WHERE id=?");
            $stmt->execute([$_POST['user_id'], $_POST['eskul_id'], $_POST['nama_prestasi'], $_POST['tahun'], $_POST['deskripsi'], $_POST['id']]);
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $koneksi->prepare("DELETE FROM prestasi WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
        header("Location: kelola-prestasi.php");
        exit();
    }
}

// Fetch all achievements with eskul and user name
$prestasi_list = $koneksi->query("
    SELECT p.*, e.nama_eskul, u.nama as nama_siswa 
    FROM prestasi p 
    LEFT JOIN eskul e ON p.eskul_id = e.id 
    LEFT JOIN users u ON p.user_id = u.id
    ORDER BY p.tahun DESC, p.id DESC
")->fetchAll();

// Fetch all eskul for dropdown
$eskul_list = $koneksi->query("SELECT id, nama_eskul FROM eskul ORDER BY nama_eskul ASC")->fetchAll();

// Fetch all students for dropdown
$student_list = $koneksi->query("SELECT id, nama, kelas FROM users WHERE role = 'siswa' ORDER BY nama ASC")->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Kelola Prestasi Sekolah";
        $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i> Tambah Prestasi
                        </button>';
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Prestasi</th>
                                <th>Siswa</th>
                                <th>Ekskul</th>
                                <th>Tahun</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($prestasi_list)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data prestasi.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($prestasi_list as $row): ?>
                                    <tr>
                                        <td class="fw-bold text-dark">
                                            <?php echo htmlspecialchars($row->nama_prestasi); ?>
                                            <div class="extra-small text-muted font-monospace"><?php echo htmlspecialchars($row->deskripsi); ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-bold small"><?php echo htmlspecialchars($row->nama_siswa ?? 'Umum'); ?></div>
                                        </td>
                                        <td><span class="badge bg-maroon-light px-3 rounded-pill"><?php echo htmlspecialchars($row->nama_eskul ?? 'Umum'); ?></span></td>
                                        <td><code><?php echo htmlspecialchars($row->tahun); ?></code></td>
                                        <td class="text-center">
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
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Prestasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body text-dark">
                                                        <input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Prestasi</label>
                                                            <input type="text" name="nama_prestasi" class="form-control" value="<?php echo htmlspecialchars($row->nama_prestasi); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Siswa Peraih</label>
                                                            <select name="user_id" class="form-select" required>
                                                                <option value="">-- Pilih Siswa --</option>
                                                                <?php foreach ($student_list as $s): ?>
                                                                    <option value="<?php echo $s->id; ?>" <?php echo ($s->id == $row->user_id) ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($s->nama . " (" . $s->kelas . ")"); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-8 mb-3">
                                                                <label class="form-label">Ekskul Terkait</label>
                                                                <select name="eskul_id" class="form-select">
                                                                    <option value="">Umum / Tidak Ada</option>
                                                                    <?php foreach ($eskul_list as $e): ?>
                                                                        <option value="<?php echo $e->id; ?>" <?php echo ($e->id == $row->eskul_id) ? 'selected' : ''; ?>>
                                                                            <?php echo htmlspecialchars($e->nama_eskul); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">Tahun</label>
                                                                <input type="number" name="tahun" class="form-control" value="<?php echo htmlspecialchars($row->tahun); ?>" min="2000" max="<?php echo date('Y'); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Deskripsi</label>
                                                            <textarea name="deskripsi" class="form-control" rows="3"><?php echo htmlspecialchars($row->deskripsi); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-sm text-dark">
                                            <div class="modal-content">
                                                <div class="modal-body text-center py-4">
                                                    <i class="fas fa-trophy text-danger display-4 mb-3"></i>
                                                    <h5>Hapus Prestasi?</h5>
                                                    <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
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
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Prestasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Nama Prestasi</label>
                        <input type="text" name="nama_prestasi" class="form-control" placeholder="Contoh: Juara 1 Futsal Cup" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Siswa Peraih</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach ($student_list as $s): ?>
                                <option value="<?php echo $s->id; ?>"><?php echo htmlspecialchars($s->nama . " (" . $s->kelas . ")"); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Ekskul Terkait</label>
                            <select name="eskul_id" class="form-select">
                                <option value="">Umum / Tidak Ada</option>
                                <?php foreach ($eskul_list as $e): ?>
                                    <option value="<?php echo $e->id; ?>"><?php echo htmlspecialchars($e->nama_eskul); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="<?php echo date('Y'); ?>" min="2000" max="<?php echo date('Y'); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan singkat mengenai prestasi ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon">Tambahkan</button>
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
