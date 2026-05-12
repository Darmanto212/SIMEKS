<?php 
$current_page = basename($_SERVER['PHP_SELF']); 

// Fetch user data for sidebar
if (isset($_SESSION['siswa_data'])) {
    $stmt_sb = $koneksi->prepare("SELECT nama, foto FROM users WHERE id = ?");
    $stmt_sb->execute([$_SESSION['siswa_data']['user_id']]);
    $user_sb = $stmt_sb->fetch();
    
    $foto_sb = (!empty($user_sb->foto) && $user_sb->foto != 'default.png') 
        ? "../assets/uploads/profile/" . $user_sb->foto 
        : "https://ui-avatars.com/api/?name=" . urlencode($user_sb->nama) . "&background=fff&color=800000";
}
?>
<div class="bg-maroon text-white shadow-lg" id="sidebar-wrapper" style="min-width: 280px; min-height: 100vh;">
    <div class="sidebar-heading p-4 border-bottom border-white border-opacity-10 text-center">
        <div class="mb-3">
            <img src="../assets/logo.png" alt="Logo Sekolah" style="height: 30px;">
            <span class="fw-bold ms-2">SI<span class="text-warning">MEKS</span></span>
        </div>
        <div class="mt-4">
            <img src="<?php echo $foto_sb; ?>" class="rounded-circle border border-2 border-white shadow-sm mb-2 object-fit-cover" width="80" height="80">
            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($user_sb->nama); ?></h6>
            <small class="opacity-75">Siswa</small>
        </div>
    </div>
    <div class="list-group list-group-flush p-3">
        <a href="dashboardsiswa.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'dashboardsiswa.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-th-large me-2"></i> Dashboard
        </a>
        <a href="daftar-eskul.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'daftar-eskul.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-running me-2"></i> Pilih Ekskul
        </a>
        <a href="absensi.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'absensi.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-calendar-check me-2"></i> Kehadiran Saya
        </a>
        <a href="pengumuman.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'pengumuman.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-bullhorn me-2"></i> Pengumuman
        </a>
        <a href="prestasi.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'prestasi.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-trophy me-2"></i> Prestasi Saya
        </a>
        <a href="profil.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'profil.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-user-edit me-2"></i> Profil Saya
        </a>
        <div class="mt-auto py-5">
            <a href="../logout.php?role=siswa" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 hover-bg">
                <i class="fas fa-sign-out-alt me-2"></i> Keluar
            </a>
        </div>
    </div>
</div>
