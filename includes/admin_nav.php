<?php
// Unified Admin Top Navigation
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm mb-4">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-maroon d-lg-none me-3" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="fw-bold mb-0 text-dark"><?php echo isset($navTitle) ? $navTitle : 'Admin Panel'; ?></h4>
        </div>
        <div class="ms-auto d-flex align-items-center">
            <!-- Contextual Action Button (Optional) -->
            <?php if (isset($extraAction)): ?>
                <div class="me-3 d-none d-sm-block">
                    <?php echo $extraAction; ?>
                </div>
            <?php endif; ?>

            <!-- Notification Bell -->
            <div class="dropdown me-3">
                <a href="notifikasi.php" class="text-dark position-relative p-2 rounded-circle hover-bg-light">
                    <i class="fas fa-bell fs-5"></i>
                    <?php if (isset($notif_count) && $notif_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            <?php echo $notif_count > 9 ? '9+' : $notif_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="d-none d-md-block border-start ps-3 ms-2">
                <span class="small text-muted"><?php echo isset($_SESSION['master_data']) ? 'Master Administrator' : 'Administrator'; ?></span>
                <span class="small fw-bold d-block text-maroon"><?php
                                                                echo htmlspecialchars($_SESSION['master_data']['nama'] ?? $_SESSION['admin_data']['nama'] ?? 'Admin');
                                                                ?></span>
            </div>
        </div>
    </div>
</nav>