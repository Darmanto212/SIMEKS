<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}

include __DIR__ . '/../../config/koneksi.php';

try {
    // 1. Add status column
    $cols = $koneksi->query("SHOW COLUMNS FROM users LIKE 'status'")->fetchAll();
    if (empty($cols)) {
        $koneksi->exec("ALTER TABLE users ADD COLUMN status ENUM('aktif', 'nonaktif') DEFAULT 'aktif' AFTER role");
        echo "Column 'status' successfully added to users table.<br>\n";
    } else {
        echo "Column 'status' already exists in users table.<br>\n";
    }

    // 2. Add login_attempts column
    $cols = $koneksi->query("SHOW COLUMNS FROM users LIKE 'login_attempts'")->fetchAll();
    if (empty($cols)) {
        $koneksi->exec("ALTER TABLE users ADD COLUMN login_attempts INT DEFAULT 0 AFTER status");
        echo "Column 'login_attempts' successfully added to users table.<br>\n";
    } else {
        echo "Column 'login_attempts' already exists in users table.<br>\n";
    }

    // 3. Add lock_until column
    $cols = $koneksi->query("SHOW COLUMNS FROM users LIKE 'lock_until'")->fetchAll();
    if (empty($cols)) {
        $koneksi->exec("ALTER TABLE users ADD COLUMN lock_until TIMESTAMP NULL DEFAULT NULL AFTER login_attempts");
        echo "Column 'lock_until' successfully added to users table.<br>\n";
    } else {
        echo "Column 'lock_until' already exists in users table.<br>\n";
    }

    echo "Migration completed successfully!";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>
