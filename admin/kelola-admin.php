<?php
require_once '../includes/auth_check.php';
check_auth('admin');

// Only allow Admin Master to access this page
if (!isset($_SESSION['admin_data']) || $_SESSION['admin_data']['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Kelola Admin Master - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php';

$current_admin_id = $_SESSION['admin_data']['user_id'];
$msg = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Check if email already exists
            $stmt_check = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
            $stmt_check->execute([$email]);
            if ($stmt_check->fetch()) {
                $msg = "Email sudah terdaftar!";
                $type = "danger";
            } else {
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $koneksi->prepare("INSERT INTO users (nama, email, password, role, status, needs_password_change, foto) VALUES (?, ?, ?, 'admin', 'aktif', 1, 'default.png')");
                $stmt->execute([$nama, $email, $pass_hash]);
                log_activity($koneksi, 'Tambah Admin Master', "Nama: $nama, Email: $email", 'SUKSES');
                $msg = "Admin Master baru berhasil ditambahkan!";
                $type = "success";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'];
            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Check if email is used by another user
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
                
                // If editing self, update active session
                if ($id == $current_admin_id) {
                    $_SESSION['nama'] = $nama;
                    $_SESSION['admin_data']['nama'] = $nama;
                }

                log_activity($koneksi, 'Update Admin Master', "ID: $id, Nama: $nama", 'INFO');
                $msg = "Data Admin Master berhasil diperbarui!";
                $type = "success";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            
            // Prevent deleting self
            if ($id == $current_admin_id) {
                $msg = "Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!";
                $type = "danger";
            } else {
                $stmt_name = $koneksi->prepare("SELECT nama FROM users WHERE id = ?");
                $stmt_name->execute([$id]);
                $admin_name = $stmt_name->fetchColumn();

                $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                log_activity($koneksi, 'Hapus Admin Master', "ID: $id, Nama: $admin_name", 'BAHAYA');
                $msg = "Akun Admin Master berhasil dihapus!";
                $type = "success";
            }
        }
    }
}

// Fetch all Admin Master accounts
$admins = $koneksi->query("
    SELECT * FROM users 
    WHERE role = 'admin' 
    ORDER BY nama ASC
")->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Kelola Admin Master";
        $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i> Tambah Admin
                        </button>';
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if (!empty($msg)): ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 text-dark" role="alert">
                    <i class="fas <?php echo $type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email (Username)</th>
                                <th>Tanggal Registrasi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $row): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center text-dark">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row->nama); ?>&background=random" class="rounded-circle me-3" width="35">
                                            <div>
                                                <span class="fw-bold"><?php echo htmlspecialchars($row->nama); ?></span>
                                                <?php if ($row->id == $current_admin_id): ?>
                                                    <span class="badge bg-primary-subtle text-primary rounded-pill ms-2 px-2 py-1 extra-small">Anda</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-dark"><?php echo htmlspecialchars($row->email); ?></td>
                                    <td class="text-dark"><span class="small text-muted"><?php echo date('d M Y', strtotime($row->created_at)); ?></span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal<?php echo $row->id; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($row->id != $current_admin_id): ?>
                                            <button class="btn btn-sm btn-outline-danger rounded-circle" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal<?php echo $row->id; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle" disabled title="Tidak dapat menghapus diri sendiri">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?php echo $row->id; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-dark text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Akun Admin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
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

                                <?php if ($row->id != $current_admin_id): ?>
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-sm text-dark">
                                            <div class="modal-content">
                                                <div class="modal-body text-center py-4">
                                                    <i class="fas fa-user-times text-danger display-4 mb-3"></i>
                                                    <h5 class="fw-bold">Hapus Akun Admin?</h5>
                                                    <p class="text-muted small">Akun ini tidak akan memiliki akses kontrol sistem lagi.</p>
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
                                <?php endif; ?>
                            <?php endforeach; ?>
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
        <div class="modal-content text-dark text-start">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Akun Admin Master</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap Admin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (Digunakan untuk Login)</label>
                        <input type="email" name="email" class="form-control" placeholder="admin@sekolah.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password Admin" required>
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
