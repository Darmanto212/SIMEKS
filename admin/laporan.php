<?php 
session_start();
require_once '../includes/auth_check.php';
check_auth('admin');

$pageTitle = "Laporan & Cetak - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

// Fetch all eskul for filtering
$eskul_list = $koneksi->query("SELECT * FROM eskul ORDER BY nama_eskul ASC")->fetchAll();
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
            <div class="row g-4">
                <!-- Report Type 1: Student List -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-maroon bg-opacity-10 text-maroon p-3 rounded-circle me-3">
                                <i class="fas fa-users fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Daftar Siswa per Ekskul</h5>
                        </div>
                        <p class="text-muted small mb-4">Cetak daftar seluruh siswa yang terdaftar aktif dalam kegiatan ekstrakurikuler tertentu.</p>
                        
                        <form action="cetak.php" target="_blank" method="GET">
                            <input type="hidden" name="type" value="siswa">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Pilih Ekstrakurikuler</label>
                                <select name="id" id="eskul_id_select" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Eskul --</option>
                                    <option value="all">Semua Ekskul</option>
                                    <?php foreach ($eskul_list as $e): ?>
                                        <option value="<?php echo $e->id; ?>"><?php echo htmlspecialchars($e->nama_eskul); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-maroon rounded-pill">
                                    <i class="fas fa-print me-2"></i> Cetak PDF
                                </button>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="button" onclick="exportData('siswa', 'excel')" class="btn btn-outline-success w-100 rounded-pill small">
                                            <i class="fas fa-file-excel me-1"></i> Excel
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" onclick="exportData('siswa', 'word')" class="btn btn-outline-primary w-100 rounded-pill small">
                                            <i class="fas fa-file-word me-1"></i> Word
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Type 2: Achievements -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle me-3">
                                <i class="fas fa-trophy fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Rekapitulasi Prestasi</h5>
                        </div>
                        <p class="text-muted small mb-4">Cetak seluruh daftar prestasi dan penghargaan yang telah diraih oleh siswa SMAN 2 Sukatani.</p>
                        
                        <div class="mt-auto">
                            <form action="cetak.php" target="_blank" method="GET">
                                <input type="hidden" name="type" value="prestasi">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-outline-maroon rounded-pill">
                                        <i class="fas fa-print me-2"></i> Cetak PDF
                                    </button>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="export.php?type=prestasi&format=excel" class="btn btn-light w-100 rounded-pill small border">
                                                <i class="fas fa-file-excel text-success me-1"></i> Excel
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="export.php?type=prestasi&format=word" class="btn btn-light w-100 rounded-pill small border">
                                                <i class="fas fa-file-word text-primary me-1"></i> Word
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Report Type 3: Eskul List -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                                <i class="fas fa-list-alt fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Daftar Ekstrakurikuler</h5>
                        </div>
                        <p class="text-muted small mb-4">Cetak daftar aktif seluruh ekstrakurikuler beserta pembina dan jadwal kegiatannya.</p>
                        
                        <div class="mt-auto">
                            <form action="cetak.php" target="_blank" method="GET">
                                <input type="hidden" name="type" value="eskul">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-outline-maroon rounded-pill">
                                        <i class="fas fa-print me-2"></i> Cetak PDF
                                    </button>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="export.php?type=eskul&format=excel" class="btn btn-light w-100 rounded-pill small border">
                                                <i class="fas fa-file-excel text-success me-1"></i> Excel
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="export.php?type=eskul&format=word" class="btn btn-light w-100 rounded-pill small border">
                                                <i class="fas fa-file-word text-primary me-1"></i> Word
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });

    function exportData(type, format) {
        const id = document.getElementById('eskul_id_select').value;
        if (!id && type === 'siswa') {
            alert('Pilih ekstrakurikuler terlebih dahulu!');
            return;
        }
        window.location.href = `export.php?type=${type}&format=${format}&id=${id}`;
    }
</script>

<?php include '../includes/footer.php'; ?>
