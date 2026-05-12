<?php
session_start();
require_once '../includes/auth_check.php';
check_auth('admin');
include '../config/koneksi.php';

// Cek parameter
$format = isset($_GET['format']) ? $_GET['format'] : 'excel';
$type = isset($_GET['type']) ? $_GET['type'] : 'siswa';
$eskul_id = isset($_GET['id']) ? $_GET['id'] : '';

// Judul file & Query logic
$filename = "Laporan_" . ucfirst($type) . "_" . date('Ymd_His');
$title_text = "";
$headers = [];
$data = [];

if ($type == 'siswa') {
    $title_text = "DAFTAR SISWA PER EKSTRAKURIKULER";
    $headers = ['No', 'Nama Siswa', 'NISN', 'Kelas', 'Ekstrakurikuler', 'Tanggal Daftar'];
    
    $query = "SELECT p.*, u.nama, u.kelas, u.nisn, e.nama_eskul 
              FROM pendaftaran p
              JOIN users u ON p.user_id = u.id
              JOIN eskul e ON p.eskul_id = e.id
              WHERE p.status = 'diterima'";
    
    if (!empty($eskul_id) && $eskul_id != 'all') {
        $query .= " AND p.eskul_id = :id";
    }
    $query .= " ORDER BY e.nama_eskul ASC, u.nama ASC";
    
    $stmt = $koneksi->prepare($query);
    if (!empty($eskul_id) && $eskul_id != 'all') {
        $stmt->bindParam(':id', $eskul_id);
    }
    $stmt->execute();
    $raw_data = $stmt->fetchAll();
    
    $no = 1;
    foreach ($raw_data as $row) {
        $data[] = [
            $no++,
            $row->nama,
            "'" . $row->nisn,
            $row->kelas,
            $row->nama_eskul,
            date('d/m/Y', strtotime($row->tanggal_daftar))
        ];
    }
} elseif ($type == 'prestasi') {
    $title_text = "REKAPITULASI PRESTASI SISWA";
    $headers = ['No', 'Nama Siswa', 'Ekskul', 'Prestasi / Penghargaan', 'Tingkat', 'Tahun'];
    
    // Asumsi tabel prestasi (sesuaikan jika berbeda)
    $query = "SELECT p.*, u.nama, e.nama_eskul 
              FROM prestasi p
              JOIN users u ON p.user_id = u.id
              JOIN eskul e ON p.eskul_id = e.id
              ORDER BY p.tahun DESC";
    $stmt = $koneksi->query($query);
    $raw_data = $stmt->fetchAll();
    
    $no = 1;
    foreach ($raw_data as $row) {
        $data[] = [
            $no++,
            $row->nama,
            $row->nama_eskul,
            $row->nama_prestasi,
            $row->tingkat,
            $row->tahun
        ];
    }
} elseif ($type == 'eskul') {
    $title_text = "DAFTAR EKSTRAKURIKULER AKTIF";
    $headers = ['No', 'Nama Ekskul', 'Pembina', 'Jadwal', 'Lokasi', 'Kuota'];
    
    $query = "SELECT * FROM eskul WHERE status = 'aktif' ORDER BY nama_eskul ASC";
    $stmt = $koneksi->query($query);
    $raw_data = $stmt->fetchAll();
    
    $no = 1;
    foreach ($raw_data as $row) {
        $data[] = [
            $no++,
            $row->nama_eskul,
            $row->pembina,
            $row->jadwal,
            $row->lokasi,
            $row->kuota
        ];
    }
}

// Header untuk format file
if ($format == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename.xls\"");
} else {
    header("Content-Type: application/vnd.ms-word");
    header("Content-Disposition: attachment; filename=\"$filename.doc\"");
}

// Log aktivitas
log_activity($koneksi, 'Export Laporan', "Export $type dalam format $format", 'INFO');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .title { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 20px; }
        .table { border-collapse: collapse; width: 100%; border: 1px solid #000; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="title">
        <?php echo $title_text; ?><br>
        SMAN 2 SUKATANI<br>
        Tanggal Export: <?php echo date('d/m/Y H:i'); ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <?php foreach ($headers as $h): ?>
                    <th><?php echo $h; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="<?php echo count($headers); ?>" style="text-align: center;">Tidak ada data ditemukan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td><?php echo htmlspecialchars($cell); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <div style="text-align: right; margin-top: 30px;">
        Dicetak otomatis oleh Sistem SIMEKS pada <?php echo date('d/m/Y'); ?>
    </div>
</body>
</html>
