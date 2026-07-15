import os

out_dir = 'c:/xampp/htdocs/SIMEKS-main/mockup_html'
os.makedirs(out_dir, exist_ok=True)

wireframe_style = """
    body {
        background-color: #f5f5f5;
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #000000;
        padding: 20px 0;
        margin: 0;
    }
    .wf-container {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto 30px auto;
        background: #ffffff;
        border: 2px solid #000000;
        padding: 15mm;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .wf-title {
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        border-bottom: 2px solid #000000;
        padding-bottom: 8px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .wf-navbar {
        border: 1.5px solid #000000;
        background-color: #fcfcfc;
        padding: 8px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    .wf-nav-links {
        display: flex;
        gap: 15px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .wf-nav-link {
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        color: #000000;
        border: 1px solid transparent;
        padding: 4px 8px;
    }
    .wf-nav-link.active {
        border: 1.5px solid #000000;
        background: #f0f0f0;
        border-radius: 3px;
    }
    .wf-btn {
        background: #f0f0f0;
        border: 1.5px solid #000000;
        color: #000000;
        padding: 5px 12px;
        font-size: 11px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
    }
    .wf-btn:hover {
        background: #e0e0e0;
    }
    .wf-btn-outline {
        background: #ffffff;
        border: 1.5px dashed #000000;
        color: #000000;
        padding: 5px 12px;
        font-size: 11px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        border-radius: 4px;
    }
    .wf-btn-disabled {
        background: #ffffff;
        border: 1.5px solid #cccccc;
        color: #999999;
        padding: 5px 12px;
        font-size: 11px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        border-radius: 4px;
        cursor: not-allowed;
    }
    .wf-hero {
        border: 1.5px solid #000000;
        background-color: #fafafa;
        padding: 30px;
        text-align: center;
        margin-bottom: 25px;
        border-radius: 6px;
    }
    .wf-hero h1 {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 12px;
    }
    .wf-hero p {
        font-size: 12px;
        color: #555555;
        margin-bottom: 20px;
    }
    .wf-badge {
        border: 1px solid #000000;
        background: #f0f0f0;
        padding: 3px 8px;
        font-size: 9px;
        font-weight: bold;
        border-radius: 20px;
        display: inline-block;
    }
    .wf-card {
        border: 1.5px solid #000000;
        background: #ffffff;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
    }
    .wf-card-header {
        font-size: 12px;
        font-weight: bold;
        border-bottom: 1.5px solid #000000;
        padding-bottom: 6px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .wf-placeholder {
        position: relative;
        border: 1.5px dashed #000000;
        background-color: #fcfcfc;
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .wf-placeholder::before, .wf-placeholder::after {
        content: "";
        position: absolute;
        top: 0; bottom: 0; left: 0; right: 0;
        background: linear-gradient(to top right, transparent 49.5%, #dddddd 49.5%, #dddddd 50.5%, transparent 50.5%);
        pointer-events: none;
    }
    .wf-placeholder::after {
        background: linear-gradient(to bottom right, transparent 49.5%, #dddddd 49.5%, #dddddd 50.5%, transparent 50.5%);
    }
    .wf-placeholder-text {
        background: #ffffff;
        border: 1px solid #000000;
        padding: 2px 6px;
        font-size: 9px;
        font-weight: bold;
        z-index: 1;
        border-radius: 3px;
    }
    .wf-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1.5px solid #000000;
        background: #f0f0f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }
    .wf-circle-dashed {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1.5px dashed #000000;
        background: #fcfcfc;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .wf-input {
        width: 100%;
        border: 1.5px solid #000000;
        padding: 6px;
        font-size: 11px;
        box-sizing: border-box;
        border-radius: 4px;
        background: #ffffff;
        margin-bottom: 10px;
    }
    .wf-label {
        font-size: 10px;
        font-weight: bold;
        display: block;
        margin-bottom: 4px;
    }
    .wf-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
        font-size: 11px;
    }
    .wf-table th {
        background: #f0f0f0;
        border: 1.5px solid #000000;
        padding: 6px;
        font-weight: bold;
        text-align: center;
    }
    .wf-table td {
        border: 1.5px solid #000000;
        padding: 6px;
        text-align: center;
    }
    .wf-sidebar-layout {
        display: flex;
        flex-grow: 1;
        gap: 15px;
        min-height: 240mm;
    }
    .wf-sidebar {
        width: 180px;
        border: 1.5px solid #000000;
        background: #fafafa;
        padding: 15px 10px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        gap: 8px;
        border-radius: 4px;
    }
    .wf-sidebar .brand {
        font-weight: bold;
        font-size: 13px;
        border-bottom: 1.5px solid #000000;
        padding-bottom: 8px;
        margin-bottom: 10px;
        text-align: center;
    }
    .wf-sidebar-link {
        font-size: 11px;
        text-decoration: none;
        color: #000000;
        padding: 6px 10px;
        border: 1.5px solid transparent;
        display: flex;
        align-items: center;
        gap: 8px;
        border-radius: 3px;
    }
    .wf-sidebar-link.active {
        border: 1.5px solid #000000;
        background: #f0f0f0;
        font-weight: bold;
    }
    .wf-main-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .wf-header-bar {
        border: 1.5px solid #000000;
        background: #ffffff;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 4px;
    }
    .wf-footer {
        border-top: 1.5px solid #000000;
        padding-top: 10px;
        margin-top: auto;
        text-align: center;
        font-size: 9px;
        font-weight: 500;
    }
    .wf-progress {
        width: 100%;
        height: 10px;
        border: 1.5px solid #000000;
        background: #ffffff;
        border-radius: 5px;
        overflow: hidden;
        margin: 4px 0;
    }
    .wf-progress-bar {
        height: 100%;
        background: #777777;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -8px;
        margin-left: -8px;
    }
    .col-3 { flex: 0 0 25%; max-width: 25%; padding: 0 8px; box-sizing: border-box; }
    .col-4 { flex: 0 0 33.333333%; max-width: 33.333333%; padding: 0 8px; box-sizing: border-box; }
    .col-5 { flex: 0 0 41.666667%; max-width: 41.666667%; padding: 0 8px; box-sizing: border-box; }
    .col-6 { flex: 0 0 50%; max-width: 50%; padding: 0 8px; box-sizing: border-box; }
    .col-7 { flex: 0 0 58.333333%; max-width: 58.333333%; padding: 0 8px; box-sizing: border-box; }
    .col-8 { flex: 0 0 66.666667%; max-width: 66.666667%; padding: 0 8px; box-sizing: border-box; }
    .col-12 { flex: 0 0 100%; max-width: 100%; padding: 0 8px; box-sizing: border-box; }
"""

