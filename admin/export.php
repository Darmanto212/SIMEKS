<?php require_once '../includes/auth_check.php';
check_auth('admin');
include '../config/koneksi.php';

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$pembina_eskul_id = null;
if ($is_pembina) {
    $pembina_eskul_id = get_pembina_eskul_id($koneksi, $_SESSION['pembina_data']['user_id']);
}

// Cek parameter
$format = isset($_GET['format']) ? $_GET['format'] : 'excel';
$type = isset($_GET['type']) ? $_GET['type'] : 'siswa';
$eskul_id = isset($_GET['id']) ? $_GET['id'] : '';

// Security enforcement for Pembina
if ($is_pembina) {
    if ($type == 'eskul') {
        header("Location: laporan.php");
        exit();
    }
    if ($type == 'siswa') {
        $eskul_id = $pembina_eskul_id;
    }
}

// Judul file & Query logic
$filename = "Laporan_" . ucfirst($type) . "_" . date('Ymd_His');
$title_text = "";
$headers = [];
$data = [];

if ($type == 'siswa') {
    $title_text = "DAFTAR SISWA PER EKSTRAKURIKULER";
    $headers = ['No', 'Nama Siswa', 'NISN', 'Kelas', 'Ekstrakurikuler', 'Tanggal Daftar'];
    
    if ($is_pembina && empty($pembina_eskul_id)) {
        $raw_data = [];
    } else {
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
    }
    
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
    
    if ($is_admin) {
        $query = "SELECT p.*, u.nama, e.nama_eskul 
                  FROM prestasi p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN eskul e ON p.eskul_id = e.id
                  ORDER BY p.tahun DESC";
        $stmt = $koneksi->query($query);
        $raw_data = $stmt->fetchAll();
    } else {
        if ($pembina_eskul_id) {
            $query = "SELECT p.*, u.nama, e.nama_eskul 
                      FROM prestasi p
                      JOIN users u ON p.user_id = u.id
                      LEFT JOIN eskul e ON p.eskul_id = e.id
                      WHERE p.eskul_id = :eskul_id
                      ORDER BY p.tahun DESC";
            $stmt = $koneksi->prepare($query);
            $stmt->bindParam(':eskul_id', $pembina_eskul_id);
            $stmt->execute();
            $raw_data = $stmt->fetchAll();
        } else {
            $raw_data = [];
        }
    }
    
    $no = 1;
    foreach ($raw_data as $row) {
        $data[] = [
            $no++,
            $row->nama,
            $row->nama_eskul ?: 'Umum / Semua',
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
} elseif ($type == 'rekap_absensi') {
    // Security check for Pembina
    if ($is_pembina) {
        $eskul_id = $pembina_eskul_id;
    }
    
    if (empty($eskul_id)) {
        header("Location: laporan.php");
        exit();
    }
    
    // Get eskul name and target
    $stmt_eskul = $koneksi->prepare("SELECT nama_eskul, target_pertemuan FROM eskul WHERE id = ?");
    $stmt_eskul->execute([$eskul_id]);
    $eskul_row = $stmt_eskul->fetch();
    $eskul_name = $eskul_row->nama_eskul ?? "";
    $target_pertemuan = $eskul_row->target_pertemuan ?? 16;
    
    // Count days marked as libur for this eskul
    $stmt_libur = $koneksi->prepare("SELECT COUNT(*) FROM eskul_libur WHERE eskul_id = ?");
    $stmt_libur->execute([$eskul_id]);
    $jumlah_libur = $stmt_libur->fetchColumn() ?: 0;
    
    $efektif_pertemuan = $target_pertemuan - $jumlah_libur;
    if ($efektif_pertemuan < 0) $efektif_pertemuan = 0;
    
    $title_text = "REKAPITULASI KEHADIRAN SISWA - " . strtoupper($eskul_name) . " (Target: " . $target_pertemuan . " Sesi, Libur: " . $jumlah_libur . " Sesi, Efektif: " . $efektif_pertemuan . " Sesi)";
    
    $headers = ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Hadir (H)', 'Sakit (S)', 'Izin (I)', 'Alpa (A)', 'Persentase Kehadiran'];
    
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
    $stmt->execute([$eskul_id]);
    $raw_data = $stmt->fetchAll();
    
    $no = 1;
    foreach ($raw_data as $row) {
        if ($efektif_pertemuan > 0) {
            $persen = round(($row->jumlah_hadir / $efektif_pertemuan) * 100, 1) . "%";
        } else {
            $persen = "100%";
        }
        $data[] = [
            $no++,
            "'" . $row->nisn,
            $row->nama,
            $row->kelas,
            $row->jumlah_hadir,
            $row->jumlah_sakit,
            $row->jumlah_izin,
            $row->jumlah_alpa,
            $persen
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
