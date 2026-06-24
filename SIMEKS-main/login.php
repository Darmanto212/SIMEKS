<?php 
$pageTitle = "Masuk - SIMEKS";
include 'includes/header.php'; 
?>

<section class="min-vh-100 d-flex align-items-center py-5 bg-light-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                    <div class="bg-maroon p-5 text-center text-white">
                        <div class="bg-white text-maroon rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 70px; height: 70px;">
                            <i class="fas fa-user-lock fs-2"></i>
                        </div>
                        <h2 class="fw-bold mb-1 text-white">Selamat Datang</h2>
                        <p class="text-white-50 mb-0">Silakan masuk ke akun SIMEKS Anda</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <form action="proses_login.php" method="POST">
                            <div class="mb-4">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control bg-light border-0 py-3 ps-2" id="username" name="username" placeholder="Masukkan username" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <a href="#" class="small text-maroon text-decoration-none fw-semibold">Lupa Password?</a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control bg-light border-0 py-3 ps-2" id="password" name="password" placeholder="Masukkan password" required>
                                    <button class="btn btn-light border-0 text-muted" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <script>
                                document.getElementById('togglePassword').addEventListener('click', function() {
                                    const passwordField = document.getElementById('password');
                                    const eyeIcon = document.getElementById('eyeIcon');
                                    if (passwordField.type === 'password') {
                                        passwordField.type = 'text';
                                        eyeIcon.classList.remove('fa-eye');
                                        eyeIcon.classList.add('fa-eye-slash');
                                    } else {
                                        passwordField.type = 'password';
                                        eyeIcon.classList.remove('fa-eye-slash');
                                        eyeIcon.classList.add('fa-eye');
                                    }
                                });
                            </script>
                            
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember">
                                    <label class="form-check-label text-muted small" for="remember">
                                        Ingat saya di perangkat ini
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-maroon w-100 py-3 rounded-pill fw-bold shadow-sm mb-4">
                                Masuk ke SIMEKS
                            </button>
                            
                            <div class="text-center">
                                <p class="text-muted mb-0">Belum punya akun? <a href="register.php" class="text-maroon fw-bold text-decoration-none">Daftar Sekarang</a></p>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="index.php" class="text-muted text-decoration-none small">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