pages_dict = {}

# Page 1: Landing Page
pages_dict['01_landing_page.html'] = {
    'title': 'Perancangan Halaman Landing Page',
    'content': """
        <div class="wf-navbar">
            <span style="font-weight: bold; font-size: 13px;">[LOGO PLACEHOLDER] SIMEKS</span>
            <ul class="wf-nav-links">
                <li><a href="#" class="wf-nav-link active">Beranda</a></li>
                <li><a href="#" class="wf-nav-link">Ekskul</a></li>
                <li><a href="#" class="wf-nav-link">Tentang</a></li>
                <li><a href="#" class="wf-btn" style="padding: 3px 10px;">Daftar Sekarang</a></li>
                <li><a href="#" class="wf-btn-outline" style="padding: 2px 10px;">Lihat Ekskul</a></li>
            </ul>
        </div>
        
        <div class="wf-hero">
            <div class="wf-badge" style="margin-bottom: 10px;">+ TAHUN AJARAN 2025/2026</div>
            <h1>Salurkan Bakat & Minatmu di<br>SMAN 2 SUKATANI</h1>
            <p>Bergabunglah dengan komunitas positif, menerangkan kepemimpinan melalui ekstrakurikuler.</p>
            <div style="display: flex; justify-content: center; gap: 10px;">
                <a href="#" class="wf-btn">[Daftar Sekarang]</a>
                <a href="#" class="wf-btn-outline">[Lihat Ekskul]</a>
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 25px;">
            <div class="wf-badge" style="margin-bottom: 5px;">Kenapa Memilih Kami?</div>
            <h2 style="font-size: 14px; font-weight: bold; margin: 5px 0 0 0;">Mengembangkan Potensi, Membangun Masa Depan.</h2>
        </div>

        <div class="row" style="margin-bottom: 25px;">
            <div class="col-6">
                <div class="wf-card" style="display: flex; align-items: center; gap: 12px; padding: 10px;">
                    <div class="wf-circle-dashed"><i class="fas fa-laptop-code"></i></div>
                    <div>
                        <h6 style="font-size: 11px; font-weight: bold; margin: 0 0 2px 0;">SISTEM DIGITAL</h6>
                        <div style="width: 120px; height: 3px; background: #999;"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="wf-card" style="display: flex; align-items: center; gap: 12px; padding: 10px;">
                    <div class="wf-circle-dashed"><i class="fas fa-user-clock"></i></div>
                    <div>
                        <h6 style="font-size: 11px; font-weight: bold; margin: 0 0 2px 0;">REAL-TIME INFO</h6>
                        <div style="width: 120px; height: 3px; background: #999;"></div>
                    </div>
                </div>
            </div>
            <div class="col-6" style="margin-top: 10px;">
                <div class="wf-card" style="display: flex; align-items: center; gap: 12px; padding: 10px;">
                    <div class="wf-circle-dashed"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h6 style="font-size: 11px; font-weight: bold; margin: 0 0 2px 0;">DATA AMAN</h6>
                        <div style="width: 120px; height: 3px; background: #999;"></div>
                    </div>
                </div>
            </div>
            <div class="col-6" style="margin-top: 10px;">
                <div class="wf-card" style="display: flex; align-items: center; gap: 12px; padding: 10px;">
                    <div class="wf-circle-dashed"><i class="fas fa-medal"></i></div>
                    <div>
                        <h6 style="font-size: 11px; font-weight: bold; margin: 0 0 2px 0;">FOKUS PRESTASI</h6>
                        <div style="width: 120px; height: 3px; background: #999;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="#" class="wf-btn-outline" style="font-size: 10px; padding: 4px 15px;">Lihat Lebih Banyak</a>
        </div>
    """
}

# Page 2: Registrasi
pages_dict['02_registrasi.html'] = {
    'title': 'Perancangan Halaman Registrasi',
    'content': """
        <div style="display: flex; justify-content: center; align-items: center; flex-grow: 1; padding: 40px 0;">
            <div class="wf-card" style="width: 400px; padding: 25px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div class="wf-circle" style="width: 45px; height: 45px; margin-bottom: 8px;"><i class="fas fa-user-plus"></i></div>
                    <h5 style="font-size: 14px; font-weight: bold; margin: 0;">Registrasi Akun Siswa</h5>
                    <p style="font-size: 10px; color: #666; margin: 2px 0 0 0;">SIMEKS — SMAN 2 Sukatani</p>
                </div>
                <form>
                    <div class="row">
                        <div class="col-6">
                            <label class="wf-label">NISN *</label>
                            <input type="text" class="wf-input" placeholder="10 digit NISN">
                        </div>
                        <div class="col-6">
                            <label class="wf-label">Nama Lengkap *</label>
                            <input type="text" class="wf-input" placeholder="Nama sesuai rapor">
                        </div>
                        <div class="col-6">
                            <label class="wf-label">Alamat Email *</label>
                            <input type="email" class="wf-input" placeholder="nama@email.com">
                        </div>
                        <div class="col-6">
                            <label class="wf-label">Kelas *</label>
                            <select class="wf-input">
                                <option>-- Pilih Kelas --</option>
                                <option>X MIPA 1</option>
                                <option>XI MIPA 2</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="wf-label">Kata Sandi *</label>
                            <input type="password" class="wf-input" placeholder="Min. 8 karakter">
                        </div>
                        <div class="col-6">
                            <label class="wf-label">Konfirmasi Sandi *</label>
                            <input type="password" class="wf-input" placeholder="Ulangi sandi">
                        </div>
                    </div>
                    <button type="submit" class="wf-btn w-100" style="margin-top: 15px; padding: 8px;">[Daftar Akun Baru]</button>
                    <div style="text-align: center; margin-top: 15px; font-size: 10px;">
                        Sudah punya akun? <a href="#" style="color: #000; font-weight: bold; text-decoration: underline;">Login di sini</a>
                    </div>
                </form>
            </div>
        </div>
    """
}

