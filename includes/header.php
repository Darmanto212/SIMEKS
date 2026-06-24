<?php
// Deteksi base_url yang lebih aman
$current_path = $_SERVER['PHP_SELF'];
$base_url = (stripos($current_path, '/siswa/') !== false || stripos($current_path, '/admin/') !== false) ? '../' : './';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'SIMEKS - SMAN 2 Sukatani'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $base_url; ?>assets/logo.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/style.css?v=1.7">
</head>
<body class="bg-light">

<?php 
// Global Notification Logic
$notif_count = 0;
$isAdminPage = (stripos($current_path, '/admin/') !== false);
$isSiswaPage = (stripos($current_path, '/siswa/') !== false);

if ($isAdminPage && isset($_SESSION['admin_data'])) {
    include_once __DIR__ . '/../config/koneksi.php';
    $stmt_notif = $koneksi->prepare("SELECT COUNT(*) FROM notifikasi WHERE user_id = ? AND is_read = 0");
    $stmt_notif->execute([$_SESSION['admin_data']['user_id']]);
    $notif_count = $stmt_notif->fetchColumn();
} elseif ($isSiswaPage && isset($_SESSION['siswa_data'])) {
    include_once __DIR__ . '/../config/koneksi.php';
    $stmt_notif = $koneksi->prepare("SELECT COUNT(*) FROM notifikasi WHERE user_id = ? AND is_read = 0");
    $stmt_notif->execute([$_SESSION['siswa_data']['user_id']]);
    $notif_count = $stmt_notif->fetchColumn();
}
?>

<?php 
// Hanya tampilkan navbar publik di halaman luar (bukan dashboard admin/siswa)
if (stripos($current_path, '/siswa/') === false && stripos($current_path, '/admin/') === false) {
    include __DIR__ . '/navbar.php'; 
}
?>