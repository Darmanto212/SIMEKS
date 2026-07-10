<?php
include_once 'includes/auth_check.php';
include_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = trim($_POST['nisn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 1. Password validation
    if (strlen($password) < 8) {
        echo "<script>alert('Password minimal harus 8 karakter!'); window.location='register.php';</script>";
        exit();
    }

    if ($password === $nisn) {
        echo "<script>alert('Password tidak boleh sama dengan NISN Anda!'); window.location='register.php';</script>";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.location='register.php';</script>";
        exit();
    }

    try {
        // 2. Validate if NISN is pre-registered by Admin
        $stmt = $koneksi->prepare("SELECT * FROM users WHERE nisn = ? AND role = 'siswa'");
        $stmt->execute([$nisn]);
        $user = $stmt->fetch();

        if (!$user) {
            echo "<script>alert('Pendaftaran gagal. NISN Anda belum terdaftar oleh Admin. Silakan hubungi pihak sekolah.'); window.location='register.php';</script>";
            exit();
        }

        // Check if account is already activated (status is active and doesn't need password change)
        if ($user->status === 'aktif' && $user->needs_password_change == 0) {
            echo "<script>alert('Akun dengan NISN ini sudah terdaftar dan aktif. Silakan login.'); window.location='login.php';</script>";
            exit();
        }

        // 3. Validate email uniqueness (must not be used by other accounts)
        $stmt_email = $koneksi->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt_email->execute([$email, $user->id]);
        if ($stmt_email->fetch()) {
            echo "<script>alert('Email sudah digunakan oleh akun lain! Gunakan email lain.'); window.location='register.php';</script>";
            exit();
        }

        // 4. Activate the pre-registered student record
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $stmt_update = $koneksi->prepare("UPDATE users SET nama = ?, email = ?, password = ?, status = 'aktif', needs_password_change = 0 WHERE id = ?");
        $stmt_update->execute([$nama, $email, $hashed_pass, $user->id]);

        log_activity($koneksi, 'Aktivasi Akun Berhasil', 'Siswa mengaktifkan akun dengan NISN: ' . htmlspecialchars($nisn), 'SUKSES');

        echo "<script>alert('Aktivasi Akun Berhasil! Silakan Login.'); window.location='login.php';</script>";
        exit();
    } catch (PDOException $e) {
        error_log("Registration activation error: " . $e->getMessage());
        echo "<script>alert('Terjadi kesalahan pada sistem. Silakan coba lagi.'); window.location='register.php';</script>";
        exit();
    }
}
?>