# Page 3: Login
pages_dict['03_login.html'] = {
    'title': 'Perancangan Halaman Login',
    'content': """
        <div style="display: flex; justify-content: center; align-items: center; flex-grow: 1; padding: 60px 0;">
            <div class="wf-card" style="width: 350px; padding: 25px;">
                <div style="text-align: center; margin-bottom: 25px;">
                    <div class="wf-circle" style="width: 45px; height: 45px; margin-bottom: 8px;"><i class="fas fa-lock"></i></div>
                    <h5 style="font-size: 14px; font-weight: bold; margin: 0;">Masuk SIMEKS</h5>
                    <p style="font-size: 10px; color: #666; margin: 2px 0 0 0;">Portal Ekstrakurikuler Digital</p>
                </div>
                <form>
                    <div style="margin-bottom: 12px;">
                        <label class="wf-label">Email / NISN</label>
                        <input type="text" class="wf-input" placeholder="Masukkan email atau NISN">
                    </div>
                    <div style="margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between;">
                            <label class="wf-label">Kata Sandi</label>
                            <a href="#" style="font-size: 9px; color: #000; text-decoration: underline;">Lupa Password?</a>
                        </div>
                        <input type="password" class="wf-input" placeholder="Masukkan kata sandi">
                    </div>
                    <button type="submit" class="wf-btn w-100" style="padding: 8px;">[Masuk Sekarang]</button>
                    <div style="text-align: center; margin-top: 15px; font-size: 10px;">
                        Belum punya akun? <a href="#" style="color: #000; font-weight: bold; text-decoration: underline;">Daftar</a>
                    </div>
                </form>
            </div>
        </div>
    """
}

# Page 4: Dashboard Siswa
pages_dict['04_dashboard_siswa.html'] = {
    'title': 'Perancangan Halaman Dashboard Siswa',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS</div>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" class="wf-sidebar-link"><i class="fas fa-basketball-ball"></i> Daftar Ekskul</a>
                <a href="#" class="wf-sidebar-link"><i class="fas fa-clipboard-list"></i> Riwayat Absensi</a>
                <a href="#" class="wf-sidebar-link"><i class="fas fa-trophy"></i> Prestasi</a>
                <a href="#" class="wf-sidebar-link"><i class="fas fa-user-edit"></i> Edit Profil</a>
                <a href="#" class="wf-sidebar-link" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-header-bar">
                    <div>
                        <h6 style="font-size: 12px; font-weight: bold; margin: 0;">Selamat Datang, Ahmad Fauzi 👋</h6>
                        <small style="font-size: 9px; color: #666;">Siswa XI MIPA 2</small>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="wf-btn-outline" style="padding: 2px 6px;"><i class="far fa-bell"></i></span>
                        <div class="wf-circle">AF</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="wf-card" style="padding: 10px;">
                            <div style="font-size: 9px; color: #666;">Ekskul Aktif</div>
                            <div style="font-size: 12px; font-weight: bold;">Bola Basket</div>
                            <span class="wf-badge" style="margin-top: 4px;">Anggota Aktif</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="wf-card" style="padding: 10px;">
                            <div style="font-size: 9px; color: #666;">Kehadiran Latihan</div>
                            <div style="font-size: 14px; font-weight: bold;">92%</div>
                            <span class="wf-badge" style="margin-top: 4px;">Baik</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="wf-card" style="padding: 10px;">
                            <div style="font-size: 9px; color: #666;">Total Prestasi</div>
                            <div style="font-size: 14px; font-weight: bold;">2</div>
                            <span class="wf-badge" style="margin-top: 4px;">Penghargaan</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-7">
                        <div class="wf-card">
                            <div class="wf-card-header"><span><i class="fas fa-bullhorn"></i> Pengumuman Terbaru</span></div>
                            <div style="border-left: 3px solid #000; padding-left: 8px; margin-bottom: 12px;">
                                <div style="font-size: 10px; font-weight: bold;">Latihan Basket Diliburkan</div>
                                <div style="font-size: 9px; color: #666;">Latihan hari Rabu ditiadakan sementara karena perbaikan lapangan.</div>
                            </div>
                            <div style="border-left: 3px solid #000; padding-left: 8px;">
                                <div style="font-size: 10px; font-weight: bold;">Penerimaan Anggota Baru PMR</div>
                                <div style="font-size: 9px; color: #666;">Pendaftaran PMR dibuka hingga akhir bulan Juli.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="wf-card">
                            <div class="wf-card-header"><span><i class="fas fa-calendar-alt"></i> Jadwal Terdekat</span></div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <div class="wf-circle" style="border-radius: 4px; width: 28px; height: 28px; font-size: 9px;">SAB</div>
                                <div>
                                    <div style="font-size: 9px; font-weight: bold;">Latihan Basket</div>
                                    <div style="font-size: 8px; color: #666;">Lapangan Utama</div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="wf-circle" style="border-radius: 4px; width: 28px; height: 28px; font-size: 9px;">SEN</div>
                                <div>
                                    <div style="font-size: 9px; font-weight: bold;">Turnamen DBL</div>
                                    <div style="font-size: 8px; color: #666;">GOR Bekasi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    """
}

# Page 5: Pendaftaran Ekskul
pages_dict['05_pendaftaran_ekskul.html'] = {
    'title': 'Perancangan Halaman Pendaftaran Ekstrakurikuler',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS</div>
                <a href="#"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-basketball-ball"></i> Daftar Ekskul</a>
                <a href="#"><i class="fas fa-clipboard-list"></i> Riwayat Absensi</a>
                <a href="#"><i class="fas fa-trophy"></i> Prestasi</a>
                <a href="#"><i class="fas fa-user-edit"></i> Edit Profil</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-header-bar">
                    <h6 style="font-size: 12px; font-weight: bold; margin: 0;">Pendaftaran Ekstrakurikuler</h6>
                    <input type="text" class="wf-input" style="width: 150px; margin-bottom: 0; padding: 4px;" placeholder="Cari Ekskul...">
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="wf-card" style="padding: 10px;">
                            <div class="wf-placeholder" style="min-height: 70px;"><span class="wf-placeholder-text">FOTO</span></div>
                            <h6 style="font-size: 11px; font-weight: bold; margin: 5px 0;">Bola Basket</h6>
                            <p style="font-size: 8px; color: #555; margin-bottom: 8px;">Latihan kebugaran, teknik, dan kompetisi turnamen resmi.</p>
                            <span class="wf-badge" style="margin-bottom: 8px;">Kuota: 12/30</span>
                            <button class="wf-btn w-100" style="padding: 4px;">[Daftar Ekskul]</button>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="wf-card" style="padding: 10px;">
                            <div class="wf-placeholder" style="min-height: 70px;"><span class="wf-placeholder-text">FOTO</span></div>
                            <h6 style="font-size: 11px; font-weight: bold; margin: 5px 0;">Paduan Suara</h6>
                            <p style="font-size: 8px; color: #555; margin-bottom: 8px;">Pengembangan vokal, harmoni, dan penampilan seni musik.</p>
                            <span class="wf-badge" style="margin-bottom: 8px;">Kuota: 25/30</span>
                            <button class="wf-btn w-100" style="padding: 4px;">[Daftar Ekskul]</button>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="wf-card" style="padding: 10px;">
                            <div class="wf-placeholder" style="min-height: 70px;"><span class="wf-placeholder-text">FOTO</span></div>
                            <h6 style="font-size: 11px; font-weight: bold; margin: 5px 0;">Seni Rupa</h6>
                            <p style="font-size: 8px; color: #555; margin-bottom: 8px;">Teknik menggambar, melukis, dan karya seni kontemporer.</p>
                            <span class="wf-badge" style="margin-bottom: 8px;">Kuota: 8/25</span>
                            <button class="wf-btn-disabled w-100" style="padding: 4px;" disabled>[Menunggu Verifikasi]</button>
                        </div>
                    </div>
                </div>

                <div class="wf-card" style="border: 2px dashed #000; background: #fafafa; margin-top: 10px;">
                    <div class="wf-card-header" style="border-bottom: 1.5px dashed #000;"><span class="text-danger"><i class="fas fa-clipboard-check"></i> Modal Preview: Konfirmasi Pendaftaran</span></div>
                    <div class="row">
                        <div class="col-4"><div style="font-size: 9px; border: 1px solid #000; padding: 4px; background:#fff; text-align:center;">NISN: 101230456</div></div>
                        <div class="col-4"><div style="font-size: 9px; border: 1px solid #000; padding: 4px; background:#fff; text-align:center;">Nama: Ahmad Fauzi</div></div>
                        <div class="col-4"><div style="font-size: 9px; border: 1px solid #000; padding: 4px; background:#fff; text-align:center;">Kelas: XI MIPA 2</div></div>
                    </div>
                    <button class="wf-btn w-100" style="margin-top: 10px; padding: 6px;">[Kirim Permohonan Pendaftaran]</button>
                </div>
            </div>
        </div>
    """
}

