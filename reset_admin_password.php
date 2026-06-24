<?php
include 'config/koneksi.php';

try {
    $new_pass = password_hash('password', PASSWORD_DEFAULT);
    $stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE email = 'admin@simeks.com'");
    $stmt->execute([$new_pass]);
    echo "Password for admin@simeks.com has been successfully reset to 'password'!";
} catch (Exception $e) {
    echo "Error resetting password: " . $e->getMessage();
}
?>
