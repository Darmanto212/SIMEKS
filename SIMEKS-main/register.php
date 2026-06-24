<?php 
$pageTitle = "Daftar Akun - SIMEKS";
include 'includes/header.php'; 
?>

<section class="min-vh-100 d-flex align-items-center py-5 bg-light-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                    <div class="bg-maroon p-5 text-center text-white">
                        <div class="bg-white text-maroon rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 70px; height: 70px;">
                            <i class="fas fa-user-plus fs-2"></i>
                        </div>
                        <h2 class="fw-semibold mb-1 text-white">Daftar Akun Baru</h2>
                        <p class="text-white-50 mb-0">Bergabunglah dengan SIMEKS hari ini</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <form action="proses_register.php" method="POST">
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control bg-light border-0 py-3 ps-2" id="nama" name="nama" placeholder="Nama lengkap sesuai ijazah" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="username" class="form-label fw-semibold">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control bg-light border-0 py-3 ps-2" id="username" name="username" placeholder="Username" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="nisn" class="form-label fw-semibold">NISN</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-id-badge"></i></span>
                                        <input type="text" class="form-control bg-light border-0 py-3 ps-2" id="nisn" name="nisn" placeholder="Nomor NISN" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control bg-light border-0 py-3 ps-2" id="password" name="password" placeholder="Buat password minimal 8 karakter" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label text-muted small" for="terms">
                                        Saya setuju dengan <a href="#" class="text-maroon text-decoration-none fw-semibold">Syarat & Ketentuan</a> yang berlaku.
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-maroon w-100 py-3 rounded-pill fw-bold shadow-sm mb-4">
                                Buat Akun Sekarang
                            </button>
                            
                            <div class="text-center">
                                <p class="text-muted mb-0">Sudah punya akun? <a href="login.php" class="text-maroon fw-bold text-decoration-none">Masuk Disini</a></p>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4 mb-5">
                    <a href="index.php" class="text-muted text-decoration-none small">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