# Page 6: Riwayat Absensi dan Prestasi Siswa
pages_dict['06_riwayat_absensi_prestasi.html'] = {
    'title': 'Perancangan Halaman Riwayat Absensi dan Prestasi Siswa',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS</div>
                <a href="#"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#"><i class="fas fa-basketball-ball"></i> Daftar Ekskul</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-clipboard-list"></i> Riwayat Absensi</a>
                <a href="#"><i class="fas fa-trophy"></i> Prestasi</a>
                <a href="#"><i class="fas fa-user-edit"></i> Edit Profil</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-header-bar">
                    <h6 style="font-size: 12px; font-weight: bold; margin: 0;">Riwayat Absensi &amp; Catatan Prestasi</h6>
                </div>

                <div class="row">
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">18</div><div style="font-size: 9px;">Hadir</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">2</div><div style="font-size: 9px;">Sakit</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">1</div><div style="font-size: 9px;">Izin</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">1</div><div style="font-size: 9px;">Alpa</div></div></div>
                </div>

                <div class="wf-card" style="padding: 10px;">
                    <div class="row">
                        <div class="col-5"><select class="wf-input" style="margin-bottom:0;"><option>Bola Basket</option></select></div>
                        <div class="col-4"><select class="wf-input" style="margin-bottom:0;"><option>Semua Bulan</option></select></div>
                        <div class="col-3"><button class="wf-btn w-100">[Filter]</button></div>
                    </div>
                </div>

                <div class="wf-card">
                    <div class="wf-card-header"><span>Riwayat Kehadiran Latihan</span></div>
                    <table class="wf-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Ekskul</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td>24 Jun 2026</td><td>Bola Basket</td><td><span class="wf-badge">Hadir</span></td><td>-</td></tr>
                            <tr><td>2</td><td>17 Jun 2026</td><td>Bola Basket</td><td><span class="wf-badge">Izin</span></td><td>Ujian Susulan</td></tr>
                            <tr><td>3</td><td>10 Jun 2026</td><td>Bola Basket</td><td><span class="wf-badge">Alpa</span></td><td>Tanpa Keterangan</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="wf-card">
                    <div class="wf-card-header"><span>🥇 Catatan Prestasi &amp; E-Sertifikat</span></div>
                    <div class="row">
                        <div class="col-6">
                            <div style="border: 1px solid #000; padding: 10px; border-radius: 4px; background: #fafafa;">
                                <div style="font-size: 8px; font-weight: bold; color: #555;">REGIONAL JAWA BARAT</div>
                                <h6 style="font-size: 10px; font-weight: bold; margin: 4px 0;">Juara 1 - Kejuaraan DBL</h6>
                                <p style="font-size: 8px; margin-bottom: 8px;">15 Maret 2026</p>
                                <button class="wf-btn w-100" style="padding: 3px;"><i class="fas fa-download"></i> [Unduh E-Sertifikat]</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="border: 1px solid #000; padding: 10px; border-radius: 4px; background: #fafafa;">
                                <div style="font-size: 8px; font-weight: bold; color: #555;">KABUPATEN BEKASI</div>
                                <h6 style="font-size: 10px; font-weight: bold; margin: 4px 0;">Juara 2 - Piala Bupati</h6>
                                <p style="font-size: 8px; margin-bottom: 8px;">20 Nov 2025</p>
                                <button class="wf-btn w-100" style="padding: 3px;"><i class="fas fa-download"></i> [Unduh E-Sertifikat]</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    """
}

# Page 7: Dashboard Pembina
pages_dict['07_dashboard_pembina.html'] = {
    'title': 'Perancangan Halaman Dashboard Pembina',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">PORTAL PEMBINA</div>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="#"><i class="fas fa-user-check"></i> Verifikasi Anggota</a>
                <a href="#"><i class="fas fa-calendar-alt"></i> Kelola Absensi</a>
                <a href="#"><i class="fas fa-award"></i> Kelola Prestasi</a>
                <a href="#"><i class="fas fa-file-pdf"></i> Laporan</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-power-off"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-header-bar">
                    <div>
                        <h6 style="font-size: 12px; font-weight: bold; margin: 0;">Selamat Datang, Budi Santoso, S.Pd.</h6>
                        <small style="font-size: 9px; color: #666;">Pembina Ekstrakurikuler Bola Basket</small>
                    </div>
                    <div class="wf-circle">BS</div>
                </div>

                <div class="row">
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">28</div><div style="font-size: 9px;">Anggota Aktif</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">4</div><div style="font-size: 9px;">Menunggu Verif.</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">48</div><div style="font-size: 9px;">Sesi Latihan</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">7</div><div style="font-size: 9px;">Total Prestasi</div></div></div>
                </div>

                <div class="wf-card">
                    <div class="wf-card-header">
                        <span>Pendaftaran Anggota Baru Terkini</span>
                        <button class="wf-btn" style="padding: 2px 8px; font-size: 9px;">[Lihat Semua]</button>
                    </div>
                    <table class="wf-table">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tgl. Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Siti Rahayu</td>
                                <td>X MIPA 3</td>
                                <td>14 Jul 2026</td>
                                <td>
                                    <button class="wf-btn" style="padding: 2px 6px;">[Terima]</button>
                                    <button class="wf-btn-outline" style="padding: 2px 6px;">[Tolak]</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Budi Permana</td>
                                <td>XI IPS 1</td>
                                <td>13 Jul 2026</td>
                                <td>
                                    <button class="wf-btn" style="padding: 2px 6px;">[Terima]</button>
                                    <button class="wf-btn-outline" style="padding: 2px 6px;">[Tolak]</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    """
}

