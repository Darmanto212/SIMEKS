<?php require_once '../includes/auth_check.php';
check_auth('siswa');

$pageTitle = "Profil Saya - SIMEKS";
include '../config/koneksi.php'; 
include '../includes/header.php'; 

$user_id = $_SESSION['siswa_data']['user_id'];
$success_msg = "";
$error_msg = "";

// Handle Profile Information Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $stmt = $koneksi->prepare("UPDATE users SET nama = ?, kelas = ? WHERE id = ?");
    $stmt->execute([$_POST['nama'], $_POST['kelas'], $user_id]);
    $_SESSION['siswa_data']['nama'] = $_POST['nama'];
    $success_msg = "Informasi profil berhasil diperbarui!";
    log_activity($koneksi, 'Update Profil', 'Siswa memperbarui data diri', 'INFO');
}

// Handle Photo Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_profil'])) {
    $file = $_FILES['foto_profil'];
    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (in_array($ext, $allowed)) {
        if ($file['size'] < 2000000) { // 2MB limit
            $filename = "user_" . $user_id . "_" . time() . "." . $ext;
            $upload_path = "../assets/uploads/profile/" . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Delete old photo if exists
                $stmt = $koneksi->prepare("SELECT foto FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $old_photo = $stmt->fetchColumn();
                if ($old_photo && $old_photo != 'default.png' && file_exists("../assets/uploads/profile/" . $old_photo)) {
                    unlink("../assets/uploads/profile/" . $old_photo);
                }

                $stmt = $koneksi->prepare("UPDATE users SET foto = ? WHERE id = ?");
                $stmt->execute([$filename, $user_id]);
                $success_msg = "Foto profil berhasil diperbarui!";
                log_activity($koneksi, 'Update Foto', 'Siswa mengganti foto profil', 'INFO');
            } else {
                $error_msg = "Gagal mengunggah foto.";
            }
        } else {
            $error_msg = "Ukuran file terlalu besar (Maks 2MB).";
        }
    } else {
        $error_msg = "Format file tidak didukung (Hanya JPG/PNG).";
    }
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    $stmt = $koneksi->prepare("SELECT password, nisn FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_row = $stmt->fetch();

    if (password_verify($old_pass, $user_row->password)) {
        if (strlen($new_pass) < 8) {
            $error_msg = "Password baru minimal harus 8 karakter.";
        } elseif ($new_pass === $user_row->nisn) {
            $error_msg = "Password baru tidak boleh sama dengan NISN Anda.";
        } elseif ($new_pass === $confirm_pass) {
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("UPDATE users SET password = ?, needs_password_change = 0 WHERE id = ?");
            $stmt->execute([$new_hash, $user_id]);
            $_SESSION['needs_password_change'] = 0;
            $success_msg = "Password berhasil diubah!";
            log_activity($koneksi, 'Ganti Password', 'Siswa mengubah password akun', 'SECURITY');
        } else {
            $error_msg = "Konfirmasi password baru tidak cocok.";
        }
    } else {
        $error_msg = "Password lama salah.";
    }
}

// Fetch Student Info
$stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch Registration Statistics
$stmt = $koneksi->prepare("SELECT COUNT(*) FROM pendaftaran WHERE user_id = ? AND status = 'diterima'");
$stmt->execute([$user_id]);
$total_diterima = $stmt->fetchColumn();

$stmt = $koneksi->prepare("SELECT COUNT(*) FROM pendaftaran WHERE user_id = ? AND status = 'menunggu'");
$stmt->execute([$user_id]);
$total_menunggu = $stmt->fetchColumn();
if (isset($_GET['force_change'])) {
    $error_msg = "Demi keamanan, Anda wajib mengubah password bawaan dari Admin terlebih dahulu.";
}
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Profil Saya";
        include '../includes/siswa_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if ($success_msg): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 animate__animated animate__fadeIn" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($error_msg): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 animate__animated animate__shakeX" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Profile Information Card -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                        <div class="position-relative mb-4 mx-auto" style="width: 150px;">
                            <?php 
                                $foto_path = (!empty($user->foto) && $user->foto != 'default.png') 
                                    ? "../assets/uploads/profile/" . $user->foto 
                                    : "https://ui-avatars.com/api/?name=" . urlencode($user->nama) . "&background=800000&color=fff&size=200";
                            ?>
                            <img src="<?php echo $foto_path; ?>" class="rounded-circle shadow object-fit-cover" width="150" height="150">
                            <button class="btn btn-maroon btn-sm rounded-circle position-absolute bottom-0 end-0 p-2" data-bs-toggle="modal" data-bs-target="#photoModal">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($user->nama); ?></h4>
                        <p class="text-muted mb-4"><?php echo htmlspecialchars($user->nisn); ?> • <?php echo htmlspecialchars($user->kelas); ?></p>
                        
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="bg-light p-3 rounded-4">
                                    <h5 class="fw-bold mb-0 text-maroon"><?php echo $total_diterima; ?></h5>
                                    <span class="extra-small text-muted">Ekskul Aktif</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-3 rounded-4">
                                    <h5 class="fw-bold mb-0 text-warning"><?php echo $total_menunggu; ?></h5>
                                    <span class="extra-small text-muted">Aplikasi Pending</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-maroon rounded-pill fw-bold py-2" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                <i class="fas fa-lock me-2"></i> Ganti Password
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Card -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4">
                        <h5 class="fw-bold mb-4"><i class="fas fa-user-edit text-maroon me-2"></i>Informasi Pribadi</h5>
                        <form method="POST">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control bg-light border-0 py-3 rounded-3" value="<?php echo htmlspecialchars($user->nama); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase">NISN (Username)</label>
                                    <input type="text" class="form-control bg-white border py-3 rounded-3" value="<?php echo htmlspecialchars($user->nisn); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Kelas</label>
                                    <input type="text" name="kelas" class="form-control bg-light border-0 py-3 rounded-3" value="<?php echo htmlspecialchars($user->kelas); ?>" required>
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn btn-maroon px-5 py-3 rounded-pill fw-bold shadow-sm">
                                        <i class="fas fa-check me-2"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-maroon text-white position-relative overflow-hidden">
                        <div class="position-relative z-index-1">
                            <h5 class="fw-bold mb-2">Informasi Akun</h5>
                            <p class="small mb-0 opacity-75">Gunakan informasi NISN Anda untuk login ke sistem. Jika Anda lupa password atau ingin mengganti data permanen, hubungi Bagian Kesiswaan.</p>
                        </div>
                        <i class="fas fa-shield-alt position-absolute end-0 bottom-0 mb-n3 me-n2 fs-1 opacity-25" style="transform: rotate(-15deg);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Upload Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow text-dark">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Ganti Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-cloud-upload-alt fs-1 text-maroon mb-3"></i>
                        <p class="small text-muted">Pilih foto format JPG/PNG (Maks 2MB)</p>
                        <input type="file" name="foto_profil" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-maroon w-100 rounded-pill">Unggah Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow text-dark">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-key me-2 text-maroon"></i>Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="change_password" value="1">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Lama</label>
                        <input type="password" name="old_password" class="form-control rounded-3" required>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input type="password" name="new_password" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control rounded-3" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon rounded-pill px-4">Simpan Password</button>
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
