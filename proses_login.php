<?php
include_once 'includes/auth_check.php';
include_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username'] ?? ''; // Login uses username field for email or NISN
    $password = $_POST['password'] ?? '';

    // Search for user by email or NISN
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE email = ? OR nisn = ?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch();

    if ($user) {
        // 1. Check if user is inactive (nonaktif)
        if ($user->status === 'nonaktif') {
            log_activity($koneksi, 'Upaya Login Gagal', 'Akun nonaktif mencoba login: ' . htmlspecialchars($email), 'PERINGATAN');
            echo "<script>alert('Identitas pengguna atau kata sandi tidak sesuai.'); window.location='login.php';</script>";
            exit();
        }

        // 3. Verify password
        if (password_verify($password, $user->password)) {

            // Clear old session data and regenerate session ID to prevent session fixation
            session_unset();
            session_regenerate_id(true);

            // Populate session keys
            $_SESSION['user_id'] = $user->id;
            $_SESSION['nama']    = $user->nama;
            $_SESSION['role']    = $user->role;
            $_SESSION['last_activity'] = time();
            $_SESSION['needs_password_change'] = $user->needs_password_change;

            // Backward compatibility structures
            $sessionKey = $user->role . '_data';
            $_SESSION[$sessionKey] = [
                'user_id' => $user->id,
                'nama'    => $user->nama,
                'role'    => $user->role
            ];

            log_activity($koneksi, 'Login Berhasil', 'Pengguna masuk ke sistem sebagai ' . $user->role, 'SUKSES');

            if ($user->role === 'admin' || $user->role === 'pembina') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: siswa/dashboardsiswa.php");
            }
            exit();
        } else {
            log_activity($koneksi, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan identitas: ' . htmlspecialchars($email), 'PERINGATAN');
        }
    } else {
        // Timing attack mitigation for non-existent users
        password_verify($password, '$2y$10$abcdefghijklmnopqrstuvwxyDummyHashForTimingMitigation');
        log_activity($koneksi, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan identitas non-eksisten: ' . htmlspecialchars($email), 'PERINGATAN');
    }

    // Generic login failure message
    echo "<script>alert('Identitas pengguna atau kata sandi tidak sesuai.'); window.location='login.php';</script>";
    exit();
}
