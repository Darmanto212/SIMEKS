<?php 
session_start();
require_once '../includes/auth_check.php';
check_auth('admin');

$pageTitle = "Log Aktivitas Sistem - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

// Fetch all logs
$logs = $koneksi->query("
    SELECT l.*, u.nama as nama_user 
    FROM logs l 
    LEFT JOIN users u ON l.user_id = u.id 
    ORDER BY l.tanggal DESC
")->fetchAll();

?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Audit Trail / Log Aktivitas";
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0">Waktu</th>
                                <th class="py-3 border-0">Tipe</th>
                                <th class="py-3 border-0">Pengguna</th>
                                <th class="py-3 border-0">Aktivitas</th>
                                <th class="py-3 border-0">Keterangan</th>
                                <th class="py-3 border-0">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada catatan aktivitas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $row): ?>
                                    <tr>
                                        <td class="px-4 small text-muted"><?php echo date('d M Y, H:i:s', strtotime($row->tanggal)); ?></td>
                                        <td>
                                            <?php 
                                            $badge = 'bg-info';
                                            if ($row->tipe == 'SUKSES') $badge = 'bg-success';
                                            elseif ($row->tipe == 'PERINGATAN') $badge = 'bg-warning text-dark';
                                            elseif ($row->tipe == 'BAHAYA') $badge = 'bg-danger';
                                            ?>
                                            <span class="badge <?php echo $badge; ?> rounded-pill px-3"><?php echo $row->tipe; ?></span>
                                        </td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($row->nama_user ?? 'Sistem/Guest'); ?></td>
                                        <td><?php echo htmlspecialchars($row->aktivitas); ?></td>
                                        <td class="small text-muted"><?php echo htmlspecialchars($row->keterangan); ?></td>
                                        <td class="extra-small text-muted font-monospace"><?php echo $row->ip_address; ?></td>
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
