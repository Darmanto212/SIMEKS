<?php
include 'config/koneksi.php';

try {
    // 1. Create a test pembina account if it doesn't exist
    $email = 'fauzani@pembina.com';
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $pembina = $stmt->fetch();

    if (!$pembina) {
        $pass_hash = password_hash('pembina123', PASSWORD_DEFAULT);
        $stmt_ins = $koneksi->prepare("INSERT INTO users (nama, email, password, role, foto) VALUES ('Fauzani Rahman', ?, ?, 'pembina', 'default.png')");
        $stmt_ins->execute([$email, $pass_hash]);
        $pembina_id = $koneksi->lastInsertId();
        echo "Created test pembina account (ID: $pembina_id).<br>\n";
    } else {
        $pembina_id = $pembina->id;
        echo "Test pembina account already exists (ID: $pembina_id).<br>\n";
    }

    // 2. Link this pembina to the 'SENI' eskul (id = 4 in original schema)
    $stmt_e = $koneksi->prepare("SELECT nama_eskul FROM eskul WHERE id = 4");
    $stmt_e->execute();
    $eskul_name = $stmt_e->fetchColumn();

    if ($eskul_name === 'SENI') {
        $stmt_link = $koneksi->prepare("UPDATE eskul SET pembina_id = ? WHERE id = 4");
        $stmt_link->execute([$pembina_id]);
        echo "Linked pembina 'Fauzani Rahman' to 'SENI' eskul (ID: 4).<br>\n";
    } else {
        // Find SENI by name
        $stmt_e2 = $koneksi->prepare("SELECT id FROM eskul WHERE nama_eskul = 'SENI'");
        $stmt_e2->execute();
        $eskul_id = $stmt_e2->fetchColumn();
        if ($eskul_id) {
            $stmt_link = $koneksi->prepare("UPDATE eskul SET pembina_id = ? WHERE id = ?");
            $stmt_link->execute([$pembina_id, $eskul_id]);
            echo "Linked pembina 'Fauzani Rahman' to 'SENI' eskul (ID: $eskul_id).<br>\n";
        } else {
            echo "Could not find 'SENI' eskul in database.<br>\n";
        }
    }

    echo "Test setup completed successfully!";
} catch (PDOException $e) {
    echo "Setup failed: " . $e->getMessage();
}
?>
