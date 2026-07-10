<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_trans_sid', 0);

    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    session_set_cookie_params([
        'lifetime' => 0, // Session cookie expires when browser is closed
        'path' => '/',
        'domain' => null,
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Check if user is logged in and session hasn't expired.
 */
function require_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verify presence of required session elements
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        logout_user();
        $base = (stripos($_SERVER['PHP_SELF'], '/siswa/') !== false || stripos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : './';
        header("Location: " . $base . "login.php");
        exit();
    }

    // Handle session timeout (1800 seconds = 30 minutes)
    $timeout = 1800;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        logout_user();
        $base = (stripos($_SERVER['PHP_SELF'], '/siswa/') !== false || stripos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : './';
        header("Location: " . $base . "login.php?timeout=1");
        exit();
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
}

/**
 * Restrict page access to a specific role.
 */
function require_role(string $role) {
    require_login();
    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        echo "<div style='text-align:center; margin-top:100px; font-family: sans-serif;'>
                <h2>403 Forbidden</h2>
                <p>Anda tidak memiliki hak akses ke halaman ini.</p>
                <a href='../logout.php'>Keluar dari sistem</a>
              </div>";
        exit();
    }
}

/**
 * Restrict page access to any of the specified roles.
 */
function require_any_role(array $roles) {
    require_login();
    if (!in_array($_SESSION['role'], $roles)) {
        http_response_code(403);
        echo "<div style='text-align:center; margin-top:100px; font-family: sans-serif;'>
                <h2>403 Forbidden</h2>
                <p>Anda tidak memiliki hak akses ke halaman ini.</p>
                <a href='../logout.php'>Keluar dari sistem</a>
              </div>";
        exit();
    }
}

/**
 * Get current user ID.
 */
function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role.
 */
function current_user_role() {
    return $_SESSION['role'] ?? null;
}

/**
 * Completely clear session and destroy session cookie.
 */
function logout_user() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Backward compatible auth checking function.
 */
function check_auth($requiredRole) {
    require_login();
    if ($requiredRole === 'admin') {
        require_any_role(['admin', 'pembina']);
    } else {
        require_role($requiredRole);
    }
}

/**
 * Record user activity to database
 */
function log_activity($koneksi, $aktivitas, $keterangan = null, $tipe = 'INFO')
{
    $user_id = null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Check which session is active to get user_id
    if (isset($_SESSION['admin_data'])) {
        $user_id = $_SESSION['admin_data']['user_id'];
    } elseif (isset($_SESSION['pembina_data'])) {
        $user_id = $_SESSION['pembina_data']['user_id'];
    } elseif (isset($_SESSION['siswa_data'])) {
        $user_id = $_SESSION['siswa_data']['user_id'];
    }

    $stmt = $koneksi->prepare("INSERT INTO logs (user_id, aktivitas, keterangan, tipe, ip_address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $aktivitas, $keterangan, $tipe, $ip]);
}

/**
 * Global helper to send notifications to users
 */
function send_notification($koneksi, $user_id, $judul, $pesan, $tipe = 'info')
{
    $stmt = $koneksi->prepare("INSERT INTO notifikasi (user_id, judul, pesan, type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $judul, $pesan, $tipe]);
}

/**
 * Get the eskul ID managed by a Pembina
 */
function get_pembina_eskul_id($koneksi, $pembina_id)
{
    $stmt = $koneksi->prepare("SELECT id FROM eskul WHERE pembina_id = ?");
    $stmt->execute([$pembina_id]);
    return $stmt->fetchColumn();
}

