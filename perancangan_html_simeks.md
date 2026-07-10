# Kode HTML Rancangan Antarmuka (UI Mockup Code)
**Sistem Informasi Manajemen Ekstrakurikuler Sekolah (SIMEKS) - SMAN 2 Sukatani**

Dokumen ini berisi kode HTML (menggunakan standard **Bootstrap 5** dan **FontAwesome 5/6**) untuk rancangan antarmuka aplikasi SIMEKS. Kode ini dapat Anda gunakan sebagai referensi dokumentasi laporan Skripsi Bab 4 atau sebagai basis implementasi halaman mockup Anda.

---

## 📂 DAFTAR KODE HTML

### 3.6.1 Landing Page (`index.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - SIMEKS SMAN 2 Sukatani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Desain Khusus Halaman A4 untuk Screen & Print */
        @media screen {
            body {
                background-color: #e9ecef;
                padding: 30px 0;
            }
            .a4-page {
                width: 210mm;
                height: 297mm;
                margin: 0 auto 40px auto;
                background: white;
                box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
                box-sizing: border-box;
                padding: 20mm 15mm;
                position: relative;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
            .a4-page {
                width: 210mm;
                height: 297mm;
                box-shadow: none;
                padding: 20mm 15mm;
                box-sizing: border-box;
                page-break-after: always;
                break-after: page;
                position: relative;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .no-print {
                display: none !important;
            }
        }
        
        .page-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .page-footer-label {
            font-size: 11px;
            color: #8c96a0;
            text-align: center;
            border-top: 1px solid #dee2e6;
            padding-top: 8px;
            margin-top: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <!-- ====== HALAMAN 1 (A4) - NAVIGASI & HERO SECTION ====== -->
    <div class="a4-page">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded-3 mb-4">
            <div class="container-fluid px-3">
                <a class="navbar-brand fw-bold text-white" href="#">SIMEKS</a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto gap-2">
                        <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="#ekskul">Ekskul</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                        <li class="nav-item ms-lg-3"><a class="btn btn-sm btn-outline-light px-3 rounded-pill" href="login.html">Masuk</a></li>
                        <li class="nav-item"><a class="btn btn-sm btn-light px-3 rounded-pill text-dark" href="register.html">Daftar</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="page-content">
            <!-- Hero Section -->
            <header class="bg-dark text-white text-center py-5 rounded-4 shadow-sm">
                <div class="container py-4">
                    <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold mb-3">TAHUN AJARAN 2025/2026</span>
                    <h1 class="display-5 fw-bold">Salurkan Bakat & Minatmu di <br><span class="text-light">SMAN 2 SUKATANI</span></h1>
                    <p class="lead text-secondary my-4 small">Bergabunglah dengan komunitas positif, raih prestasi gemilang, dan bentuk karakter kepemimpinan.</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="register.html" class="btn btn-light px-4 rounded-pill shadow text-dark fw-bold">Daftar Sekarang</a>
                        <a href="#ekskul" class="btn btn-outline-light px-4 rounded-pill">Lihat Ekskul</a>
                    </div>
                </div>
            </header>
        </div>

        <div class="page-footer-label">
            RANCANGAN ANTARMUKA SIMEKS SMAN 2 SUKATANI - HALAMAN 1 (BERANDA - HERO)
        </div>
    </div>

    <!-- ====== HALAMAN 2 (A4) - KEUNGGULAN SYSTEM ====== -->
    <div class="a4-page">
        <div class="page-content">
            <!-- Keunggulan Section -->
            <section class="py-3" id="tentang">
                <div class="container text-center">
                    <h3 class="fw-bold mb-5 text-dark">Mengembangkan Potensi, Membangun Masa Depan</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border border-secondary-subtle shadow-sm p-4 h-100">
                                <i class="fas fa-laptop-code text-dark fs-2 mb-3"></i>
                                <h6 class="fw-bold">Sistem Digital</h6>
                                <p class="text-muted small mb-0">Pendaftaran & administrasi terintegrasi berbasis online.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border border-secondary-subtle shadow-sm p-4 h-100">
                                <i class="fas fa-user-clock text-dark fs-2 mb-3"></i>
                                <h6 class="fw-bold">Real-Time Info</h6>
                                <p class="text-muted small mb-0">Informasi jadwal latihan & absensi langsung ter-update.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border border-secondary-subtle shadow-sm p-4 h-100">
                                <i class="fas fa-shield-alt text-dark fs-2 mb-3"></i>
                                <h6 class="fw-bold">Data Aman</h6>
                                <p class="text-muted small mb-0">Keamanan data pengguna terjamin dengan enkripsi password.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-dark text-white shadow-sm p-4 h-100">
                                <i class="fas fa-medal text-light fs-2 mb-3"></i>
                                <h6 class="fw-bold">Fokus Prestasi</h6>
                                <p class="text-secondary small mb-0">Mempermudah monitoring pencapaian sertifikat kejuaraan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="page-footer-label">
            RANCANGAN ANTARMUKA SIMEKS SMAN 2 SUKATANI - HALAMAN 2 (KEUNGGULAN SISTEM)
        </div>
    </div>

    <!-- ====== HALAMAN 3 (A4) - DAFTAR EKSKUL & FOOTER ====== -->
    <div class="a4-page">
        <div class="page-content">
            <!-- Ekskul Section -->
            <section class="py-3" id="ekskul">
                <div class="container">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark">Daftar Ekstrakurikuler</h4>
                        <p class="text-muted small">Pilih dan ikuti ekstrakurikuler yang sesuai dengan minat bakatmu</p>
                    </div>
                    <div class="row justify-content-center">
                        <!-- Card Ekskul 1 -->
                        <div class="col-md-8">
                            <div class="card border border-secondary-subtle shadow-sm overflow-hidden">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400" class="img-fluid h-100 w-100" alt="Basket" style="object-fit: cover; min-height: 180px;">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body p-3">
                                            <h6 class="card-title fw-bold text-dark mb-1">Bola Basket</h6>
                                            <p class="card-text text-muted extra-small mb-2">Melatih kerja sama tim, stamina fisik, teknik dribbling, shooting, dan kompetisi turnamen resmi.</p>
                                            <ul class="list-unstyled mb-3 extra-small text-secondary">
                                                <li><i class="far fa-calendar me-2"></i> Rabu & Sabtu, 15:30 WIB</li>
                                                <li><i class="fas fa-user-tie me-2"></i> Pembina: Budi Santoso, S.Pd.</li>
                                            </ul>
                                            <a href="detail-ekskul.html" class="btn btn-outline-dark btn-sm rounded-pill py-1 px-3 extra-small">Detail Informasi</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-white text-center py-3 rounded-3 mt-4">
            <div class="container">
                <p class="mb-1 extra-small">&copy; 2026 SIMEKS SMAN 2 Sukatani. All Rights Reserved.</p>
                <p class="text-secondary extra-small mb-0">Developed for School Extracurricular Management</p>
            </div>
        </footer>

        <div class="page-footer-label">
            RANCANGAN ANTARMUKA SIMEKS SMAN 2 SUKATANI - HALAMAN 3 (DAFTAR EKSKUL)
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

### 3.6.2 Halaman Detail Ekstrakurikuler (`detail-ekskul.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Basket - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="index.html">SIMEKS</a>
        </div>
    </nav>

    <!-- Detail Content -->
    <main class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Beranda</a></li>
                <li class="breadcrumb-item"><a href="index.html#ekskul">Ekskul</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bola Basket</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800" class="img-fluid rounded mb-4" alt="Basket" style="max-height: 400px; width: 100%; object-fit: cover;">
                    <h2 class="fw-bold mb-3">Ekstrakurikuler Bola Basket</h2>
                    <p class="text-muted">Ekstrakurikuler bola basket SMAN 2 Sukatani merupakan wadah bagi siswa untuk melatih kebugaran fisik, menguasai teknik permainan basket dasar hingga lanjutan, serta membangun mental kompetisi yang sehat melalui berbagai turnamen antar sekolah.</p>
                    
                    <h5 class="fw-bold mt-4 mb-3">Prestasi Yang Diraih</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                            <span><i class="fas fa-trophy text-warning me-2"></i> Juara 1 DBL tingkat Regional Bekasi</span>
                            <span class="badge bg-danger rounded-pill">2025</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                            <span><i class="fas fa-trophy text-secondary me-2"></i> Juara 2 Turnamen Piala Walikota</span>
                            <span class="badge bg-danger rounded-pill">2024</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 80px;">
                    <h5 class="fw-bold mb-4">Informasi Latihan</h5>
                    <div class="mb-3 d-flex align-items-center">
                        <i class="far fa-calendar-alt text-danger fs-4 me-3"></i>
                        <div>
                            <span class="text-secondary small d-block">Jadwal Latihan</span>
                            <strong class="small text-dark">Rabu & Sabtu (15:30 WIB)</strong>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <i class="fas fa-map-marker-alt text-danger fs-4 me-3"></i>
                        <div>
                            <span class="text-secondary small d-block">Lokasi Latihan</span>
                            <strong class="small text-dark">Lapangan Utama Sekolah</strong>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <i class="fas fa-user-tie text-danger fs-4 me-3"></i>
                        <div>
                            <span class="text-secondary small d-block">Guru Pembina</span>
                            <strong class="small text-dark">Budi Santoso, S.Pd.</strong>
                        </div>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <i class="fas fa-users text-danger fs-4 me-3"></i>
                        <div>
                            <span class="text-secondary small d-block">Kuota Keanggotaan</span>
                            <strong class="small text-dark">12 dari 30 Kursi Tersisa</strong>
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="login.html" class="btn btn-danger btn-lg rounded-pill shadow-sm">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.3 Halaman Login (`login.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark min-vh-100 d-flex align-items-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 60px; height: 60px;">
                            <i class="fas fa-lock fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Masuk SIMEKS</h3>
                        <p class="text-muted small">Akses Sistem Monitoring Ekskul Sekolah</p>
                    </div>

                    <form action="#">
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold text-secondary">Email atau NISN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control bg-light border-0 py-2.5 small" id="email" placeholder="Contoh: 12345678" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <label for="password" class="form-label small fw-bold text-secondary mb-0">Kata Sandi</label>
                                <a href="#" class="small text-danger text-decoration-none">Lupa Password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control bg-light border-0 py-2.5 small" id="password" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-danger py-2.5 rounded-pill fw-bold shadow-sm">Masuk Sekarang</button>
                        </div>

                        <div class="text-center">
                            <span class="small text-muted">Belum punya akun? <a href="register.html" class="text-danger fw-bold text-decoration-none">Daftar</a></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
```

---

### 3.6.4 Halaman Registrasi Siswa (`register.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark min-vh-100 d-flex align-items-center py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-1">Registrasi Akun Siswa</h3>
                        <p class="text-muted small">Buat akun untuk melakukan pendaftaran ekskul</p>
                    </div>

                    <form action="#">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nisn" class="form-label small fw-bold text-secondary">NISN</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="nisn" placeholder="10 digit NISN" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="nama" placeholder="Nama lengkap" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label small fw-bold text-secondary">Alamat Email</label>
                                <input type="email" class="form-control bg-light border-0 py-2" id="email" placeholder="name@domain.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="kelas" class="form-label small fw-bold text-secondary">Kelas</label>
                                <select class="form-select bg-light border-0 py-2 text-muted" id="kelas" required>
                                    <option value="" selected disabled>Pilih Kelas...</option>
                                    <option value="X MIPA 1">X MIPA 1</option>
                                    <option value="XI IPS 2">XI IPS 2</option>
                                    <option value="XII MIPA 3">XII MIPA 3</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label small fw-bold text-secondary">Kata Sandi</label>
                                <input type="password" class="form-control bg-light border-0 py-2" id="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirm" class="form-label small fw-bold text-secondary">Ulangi Kata Sandi</label>
                                <input type="password" class="form-control bg-light border-0 py-2" id="password_confirm" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4 mb-3">
                            <button type="submit" class="btn btn-danger py-2.5 rounded-pill fw-bold shadow-sm">Daftar Akun Baru</button>
                        </div>

                        <div class="text-center">
                            <span class="small text-muted">Sudah punya akun? <a href="login.html" class="text-danger text-decoration-none fw-bold">Login disini</a></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
```

---

### 3.6.5 Halaman Dashboard Siswa (`dashboard-siswa.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #212529; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #adb5bd; font-size: 15px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-warning fw-bold mb-0">SIMEKS</h4>
            <span class="text-secondary extra-small">PORTAL SISWA</span>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-home me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="daftar-ekskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Daftar Ekskul</a></li>
            <li class="nav-item"><a href="absensi-siswa.html" class="nav-link"><i class="fas fa-clipboard-user me-2"></i> Absensi Latihan</a></li>
            <li class="nav-item"><a href="prestasi-siswa.html" class="nav-link"><i class="fas fa-trophy me-2"></i> Prestasi</a></li>
            <li class="nav-item"><a href="profil-siswa.html" class="nav-link"><i class="fas fa-user-edit me-2"></i> Edit Profil</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Selamat Datang, Ahmad Fauzi</h4>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-secondary p-2 rounded-circle"><i class="far fa-bell fs-5"></i></span>
                <img src="https://via.placeholder.com/40" class="rounded-circle shadow-sm" alt="Foto Siswa">
            </div>
        </header>

        <!-- Stats Widgets -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small d-block">Ekskul Aktif</span>
                            <h3 class="fw-bold mb-0 text-dark">Basket</h3>
                        </div>
                        <div class="bg-danger text-white p-3 rounded-circle"><i class="fas fa-basketball-ball fs-3"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small d-block">Kehadiran Latihan</span>
                            <h3 class="fw-bold mb-0 text-dark">92%</h3>
                        </div>
                        <div class="bg-success text-white p-3 rounded-circle"><i class="fas fa-calendar-check fs-3"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small d-block">Total Prestasi</span>
                            <h3 class="fw-bold mb-0 text-dark">2 Piala</h3>
                        </div>
                        <div class="bg-warning text-dark p-3 rounded-circle"><i class="fas fa-award fs-3"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcement and Activities -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 bg-white h-100">
                    <h5 class="fw-bold mb-4">Pengumuman Terbaru</h5>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item bg-transparent px-0 py-3">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-bold text-danger mb-0">Latihan Rutin Basket Ditiadakan</h6>
                                <small class="text-muted">30 Juni 2026</small>
                            </div>
                            <p class="text-secondary small mb-0">Sehubungan dengan perbaikan lapangan sekolah, latihan hari Rabu ditiadakan dan diganti ke hari Sabtu pagi.</p>
                        </div>
                        <div class="list-group-item bg-transparent px-0 py-3">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">Penerimaan Anggota Baru Ekskul PMR</h6>
                                <small class="text-muted">28 Juni 2026</small>
                            </div>
                            <p class="text-secondary small mb-0">Pendaftaran ekskul PMR resmi dibuka hingga akhir bulan depan. Silakan daftar via portal SIMEKS.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 bg-white h-100">
                    <h5 class="fw-bold mb-4">Jadwal Terdekat</h5>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item px-0 bg-transparent py-3">
                            <div class="fw-bold">Latihan Rutin Basket</div>
                            <span class="text-muted">Sabtu, 04 Juli 2026 | Lapangan Utama</span>
                        </li>
                        <li class="list-group-item px-0 bg-transparent py-3">
                            <div class="fw-bold">Turnamen Basket DBL</div>
                            <span class="text-muted">Senin, 13 Juli 2026 | Gor Bekasi</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.6 Halaman Daftar & Form Pendaftaran Ekskul (`daftar-ekskul.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ekskul - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #212529; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #adb5bd; font-size: 15px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-warning fw-bold mb-0">SIMEKS</h4>
            <span class="text-secondary extra-small">PORTAL SISWA</span>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-siswa.html" class="nav-link"><i class="fas fa-home me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-basketball-ball me-2"></i> Daftar Ekskul</a></li>
            <li class="nav-item"><a href="absensi-siswa.html" class="nav-link"><i class="fas fa-clipboard-user me-2"></i> Absensi Latihan</a></li>
            <li class="nav-item"><a href="prestasi-siswa.html" class="nav-link"><i class="fas fa-trophy me-2"></i> Prestasi</a></li>
            <li class="nav-item"><a href="profil-siswa.html" class="nav-link"><i class="fas fa-user-edit me-2"></i> Edit Profil</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Daftar Kegiatan Ekstrakurikuler</h4>
            <div class="input-group w-25">
                <input type="text" class="form-control form-control-sm" placeholder="Cari ekskul...">
                <button class="btn btn-sm btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
            </div>
        </header>

        <div class="row g-4">
            <!-- Card Basket -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?w=500" class="card-img-top" alt="Basket" style="height: 180px; object-fit: cover;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold card-title mb-0">Bola Basket</h5>
                            <span class="badge bg-success">Pendaftaran Buka</span>
                        </div>
                        <p class="text-secondary small mb-3">Melatih kemampuan permainan basket, disiplin tim, stamina, dan persiapan lomba.</p>
                        <div class="small text-secondary mb-4">
                            <div class="mb-1"><i class="far fa-clock me-2"></i> Rabu & Sabtu (15:30)</div>
                            <div><i class="fas fa-user-tie me-2"></i> Budi Santoso, S.Pd.</div>
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#daftarModal">Daftar Ekskul</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Form Pendaftaran -->
    <div class="modal fade" id="daftarModal" tabindex="-1" aria-labelledby="daftarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="daftarModalLabel">Pendaftaran Ekstrakurikuler</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-4">Harap konfirmasi pendaftaran Anda untuk ekstrakurikuler **Bola Basket**.</p>
                    <form>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">NISN</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="101230456" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="Ahmad Fauzi" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Kelas</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="XI MIPA 2" readonly>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger py-2 rounded-pill fw-bold">Kirim Permohonan Pendaftaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

### 3.6.7 Halaman Riwayat Kehadiran (`absensi-siswa.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Absensi - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #212529; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #adb5bd; font-size: 15px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-warning fw-bold mb-0">SIMEKS</h4>
            <span class="text-secondary extra-small">PORTAL SISWA</span>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-siswa.html" class="nav-link"><i class="fas fa-home me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="daftar-ekskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Daftar Ekskul</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-clipboard-user me-2"></i> Absensi Latihan</a></li>
            <li class="nav-item"><a href="prestasi-siswa.html" class="nav-link"><i class="fas fa-trophy me-2"></i> Prestasi</a></li>
            <li class="nav-item"><a href="profil-siswa.html" class="nav-link"><i class="fas fa-user-edit me-2"></i> Edit Profil</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Riwayat Kehadiran Latihan</h4>
            <span class="badge bg-success p-2 fs-6">Persentase Kehadiran: 92%</span>
        </header>

        <!-- Filters -->
        <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <select class="form-select form-select-sm" id="selectEskul">
                        <option value="basket">Bola Basket (Aktif)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="selectBulan">
                        <option value="">Semua Bulan</option>
                        <option value="6">Juni 2026</option>
                        <option value="5">Mei 2026</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-danger w-100">Filter Data</button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center small">
                    <thead class="table-light text-secondary fw-bold">
                        <tr>
                            <th scope="col" style="width: 80px;">No</th>
                            <th scope="col">Tanggal Latihan</th>
                            <th scope="col">Nama Ekskul</th>
                            <th scope="col">Status Kehadiran</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>24 Juni 2026</td>
                            <td>Bola Basket</td>
                            <td><span class="badge bg-success px-3 py-1.5 rounded-pill">Hadir</span></td>
                            <td class="text-secondary">-</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>17 Juni 2026</td>
                            <td>Bola Basket</td>
                            <td><span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill">Izin</span></td>
                            <td class="text-secondary">Ujian Sekolah Susulan</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>10 Juni 2026</td>
                            <td>Bola Basket</td>
                            <td><span class="badge bg-danger px-3 py-1.5 rounded-pill">Alpa</span></td>
                            <td class="text-secondary">Tanpa Keterangan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.8 Halaman Prestasi & E-Sertifikat (`prestasi-siswa.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestasi Siswa - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #212529; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #adb5bd; font-size: 15px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-warning fw-bold mb-0">SIMEKS</h4>
            <span class="text-secondary extra-small">PORTAL SISWA</span>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-siswa.html" class="nav-link"><i class="fas fa-home me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="daftar-ekskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Daftar Ekskul</a></li>
            <li class="nav-item"><a href="absensi-siswa.html" class="nav-link"><i class="fas fa-clipboard-user me-2"></i> Absensi Latihan</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-trophy me-2"></i> Prestasi</a></li>
            <li class="nav-item"><a href="profil-siswa.html" class="nav-link"><i class="fas fa-user-edit me-2"></i> Edit Profil</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Catatan Prestasi & E-Sertifikat</h4>
        </header>

        <div class="row g-4">
            <!-- Card Prestasi 1 -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 bg-white">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <span class="badge bg-warning text-dark mb-2">Tingkat Regional Jawa Barat</span>
                            <h5 class="fw-bold mb-1">Juara 1 Kejuaraan Bola Basket DBL</h5>
                            <span class="text-secondary small">Diinput oleh: Budi Santoso, S.Pd.</span>
                        </div>
                        <i class="fas fa-award text-warning fs-1"></i>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-secondary">Tanggal Perolehan: 15 Maret 2026</span>
                        <a href="#" class="btn btn-outline-danger btn-sm px-4 rounded-pill"><i class="fas fa-download me-2"></i> E-Sertifikat</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.9 Halaman Profil Siswa (`profil-siswa.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #212529; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #adb5bd; font-size: 15px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-warning fw-bold mb-0">SIMEKS</h4>
            <span class="text-secondary extra-small">PORTAL SISWA</span>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-siswa.html" class="nav-link"><i class="fas fa-home me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="daftar-ekskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Daftar Ekskul</a></li>
            <li class="nav-item"><a href="absensi-siswa.html" class="nav-link"><i class="fas fa-clipboard-user me-2"></i> Absensi Latihan</a></li>
            <li class="nav-item"><a href="prestasi-siswa.html" class="nav-link"><i class="fas fa-trophy me-2"></i> Prestasi</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-user-edit me-2"></i> Edit Profil</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Pengaturan Akun & Profil</h4>
        </header>

        <div class="row g-4">
            <!-- Sisi Foto -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 text-center bg-white">
                    <img src="https://via.placeholder.com/150" class="rounded-circle img-thumbnail mx-auto mb-3 shadow" style="width: 150px; height: 150px; object-fit: cover;" alt="Foto Siswa">
                    <h5 class="fw-bold">Ahmad Fauzi</h5>
                    <span class="text-secondary small d-block mb-3">Siswa Kelas XI MIPA 2</span>
                    <form>
                        <div class="mb-3">
                            <input class="form-control form-control-sm" type="file" id="fotoFile">
                        </div>
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">Ganti Foto Profil</button>
                    </form>
                </div>
            </div>

            <!-- Sisi Detail Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 bg-white">
                    <h5 class="fw-bold mb-4">Detail Informasi Diri</h5>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">NISN (Tidak dapat diubah)</label>
                                <input type="text" class="form-control bg-light border-0" value="101230456" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                <input type="text" class="form-control" value="Ahmad Fauzi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                                <input type="email" class="form-control" value="ahmad.fauzi@gmail.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Ganti Kata Sandi (Kosongkan jika tidak diganti)</label>
                                <input type="password" class="form-control" placeholder="Kata sandi baru">
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.10 Dashboard Admin / Pembina (`dashboard-admin.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
            <span class="text-secondary extra-small">SMAN 2 SUKATANI</span>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item"><a href="kelola-pengumuman.html" class="nav-link"><i class="fas fa-bullhorn me-2"></i> Kelola Pengumuman</a></li>
            <li class="nav-item"><a href="laporan.html" class="nav-link"><i class="fas fa-file-pdf me-2"></i> Laporan</a></li>
            <li class="nav-item"><a href="logs.html" class="nav-link"><i class="fas fa-shield-alt me-2"></i> Audit Logs</a></li>
            <li class="nav-item"><a href="pengaturan.html" class="nav-link"><i class="fas fa-cogs me-2"></i> Pengaturan</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Halaman Dashboard Ringkasan</h4>
            <span class="text-secondary small">Selasa, 30 Juni 2026</span>
        </header>

        <!-- Stat Card Widget -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small">Total Ekskul</span>
                            <h3 class="fw-bold mb-0 text-dark">15</h3>
                        </div>
                        <div class="bg-primary text-white p-3 rounded"><i class="fas fa-running fs-4"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small">Guru Pembina</span>
                            <h3 class="fw-bold mb-0 text-dark">12</h3>
                        </div>
                        <div class="bg-success text-white p-3 rounded"><i class="fas fa-user-graduate fs-4"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small">Siswa Terdaftar</span>
                            <h3 class="fw-bold mb-0 text-dark">450</h3>
                        </div>
                        <div class="bg-info text-white p-3 rounded"><i class="fas fa-users-cog fs-4"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small">Pendaftaran Baru</span>
                            <h3 class="fw-bold mb-0 text-danger">8</h3>
                        </div>
                        <div class="bg-danger text-white p-3 rounded"><i class="fas fa-envelope-open fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts or Recents Panel -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <h5 class="fw-bold mb-3">Aktivitas Pendaftaran Masuk Terkini</h5>
            <div class="table-responsive">
                <table class="table table-hover text-center small align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Ekskul Pilihan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>30/06/2026</td>
                            <td>Ahmad Fauzi</td>
                            <td>XI MIPA 2</td>
                            <td>Bola Basket</td>
                            <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.11 Kelola Data Ekstrakurikuler (`kelola-eskul.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ekskul - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item"><a href="kelola-pengumuman.html" class="nav-link"><i class="fas fa-bullhorn me-2"></i> Kelola Pengumuman</a></li>
            <li class="nav-item"><a href="laporan.html" class="nav-link"><i class="fas fa-file-pdf me-2"></i> Laporan</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Kelola Data Ekstrakurikuler</h4>
            <button class="btn btn-danger btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahEskulModal"><i class="fas fa-plus me-1"></i> Tambah Ekskul</button>
        </header>

        <!-- Ekskul Table -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle small">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Ekskul</th>
                            <th>Pembina</th>
                            <th>Jadwal Latihan</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><img src="https://via.placeholder.com/60" class="rounded shadow-sm" alt="basket"></td>
                            <td class="fw-bold">Bola Basket</td>
                            <td>Budi Santoso, S.Pd.</td>
                            <td>Rabu & Sabtu (15:30)</td>
                            <td>30 Siswa</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Form Tambah/Edit -->
    <div class="modal fade" id="tambahEskulModal" tabindex="-1" aria-labelledby="tambahEskulModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="tambahEskulModalLabel">Tambah Data Ekskul Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nama Ekstrakurikuler</label>
                                <input type="text" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Guru Pembina</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Pilih Pembina...</option>
                                    <option value="1">Budi Santoso, S.Pd.</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Jadwal Latihan</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Contoh: Rabu & Sabtu 15:30" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Kuota Anggota</label>
                                <input type="number" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Lokasi Latihan</label>
                                <input type="text" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Gambar Banner</label>
                                <input type="file" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-secondary">Deskripsi Ekskul</label>
                                <textarea class="form-control form-control-sm" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger px-4 rounded-pill btn-sm">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

### 3.6.12 Kelola Akun Pembina (`kelola-pembina.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pembina - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item"><a href="laporan.html" class="nav-link"><i class="fas fa-file-pdf me-2"></i> Laporan</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Kelola Akun Guru Pembina</h4>
            <button class="btn btn-danger btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahPembinaModal"><i class="fas fa-plus me-1"></i> Tambah Pembina</button>
        </header>

        <!-- Pembina Table -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle small">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Nama Pembina</th>
                            <th>Email</th>
                            <th>No WhatsApp</th>
                            <th>Ekskul Binaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="fw-bold">Budi Santoso, S.Pd.</td>
                            <td>budi.pembina@gmail.com</td>
                            <td>081234567890</td>
                            <td>Bola Basket</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Form Tambah/Edit Pembina -->
    <div class="modal fade" id="tambahPembinaModal" tabindex="-1" aria-labelledby="tambahPembinaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="tambahPembinaModalLabel">Tambah Akun Pembina</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nama Lengkap Pembina</label>
                            <input type="text" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                            <input type="email" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">No. WhatsApp</label>
                            <input type="text" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Ekskul yang Dibina</label>
                            <select class="form-select form-select-sm">
                                <option value="">Pilih Ekskul...</option>
                                <option value="1">Bola Basket</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger py-2 rounded-pill fw-bold">Simpan Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

### 3.6.13 Kelola Data Siswa (`kelola-siswa.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Kelola Data Siswa Aktif</h4>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm">
                    <option value="">Semua Kelas</option>
                    <option value="X">Kelas X</option>
                    <option value="XI">Kelas XI</option>
                    <option value="XII">Kelas XII</option>
                </select>
                <input type="text" class="form-control form-control-sm" placeholder="Cari Siswa...">
            </div>
        </header>

        <!-- Siswa Table -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle small">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>No</th>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Kelas</th>
                            <th>Email</th>
                            <th>Ekskul Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>101230456</td>
                            <td class="fw-bold text-start">Ahmad Fauzi</td>
                            <td>XI MIPA 2</td>
                            <td>ahmad.fauzi@gmail.com</td>
                            <td>Bola Basket</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.14 Verifikasi Pendaftaran (`verifikasi-pendaftaran.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pendaftaran - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Verifikasi Pengajuan Anggota Baru</h4>
        </header>

        <!-- Table Pendaftaran -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle small">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Daftar</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Ekskul Tujuan</th>
                            <th>Keputusan Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>30 Juni 2026</td>
                            <td>101230456</td>
                            <td class="fw-bold text-start">Ahmad Fauzi</td>
                            <td>XI MIPA 2</td>
                            <td>Bola Basket</td>
                            <td>
                                <button class="btn btn-success btn-sm rounded-pill px-3"><i class="fas fa-check me-1"></i> Terima</button>
                                <button class="btn btn-danger btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#tolakModal"><i class="fas fa-times me-1"></i> Tolak</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Alasan Tolak -->
    <div class="modal fade" id="tolakModal" tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="tolakModalLabel">Tolak Pendaftaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Alasan Penolakan</label>
                            <textarea class="form-control form-control-sm" rows="3" placeholder="Contoh: Kuota ekskul ini sudah penuh." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger btn-sm px-4 rounded-pill">Simpan Keputusan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

### 3.6.15 Kelola Absensi Kehadiran (`kelola-absensi.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Absensi - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Input Presensi Kehadiran Latihan</h4>
        </header>

        <!-- Filter Absen -->
        <div class="card border-0 shadow-sm p-4 bg-white mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Pilih Ekstrakurikuler</label>
                    <select class="form-select form-select-sm">
                        <option value="1">Bola Basket</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">Tanggal Latihan</label>
                    <input type="date" class="form-control form-control-sm" value="2026-06-30">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-danger btn-sm w-100"><i class="fas fa-search me-1"></i> Tampilkan Anggota</button>
                </div>
            </div>
        </div>

        <!-- Input Table -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <form>
                <div class="table-responsive mb-4">
                    <table class="table table-hover text-center align-middle small">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>Kehadiran</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="fw-bold text-start">Ahmad Fauzi</td>
                                <td>XI MIPA 2</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="absen_1" id="h_1" value="hadir" checked>
                                            <label class="form-check-label text-success fw-bold" for="h_1">H</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="absen_1" id="i_1" value="izin">
                                            <label class="form-check-label text-warning fw-bold" for="i_1">I</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="absen_1" id="s_1" value="sakit">
                                            <label class="form-check-label text-info fw-bold" for="s_1">S</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="absen_1" id="a_1" value="alpa">
                                            <label class="form-check-label text-danger fw-bold" for="a_1">A</label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" placeholder="Opsional alasan">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold">Simpan Presensi</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.16 Kelola Prestasi Siswa (`kelola-prestasi.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Prestasi - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Kelola Data Prestasi Siswa</h4>
            <button class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#prestasiModal"><i class="fas fa-plus me-1"></i> Tambah Prestasi</button>
        </header>

        <!-- Prestasi Table -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle small">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Ekskul</th>
                            <th>Nama Kejuaraan</th>
                            <th>Tingkat</th>
                            <th>Peringkat</th>
                            <th>E-Sertifikat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="fw-bold text-start">Ahmad Fauzi</td>
                            <td>Bola Basket</td>
                            <td>DBL Jawa Barat</td>
                            <td>Provinsi</td>
                            <td><span class="badge bg-warning text-dark">Juara 1</span></td>
                            <td><a href="#" class="btn btn-outline-danger btn-sm"><i class="fas fa-download"></i></a></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Input Prestasi -->
    <div class="modal fade" id="prestasiModal" tabindex="-1" aria-labelledby="prestasiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="prestasiModalLabel">Input Prestasi Anggota</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Pilih Siswa</label>
                            <select class="form-select form-select-sm" required>
                                <option value="">Pilih Siswa...</option>
                                <option value="1">Ahmad Fauzi (XI MIPA 2)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nama Kejuaraan/Lomba</label>
                            <input type="text" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Tingkat Lomba</label>
                            <select class="form-select form-select-sm" required>
                                <option value="Kecamatan">Kecamatan</option>
                                <option value="Kabupaten">Kabupaten/Kota</option>
                                <option value="Provinsi">Provinsi</option>
                                <option value="Nasional">Nasional</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Juara / Peringkat</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Contoh: Juara 1 / Harapan 3" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Unggah Berkas Sertifikat (PDF/Gambar)</label>
                            <input type="file" class="form-control form-control-sm" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger py-2 rounded-pill fw-bold">Simpan Prestasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

### 3.6.17 Kelola Pengumuman (`kelola-pengumuman.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengumuman - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-bullhorn me-2"></i> Kelola Pengumuman</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Manajemen Pengumuman Sekolah</h4>
            <button class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#pengumumanModal"><i class="fas fa-plus me-1"></i> Buat Pengumuman</button>
        </header>

        <!-- Table -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle small">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Judul Pengumuman</th>
                            <th>Tanggal Rilis</th>
                            <th>Target Penerima</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="fw-bold text-start">Latihan Basket Diliburkan Sementara</td>
                            <td>30 Juni 2026</td>
                            <td>Anggota Bola Basket</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Form Buat Pengumuman -->
    <div class="modal fade" id="pengumumanModal" tabindex="-1" aria-labelledby="pengumumanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="pengumumanModalLabel">Buat Pengumuman Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Judul Pengumuman</label>
                            <input type="text" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Target Penerima</label>
                            <select class="form-select form-select-sm" required>
                                <option value="semua">Semua Siswa</option>
                                <option value="1">Anggota Bola Basket</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Isi Detail Pengumuman</label>
                            <textarea class="form-control form-control-sm" rows="4" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger py-2 rounded-pill fw-bold">Rilis Pengumuman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

### 3.6.18 Laporan & Cetak Laporan (`laporan.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-file-pdf me-2"></i> Laporan</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Penyusunan & Cetak Laporan</h4>
        </header>

        <!-- Filter Laporan -->
        <div class="card border-0 shadow-sm p-4 bg-white mb-4">
            <h5 class="fw-bold small text-secondary mb-3">Parameter Filter</h5>
            <form>
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-secondary">Jenis Laporan</label>
                        <select class="form-select form-select-sm">
                            <option value="anggota">Rekap Anggota Ekskul</option>
                            <option value="absensi">Rekapitulasi Absensi Kehadiran</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-secondary">Pilih Ekskul</label>
                        <select class="form-select form-select-sm">
                            <option value="1">Bola Basket</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-secondary">Bulan / Periode</label>
                        <input type="month" class="form-control form-control-sm" value="2026-06">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-danger btn-sm w-50">Preview</button>
                            <button type="button" class="btn btn-danger btn-sm w-50"><i class="fas fa-print me-1"></i> Cetak</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Preview Area -->
        <div class="card border-0 shadow-sm p-5 bg-white text-center">
            <div class="border p-4 rounded-3 text-secondary small">
                <i class="far fa-file-alt fs-1 text-muted mb-3"></i>
                <p class="mb-0">Klik tombol **Preview** untuk memuat pratonton data laporan disini sebelum mengekspor.</p>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.19 Monitoring Audit Logs (`logs.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-shield-alt me-2"></i> Audit Logs</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Sistem Keamanan & Audit Logs</h4>
            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt me-1"></i> Bersihkan Log</button>
        </header>

        <!-- Log Table -->
        <div class="card border-0 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle small">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>No</th>
                            <th>Waktu Aktivitas</th>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Aktivitas</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>30/06/2026 07:15</td>
                            <td class="fw-bold">Ahmad Fauzi</td>
                            <td>Siswa</td>
                            <td>Login Berhasil</td>
                            <td>Siswa masuk ke portal dashboardsiswa.php</td>
                            <td><span class="badge bg-success">SUKSES</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>29/06/2026 19:40</td>
                            <td class="fw-bold">Admin Utama</td>
                            <td>Admin</td>
                            <td>Tambah Ekskul</td>
                            <td>Menambahkan data eskul Bola Basket baru</td>
                            <td><span class="badge bg-success">SUKSES</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
```

---

### 3.6.20 Pengaturan Sistem (`pengaturan.html`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; top: 0; left: 0; background-color: #1a1c1e; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .sidebar .nav-link { color: #8a8d93; font-size: 14px; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #2b3035; border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div class="px-3 mb-4">
            <h4 class="text-danger fw-bold mb-0">SIMEKS ADMIN</h4>
        </div>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard-admin.html" class="nav-link"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="kelola-eskul.html" class="nav-link"><i class="fas fa-basketball-ball me-2"></i> Kelola Ekskul</a></li>
            <li class="nav-item"><a href="kelola-pembina.html" class="nav-link"><i class="fas fa-user-tie me-2"></i> Kelola Pembina</a></li>
            <li class="nav-item"><a href="kelola-siswa.html" class="nav-link"><i class="fas fa-users me-2"></i> Kelola Siswa</a></li>
            <li class="nav-item"><a href="verifikasi-pendaftaran.html" class="nav-link"><i class="fas fa-user-check me-2"></i> Kelola Pendaftaran</a></li>
            <li class="nav-item"><a href="kelola-absensi.html" class="nav-link"><i class="fas fa-calendar-alt me-2"></i> Kelola Absensi</a></li>
            <li class="nav-item"><a href="kelola-prestasi.html" class="nav-link"><i class="fas fa-award me-2"></i> Kelola Prestasi</a></li>
            <li class="nav-item"><a href="logs.html" class="nav-link"><i class="fas fa-shield-alt me-2"></i> Audit Logs</a></li>
            <li class="nav-item"><a href="#" class="nav-link active"><i class="fas fa-cogs me-2"></i> Pengaturan</a></li>
            <li class="nav-item mt-4"><a href="login.html" class="nav-link text-danger"><i class="fas fa-power-off me-2"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="pb-3 mb-4 border-bottom">
            <h4 class="fw-bold mb-0">Konfigurasi Parameter Sistem</h4>
        </header>

        <div class="card border-0 shadow-sm p-4 bg-white" style="max-width: 800px;">
            <h5 class="fw-bold mb-4">Pengaturan Global SIMEKS</h5>
            <form>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Nama Instansi / Sekolah</label>
                    <input type="text" class="form-control form-control-sm" value="SMAN 2 Sukatani">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Tahun Ajaran Aktif</label>
                    <input type="text" class="form-control form-control-sm" value="2025/2026">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Batas Minimum Kehadiran Absensi (%)</label>
                    <input type="number" class="form-control form-control-sm" value="75">
                    <span class="text-secondary extra-small">Siswa dengan persentase kehadiran di bawah batas minimum tidak berhak mengunduh sertifikat prestasi.</span>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Logo Aplikasi / Sekolah</label>
                    <input type="file" class="form-control form-control-sm">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
```
