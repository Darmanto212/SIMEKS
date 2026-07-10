<?php
include_once 'includes/auth_check.php';
include_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username'] ?? ''; // Login uses username field for email or NISN
    $password = $_POST['password'] ?? '';

    $max_attempts = 5;
    $lockout_time = 15 * 60; // 15 minutes in seconds

    // Search for user by email or NISN
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE email = ? OR nisn = ?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch();

    if ($user) {
        // 1. Check if user is locked out
        if ($user->lock_until && strtotime($user->lock_until) > time()) {
            $remaining = ceil((strtotime($user->lock_until) - time()) / 60);
            log_activity($koneksi, 'Upaya Login Gagal', 'Akun terkunci mencoba login: ' . htmlspecialchars($email), 'PERINGATAN');
            echo "<script>alert('Akun Anda dikunci sementara. Silakan coba lagi beberapa saat lagi.'); window.location='login.php';</script>";
            exit();
        }

        // 2. Check if user is inactive (nonaktif)
        if ($user->status === 'nonaktif') {
            log_activity($koneksi, 'Upaya Login Gagal', 'Akun nonaktif mencoba login: ' . htmlspecialchars($email), 'PERINGATAN');
            echo "<script>alert('Identitas pengguna atau kata sandi tidak sesuai.'); window.location='login.php';</script>";
            exit();
        }

        // 3. Verify password
        if (password_verify($password, $user->password)) {
            // Reset login attempts on success
            $stmt_reset = $koneksi->prepare("UPDATE users SET login_attempts = 0, lock_until = NULL WHERE id = ?");
            $stmt_reset->execute([$user->id]);

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
            // Increment failed login attempts
            $attempts = $user->login_attempts + 1;
            $lock_until = null;
            
            if ($attempts >= $max_attempts) {
                $lock_until = date('Y-m-d H:i:s', time() + $lockout_time);
                $stmt_lock = $koneksi->prepare("UPDATE users SET login_attempts = ?, lock_until = ? WHERE id = ?");
                $stmt_lock->execute([$attempts, $lock_until, $user->id]);
                log_activity($koneksi, 'Upaya Login Gagal', 'Akun terkunci karena salah password 5 kali: ' . htmlspecialchars($email), 'PERINGATAN');
            } else {
                $stmt_inc = $koneksi->prepare("UPDATE users SET login_attempts = ? WHERE id = ?");
                $stmt_inc->execute([$attempts, $user->id]);
                log_activity($koneksi, 'Upaya Login Gagal', 'Seseorang mencoba masuk dengan identitas: ' . htmlspecialchars($email) . " (Percobaan ke-$attempts)", 'PERINGATAN');
            }
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
