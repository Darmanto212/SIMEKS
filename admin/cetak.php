<?php 
session_start();
require_once '../includes/auth_check.php';
check_auth('admin');

include '../config/koneksi.php';

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';

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
    $data = $koneksi->query("SELECT p.*, e.nama_eskul FROM prestasi p LEFT JOIN eskul e ON p.eskul_id = e.id ORDER BY p.tahun DESC")->fetchAll();
    $title = "LAPORAN REKAPITULASI PRESTASI SISWA";
} elseif ($type == 'eskul') {
    $data = $koneksi->query("SELECT * FROM eskul ORDER BY nama_eskul")->fetchAll();
    $title = "DAFTAR EKSTRAKURIKULER AKTIF";
} elseif ($type == 'absensi') {
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');
    $eskul_id = $_GET['eskul_id'] ?? 'all';
    
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
                    <?php $no=1; foreach($data as $row): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row->nisn; ?></td>
                        <td><?php echo htmlspecialchars($row->nama); ?></td>
                        <td><?php echo $row->kelas; ?></td>
                        <td><?php echo htmlspecialchars($row->nama_eskul); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row->tanggal_daftar)); ?></td>
                    </tr>
                    <?php endforeach; ?>
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
                    <?php $no=1; foreach($data as $row): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row->nama_prestasi); ?></td>
                        <td><?php echo htmlspecialchars($row->nama_eskul ?? 'Sekolah'); ?></td>
                        <td><?php echo $row->tahun; ?></td>
                        <td><?php echo htmlspecialchars($row->deskripsi); ?></td>
                    </tr>
                    <?php endforeach; ?>
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
                            <td><?php echo $row->kelas; ?></td>
                            <td><?php echo htmlspecialchars($row->nama_eskul); ?></td>
                            <td class="text-capitalize"><?php echo $row->status; ?></td>
                            <td><?php echo htmlspecialchars($row->keterangan ?? '-'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="mb-0">Bekasi, <?php echo date('d F Y'); ?></p>
                <p>Kepala Sekolah,</p>
                <div style="height: 80px;"></div>
                <p class="fw-bold mb-0">Drs. H. Ahmad Fauzi, M.Pd</p>
                <p>NIP. 19700101 199501 1 001</p>
            </div>
        </div>
    </div>
</body>
</html>
