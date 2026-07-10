<?php
require_once '../includes/auth_check.php';
check_auth('admin');

// Only allow Admin Master to access this page
if (!isset($_SESSION['admin_data']) || $_SESSION['admin_data']['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Kelola Pembina - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php';

// Handle CRUD operations
$msg = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Validation: Check if email already exists
            $stmt_check = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
            $stmt_check->execute([$email]);
            if ($stmt_check->fetch()) {
                $msg = "Email sudah terdaftar!";
                $type = "danger";
            } else {
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $koneksi->prepare("INSERT INTO users (nama, email, password, role, status, needs_password_change, foto) VALUES (?, ?, ?, 'pembina', 'aktif', 1, 'default.png')");
                $stmt->execute([$nama, $email, $pass_hash]);
                log_activity($koneksi, 'Tambah Pembina', "Nama: $nama, Email: $email", 'SUKSES');
                $msg = "Pembina baru berhasil ditambahkan!";
                $type = "success";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'];
            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Validation: Check if email is used by another user
            $stmt_check = $koneksi->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt_check->execute([$email, $id]);
            if ($stmt_check->fetch()) {
                $msg = "Email sudah digunakan oleh akun lain!";
                $type = "danger";
            } else {
                if (!empty($password)) {
                    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $koneksi->prepare("UPDATE users SET nama = ?, email = ?, password = ?, needs_password_change = 1 WHERE id = ?");
                    $stmt->execute([$nama, $email, $pass_hash, $id]);
                } else {
                    $stmt = $koneksi->prepare("UPDATE users SET nama = ?, email = ? WHERE id = ?");
                    $stmt->execute([$nama, $email, $id]);
                }
                log_activity($koneksi, 'Update Pembina', "ID: $id, Nama: $nama", 'INFO');
                $msg = "Data pembina berhasil diperbarui!";
                $type = "success";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            
            // Get pembina name for logs
            $stmt_name = $koneksi->prepare("SELECT nama FROM users WHERE id = ?");
            $stmt_name->execute([$id]);
            $pembina_name = $stmt_name->fetchColumn();

            $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            log_activity($koneksi, 'Hapus Pembina', "ID: $id, Nama: $pembina_name", 'BAHAYA');
            $msg = "Akun pembina berhasil dihapus!";
            $type = "success";
        }
    }
}

// Fetch all pembinas and the eskul they manage
$pembinas = $koneksi->query("
    SELECT u.*, e.nama_eskul 
    FROM users u 
    LEFT JOIN eskul e ON u.id = e.pembina_id 
    WHERE u.role = 'pembina' 
    ORDER BY u.nama ASC
")->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Kelola Pembina Ekskul";
        $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i> Tambah Pembina
                        </button>';
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if (!empty($msg)): ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert">
                    <i class="fas <?php echo $type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Ekskul yang Dibina</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pembinas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pembina. Silakan tambahkan pembina baru.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pembinas as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row->nama); ?>&background=random" class="rounded-circle me-3" width="35">
                                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($row->nama); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($row->email); ?></td>
                                        <td>
                                            <?php if ($row->nama_eskul): ?>
                                                <span class="badge bg-success-subtle text-success px-3 rounded-pill">
                                                    <i class="fas fa-running me-1"></i> <?php echo htmlspecialchars($row->nama_eskul); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning px-3 rounded-pill">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> Belum ditugaskan
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="small text-muted"><?php echo date('d M Y', strtotime($row->created_at)); ?></span></td>
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
                                                    <h5 class="modal-title fw-bold">Edit Akun Pembina</h5>
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
                                                        <div class="mb-3">
                                                            <label class="form-label">Email (Username Login)</label>
                                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row->email); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                                                            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
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

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body text-center py-4">
                                                    <i class="fas fa-user-times text-danger display-4 mb-3"></i>
                                                    <h5 class="fw-bold">Hapus Akun Pembina?</h5>
                                                    <p class="text-muted small">Ekskul yang dibina pembina ini akan kehilangan relasi pembina.</p>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-3">Hapus Akun</button>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Akun Pembina Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap Pembina" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (Digunakan untuk Login)</label>
                        <input type="email" name="email" class="form-control" placeholder="pembina@sekolah.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Default</label>
                        <input type="password" name="password" class="form-control" value="pembina123" required>
                        <small class="text-muted">Password awal untuk log in pembina.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon rounded-pill px-4">Buat Akun</button>
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
