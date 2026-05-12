<!-- Sidebar -->
<div class="bg-maroon text-white shadow-lg" id="sidebar-wrapper" style="min-width: 280px; min-height: 100vh;">
    <div class="sidebar-heading p-4 border-bottom border-white border-opacity-10">
        <div class="logo-container">
            <img src="../assets/logo.png" alt="Logo Sekolah" class="logo-img" style="height: 40px;">
            <span class="logo-text text-white">SI<span class="text-warning">MEKS</span></span>
        </div>
    </div>
    <div class="list-group list-group-flush p-3">
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'dashboard.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-th-large me-2"></i> Dashboard
        </a>

        <?php if (isset($_SESSION['master_data'])): ?>
            <a href="kelola-admin.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'kelola-admin.php') ? 'active-nav' : 'hover-bg'; ?>">
                <i class="fas fa-user-shield me-2"></i> Kelola Admin
            </a>
        <?php endif; ?>

        <a href="kelola-siswa.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'kelola-siswa.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-users me-2"></i> Kelola Siswa
        </a>
        <a href="kelola-absensi.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'kelola-absensi.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-calendar-check me-2"></i> Kelola Absensi
        </a>
        <a href="kelola-eskul.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'kelola-eskul.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-running me-2"></i> Kelola Ekskul
        </a>
        <a href="kelola-pendaftaran.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'kelola-pendaftaran.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-file-signature me-2"></i> Pendaftaran
        </a>
        <a href="kelola-prestasi.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'kelola-prestasi.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-trophy me-2"></i> Prestasi
        </a>
        <a href="kelola-pengumuman.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'kelola-pengumuman.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-bullhorn me-2"></i> Pengumuman
        </a>
        <a href="notifikasi.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'notifikasi.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-bell me-2"></i> Log Notifikasi
        </a>
        <a href="laporan.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'laporan.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-print me-2"></i> Laporan
        </a>
        <a href="pengaturan.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 mb-2 <?php echo ($current_page == 'pengaturan.php') ? 'active-nav' : 'hover-bg'; ?>">
            <i class="fas fa-cog me-2"></i> Pengaturan
        </a>
        <div class="mt-auto py-5">
            <a href="../logout.php?role=admin" class="list-group-item list-group-item-action bg-transparent text-white border-0 rounded-3 hover-bg">
                <i class="fas fa-sign-out-alt me-2"></i> Keluar
            </a>
        </div>
    </div>
</div>