# Page 8: Verifikasi Pendaftaran Anggota
pages_dict['08_verifikasi_pendaftaran.html'] = {
    'title': 'Perancangan Halaman Verifikasi Pendaftaran Anggota',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">PORTAL PEMBINA</div>
                <a href="#"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-user-check"></i> Verifikasi Anggota</a>
                <a href="#"><i class="fas fa-calendar-alt"></i> Kelola Absensi</a>
                <a href="#"><i class="fas fa-award"></i> Kelola Prestasi</a>
                <a href="#"><i class="fas fa-file-pdf"></i> Laporan</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-power-off"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-header-bar">
                    <h6 style="font-size: 12px; font-weight: bold; margin: 0;">Verifikasi Pengajuan Anggota Baru</h6>
                    <div style="display: flex; gap: 8px;">
                        <select class="wf-input" style="width: 100px; margin-bottom: 0; padding: 4px;"><option>Bola Basket</option></select>
                        <select class="wf-input" style="width: 100px; margin-bottom: 0; padding: 4px;"><option>Menunggu</option></select>
                    </div>
                </div>

                <div class="wf-card">
                    <table class="wf-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Ekskul</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>101230111</td>
                                <td>Siti Rahayu</td>
                                <td>X MIPA 3</td>
                                <td>Bola Basket</td>
                                <td><span class="wf-badge">Menunggu</span></td>
                                <td>
                                    <button class="wf-btn" style="padding: 2px 6px;">[Terima]</button>
                                    <button class="wf-btn-outline" style="padding: 2px 6px;">[Tolak]</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>101230222</td>
                                <td>Budi Permana</td>
                                <td>XI IPS 1</td>
                                <td>Bola Basket</td>
                                <td><span class="wf-badge">Menunggu</span></td>
                                <td>
                                    <button class="wf-btn" style="padding: 2px 6px;">[Terima]</button>
                                    <button class="wf-btn-outline" style="padding: 2px 6px;">[Tolak]</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="wf-card" style="border: 2px dashed #000; background: #fff5f5;">
                    <div class="wf-card-header" style="border-bottom: 1.5px dashed #000;"><span class="text-danger"><i class="fas fa-times-circle"></i> Modal: Alasan Penolakan Pendaftaran</span></div>
                    <label class="wf-label">Tulis alasan penolakan:</label>
                    <textarea class="wf-input" rows="2" placeholder="Contoh: Kuota kelas ekstrakurikuler sudah penuh."></textarea>
                    <button class="wf-btn w-100" style="padding: 6px;">[Simpan Keputusan &amp; Kirim Notifikasi]</button>
                </div>
            </div>
        </div>
    """
}

# Page 9: Pengelolaan Absensi & Prestasi
pages_dict['09_kelola_absensi_prestasi.html'] = {
    'title': 'Perancangan Halaman Pengelolaan Absensi dan Prestasi',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">PORTAL PEMBINA</div>
                <a href="#"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="#"><i class="fas fa-user-check"></i> Verifikasi Anggota</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-calendar-alt"></i> Kelola Absensi</a>
                <a href="#"><i class="fas fa-award"></i> Kelola Prestasi</a>
                <a href="#"><i class="fas fa-file-pdf"></i> Laporan</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-power-off"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-card">
                    <div class="wf-card-header"><span><i class="fas fa-clipboard-list"></i> Input Presensi Kehadiran Latihan</span></div>
                    <div class="row" style="margin-bottom: 12px;">
                        <div class="col-5">
                            <label class="wf-label">Ekstrakurikuler</label>
                            <select class="wf-input" style="margin-bottom:0;"><option>Bola Basket</option></select>
                        </div>
                        <div class="col-4">
                            <label class="wf-label">Tanggal Latihan</label>
                            <input type="date" class="wf-input" style="margin-bottom:0;" value="2026-07-15">
                        </div>
                        <div class="col-3" style="display: flex; align-items: flex-end;">
                            <button class="wf-btn w-100" style="padding: 8px;">[Tampilkan]</button>
                        </div>
                    </div>
                    
                    <table class="wf-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Kehadiran (H / I / S / A)</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Ahmad Fauzi</td>
                                <td>XI MIPA 2</td>
                                <td>
                                    <input type="radio" checked> H &nbsp;
                                    <input type="radio"> I &nbsp;
                                    <input type="radio"> S &nbsp;
                                    <input type="radio"> A
                                </td>
                                <td><input type="text" class="wf-input" style="margin-bottom:0; padding:2px;" placeholder="Opsional"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Siti Rahayu</td>
                                <td>X MIPA 3</td>
                                <td>
                                    <input type="radio" checked> H &nbsp;
                                    <input type="radio"> I &nbsp;
                                    <input type="radio"> S &nbsp;
                                    <input type="radio"> A
                                </td>
                                <td><input type="text" class="wf-input" style="margin-bottom:0; padding:2px;" placeholder="Opsional"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="display: flex; justify-content: flex-end;">
                        <button class="wf-btn">[Simpan Presensi]</button>
                    </div>
                </div>

                <div class="wf-card">
                    <div class="wf-card-header">
                        <span>🏆 Data Prestasi &amp; Rekor Siswa</span>
                        <button class="wf-btn" style="padding: 2px 8px; font-size: 9px;">[+ Tambah Prestasi]</button>
                    </div>
                    <table class="wf-table">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kejuaraan</th>
                                <th>Tingkat</th>
                                <th>Peringkat</th>
                                <th>E-Sertifikat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad Fauzi</td>
                                <td>DBL Jawa Barat</td>
                                <td>Provinsi</td>
                                <td>Juara 1</td>
                                <td>[File.pdf]</td>
                                <td>
                                    <button class="wf-btn" style="padding:2px 4px;"><i class="fas fa-edit"></i></button>
                                    <button class="wf-btn-outline" style="padding:2px 4px;"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    """
}

