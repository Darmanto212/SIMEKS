<nav class="navbar navbar-expand-lg fixed-top transition-navbar">
    <div class="container-fluid px-lg-5">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $base_url; ?>index.php">
            <div class="logo-container">
                <img src="<?php echo $base_url; ?>assets/logo.png?v=1.0" alt="Logo Sekolah" class="logo-img">
                <span class="fw-bold mb-0 text-black">SI<span class="text-maroon">MEKS</span></span>
            </div>
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#ekskul">Ekstrakurikuler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#prestasi">Prestasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#pengumuman">Pengumuman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tentang">Tentang</a>
                </li>
                <li class="nav-item ms-lg-3 mt-3 mt-lg-0 d-flex flex-column flex-lg-row gap-2 align-items-center">
                    <a href="login.php" class="btn btn-outline-maroon px-4 py-2 rounded-pill shadow-sm">Masuk</a>
                    <a href="register.php" class="btn btn-maroon px-4 py-2 rounded-pill shadow-sm">Daftar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<script>
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>
