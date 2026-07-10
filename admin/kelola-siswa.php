<?php
require_once '../includes/auth_check.php';
check_auth('admin');
$pageTitle = "Kelola Siswa - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$pembina_eskul_id = null;
if ($is_pembina) {
    $pembina_eskul_id = get_pembina_eskul_id($koneksi, $_SESSION['pembina_data']['user_id']);
}

// Handle CRUD logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' && $is_admin) {
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("INSERT INTO users (nama, nisn, email, password, kelas, role, status, needs_password_change) VALUES (?, ?, ?, ?, ?, 'siswa', 'aktif', 1)");
            $stmt->execute([$_POST['nama'], $_POST['nisn'], $_POST['email'], $pass, $_POST['kelas']]);
            log_activity($koneksi, 'Tambah Siswa', 'Nama: ' . $_POST['nama'], 'SUKSES');
        } elseif ($_POST['action'] === 'edit') {
            // Check if user has permission to edit this student
            $can_edit = false;
            if ($is_admin) {
                $can_edit = true;
            } elseif ($is_pembina && $pembina_eskul_id) {
                $stmt_chk = $koneksi->prepare("SELECT id FROM pendaftaran WHERE user_id = ? AND eskul_id = ? AND status = 'diterima'");
                $stmt_chk->execute([$_POST['id'], $pembina_eskul_id]);
                if ($stmt_chk->fetch()) {
                    $can_edit = true;
                }
            }

            if ($can_edit) {
                $stmt = $koneksi->prepare("UPDATE users SET nama=?, nisn=?, email=?, kelas=? WHERE id=?");
                $stmt->execute([$_POST['nama'], $_POST['nisn'], $_POST['email'], $_POST['kelas'], $_POST['id']]);
                log_activity($koneksi, 'Update Profil Siswa', 'ID: ' . $_POST['id'] . ' Nama: ' . $_POST['nama'], 'INFO');
            }
        } elseif ($_POST['action'] === 'delete' && $is_admin) {
            $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            log_activity($koneksi, 'Hapus Siswa', 'ID: ' . $_POST['id'], 'BAHAYA');
        }
        header("Location: kelola-siswa.php");
        exit();
    }
}

// Fetch students based on role
if ($is_admin) {
    $students = $koneksi->query("SELECT * FROM users WHERE role = 'siswa' ORDER BY nama ASC")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_s = $koneksi->prepare("
            SELECT u.* 
            FROM pendaftaran p
            JOIN users u ON p.user_id = u.id
            WHERE p.eskul_id = ? AND p.status = 'diterima' AND u.role = 'siswa'
            ORDER BY u.nama ASC
        ");
        $stmt_s->execute([$pembina_eskul_id]);
        $students = $stmt_s->fetchAll();
    } else {
        $students = [];
    }
}
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = $is_admin ? "Kelola Data Siswa" : "Daftar Siswa Ekskul Binaan";
        $extraAction = '';
        if ($is_admin) {
            $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fas fa-plus me-2"></i> Tambah Siswa
                            </button>';
        }
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Email</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data siswa.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row->nama); ?>&background=random" class="rounded-circle me-3" width="35">
                                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($row->nama); ?></span>
                                            </div>
                                        </td>
                                        <td><code><?php echo htmlspecialchars($row->nisn); ?></code></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row->kelas); ?></span></td>
                                        <td><?php echo htmlspecialchars($row->email); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal<?php echo $row->id; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($is_admin): ?>
                                            <button class="btn btn-sm btn-outline-danger rounded-circle" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal<?php echo $row->id; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Data Siswa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body text-start">
                                                        <input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Lengkap</label>
                                                            <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($row->nama); ?>" required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">NISN</label>
                                                                <input type="text" name="nisn" class="form-control" value="<?php echo htmlspecialchars($row->nisn); ?>" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Kelas</label>
                                                                <input type="text" name="kelas" class="form-control" value="<?php echo htmlspecialchars($row->kelas); ?>" placeholder="Contoh: XII MIPA 1" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row->email); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-maroon rounded-pill px-4">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($is_admin): ?>
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body text-center py-4">
                                                    <i class="fas fa-user-times text-danger display-4 mb-3"></i>
                                                    <h5 class="fw-bold">Hapus Siswa?</h5>
                                                    <p class="text-muted small">Data pendaftaran siswa ini juga akan terhapus.</p>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-3">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($is_admin): ?>
<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control" placeholder="10 digit nomor" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" class="form-control" placeholder="X / XI / XII ..." required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Default</label>
                        <input type="password" name="password" class="form-control" value="siswa123" required>
                        <small class="text-muted">Password awal siswa (Bisa diganti nanti)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon rounded-pill px-4">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });
</script>

<?php include '../includes/footer.php'; ?>