# Page 10: Dashboard Admin
pages_dict['10_dashboard_admin.html'] = {
    'title': 'Perancangan Halaman Dashboard Admin',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS ADMIN</div>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="#"><i class="fas fa-basketball-ball"></i> Kelola Ekskul</a>
                <a href="#"><i class="fas fa-user-tie"></i> Kelola Pembina</a>
                <a href="#"><i class="fas fa-users"></i> Kelola Siswa</a>
                <a href="#"><i class="fas fa-user-check"></i> Verifikasi Pendaftaran</a>
                <a href="#"><i class="fas fa-bullhorn"></i> Pengumuman</a>
                <a href="#"><i class="fas fa-file-pdf"></i> Laporan</a>
                <a href="#"><i class="fas fa-shield-alt"></i> Audit Logs</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-power-off"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-header-bar">
                    <h6 style="font-size: 12px; font-weight: bold; margin: 0;">Dashboard Utama Admin</h6>
                    <small style="font-size: 9px; color: #666;">Selasa, 15 Juli 2026</small>
                </div>

                <div class="row">
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">15</div><div style="font-size: 8px; color:#555;">Total Ekskul</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">12</div><div style="font-size: 8px; color:#555;">Guru Pembina</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">450</div><div style="font-size: 8px; color:#555;">Siswa Terdaftar</div></div></div>
                    <div class="col-3"><div class="wf-card" style="padding: 10px; text-align: center;"><div style="font-size: 14px; font-weight: bold;">8</div><div style="font-size: 8px; color:#555;">Pendaftaran Baru</div></div></div>
                </div>

                <div class="row">
                    <div class="col-7">
                        <div class="wf-card">
                            <div class="wf-card-header"><span>Pendaftaran Terkini</span></div>
                            <table class="wf-table">
                                <thead>
                                    <tr><th>Nama Siswa</th><th>Kelas</th><th>Ekskul</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>Ahmad Fauzi</td><td>XI MIPA 2</td><td>Bola Basket</td><td><span class="wf-badge">Menunggu</span></td></tr>
                                    <tr><td>Siti Rahayu</td><td>X MIPA 3</td><td>Paduan Suara</td><td><span class="wf-badge">Diterima</span></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="wf-card">
                            <div class="wf-card-header"><span>Keaktifan Anggota Ekskul</span></div>
                            <div style="font-size: 9px; margin-bottom: 6px;">
                                <span>Bola Basket (28/30)</span>
                                <div class="wf-progress"><div class="wf-progress-bar" style="width: 93%;"></div></div>
                            </div>
                            <div style="font-size: 9px; margin-bottom: 6px;">
                                <span>Paduan Suara (25/30)</span>
                                <div class="wf-progress"><div class="wf-progress-bar" style="width: 83%;"></div></div>
                            </div>
                            <div style="font-size: 9px;">
                                <span>Seni Rupa (14/25)</span>
                                <div class="wf-progress"><div class="wf-progress-bar" style="width: 56%;"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    """
}

# Page 11: Pengelolaan Pengguna & Ekskul
pages_dict['11_kelola_pengguna_ekskul.html'] = {
    'title': 'Perancangan Halaman Pengelolaan Pengguna dan Ekstrakurikuler',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS ADMIN</div>
                <a href="#"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-basketball-ball"></i> Kelola Ekskul</a>
                <a href="#"><i class="fas fa-user-tie"></i> Kelola Pembina</a>
                <a href="#"><i class="fas fa-users"></i> Kelola Siswa</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-power-off"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-card">
                    <div class="wf-card-header">
                        <span>Data Ekstrakurikuler Sekolah</span>
                        <button class="wf-btn" style="padding: 2px 8px; font-size: 9px;">[+ Tambah Ekskul]</button>
                    </div>
                    <table class="wf-table">
                        <thead>
                            <tr><th>No</th><th>Nama Ekskul</th><th>Pembina</th><th>Jadwal</th><th>Kuota</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td>Bola Basket</td><td>Budi Santoso, S.Pd.</td><td>Rabu &amp; Sabtu</td><td>28/30</td><td>[Edit] [Hapus]</td></tr>
                            <tr><td>2</td><td>Paduan Suara</td><td>Dewi Rahayu, S.Pd.</td><td>Senin &amp; Kamis</td><td>25/30</td><td>[Edit] [Hapus]</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="wf-card">
                    <div class="wf-card-header">
                        <span>Data Guru Pembina</span>
                        <button class="wf-btn" style="padding: 2px 8px; font-size: 9px;">[+ Tambah Pembina]</button>
                    </div>
                    <table class="wf-table">
                        <thead>
                            <tr><th>Nama Pembina</th><th>Email</th><th>Ekskul Binaan</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Budi Santoso, S.Pd.</td><td>budi@sman2.sch.id</td><td>Bola Basket</td><td>[Edit] [Hapus]</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="wf-card">
                    <div class="wf-card-header">
                        <span>Data Akun Siswa</span>
                        <button class="wf-btn" style="padding: 2px 8px; font-size: 9px;">[Export Excel]</button>
                    </div>
                    <table class="wf-table">
                        <thead>
                            <tr><th>NISN</th><th>Nama Siswa</th><th>Kelas</th><th>Status Akun</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>101230456</td><td>Ahmad Fauzi</td><td>XI MIPA 2</td><td><span class="wf-badge">Aktif</span></td><td>[Edit] [Hapus]</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    """
}

