<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI.');
}

include __DIR__ . '/../../config/koneksi.php';

try {
    $koneksi->beginTransaction();
    echo "Starting schema rollback...\n";

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

    // 1. Revert eskul_libur table
    if ($constraintExists('eskul_libur', 'fk_libur_created_by')) {
        $koneksi->exec("ALTER TABLE eskul_libur DROP FOREIGN KEY fk_libur_created_by");
    }
    if ($columnExists('eskul_libur', 'created_by')) {
        $koneksi->exec("ALTER TABLE eskul_libur DROP COLUMN created_by");
    }
    if ($columnExists('eskul_libur', 'alasan')) {
        $koneksi->exec("ALTER TABLE eskul_libur CHANGE COLUMN alasan keterangan VARCHAR(255) NULL");
    }
    echo "Table 'eskul_libur' reverted.\n";

    // 2. Revert logs table
    if ($columnExists('logs', 'created_at')) {
        $koneksi->exec("ALTER TABLE logs CHANGE COLUMN created_at tanggal TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }
    if ($columnExists('logs', 'entity_id')) {
        $koneksi->exec("ALTER TABLE logs DROP COLUMN entity_id");
    }
    if ($columnExists('logs', 'entity_type')) {
        $koneksi->exec("ALTER TABLE logs DROP COLUMN entity_type");
    }
    if ($columnExists('logs', 'result')) {
        $koneksi->exec("ALTER TABLE logs CHANGE COLUMN result keterangan TEXT NULL");
    }
    if ($columnExists('logs', 'activity')) {
        $koneksi->exec("ALTER TABLE logs CHANGE COLUMN activity aktivitas VARCHAR(255) NOT NULL");
    }
    echo "Table 'logs' reverted.\n";

    // 3. Revert notifikasi table
    if ($indexExists('notifikasi', 'unique_event_key')) {
        $koneksi->exec("ALTER TABLE notifikasi DROP INDEX unique_event_key");
    }
    if ($columnExists('notifikasi', 'event_key')) {
        $koneksi->exec("ALTER TABLE notifikasi DROP COLUMN event_key");
    }
    if ($columnExists('notifikasi', 'reference_id')) {
        $koneksi->exec("ALTER TABLE notifikasi DROP COLUMN reference_id");
    }
    if ($columnExists('notifikasi', 'reference_type')) {
        $koneksi->exec("ALTER TABLE notifikasi DROP COLUMN reference_type");
    }
    echo "Table 'notifikasi' reverted.\n";

    // 4. Revert pengumuman table
    if ($constraintExists('pengumuman', 'fk_pengumuman_created_by')) {
        $koneksi->exec("ALTER TABLE pengumuman DROP FOREIGN KEY fk_pengumuman_created_by");
    }
    if ($columnExists('pengumuman', 'updated_at')) {
        $koneksi->exec("ALTER TABLE pengumuman DROP COLUMN updated_at");
    }
    if ($columnExists('pengumuman', 'created_by')) {
        $koneksi->exec("ALTER TABLE pengumuman DROP COLUMN created_by");
    }
    if ($columnExists('pengumuman', 'tanggal_terbit')) {
        $koneksi->exec("ALTER TABLE pengumuman CHANGE COLUMN tanggal_terbit tanggal TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }
    echo "Table 'pengumuman' reverted.\n";

    // 5. Revert prestasi table
    if ($constraintExists('prestasi', 'fk_prestasi_updated_by')) {
        $koneksi->exec("ALTER TABLE prestasi DROP FOREIGN KEY fk_prestasi_updated_by");
    }
    if ($constraintExists('prestasi', 'fk_prestasi_created_by')) {
        $koneksi->exec("ALTER TABLE prestasi DROP FOREIGN KEY fk_prestasi_created_by");
    }
    if ($columnExists('prestasi', 'updated_at')) {
        $koneksi->exec("ALTER TABLE prestasi DROP COLUMN updated_at");
    }
    if ($columnExists('prestasi', 'created_at')) {
        $koneksi->exec("ALTER TABLE prestasi DROP COLUMN created_at");
    }
    if ($columnExists('prestasi', 'updated_by')) {
        $koneksi->exec("ALTER TABLE prestasi DROP COLUMN updated_by");
    }
    if ($columnExists('prestasi', 'created_by')) {
        $koneksi->exec("ALTER TABLE prestasi DROP COLUMN created_by");
    }
    if ($columnExists('prestasi', 'keterangan')) {
        $koneksi->exec("ALTER TABLE prestasi CHANGE COLUMN keterangan deskripsi TEXT NULL");
    }
    if ($columnExists('prestasi', 'tanggal_prestasi')) {
        $koneksi->exec("ALTER TABLE prestasi DROP COLUMN tanggal_prestasi");
    }
    if ($columnExists('prestasi', 'tingkat')) {
        $koneksi->exec("ALTER TABLE prestasi DROP COLUMN tingkat");
    }
    echo "Table 'prestasi' reverted.\n";

    // 6. Revert absensi table
    if ($constraintExists('absensi', 'fk_absensi_dicatat')) {
        $koneksi->exec("ALTER TABLE absensi DROP FOREIGN KEY fk_absensi_dicatat");
    }
    if ($constraintExists('absensi', 'fk_absensi_periode')) {
        $koneksi->exec("ALTER TABLE absensi DROP FOREIGN KEY fk_absensi_periode");
    }
    if ($indexExists('absensi', 'unique_user_eskul_tanggal')) {
        $koneksi->exec("ALTER TABLE absensi DROP INDEX unique_user_eskul_tanggal");
    }
    if ($columnExists('absensi', 'updated_at')) {
        $koneksi->exec("ALTER TABLE absensi DROP COLUMN updated_at");
    }
    if ($columnExists('absensi', 'dicatat_oleh')) {
        $koneksi->exec("ALTER TABLE absensi DROP COLUMN dicatat_oleh");
    }
    if ($columnExists('absensi', 'periode_id')) {
        $koneksi->exec("ALTER TABLE absensi DROP COLUMN periode_id");
    }
    echo "Table 'absensi' reverted.\n";

    // 7. Revert pendaftaran table
    if ($constraintExists('pendaftaran', 'fk_pendaftaran_diproses')) {
        $koneksi->exec("ALTER TABLE pendaftaran DROP FOREIGN KEY fk_pendaftaran_diproses");
    }
    if ($constraintExists('pendaftaran', 'fk_pendaftaran_periode')) {
        $koneksi->exec("ALTER TABLE pendaftaran DROP FOREIGN KEY fk_pendaftaran_periode");
    }
    if ($indexExists('pendaftaran', 'unique_user_eskul_periode')) {
        $koneksi->exec("ALTER TABLE pendaftaran DROP INDEX unique_user_eskul_periode");
    }
    if ($columnExists('pendaftaran', 'updated_at')) {
        $koneksi->exec("ALTER TABLE pendaftaran DROP COLUMN updated_at");
    }
    if ($columnExists('pendaftaran', 'created_at')) {
        $koneksi->exec("ALTER TABLE pendaftaran DROP COLUMN created_at");
    }
    if ($columnExists('pendaftaran', 'diproses_pada')) {
        $koneksi->exec("ALTER TABLE pendaftaran DROP COLUMN diproses_pada");
    }
    if ($columnExists('pendaftaran', 'diproses_oleh')) {
        $koneksi->exec("ALTER TABLE pendaftaran DROP COLUMN diproses_oleh");
    }
    if ($columnExists('pendaftaran', 'alasan_penolakan')) {
        $koneksi->exec("ALTER TABLE pendaftaran CHANGE COLUMN alasan_penolakan catatan TEXT NULL");
    }
    if ($columnExists('pendaftaran', 'periode_id')) {
        $koneksi->exec("ALTER TABLE pendaftaran DROP COLUMN periode_id");
    }
    echo "Table 'pendaftaran' reverted.\n";

    // 8. Revert eskul table
    if ($constraintExists('eskul', 'fk_eskul_pembina')) {
        $koneksi->exec("ALTER TABLE eskul DROP FOREIGN KEY fk_eskul_pembina");
    }
    if ($columnExists('eskul', 'deleted_at')) {
        $koneksi->exec("ALTER TABLE eskul DROP COLUMN deleted_at");
    }
    if ($columnExists('eskul', 'updated_at')) {
        $koneksi->exec("ALTER TABLE eskul DROP COLUMN updated_at");
    }
    if ($columnExists('eskul', 'created_at')) {
        $koneksi->exec("ALTER TABLE eskul DROP COLUMN created_at");
    }
    $koneksi->exec("UPDATE eskul SET status = 'non-aktif' WHERE status = 'nonaktif'");
    $koneksi->exec("ALTER TABLE eskul MODIFY COLUMN status ENUM('aktif', 'non-aktif') DEFAULT 'aktif'");
    if ($columnExists('eskul', 'pembina_id')) {
        $koneksi->exec("ALTER TABLE eskul DROP COLUMN pembina_id");
    }
    if ($columnExists('eskul', 'pembina_name_temp')) {
        $koneksi->exec("ALTER TABLE eskul CHANGE COLUMN pembina_name_temp pembina VARCHAR(100) NULL");
    }
    if ($columnExists('eskul', 'nama')) {
        $koneksi->exec("ALTER TABLE eskul CHANGE COLUMN nama nama_eskul VARCHAR(100) NOT NULL");
    }
    echo "Table 'eskul' reverted.\n";

    // 9. Revert users table
    if ($columnExists('users', 'deleted_at')) {
        $koneksi->exec("ALTER TABLE users DROP COLUMN deleted_at");
    }
    if ($columnExists('users', 'updated_at')) {
        $koneksi->exec("ALTER TABLE users DROP COLUMN updated_at");
    }
    if ($columnExists('users', 'locked_until')) {
        $koneksi->exec("ALTER TABLE users CHANGE COLUMN locked_until lock_until TIMESTAMP NULL DEFAULT NULL");
    }
    if ($columnExists('users', 'failed_login_attempts')) {
        $koneksi->exec("ALTER TABLE users CHANGE COLUMN failed_login_attempts login_attempts INT DEFAULT 0");
    }
    if ($columnExists('users', 'must_change_password')) {
        $koneksi->exec("ALTER TABLE users CHANGE COLUMN must_change_password needs_password_change TINYINT(1) DEFAULT 0");
    }
    echo "Table 'users' reverted.\n";

    $koneksi->commit();
    echo "Schema rollback completed successfully!\n";
} catch (PDOException $e) {
    $koneksi->rollBack();
    echo "Schema rollback failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
