<?php require_once '../includes/auth_check.php';
check_auth('admin');
$pageTitle = "Kelola Pendaftaran - SIMEKS";
include '../config/koneksi.php';

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$pembina_eskul_id = null;
if ($is_pembina) {
    $pembina_eskul_id = get_pembina_eskul_id($koneksi, $_SESSION['pembina_data']['user_id']);
}

// Handle Approval/Rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status']; 
    $catatan = $_POST['catatan'] ?? '';
    
    if (in_array($status, ['diterima', 'ditolak'])) {
        // Security check for Pembina
        $can_process = false;
        if ($is_admin) {
            $can_process = true;
        } elseif ($is_pembina && $pembina_eskul_id) {
            $stmt_chk = $koneksi->prepare("SELECT id FROM pendaftaran WHERE id = ? AND eskul_id = ?");
            $stmt_chk->execute([$id, $pembina_eskul_id]);
            if ($stmt_chk->fetch()) {
                $can_process = true;
            }
        }

        if ($can_process) {
            $stmt_reg = $koneksi->prepare("SELECT p.*, e.nama_eskul FROM pendaftaran p JOIN eskul e ON p.eskul_id = e.id WHERE p.id = ?");
            $stmt_reg->execute([$id]);
            $reg = $stmt_reg->fetch();

            if ($reg) {
                $stmt = $koneksi->prepare("UPDATE pendaftaran SET status = ?, catatan = ? WHERE id = ?");
                $stmt->execute([$status, $catatan, $id]);
                
                $notif_title = ($status == 'diterima') ? "Pendaftaran Diterima! ✅" : "Pendaftaran Ditolak ❌";
                $notif_msg = ($status == 'diterima') 
                    ? "Selamat! Pendaftaran kamu di ekskul " . $reg->nama_eskul . " telah disetujui." 
                    : "Maaf, pendaftaran kamu di ekskul " . $reg->nama_eskul . " belum dapat diterima. Alasan: " . ($catatan ?: 'Tidak ada keterangan.');
                
                send_notification($koneksi, $reg->user_id, $notif_title, $notif_msg, $status == 'diterima' ? 'success' : 'danger');
                
                $msg = "Pendaftaran berhasil diproses!";
                $type = "success";
                log_activity($koneksi, 'Proses Pendaftaran', "Proses pendaftaran ID $id menjadi $status oleh " . ($is_admin ? 'Admin' : 'Pembina'), 'INFO');
            }
        }
    }
}

// Fetch Pending & Latest Registrations based on role
if ($is_admin) {
    $pendaftaran = $koneksi->query("
        SELECT p.*, u.nama, u.kelas, e.nama_eskul 
        FROM pendaftaran p
        JOIN users u ON p.user_id = u.id
        JOIN eskul e ON p.eskul_id = e.id
        ORDER BY p.status = 'menunggu' DESC, p.tanggal_daftar DESC
    ")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_p = $koneksi->prepare("
            SELECT p.*, u.nama, u.kelas, e.nama_eskul 
            FROM pendaftaran p
            JOIN users u ON p.user_id = u.id
            JOIN eskul e ON p.eskul_id = e.id
            WHERE p.eskul_id = ?
            ORDER BY p.status = 'menunggu' DESC, p.tanggal_daftar DESC
        ");
        $stmt_p->execute([$pembina_eskul_id]);
        $pendaftaran = $stmt_p->fetchAll();
    } else {
        $pendaftaran = [];
    }
}

include '../includes/header.php'; 
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = $is_admin ? "Kelola Pendaftaran Siswa" : "Verifikasi Pendaftaran Ekskul Binaan";
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <?php if (isset($msg)): ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 text-dark" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Ekstrakurikuler</th>
                                <th>Tanggal Daftar</th>
                                <th>Status saat ini</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pendaftaran)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pendaftaran.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pendaftaran as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row->nama); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($row->kelas); ?></div>
                                        </td>
                                        <td><span class="badge bg-maroon-light px-3 rounded-pill"><?php echo htmlspecialchars($row->nama_eskul); ?></span></td>
                                        <td class="text-dark"><?php echo date('d M Y', strtotime($row->tanggal_daftar)); ?></td>
                                        <td>
                                            <?php 
                                                $badge = 'bg-warning-subtle text-warning';
                                                if ($row->status == 'diterima') $badge = 'bg-success-subtle text-success';
                                                if ($row->status == 'ditolak') $badge = 'bg-danger-subtle text-danger';
                                            ?>
                                            <span class="badge <?php echo $badge; ?> px-3 rounded-pill text-capitalize"><?php echo $row->status; ?></span>
                                        </td>
                                        <td class="text-center">
                                             <?php if ($row->status == 'menunggu'): ?>
                                                 <div class="d-flex justify-content-center gap-1">
                                                     <form method="POST" style="display:inline;">
                                                         <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                         <input type="hidden" name="status" value="diterima">
                                                         <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" onclick="return confirm('Terima pendaftaran ini?')">
                                                             <i class="fas fa-check me-1"></i> Terima
                                                         </button>
                                                     </form>
                                                     
                                                     <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                                             data-bs-toggle="modal" 
                                                             data-bs-target="#rejectModal<?php echo $row->id; ?>">
                                                         <i class="fas fa-times me-1"></i> Tolak
                                                     </button>
                                                 </div>
 
                                                 <!-- Reject Modal -->
                                                 <div class="modal fade" id="rejectModal<?php echo $row->id; ?>" tabindex="-1" aria-hidden="true">
                                                     <div class="modal-dialog modal-dialog-centered">
                                                         <div class="modal-content rounded-4 border-0 shadow text-dark text-start">
                                                             <form method="POST">
                                                                 <div class="modal-header border-0 pb-0">
                                                                     <h5 class="modal-title fw-bold">Alasan Penolakan</h5>
                                                                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                 </div>
                                                                 <div class="modal-body">
                                                                     <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                                     <input type="hidden" name="status" value="ditolak">
                                                                     <div class="mb-3">
                                                                         <label class="form-label small fw-bold">Berikan alasan pendaftaran ditolak:</label>
                                                                         <textarea name="catatan" class="form-control rounded-3" rows="3" placeholder="Contoh: Kuota penuh, Persyaratan tidak lengkap, dll." required></textarea>
                                                                     </div>
                                                                 </div>
                                                                 <div class="modal-footer border-0 pt-0">
                                                                     <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                                     <button type="submit" class="btn btn-danger rounded-pill px-4">Kirim Penolakan</button>
                                                                 </div>
                                                             </form>
                                                         </div>
                                                     </div>
                                                 </div>
                                             <?php else: ?>
                                                 <div class="d-flex flex-column align-items-center text-dark">
                                                     <span class="text-muted small fst-italic">Diproses pada:</span>
                                                     <span class="small fw-bold"><?php echo $row->status == 'diterima' ? 'Diterima ✅' : 'Ditolak ❌'; ?></span>
                                                     <?php if($row->catatan): ?>
                                                        <span class="extra-small text-muted text-center" style="max-width: 150px;">"<?php echo htmlspecialchars($row->catatan); ?>"</span>
                                                     <?php endif; ?>
                                                 </div>
                                             <?php endif; ?>
                                         </td>
                                     </tr>
                                 <?php endforeach; ?>
                             <?php endif; ?>
                         </tbody>
                     </table>
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
 </script>
 
 <?php include '../includes/footer.php'; ?>
