<?php
include 'config/koneksi.php';

try {
    // 1. Alter users table to support 'pembina' role
    // First, let's check current definition or just run the ALTER
    $koneksi->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pembina', 'siswa') DEFAULT 'siswa'");
    echo "Successfully updated users table role enum.<br>\n";

    // 2. Add pembina_id column to eskul table if it doesn't exist
    $cols = $koneksi->query("SHOW COLUMNS FROM eskul LIKE 'pembina_id'")->fetchAll();
    if (empty($cols)) {
        $koneksi->exec("ALTER TABLE eskul ADD COLUMN pembina_id INT NULL AFTER pembina");
        $koneksi->exec("ALTER TABLE eskul ADD CONSTRAINT fk_eskul_pembina FOREIGN KEY (pembina_id) REFERENCES users(id) ON DELETE SET NULL");
        echo "Successfully added pembina_id column and foreign key to eskul table.<br>\n";
    } else {
        echo "pembina_id column already exists in eskul table.<br>\n";
    }

    echo "Database migration completed successfully!";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>
