<?php require_once '../includes/auth_check.php';
check_auth('admin');

$pageTitle = "Pengaturan - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$admin_id = $is_admin ? $_SESSION['admin_data']['user_id'] : $_SESSION['pembina_data']['user_id'];
$sessionKey = $is_admin ? 'admin_data' : 'pembina_data';

$msg = "";
$type = "";

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    $stmt = $koneksi->prepare("UPDATE users SET nama = ?, email = ? WHERE id = ?");
    $stmt->execute([$_POST['nama'], $_POST['email'], $admin_id]);
    $_SESSION[$sessionKey]['nama'] = $_POST['nama'];
    $msg = "Profil berhasil diperbarui!";
    $type = "success";
}

// Handle Password Change
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    $stmt = $koneksi->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch();
    
    if (password_verify($current, $admin->password)) {
        if (strlen($new) < 8) {
            $msg = "Password baru minimal harus 8 karakter!";
            $type = "danger";
        } elseif ($new === $confirm) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("UPDATE users SET password = ?, needs_password_change = 0 WHERE id = ?");
            $stmt->execute([$hashed, $admin_id]);
            $_SESSION['needs_password_change'] = 0;
            $msg = "Password berhasil diubah!";
            $type = "success";
        } else {
            $msg = "Konfirmasi password baru tidak cocok!";
            $type = "danger";
        }
    } else {
        $msg = "Password saat ini salah!";
        $type = "danger";
    }
}

// Fetch Admin Data
$stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();

if (isset($_GET['force_change'])) {
    $msg = "Demi keamanan, Anda wajib mengubah password bawaan dari Admin terlebih dahulu.";
    $type = "danger";
}
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Pengaturan Akun";
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

            <div class="row g-4 text-dark">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-4">Informasi Profil</h5>
                        <form method="POST">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($admin->nama); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin->email); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $is_admin ? 'Administrator' : 'Pembina Ekskul'; ?>" readonly>
                            </div>
                            <button type="submit" class="btn btn-maroon rounded-pill px-4">Simpan Profil</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-4">Keamanan & Password</h5>
                        <form method="POST">
                            <input type="hidden" name="change_password" value="1">
                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-outline-maroon rounded-pill px-4">Ganti Password</button>
                        </form>
                    </div>
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
</script>

<?php include '../includes/footer.php'; ?>
