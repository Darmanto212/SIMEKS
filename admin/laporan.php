<?php require_once '../includes/auth_check.php';
check_auth('admin');

$pageTitle = "Laporan & Cetak - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$pembina_eskul_id = null;
if ($is_pembina) {
    $pembina_eskul_id = get_pembina_eskul_id($koneksi, $_SESSION['pembina_data']['user_id']);
}

// Fetch eskul list based on role
if ($is_admin) {
    $eskul_list = $koneksi->query("SELECT * FROM eskul ORDER BY nama_eskul ASC")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_e = $koneksi->prepare("SELECT * FROM eskul WHERE id = ?");
        $stmt_e->execute([$pembina_eskul_id]);
        $eskul_list = $stmt_e->fetchAll();
    } else {
        $eskul_list = [];
    }
}
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Pusat Laporan";
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="row g-4 justify-content-center">
                <!-- Card 1: Siswa & Absensi -->
                <div class="<?php echo $is_admin ? 'col-lg-4' : 'col-lg-6'; ?>">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-maroon bg-opacity-10 text-maroon p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users fs-5"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Siswa & Absensi</h6>
                        </div>
                        <p class="text-muted extra-small mb-3">Unduh daftar anggota aktif atau rekapitulasi kehadiran per ekstrakurikuler.</p>
                        
                        <div class="mb-2">
                            <label class="form-label extra-small fw-bold text-dark mb-1">Tipe Laporan</label>
                            <select id="siswaReportType" class="form-select form-select-sm rounded-3" onchange="toggleSiswaOpt()">
                                <option value="siswa">Daftar Anggota Ekskul</option>
                                <option value="rekap_absensi">Rekapitulasi Kehadiran</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label extra-small fw-bold text-dark mb-1">Pilih Ekstrakurikuler</label>
                            <select id="siswaEskulId" class="form-select form-select-sm rounded-3">
                                <?php if ($is_admin): ?>
                                    <option value="" id="siswaOptPlaceholder">-- Pilih Ekskul --</option>
                                    <option value="all" id="siswaOptAll">Semua Ekskul</option>
                                <?php endif; ?>
                                <?php foreach ($eskul_list as $e): ?>
                                    <option value="<?php echo $e->id; ?>"><?php echo htmlspecialchars($e->nama_eskul); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mt-auto d-grid gap-2">
                            <button type="button" onclick="triggerSiswaLaporan('pdf')" class="btn btn-maroon btn-sm rounded-pill py-2 fw-bold">
                                <i class="fas fa-print me-1"></i> Cetak PDF
                            </button>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" onclick="triggerSiswaLaporan('excel')" class="btn btn-outline-success btn-sm w-100 rounded-pill py-2 fw-bold">
                                        <i class="fas fa-file-excel me-1"></i> Excel
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" onclick="triggerSiswaLaporan('word')" class="btn btn-outline-primary btn-sm w-100 rounded-pill py-2 fw-bold">
                                        <i class="fas fa-file-word me-1"></i> Word
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Rekap Prestasi -->
                <div class="<?php echo $is_admin ? 'col-lg-4' : 'col-lg-6'; ?>">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-trophy fs-5"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Rekap Prestasi</h6>
                        </div>
                        <p class="text-muted extra-small mb-4">Unduh rekapitulasi seluruh daftar prestasi siswa SMAN 2 Sukatani tingkat regional hingga nasional.</p>
                        
                        <div class="mt-auto d-grid gap-2">
                            <a href="cetak.php?type=prestasi" target="_blank" class="btn btn-outline-maroon btn-sm rounded-pill py-2 fw-bold text-decoration-none text-center">
                                <i class="fas fa-print me-1"></i> Cetak PDF
                            </a>
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="export.php?type=prestasi&format=excel" class="btn btn-light btn-sm w-100 rounded-pill py-2 fw-bold border text-dark text-decoration-none text-center">
                                        <i class="fas fa-file-excel text-success me-1"></i> Excel
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="export.php?type=prestasi&format=word" class="btn btn-light btn-sm w-100 rounded-pill py-2 fw-bold border text-dark text-decoration-none text-center">
                                        <i class="fas fa-file-word text-primary me-1"></i> Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Daftar Ekstrakurikuler (Admin only) -->
                <?php if ($is_admin): ?>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-list-alt fs-5"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Daftar Ekskul</h6>
                        </div>
                        <p class="text-muted extra-small mb-4">Unduh rekap daftar ekstrakurikuler sekolah beserta pembina dan jadwal latihannya.</p>
                        
                        <div class="mt-auto d-grid gap-2">
                            <a href="cetak.php?type=eskul" target="_blank" class="btn btn-outline-maroon btn-sm rounded-pill py-2 fw-bold text-decoration-none text-center">
                                <i class="fas fa-print me-1"></i> Cetak PDF
                            </a>
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="export.php?type=eskul&format=excel" class="btn btn-light btn-sm w-100 rounded-pill py-2 fw-bold border text-dark text-decoration-none text-center">
                                        <i class="fas fa-file-excel text-success me-1"></i> Excel
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="export.php?type=eskul&format=word" class="btn btn-light btn-sm w-100 rounded-pill py-2 fw-bold border text-dark text-decoration-none text-center">
                                        <i class="fas fa-file-word text-primary me-1"></i> Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });

    function toggleSiswaOpt() {
        const type = document.getElementById('siswaReportType').value;
        const optPlaceholder = document.getElementById('siswaOptPlaceholder');
        const optAll = document.getElementById('siswaOptAll');

        if (optAll && optPlaceholder) {
            if (type === 'rekap_absensi') {
                optAll.style.display = 'none';
                optPlaceholder.style.display = 'block';
                if (document.getElementById('siswaEskulId').value === 'all') {
                    document.getElementById('siswaEskulId').value = '';
                }
            } else {
                optAll.style.display = 'block';
            }
        }
    }

    function triggerSiswaLaporan(format) {
        const type = document.getElementById('siswaReportType').value;
        const eskulId = document.getElementById('siswaEskulId').value;

        if (!eskulId) {
            alert('Pilih ekstrakurikuler terlebih dahulu!');
            return;
        }

        if (format === 'pdf') {
            window.open(`cetak.php?type=${type}&id=${eskulId}`, '_blank');
        } else {
            window.location.href = `export.php?type=${type}&format=${format}&id=${eskulId}`;
        }
    }

    // Run init
    toggleSiswaOpt();
</script>

<?php include '../includes/footer.php'; ?>