# Page 12: Pengumuman & Notifikasi
pages_dict['12_pengumuman_notifikasi.html'] = {
    'title': 'Perancangan Halaman Pengumuman dan Notifikasi',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS ADMIN</div>
                <a href="#"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-bullhorn"></i> Kelola Pengumuman</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-power-off"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-card">
                    <div class="wf-card-header">
                        <span>Manajemen Pengumuman Sekolah</span>
                        <button class="wf-btn" style="padding: 2px 8px; font-size: 9px;">[+ Buat Baru]</button>
                    </div>
                    <table class="wf-table">
                        <thead>
                            <tr><th>No</th><th>Judul Pengumuman</th><th>Target</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td>Latihan Basket Diliburkan</td><td>Anggota Basket</td><td><span class="wf-badge">Publik</span></td><td>[Edit] [Hapus]</td></tr>
                            <tr><td>2</td><td>Penerimaan Anggota PMR</td><td>Semua Siswa</td><td><span class="wf-badge">Publik</span></td><td>[Edit] [Hapus]</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="wf-card">
                    <div class="wf-card-header"><span>Formulir Rilis Pengumuman</span></div>
                    <div class="row">
                        <div class="col-12">
                            <label class="wf-label">Judul Pengumuman</label>
                            <input type="text" class="wf-input" placeholder="Masukkan judul pengumuman">
                        </div>
                        <div class="col-6">
                            <label class="wf-label">Target Penerima</label>
                            <select class="wf-input"><option>Semua Siswa</option></select>
                        </div>
                        <div class="col-6">
                            <label class="wf-label">Status</label>
                            <select class="wf-input"><option>Aktif (Publik)</option></select>
                        </div>
                        <div class="col-12">
                            <label class="wf-label">Konten Pengumuman</label>
                            <textarea class="wf-input" rows="3" placeholder="Tulis pengumuman..."></textarea>
                        </div>
                    </div>
                    <button class="wf-btn w-100" style="padding: 6px; margin-top: 5px;">[Rilis Pengumuman &amp; Kirim Notifikasi]</button>
                </div>
            </div>
        </div>
    """
}

# Page 13: Laporan
pages_dict['13_laporan.html'] = {
    'title': 'Perancangan Halaman Laporan Ekstrakurikuler',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS ADMIN</div>
                <a href="#"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-file-pdf"></i> Laporan</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-power-off"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-card">
                    <div class="wf-card-header"><span>Penyusunan &amp; Cetak Laporan Rekapitulasi</span></div>
                    <div class="row">
                        <div class="col-3">
                            <label class="wf-label">Jenis Laporan</label>
                            <select class="wf-input"><option>Rekap Anggota Ekskul</option></select>
                        </div>
                        <div class="col-3">
                            <label class="wf-label">Ekskul</label>
                            <select class="wf-input"><option>Bola Basket</option></select>
                        </div>
                        <div class="col-3">
                            <label class="wf-label">Periode</label>
                            <input type="month" class="wf-input" value="2026-07">
                        </div>
                        <div class="col-3" style="display: flex; gap: 5px; align-items: flex-end; padding-bottom: 10px;">
                            <button class="wf-btn w-50" style="padding: 5px;">[Preview]</button>
                            <button class="wf-btn-outline w-50" style="padding: 5px;">[Cetak]</button>
                        </div>
                    </div>
                </div>

                <div class="wf-card">
                    <div class="wf-card-header">
                        <span>Pratinjau Halaman Cetak Laporan (A4 Standard)</span>
                        <div>
                            <button class="wf-btn" style="padding: 2px 6px; font-size: 8px;">[Export Excel]</button>
                            <button class="wf-btn" style="padding: 2px 6px; font-size: 8px;">[Cetak PDF]</button>
                        </div>
                    </div>
                    
                    <div style="border: 1px solid #000; padding: 15px; background: #ffffff;">
                        <div style="text-align: center; margin-bottom: 15px;">
                            <h6 style="font-size: 11px; font-weight: bold; margin: 0 0 4px 0;">LAPORAN REKAPITULASI ANGGOTA EKSTRAKURIKULER</h6>
                            <p style="font-size: 9px; margin: 0; color: #444;">Periode: Juli 2026 | SMAN 2 Sukatani</p>
                        </div>
                        <table class="wf-table" style="font-size: 9px;">
                            <thead>
                                <tr><th>No</th><th>NISN</th><th>Nama Siswa</th><th>Kelas</th><th>% Hadir</th><th>Status Keanggotaan</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>1</td><td>101230456</td><td>Ahmad Fauzi</td><td>XI MIPA 2</td><td>92%</td><td>Aktif</td></tr>
                                <tr><td>2</td><td>101230111</td><td>Siti Rahayu</td><td>X MIPA 3</td><td>88%</td><td>Aktif</td></tr>
                            </tbody>
                        </table>
                        <div style="text-align: right; margin-top: 15px; font-size: 9px;">
                            Mengetahui,<br><br><br><strong>Budi Santoso, S.Pd.</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    """
}

