<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}

include __DIR__ . '/../../config/koneksi.php';

try {
    $cols = $koneksi->query("SHOW COLUMNS FROM users LIKE 'needs_password_change'")->fetchAll();
    if (empty($cols)) {
        $koneksi->exec("ALTER TABLE users ADD COLUMN needs_password_change TINYINT(1) DEFAULT 0 AFTER lock_until");
        echo "Column 'needs_password_change' successfully added to users table.<br>\n";
    } else {
        echo "Column 'needs_password_change' already exists in users table.<br>\n";
    }
    echo "Migration completed successfully!";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>
