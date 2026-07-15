<?php require_once '../includes/auth_check.php';
check_auth('admin');

// Only allow Admin Master to access this page
if (!isset($_SESSION['admin_data']) || $_SESSION['admin_data']['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

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
                <div class="card-header bg-white border-0 p-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="fw-bold mb-0 text-dark">Audit Trail</h5>
                    <div class="position-relative" style="width: 250px;">
                        <input type="text" id="searchInput" class="form-control rounded-pill ps-4 pe-5" placeholder="Cari aktivitas..." style="font-size: 0.85rem; border: 1px solid #ced4da;">
                        <i class="fas fa-search position-absolute text-muted" style="right: 18px; top: 50%; transform: translateY(-50%);"></i>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
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

    // Real-time search filter
    document.getElementById("searchInput").addEventListener("input", function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll("tbody tr:not(.no-result-row)");
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(query)) {
                row.style.setProperty("display", "", "important");
                visibleCount++;
            } else {
                row.style.setProperty("display", "none", "important");
            }
        });

        // Handle no results display
        let noResultRow = document.querySelector(".no-result-row");
        if (visibleCount === 0 && query !== "") {
            if (!noResultRow) {
                const tbody = document.querySelector("tbody");
                noResultRow = document.createElement("tr");
                noResultRow.className = "no-result-row";
                noResultRow.innerHTML = `<td colspan="6" class="text-center py-5 text-muted">Tidak ditemukan hasil pencarian untuk "${this.value}"</td>`;
                tbody.appendChild(noResultRow);
            } else {
                noResultRow.style.setProperty("display", "", "important");
                noResultRow.querySelector("td").textContent = `Tidak ditemukan hasil pencarian untuk "${this.value}"`;
            }
        } else if (noResultRow) {
            noResultRow.style.setProperty("display", "none", "important");
        }
    });
</script>

<?php include '../includes/footer.php'; ?>
