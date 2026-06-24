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
<div class="bg-maroon text-white shadow-lg" id="sidebar-wrapper" style="min-width: 280px; min-height: 100vh; position: relative;">
    <!-- Floating Sidebar Edge Toggle Button -->
    <button id="sidebar-toggle-btn" class="btn btn-maroon rounded-circle p-0 d-flex align-items-center justify-content-center" style="position: absolute; right: -15px; top: 100px; width: 30px; height: 30px; z-index: 1010; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
        <i class="fas fa-chevron-left" id="sidebar-toggle-icon" style="font-size: 0.8rem;"></i>
    </button>

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

<!-- Backdrop Overlay for Mobile view -->
<div id="sidebar-backdrop" class="d-lg-none" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.4); z-index: 999;"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const wrapper = document.getElementById("wrapper");
    const sidebar = document.getElementById("sidebar-wrapper");
    const toggleBtn = document.getElementById("sidebar-toggle-btn");
    const toggleIcon = document.getElementById("sidebar-toggle-icon");
    const backdrop = document.getElementById("sidebar-backdrop");

    if (!wrapper || !sidebar || !toggleBtn || !backdrop) return;

    // Helper to update toggle button icon direction
    function updateToggleIcon() {
        if (!toggleIcon) return;
        const isToggled = wrapper.classList.contains("toggled");
        if (window.innerWidth >= 992) {
            toggleIcon.className = isToggled ? "fas fa-chevron-right" : "fas fa-chevron-left";
        } else {
            toggleIcon.className = isToggled ? "fas fa-chevron-left" : "fas fa-chevron-right";
        }
    }

    // Toggle button click handler
    toggleBtn.addEventListener("click", function(e) {
        e.preventDefault();
        wrapper.classList.toggle("toggled");
    });

    // Handle backdrop click to close (only relevant on mobile)
    backdrop.addEventListener("click", function() {
        wrapper.classList.remove("toggled");
    });

    // Observe changes in wrapper classes to show/hide backdrop and update icons
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === "class") {
                const isToggled = wrapper.classList.contains("toggled");
                if (isToggled && window.innerWidth < 992) {
                    backdrop.style.display = "block";
                } else {
                    backdrop.style.display = "none";
                }
                updateToggleIcon();
            }
        });
    });

    observer.observe(wrapper, { attributes: true });

    // Initial icon state
    updateToggleIcon();

    // Mousemove tracking on the wrapper for the floating divider toggle button (Desktop only)
    wrapper.addEventListener("mousemove", function(e) {
        if (window.innerWidth < 992) return;

        const isToggled = wrapper.classList.contains("toggled");
        const sidebarRect = sidebar.getBoundingClientRect();
        
        // Find X coordinate of the dividing line
        const dividingX = isToggled ? 0 : sidebarRect.right;
        
        // Distance of mouse from the dividing line
        const distanceX = Math.abs(e.clientX - dividingX);
        
        // Show button if mouse is within 30px of the dividing line
        if (distanceX <= 30) {
            // Set vertical position following the mouse
            const mouseY = e.clientY - sidebarRect.top;
            const buttonHeight = toggleBtn.offsetHeight || 30;
            const minTop = 60; // Leave space for top logo
            const maxTop = sidebarRect.height - buttonHeight - 60; // Leave space for logout button
            const constrainedY = Math.max(minTop, Math.min(mouseY, maxTop));
            
            toggleBtn.style.top = constrainedY + "px";
            toggleBtn.style.opacity = "1";
            toggleBtn.style.visibility = "visible";
            toggleBtn.style.pointerEvents = "auto";
        } else {
            // Hide button if mouse moves away
            toggleBtn.style.opacity = "0";
            toggleBtn.style.visibility = "hidden";
            toggleBtn.style.pointerEvents = "none";
        }
    });

    // Handle window resize
    window.addEventListener("resize", function() {
        if (window.innerWidth >= 992) {
            backdrop.style.display = "none";
        } else if (wrapper.classList.contains("toggled")) {
            backdrop.style.display = "block";
        }
        updateToggleIcon();
    });
});
</script>
