<?php require_once '../includes/auth_check.php';
check_auth('admin');

include '../config/koneksi.php';

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$pembina_eskul_id = null;
if ($is_pembina) {
    $pembina_eskul_id = get_pembina_eskul_id($koneksi, $_SESSION['pembina_data']['user_id']);
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';

// Security enforcement for Pembina
if ($is_pembina) {
    if ($type == 'eskul') {
        header("Location: laporan.php");
        exit();
    }
    if ($type == 'siswa') {
        $id = $pembina_eskul_id;
    }
}

// Setup content based on type
$title = "LAPORAN SIMEKS";
$data = [];
$eskul_name = "";

if ($type == 'siswa') {
    $params = [];
    $where = "";
    if ($id != 'all' && !empty($id)) {
        $where = " AND p.eskul_id = ?";
        $params[] = $id;
        
        $stmt_eskul = $koneksi->prepare("SELECT nama_eskul FROM eskul WHERE id = ?");
        $stmt_eskul->execute([$id]);
        $eskul_name = $stmt_eskul->fetchColumn();
    }
    
    $query = "
        SELECT u.nama, u.nisn, u.kelas, e.nama_eskul, p.tanggal_daftar 
        FROM pendaftaran p
        JOIN users u ON p.user_id = u.id
        JOIN eskul e ON p.eskul_id = e.id
        WHERE p.status = 'diterima' $where
        ORDER BY e.nama_eskul, u.nama
    ";
    
    $stmt = $koneksi->prepare($query);
    $stmt->execute($params);
    $data = $stmt->fetchAll();
    
    $title = "DAFTAR PESERTA EKSTRAKURIKULER" . ($eskul_name ? " - " . strtoupper($eskul_name) : "");
} elseif ($type == 'prestasi') {
    if ($is_admin) {
        $data = $koneksi->query("SELECT p.*, e.nama_eskul FROM prestasi p LEFT JOIN eskul e ON p.eskul_id = e.id ORDER BY p.tahun DESC")->fetchAll();
    } else {
        if ($pembina_eskul_id) {
            $stmt_pr = $koneksi->prepare("SELECT p.*, e.nama_eskul FROM prestasi p LEFT JOIN eskul e ON p.eskul_id = e.id WHERE p.eskul_id = ? ORDER BY p.tahun DESC");
            $stmt_pr->execute([$pembina_eskul_id]);
            $data = $stmt_pr->fetchAll();
        } else {
            $data = [];
        }
    }
    $title = "LAPORAN REKAPITULASI PRESTASI SISWA";
} elseif ($type == 'eskul') {
    $data = $koneksi->query("SELECT * FROM eskul ORDER BY nama_eskul")->fetchAll();
    $title = "DAFTAR EKSTRAKURIKULER AKTIF";
} elseif ($type == 'absensi') {
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');
    $eskul_id = $is_admin ? ($_GET['eskul_id'] ?? 'all') : $pembina_eskul_id;
    
    $where = " WHERE MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?";
    $params = [$bulan, $tahun];
    
    if ($eskul_id != 'all' && !empty($eskul_id)) {
        $where .= " AND a.eskul_id = ?";
        $params[] = $eskul_id;
        
        $stmt_eskul = $koneksi->prepare("SELECT nama_eskul FROM eskul WHERE id = ?");
        $stmt_eskul->execute([$eskul_id]);
        $eskul_name = $stmt_eskul->fetchColumn();
    }
    
    $query = "
        SELECT a.*, u.nama, u.nisn, u.kelas, e.nama_eskul 
        FROM absensi a
        JOIN users u ON a.user_id = u.id
        JOIN eskul e ON a.eskul_id = e.id
        $where
        ORDER BY a.tanggal DESC, u.nama ASC
    ";
    
    $stmt = $koneksi->prepare($query);
    $stmt->execute($params);
    $data = $stmt->fetchAll();
    
    $monthName = date('F', mktime(0, 0, 0, $bulan, 10));
    $title = "LAPORAN KEHADIRAN SISWA - " . strtoupper($monthName) . " " . $tahun . ($eskul_name ? " (" . strtoupper($eskul_name) . ")" : "");
} elseif ($type == 'rekap_absensi') {
    // Security check for Pembina
    if ($is_pembina) {
        $id = $pembina_eskul_id;
    }
    
    if (empty($id)) {
        header("Location: laporan.php");
        exit();
    }
    
    // Get eskul name and target pertemuan
    $stmt_eskul = $koneksi->prepare("SELECT nama_eskul, target_pertemuan FROM eskul WHERE id = ?");
    $stmt_eskul->execute([$id]);
    $eskul_row = $stmt_eskul->fetch();
    $eskul_name = $eskul_row->nama_eskul ?? "";
    $target_pertemuan = $eskul_row->target_pertemuan ?? 16;
    
    // Count days marked as libur for this eskul
    $stmt_libur = $koneksi->prepare("SELECT COUNT(*) FROM eskul_libur WHERE eskul_id = ?");
    $stmt_libur->execute([$id]);
    $jumlah_libur = $stmt_libur->fetchColumn() ?: 0;
    
    $efektif_pertemuan = $target_pertemuan - $jumlah_libur;
    if ($efektif_pertemuan < 0) $efektif_pertemuan = 0;
    
    // Get all students actively registered in this eskul and aggregate attendance
    $query = "
        SELECT 
            u.nama, 
            u.nisn, 
            u.kelas,
            (SELECT COUNT(*) FROM absensi WHERE user_id = u.id AND eskul_id = e.id AND status = 'hadir') as jumlah_hadir,
            (SELECT COUNT(*) FROM absensi WHERE user_id = u.id AND eskul_id = e.id AND status = 'sakit') as jumlah_sakit,
            (SELECT COUNT(*) FROM absensi WHERE user_id = u.id AND eskul_id = e.id AND status = 'izin') as jumlah_izin,
            (SELECT COUNT(*) FROM absensi WHERE user_id = u.id AND eskul_id = e.id AND status = 'alpa') as jumlah_alpa
        FROM pendaftaran p
        JOIN users u ON p.user_id = u.id
        JOIN eskul e ON p.eskul_id = e.id
        WHERE p.status = 'diterima' AND p.eskul_id = ?
        ORDER BY u.nama ASC
    ";
    
    $stmt = $koneksi->prepare($query);
    $stmt->execute([$id]);
    $data = $stmt->fetchAll();
    
    $title = "REKAPITULASI KEHADIRAN SISWA - " . strtoupper($eskul_name);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - SIMEKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff; font-family: 'Times New Roman', Times, serif; color: #000; }
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 15px; margin-bottom: 25px; }
        .school-logo { width: 80px; }
        .table { border-color: #000 !important; }
        .table th { background-color: #f2f2f2 !important; color: #000 !important; border-bottom: 2px solid #000 !important; }
        .table td, .table th { border: 1px solid #000 !important; padding: 8px !important; }
        @media print {
            .no-print { display: none !important; }
            .container { width: 100% !important; max-width: none !important; margin: 0 !important; padding: 0 !important; }
            @page { margin: 1.5cm; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container mt-4">
        <!-- Button back for screen view -->
        <div class="no-print mb-4 d-flex gap-2">
            <a href="laporan.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Laporan
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print me-1"></i> Cetak Ulang
            </button>
        </div>

        <!-- Kop Surat -->
        <div class="kop-surat text-center d-flex align-items-center justify-content-center">
            <div class="me-4 text-center">
                 <h4 class="mb-0 fw-bold">PEMERINTAH PROVINSI JAWA BARAT</h4>
                 <h4 class="mb-0 fw-bold">DINAS PENDIDIKAN</h4>
                 <h3 class="mb-0 fw-bold">SMAN 2 SUKATANI</h3>
                 <p class="mb-0 small">Jl. Raya Sukatani No. 123, Kabupaten Bekasi, Jawa Barat</p>
                 <p class="mb-0 small">Email: info@sman2sukatani.sch.id | Website: www.sman2sukatani.sch.id</p>
            </div>
        </div>

        <h5 class="text-center fw-bold mb-4"><?php echo $title; ?></h5>

        <?php if ($type == 'siswa'): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Program Ekskul</th>
                        <th>Tgl Gabung</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="6" class="text-center">Tidak ada data peserta.</td></tr>
                    <?php else: ?>
                        <?php $no=1; foreach($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row->nisn); ?></td>
                            <td><?php echo htmlspecialchars($row->nama); ?></td>
                            <td><?php echo htmlspecialchars($row->kelas); ?></td>
                            <td><?php echo htmlspecialchars($row->nama_eskul); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row->tanggal_daftar)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php elseif ($type == 'prestasi'): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Prestasi</th>
                        <th>Ekskul / Penyelenggara</th>
                        <th>Tahun</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="5" class="text-center">Tidak ada data prestasi.</td></tr>
                    <?php else: ?>
                        <?php $no=1; foreach($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row->nama_prestasi); ?></td>
                            <td><?php echo htmlspecialchars($row->nama_eskul ?? 'Sekolah'); ?></td>
                            <td><?php echo htmlspecialchars($row->tahun); ?></td>
                            <td><?php echo htmlspecialchars($row->deskripsi); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php elseif ($type == 'eskul'): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Ekskul</th>
                        <th>Pembina</th>
                        <th>Jadwal</th>
                        <th>Lokasi</th>
                        <th>Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($data as $row): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row->nama_eskul); ?></td>
                        <td><?php echo htmlspecialchars($row->pembina); ?></td>
                        <td><?php echo htmlspecialchars($row->jadwal); ?></td>
                        <td><?php echo htmlspecialchars($row->lokasi); ?></td>
                        <td><?php echo $row->kuota; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($type == 'absensi'): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Ekskul</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data)): ?>
                        <tr><td colspan="7" class="text-center">Tidak ada data absensi untuk periode ini.</td></tr>
                    <?php else: ?>
                        <?php $no=1; foreach($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row->tanggal)); ?></td>
                            <td><?php echo htmlspecialchars($row->nama); ?></td>
                            <td><?php echo htmlspecialchars($row->kelas); ?></td>
                            <td><?php echo htmlspecialchars($row->nama_eskul); ?></td>
                            <td class="text-capitalize"><?php echo $row->status; ?></td>
                            <td><?php echo htmlspecialchars($row->keterangan ?? '-'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php elseif ($type == 'rekap_absensi'): ?>
            <div class="mb-3 text-dark">
                <strong>Target Pertemuan:</strong> <?php echo $target_pertemuan; ?> Sesi<br>
                <strong>Diliburkan:</strong> <?php echo $jumlah_libur; ?> Sesi<br>
                <strong>Pertemuan Efektif:</strong> <?php echo $efektif_pertemuan; ?> Sesi
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th class="text-center" width="8%">Hadir</th>
                        <th class="text-center" width="8%">Sakit</th>
                        <th class="text-center" width="8%">Izin</th>
                        <th class="text-center" width="8%">Alpa</th>
                        <th class="text-center" width="15%">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="9" class="text-center">Tidak ada data keanggotaan aktif.</td></tr>
                    <?php else: ?>
                        <?php $no=1; foreach($data as $row): 
                            // Calculate percentage
                            if ($efektif_pertemuan > 0) {
                                $persen = round(($row->jumlah_hadir / $efektif_pertemuan) * 100, 1) . "%";
                            } else {
                                $persen = "100%";
                            }
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row->nisn); ?></td>
                            <td><?php echo htmlspecialchars($row->nama); ?></td>
                            <td><?php echo htmlspecialchars($row->kelas); ?></td>
                            <td class="text-center"><?php echo $row->jumlah_hadir; ?></td>
                            <td class="text-center"><?php echo $row->jumlah_sakit; ?></td>
                            <td class="text-center"><?php echo $row->jumlah_izin; ?></td>
                            <td class="text-center"><?php echo $row->jumlah_alpa; ?></td>
                            <td class="text-center fw-bold"><?php echo $persen; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center text-dark">
                <p class="mb-0">Bekasi, <?php 
                    $bulan_indo = [
                        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 
                        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 
                        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 
                        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                    ];
                    echo strtr(date('d F Y'), $bulan_indo); 
                ?></p>
                <p>Kepala Sekolah,</p>
                <div style="height: 80px;"></div>
                <p class="fw-bold mb-0">Drs. H. Ahmad Fauzi, M.Pd</p>
                <p>NIP. 19700101 199501 1 001</p>
            </div>
        </div>
    </div>
</body>
</html>
