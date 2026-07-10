<?php require_once '../includes/auth_check.php';
check_auth('siswa');
include '../config/koneksi.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$user_id = $_SESSION['siswa_data']['user_id'];

// Fetch Detail Prestasi
$stmt = $koneksi->prepare("
    SELECT p.*, e.nama_eskul, u.nama, u.nisn, u.kelas
    FROM prestasi p
    JOIN eskul e ON p.eskul_id = e.id
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ? AND p.user_id = ?
");
$stmt->execute([$id, $user_id]);
$data = $stmt->fetch();

if (!$data) {
    die("Data prestasi tidak ditemukan atau Anda tidak memiliki akses.");
}

// Log activity
log_activity($koneksi, 'Cetak Sertifikat', "Cetak sertifikat prestasi ID $id", 'INFO');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat_<?php echo str_replace(' ', '_', $data->nama_prestasi); ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; background-color: #f0f0f0; margin: 0; padding: 20px; text-align: center; }
        .certificate-container { 
            background-color: #fff; 
            width: 800px; 
            height: 600px; 
            margin: 0 auto; 
            padding: 50px; 
            border: 20px solid #800000; 
            outline: 5px solid #d4af37;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            position: relative;
        }
        .header { margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #800000; letter-spacing: 2px; }
        .school-name { font-size: 20px; margin-top: 5px; color: #333; }
        .certificate-title { font-size: 48px; font-weight: bold; color: #d4af37; margin: 20px 0; text-transform: uppercase; }
        .sub-title { font-size: 22px; color: #555; margin-bottom: 30px; }
        .recipient-name { font-size: 32px; font-weight: bold; border-bottom: 2px solid #333; display: inline-block; padding: 0 40px; margin-bottom: 10px; }
        .recipient-info { font-size: 18px; color: #666; margin-bottom: 30px; }
        .achievement-text { font-size: 20px; line-height: 1.6; color: #444; padding: 0 50px; }
        .footer-sign { margin-top: 50px; display: flex; justify-content: space-between; padding: 0 100px; }
        .signature { text-align: center; }
        .sign-line { border-top: 1px solid #333; width: 200px; margin-top: 60px; }
        .no-sertif { position: absolute; bottom: 30px; left: 50px; font-size: 12px; color: #999; }
        
        @media print {
            body { background: none; padding: 0; }
            .certificate-container { box-shadow: none; margin: 0; border-width: 15px; }
            .btn-print { display: none; }
        }
        .btn-print {
            background-color: #800000; color: #fff; border: none; padding: 10px 30px; font-size: 16px; border-radius: 50px; cursor: pointer; margin-bottom: 20px; transition: 0.3s;
        }
        .btn-print:hover { background-color: #a00000; transform: translateY(-2px); }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn-print">Cetak Sertifikat</button>

    <div class="certificate-container">
        <div class="header">
            <div class="logo">SIMEKS</div>
            <div class="school-name">SMAN 2 SUKATANI</div>
        </div>

        <div class="certificate-title">Sertifikat</div>
        <div class="sub-title">Penghargaan Diberikan Kepada</div>

        <div class="recipient-name"><?php echo htmlspecialchars($data->nama); ?></div>
        <div class="recipient-info">NISN: <?php echo htmlspecialchars($data->nisn); ?> | Kelas: <?php echo htmlspecialchars($data->kelas); ?></div>

        <div class="achievement-text">
            Atas prestasi gemilang sebagai juara pada tingkat <strong><?php echo htmlspecialchars($data->tingkat); ?></strong> dalam kegiatan:<br>
            <strong style="font-size: 24px; color: #800000;"><?php echo htmlspecialchars($data->nama_prestasi); ?></strong><br>
            Bidang Ekstrakurikuler <?php echo htmlspecialchars($data->nama_eskul); ?> tahun <?php echo htmlspecialchars($data->tahun); ?>.
        </div>

        <div class="footer-sign">
            <div class="signature">
                <p>Pembina Ekstrakurikuler</p>
                <div class="sign-line"></div>
            </div>
            <div class="signature">
                <p>Kepala Sekolah</p>
                <div class="sign-line"></div>
            </div>
        </div>

        <div class="no-sertif">ID Sertifikat: SMEKS/<?php echo $data->tahun; ?>/<?php echo str_pad($data->id, 5, '0', STR_PAD_LEFT); ?></div>
    </div>
</body>
</html>
