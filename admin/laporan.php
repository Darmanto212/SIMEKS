<?php 
session_start();
require_once '../includes/auth_check.php';
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
            <div class="row g-4">
                <!-- Report Type 1: Student List -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-maroon bg-opacity-10 text-maroon p-3 rounded-circle me-3">
                                <i class="fas fa-users fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Daftar Siswa per Ekskul</h5>
                        </div>
                        <p class="text-muted small mb-4">Cetak daftar seluruh siswa yang terdaftar aktif dalam kegiatan ekstrakurikuler tertentu.</p>
                        
                        <form action="cetak.php" target="_blank" method="GET">
                            <input type="hidden" name="type" value="siswa">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Pilih Ekstrakurikuler</label>
                                <select name="id" id="eskul_id_select" class="form-select rounded-3" required>
                                    <?php if ($is_admin): ?>
                                        <option value="">-- Pilih Ekskul --</option>
                                        <option value="all">Semua Ekskul</option>
                                    <?php endif; ?>
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
                            <h5 class="fw-bold mb-0 text-dark">Rekapitulasi Prestasi</h5>
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
                                            <a href="export.php?type=prestasi&format=excel" class="btn btn-light w-100 rounded-pill small border text-dark">
                                                <i class="fas fa-file-excel text-success me-1"></i> Excel
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="export.php?type=prestasi&format=word" class="btn btn-light w-100 rounded-pill small border text-dark">
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
                <?php if ($is_admin): ?>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                                <i class="fas fa-list-alt fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Daftar Ekstrakurikuler</h5>
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
                                            <a href="export.php?type=eskul&format=excel" class="btn btn-light w-100 rounded-pill small border text-dark">
                                                <i class="fas fa-file-excel text-success me-1"></i> Excel
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="export.php?type=eskul&format=word" class="btn btn-light w-100 rounded-pill small border text-dark">
                                                <i class="fas fa-file-word text-primary me-1"></i> Word
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Report Type 4: Attendance Recap -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle me-3">
                                <i class="fas fa-calendar-check fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Rekapitulasi Kehadiran Siswa</h5>
                        </div>
                        <p class="text-muted small mb-4">Cetak rekapitulasi tingkat kehadiran siswa per ekstrakurikuler beserta persentase kehadirannya.</p>
                        
                        <form action="cetak.php" target="_blank" method="GET">
                            <input type="hidden" name="type" value="rekap_absensi">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Pilih Ekstrakurikuler</label>
                                <select name="id" id="rekap_eskul_id_select" class="form-select rounded-3" required>
                                    <?php if ($is_admin): ?>
                                        <option value="">-- Pilih Ekskul --</option>
                                    <?php endif; ?>
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
                                        <button type="button" onclick="exportData('rekap_absensi', 'excel')" class="btn btn-outline-success w-100 rounded-pill small">
                                            <i class="fas fa-file-excel me-1"></i> Excel
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" onclick="exportData('rekap_absensi', 'word')" class="btn btn-outline-primary w-100 rounded-pill small">
                                            <i class="fas fa-file-word me-1"></i> Word
                                        </button>
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

<script>
    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });

    function exportData(type, format) {
        let selectId = 'eskul_id_select';
        if (type === 'rekap_absensi') {
            selectId = 'rekap_eskul_id_select';
        }
        const id = document.getElementById(selectId).value;
        if (!id && (type === 'siswa' || type === 'rekap_absensi')) {
            alert('Pilih ekstrakurikuler terlebih dahulu!');
            return;
        }
        window.location.href = `export.php?type=${type}&format=${format}&id=${id}`;
    }
</script>

<?php include '../includes/footer.php'; ?>
