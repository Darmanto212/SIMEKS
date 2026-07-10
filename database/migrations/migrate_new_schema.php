<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}

include __DIR__ . '/../../config/koneksi.php';

try {
    echo "Starting schema migration...\n";

    // Helper helper to check column existence
    $columnExists = function($table, $column) use ($koneksi) {
        $stmt = $koneksi->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        return !empty($stmt->fetchAll());
    };

    // Helper helper to check constraint existence
    $constraintExists = function($table, $constraintName) use ($koneksi) {
        $stmt = $koneksi->prepare("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = ? 
              AND CONSTRAINT_NAME = ?
        ");
        $stmt->execute([$table, $constraintName]);
        return !empty($stmt->fetchAll());
    };

    // Helper helper to check index existence
    $indexExists = function($table, $indexName) use ($koneksi) {
        $stmt = $koneksi->prepare("SHOW INDEX FROM `$table` WHERE Key_name = ?");
        $stmt->execute([$indexName]);
        return !empty($stmt->fetchAll());
    };

    // 1. Convert all tables to InnoDB & utf8mb4
    $tables = ['users', 'eskul', 'pendaftaran', 'absensi', 'prestasi', 'pengumuman', 'notifikasi', 'logs', 'eskul_libur'];
    foreach ($tables as $t) {
        $koneksi->exec("ALTER TABLE `$t` ENGINE=InnoDB");
        $koneksi->exec("ALTER TABLE `$t` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        echo "InnoDB & utf8mb4 configured for table: $t\n";
    }

    // 2. Modify users table
    if ($columnExists('users', 'needs_password_change')) {
        $koneksi->exec("ALTER TABLE users CHANGE COLUMN needs_password_change must_change_password TINYINT(1) DEFAULT 0");
    }
    if ($columnExists('users', 'login_attempts')) {
        $koneksi->exec("ALTER TABLE users CHANGE COLUMN login_attempts failed_login_attempts INT DEFAULT 0");
    }
    if ($columnExists('users', 'lock_until')) {
        $koneksi->exec("ALTER TABLE users CHANGE COLUMN lock_until locked_until TIMESTAMP NULL DEFAULT NULL");
    }
    if (!$columnExists('users', 'updated_at')) {
        $koneksi->exec("ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER locked_until");
    }
    if (!$columnExists('users', 'deleted_at')) {
        $koneksi->exec("ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at");
    }
    echo "Table 'users' columns updated successfully.\n";

    // 3. Create periode table
    $koneksi->exec("CREATE TABLE IF NOT EXISTS periode (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tahun_ajaran VARCHAR(20) NOT NULL,
        semester ENUM('ganjil', 'genap') NOT NULL,
        tanggal_mulai DATE NOT NULL,
        tanggal_selesai DATE NOT NULL,
        status ENUM('aktif', 'nonaktif') DEFAULT 'nonaktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "Table 'periode' checked/created.\n";

    // Insert default period for existing transactional data if none exists
    $stmt_period = $koneksi->query("SELECT id FROM periode WHERE status = 'aktif' LIMIT 1");
    $active_period = $stmt_period->fetch();
    if (!$active_period) {
        $koneksi->exec("INSERT INTO periode (tahun_ajaran, semester, tanggal_mulai, tanggal_selesai, status) 
                        VALUES ('2025/2026', 'ganjil', '2025-07-01', '2025-12-31', 'aktif')");
        $period_id = $koneksi->lastInsertId();
        echo "Default active period created (ID: $period_id).\n";
    } else {
        $period_id = $active_period->id;
        echo "Active period found (ID: $period_id).\n";
    }

    // 4. Modify eskul table
    if ($columnExists('eskul', 'nama_eskul')) {
        $koneksi->exec("ALTER TABLE eskul CHANGE COLUMN nama_eskul nama VARCHAR(100) NOT NULL");
    }
    if ($columnExists('eskul', 'pembina') && !$columnExists('eskul', 'pembina_name_temp')) {
        $koneksi->exec("ALTER TABLE eskul CHANGE COLUMN pembina pembina_name_temp VARCHAR(100) NULL");
    }
    if (!$columnExists('eskul', 'pembina_id')) {
        $koneksi->exec("ALTER TABLE eskul ADD COLUMN pembina_id INT NULL AFTER nama");
    }
    // Update status values and definition
    $koneksi->exec("UPDATE eskul SET status = 'nonaktif' WHERE status = 'non-aktif'");
    $koneksi->exec("ALTER TABLE eskul MODIFY COLUMN status ENUM('aktif', 'nonaktif') DEFAULT 'aktif'");
    
    // Add timestamps
    if (!$columnExists('eskul', 'created_at')) {
        $koneksi->exec("ALTER TABLE eskul ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }
    if (!$columnExists('eskul', 'updated_at')) {
        $koneksi->exec("ALTER TABLE eskul ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
    if (!$columnExists('eskul', 'deleted_at')) {
        $koneksi->exec("ALTER TABLE eskul ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL");
    }
    // Data mapping for SENI to Fauzani Rahman
    $koneksi->exec("UPDATE eskul SET pembina_id = 5 WHERE id = 4");
    // Add foreign key constraint
    if (!$constraintExists('eskul', 'fk_eskul_pembina')) {
        $koneksi->exec("ALTER TABLE eskul ADD CONSTRAINT fk_eskul_pembina FOREIGN KEY (pembina_id) REFERENCES users(id) ON DELETE SET NULL");
    }
    echo "Table 'eskul' modified successfully.\n";

    // 5. Modify pendaftaran table
    if (!$columnExists('pendaftaran', 'periode_id')) {
        $koneksi->exec("ALTER TABLE pendaftaran ADD COLUMN periode_id INT NULL AFTER eskul_id");
        $koneksi->exec("UPDATE pendaftaran SET periode_id = $period_id");
        $koneksi->exec("ALTER TABLE pendaftaran MODIFY COLUMN periode_id INT NOT NULL");
    }
    if ($columnExists('pendaftaran', 'catatan') && !$columnExists('pendaftaran', 'alasan_penolakan')) {
        $koneksi->exec("ALTER TABLE pendaftaran CHANGE COLUMN catatan alasan_penolakan VARCHAR(255) NULL");
    }
    if (!$columnExists('pendaftaran', 'diproses_oleh')) {
        $koneksi->exec("ALTER TABLE pendaftaran ADD COLUMN diproses_oleh INT NULL AFTER status");
    }
    if (!$columnExists('pendaftaran', 'diproses_pada')) {
        $koneksi->exec("ALTER TABLE pendaftaran ADD COLUMN diproses_pada TIMESTAMP NULL DEFAULT NULL AFTER diproses_oleh");
    }
    if (!$columnExists('pendaftaran', 'created_at')) {
        $koneksi->exec("ALTER TABLE pendaftaran ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }
    if (!$columnExists('pendaftaran', 'updated_at')) {
        $koneksi->exec("ALTER TABLE pendaftaran ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
    // Add constraints
    if (!$indexExists('pendaftaran', 'unique_user_eskul_periode')) {
        $koneksi->exec("ALTER TABLE pendaftaran ADD UNIQUE KEY unique_user_eskul_periode (user_id, eskul_id, periode_id)");
    }
    if (!$constraintExists('pendaftaran', 'fk_pendaftaran_periode')) {
        $koneksi->exec("ALTER TABLE pendaftaran ADD CONSTRAINT fk_pendaftaran_periode FOREIGN KEY (periode_id) REFERENCES periode(id) ON DELETE RESTRICT");
    }
    if (!$constraintExists('pendaftaran', 'fk_pendaftaran_diproses')) {
        $koneksi->exec("ALTER TABLE pendaftaran ADD CONSTRAINT fk_pendaftaran_diproses FOREIGN KEY (diproses_oleh) REFERENCES users(id) ON DELETE SET NULL");
    }
    echo "Table 'pendaftaran' modified successfully.\n";

    // 6. Modify absensi table
    if (!$columnExists('absensi', 'periode_id')) {
        $koneksi->exec("ALTER TABLE absensi ADD COLUMN periode_id INT NULL AFTER eskul_id");
        $koneksi->exec("UPDATE absensi SET periode_id = $period_id");
        $koneksi->exec("ALTER TABLE absensi MODIFY COLUMN periode_id INT NOT NULL");
    }
    if (!$columnExists('absensi', 'dicatat_oleh')) {
        $koneksi->exec("ALTER TABLE absensi ADD COLUMN dicatat_oleh INT NULL AFTER status");
    }
    if (!$columnExists('absensi', 'updated_at')) {
        $koneksi->exec("ALTER TABLE absensi ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
    // Constraints
    if (!$indexExists('absensi', 'unique_user_eskul_tanggal')) {
        $koneksi->exec("ALTER TABLE absensi ADD UNIQUE KEY unique_user_eskul_tanggal (user_id, eskul_id, tanggal)");
    }
    if (!$constraintExists('absensi', 'fk_absensi_periode')) {
        $koneksi->exec("ALTER TABLE absensi ADD CONSTRAINT fk_absensi_periode FOREIGN KEY (periode_id) REFERENCES periode(id) ON DELETE RESTRICT");
    }
    if (!$constraintExists('absensi', 'fk_absensi_dicatat')) {
        $koneksi->exec("ALTER TABLE absensi ADD CONSTRAINT fk_absensi_dicatat FOREIGN KEY (dicatat_oleh) REFERENCES users(id) ON DELETE SET NULL");
    }
    echo "Table 'absensi' modified successfully.\n";

    // 7. Modify prestasi table
    if (!$columnExists('prestasi', 'tingkat')) {
        $koneksi->exec("ALTER TABLE prestasi ADD COLUMN tingkat VARCHAR(50) NULL AFTER eskul_id");
    }
    if (!$columnExists('prestasi', 'tanggal_prestasi')) {
        $koneksi->exec("ALTER TABLE prestasi ADD COLUMN tanggal_prestasi DATE NULL AFTER tingkat");
        $koneksi->exec("UPDATE prestasi SET tanggal_prestasi = CONCAT(tahun, '-01-01') WHERE tahun IS NOT NULL");
    }
    if ($columnExists('prestasi', 'deskripsi') && !$columnExists('prestasi', 'keterangan')) {
        $koneksi->exec("ALTER TABLE prestasi CHANGE COLUMN deskripsi keterangan TEXT NULL");
    }
    if (!$columnExists('prestasi', 'created_by')) {
        $koneksi->exec("ALTER TABLE prestasi ADD COLUMN created_by INT NULL AFTER keterangan");
    }
    if (!$columnExists('prestasi', 'updated_by')) {
        $koneksi->exec("ALTER TABLE prestasi ADD COLUMN updated_by INT NULL AFTER created_by");
    }
    if (!$columnExists('prestasi', 'created_at')) {
        $koneksi->exec("ALTER TABLE prestasi ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }
    if (!$columnExists('prestasi', 'updated_at')) {
        $koneksi->exec("ALTER TABLE prestasi ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
    // Constraints
    if (!$constraintExists('prestasi', 'fk_prestasi_created_by')) {
        $koneksi->exec("ALTER TABLE prestasi ADD CONSTRAINT fk_prestasi_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL");
    }
    if (!$constraintExists('prestasi', 'fk_prestasi_updated_by')) {
        $koneksi->exec("ALTER TABLE prestasi ADD CONSTRAINT fk_prestasi_updated_by FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL");
    }
    echo "Table 'prestasi' modified successfully.\n";

    // 8. Modify pengumuman table
    if ($columnExists('pengumuman', 'tanggal')) {
        $koneksi->exec("ALTER TABLE pengumuman CHANGE COLUMN tanggal tanggal_terbit TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }
    if (!$columnExists('pengumuman', 'created_by')) {
        $koneksi->exec("ALTER TABLE pengumuman ADD COLUMN created_by INT NULL AFTER status");
    }
    if (!$columnExists('pengumuman', 'updated_at')) {
        $koneksi->exec("ALTER TABLE pengumuman ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
    // Constraints
    if (!$constraintExists('pengumuman', 'fk_pengumuman_created_by')) {
        $koneksi->exec("ALTER TABLE pengumuman ADD CONSTRAINT fk_pengumuman_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL");
    }
    echo "Table 'pengumuman' modified successfully.\n";

    // 9. Modify notifikasi table
    if (!$columnExists('notifikasi', 'reference_type')) {
        $koneksi->exec("ALTER TABLE notifikasi ADD COLUMN reference_type VARCHAR(50) NULL AFTER pesan");
    }
    if (!$columnExists('notifikasi', 'reference_id')) {
        $koneksi->exec("ALTER TABLE notifikasi ADD COLUMN reference_id INT NULL AFTER reference_type");
    }
    if (!$columnExists('notifikasi', 'event_key')) {
        $koneksi->exec("ALTER TABLE notifikasi ADD COLUMN event_key VARCHAR(100) NULL AFTER reference_id");
    }
    if (!$indexExists('notifikasi', 'unique_event_key')) {
        $koneksi->exec("ALTER TABLE notifikasi ADD UNIQUE KEY unique_event_key (event_key)");
    }
    echo "Table 'notifikasi' modified successfully.\n";

    // 10. Modify logs table
    if ($columnExists('logs', 'aktivitas')) {
        $koneksi->exec("ALTER TABLE logs CHANGE COLUMN aktivitas activity VARCHAR(255) NOT NULL");
    }
    if ($columnExists('logs', 'keterangan')) {
        $koneksi->exec("ALTER TABLE logs CHANGE COLUMN keterangan result TEXT NULL");
    }
    if (!$columnExists('logs', 'entity_type')) {
        $koneksi->exec("ALTER TABLE logs ADD COLUMN entity_type VARCHAR(50) NULL AFTER result");
    }
    if (!$columnExists('logs', 'entity_id')) {
        $koneksi->exec("ALTER TABLE logs ADD COLUMN entity_id INT NULL AFTER entity_type");
    }
    if ($columnExists('logs', 'tanggal')) {
        $koneksi->exec("ALTER TABLE logs CHANGE COLUMN tanggal created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }
    echo "Table 'logs' modified successfully.\n";

    // 11. Modify eskul_libur table
    if ($columnExists('eskul_libur', 'keterangan')) {
        $koneksi->exec("ALTER TABLE eskul_libur CHANGE COLUMN keterangan alasan VARCHAR(255) NULL");
    }
    if (!$columnExists('eskul_libur', 'created_by')) {
        $koneksi->exec("ALTER TABLE eskul_libur ADD COLUMN created_by INT NULL AFTER alasan");
    }
    if (!$constraintExists('eskul_libur', 'fk_libur_created_by')) {
        $koneksi->exec("ALTER TABLE eskul_libur ADD CONSTRAINT fk_libur_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL");
    }
    echo "Table 'eskul_libur' modified successfully.\n";

    echo "Schema migration finished successfully!\n";
} catch (PDOException $e) {
    echo "Schema migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