# Page 14: Audit Logs
pages_dict['14_audit_logs.html'] = {
    'title': 'Perancangan Halaman Monitoring Audit Logs',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS ADMIN</div>
                <a href="#"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-shield-alt"></i> Audit Logs</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-power-off"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-header-bar">
                    <h6 style="font-size: 12px; font-weight: bold; margin: 0;">Sistem Monitoring Audit Logs Keamanan</h6>
                    <button class="wf-btn" style="padding: 2px 8px; font-size: 9px;">[Clear History]</button>
                </div>

                <div class="wf-card" style="padding: 10px;">
                    <div class="row">
                        <div class="col-4"><input type="text" class="wf-input" style="margin-bottom:0;" placeholder="Cari Aktivitas / Pengguna..."></div>
                        <div class="col-3"><select class="wf-input" style="margin-bottom:0;"><option>Semua Role</option></select></div>
                        <div class="col-3"><input type="date" class="wf-input" style="margin-bottom:0;" value="2026-07-15"></div>
                        <div class="col-2"><button class="wf-btn w-100" style="padding: 6px;">[Filter]</button></div>
                    </div>
                </div>

                <div class="wf-card">
                    <table class="wf-table">
                        <thead>
                            <tr><th>No</th><th>Waktu</th><th>Nama Pengguna</th><th>Role</th><th>Aktivitas</th><th>Keterangan</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td><td>15/07/2026 07:15</td><td>Ahmad Fauzi</td><td>Siswa</td><td>Login Berhasil</td><td>Akses portal siswa</td><td>SUKSES</td>
                            </tr>
                            <tr>
                                <td>2</td><td>14/07/2026 19:40</td><td>Admin Utama</td><td>Admin</td><td>Tambah Ekskul</td><td>Menambahkan Robotika</td><td>SUKSES</td>
                            </tr>
                            <tr>
                                <td>3</td><td>14/07/2026 16:20</td><td>Unknown User</td><td>-</td><td>Login Gagal</td><td>3x NISN salah</td><td>GAGAL</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    """
}

# Page 15: Profil Pengguna
pages_dict['15_profil_pengguna.html'] = {
    'title': 'Perancangan Halaman Profil Pengguna',
    'content': """
        <div class="wf-sidebar-layout">
            <div class="wf-sidebar">
                <div class="brand">SIMEKS</div>
                <a href="#"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#"><i class="fas fa-basketball-ball"></i> Daftar Ekskul</a>
                <a href="#"><i class="fas fa-clipboard-list"></i> Riwayat Absensi</a>
                <a href="#"><i class="fas fa-trophy"></i> Prestasi</a>
                <a href="#" class="wf-sidebar-link active"><i class="fas fa-user-edit"></i> Edit Profil</a>
                <a href="#" style="margin-top: auto; border-top: 1.5px solid #000; padding-top: 10px;"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
            
            <div class="wf-main-content">
                <div class="wf-header-bar">
                    <h6 style="font-size: 12px; font-weight: bold; margin: 0;">Pengaturan Akun &amp; Profil Pengguna</h6>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="wf-card" style="text-align: center;">
                            <div class="wf-circle" style="width: 80px; height: 80px; font-size: 28px; margin: 0 auto 10px auto;">AF</div>
                            <h6 style="font-size: 11px; font-weight: bold; margin: 0 0 2px 0;">Ahmad Fauzi</h6>
                            <p style="font-size: 9px; color: #666; margin: 0 0 8px 0;">Siswa Kelas XI MIPA 2</p>
                            <span class="wf-badge">Bola Basket</span>
                            <hr style="margin: 10px 0; border: none; border-top: 1px solid #000;">
                            <label class="wf-label">Ganti Foto Profil</label>
                            <input type="file" class="wf-input" style="font-size: 9px;">
                            <button class="wf-btn w-100" style="padding: 4px; margin-top: 5px;">[Unggah Foto]</button>
                        </div>
                    </div>
                    
                    <div class="col-8">
                        <div class="wf-card">
                            <h6 style="font-size: 11px; font-weight: bold; margin-bottom: 12px; border-bottom: 1px solid #000; padding-bottom: 5px;">Detail Profil Diri</h6>
                            <form>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="wf-label">NISN (Read Only)</label>
                                        <input type="text" class="wf-input" value="101230456" readonly style="background: #f0f0f0;">
                                    </div>
                                    <div class="col-6">
                                        <label class="wf-label">Nama Lengkap</label>
                                        <input type="text" class="wf-input" value="Ahmad Fauzi">
                                    </div>
                                    <div class="col-6">
                                        <label class="wf-label">Email</label>
                                        <input type="email" class="wf-input" value="ahmad.fauzi@gmail.com">
                                    </div>
                                    <div class="col-6">
                                        <label class="wf-label">Kelas</label>
                                        <select class="wf-input"><option>XI MIPA 2</option></select>
                                    </div>
                                </div>
                                <hr style="margin: 15px 0; border: none; border-top: 1px solid #000;">
                                <h6 style="font-size: 10px; font-weight: bold; margin-bottom: 8px; color: #555;">Ganti Kata Sandi</h6>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="wf-label">Kata Sandi Lama</label>
                                        <input type="password" class="wf-input" placeholder="Masukkan sandi saat ini">
                                    </div>
                                    <div class="col-6">
                                        <label class="wf-label">Kata Sandi Baru</label>
                                        <input type="password" class="wf-input" placeholder="Min. 8 karakter">
                                    </div>
                                    <div class="col-6">
                                        <label class="wf-label">Konfirmasi Sandi Baru</label>
                                        <input type="password" class="wf-input" placeholder="Ulangi sandi baru">
                                    </div>
                                </div>
                                <div style="display: flex; justify-content: flex-end; margin-top: 15px;">
                                    <button type="submit" class="wf-btn">[Simpan Perubahan]</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    """
}

# Write each page
for filename, page_data in pages_dict.items():
    html_content = f"""<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{page_data['title']}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>{wireframe_style}</style>
</head>
<body>
    <div class="wf-container">
        <div class="wf-title">
            <span>{page_data['title']}</span>
        </div>
        {page_data['content']}
        <div class="wf-footer">
            SIMEKS SMAN 2 SUKATANI
        </div>
    </div>
</body>
</html>"""
    filepath = os.path.join(out_dir, filename)
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(html_content)
    print(f"Generated: {filepath}")

# Write a combined index file that displays all 15 wireframes cleanly
combined_content = f"""<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kumpulan Mockup Wireframe SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        {wireframe_style}
        .page-break {{
            page-break-after: always;
            break-after: page;
        }}
    </style>
</head>
<body>
    <div style="text-align: center; padding: 20px 0;">
        <h4 style="font-weight: bold; text-transform: uppercase;">LAMPIRAN MOCKUP WIREFRAME PERANCANGAN ANTARMUKA SIMEKS</h4>
        <p style="font-size: 11px; color: #555;">(Untuk disalin ke Gemini AI / Dokumentasi Bab 3 Skripsi)</p>
    </div>
"""

for filename, page_data in pages_dict.items():
    combined_content += f"""
    <!-- START OF {page_data['title']} -->
    <div class="wf-container page-break">
        <div class="wf-title">
            <span>{page_data['title']}</span>
        </div>
        {page_data['content']}
        <div class="wf-footer">
            SIMEKS SMAN 2 SUKATANI
        </div>
    </div>
    """

combined_content += """
</body>
</html>
"""

combined_filepath = os.path.join(out_dir, '00_semua_mockup_wireframe.html')
with open(combined_filepath, 'w', encoding='utf-8') as f:
    f.write(combined_content)
print(f"Generated Combined File: {combined_filepath}")
