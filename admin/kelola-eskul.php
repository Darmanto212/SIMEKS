<?php
require_once '../includes/auth_check.php';
check_auth('admin');

// Only allow Admin Master to access this page
if (!isset($_SESSION['admin_data']) || $_SESSION['admin_data']['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Kelola Ekskul - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $upload_dir = '../assets/uploads/eskul/';

        if ($_POST['action'] === 'add') {
            $gambar_name = 'default_eskul.png';
            
            // Handle Upload
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
                $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $newName = 'eskul_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $newName)) {
                    $gambar_name = $newName;
                }
            }

            // Fetch pembina name for compatibility
            $pembina_id = $_POST['pembina_id'] ?: null;
            $pembina_nama = "";
            if ($pembina_id) {
                $stmt_p = $koneksi->prepare("SELECT nama FROM users WHERE id = ?");
                $stmt_p->execute([$pembina_id]);
                $pembina_nama = $stmt_p->fetchColumn() ?: "";
            }

            $stmt = $koneksi->prepare("INSERT INTO eskul (nama_eskul, deskripsi, pembina, pembina_id, kuota, jadwal, lokasi, gambar, target_pertemuan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['nama_eskul'], $_POST['deskripsi'], $pembina_nama, $pembina_id, $_POST['kuota'], $_POST['jadwal'], $_POST['lokasi'], $gambar_name, $_POST['target_pertemuan']]);
            log_activity($koneksi, 'Tambah Ekskul', 'Nama: ' . $_POST['nama_eskul'], 'SUKSES');

        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'];
            $eskul = $koneksi->prepare("SELECT gambar FROM eskul WHERE id = ?");
            $eskul->execute([$id]);
            $current_eskul = $eskul->fetch();
            $gambar_name = $current_eskul->gambar;

            // Handle New Upload
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
                $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $newName = 'eskul_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $newName)) {
                    // Delete old image if not default
                    if ($gambar_name !== 'default_eskul.png' && file_exists($upload_dir . $gambar_name)) {
                        unlink($upload_dir . $gambar_name);
                    }
                    $gambar_name = $newName;
                }
            }

            // Fetch pembina name for compatibility
            $pembina_id = $_POST['pembina_id'] ?: null;
            $pembina_nama = "";
            if ($pembina_id) {
                $stmt_p = $koneksi->prepare("SELECT nama FROM users WHERE id = ?");
                $stmt_p->execute([$pembina_id]);
                $pembina_nama = $stmt_p->fetchColumn() ?: "";
            }

            $stmt = $koneksi->prepare("UPDATE eskul SET nama_eskul=?, deskripsi=?, pembina=?, pembina_id=?, kuota=?, jadwal=?, lokasi=?, gambar=?, target_pertemuan=? WHERE id=?");
            $stmt->execute([$_POST['nama_eskul'], $_POST['deskripsi'], $pembina_nama, $pembina_id, $_POST['kuota'], $_POST['jadwal'], $_POST['lokasi'], $gambar_name, $_POST['target_pertemuan'], $id]);
            log_activity($koneksi, 'Update Ekskul', 'ID: ' . $id, 'INFO');

        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            $eskul = $koneksi->prepare("SELECT gambar FROM eskul WHERE id = ?");
            $eskul->execute([$id]);
            $current_eskul = $eskul->fetch();
            
            // Delete image file if not default
            if ($current_eskul->gambar !== 'default_eskul.png' && file_exists($upload_dir . $current_eskul->gambar)) {
                unlink($upload_dir . $current_eskul->gambar);
            }

            $stmt = $koneksi->prepare("DELETE FROM eskul WHERE id = ?");
            $stmt->execute([$id]);
            log_activity($koneksi, 'Hapus Ekskul', 'ID: ' . $id, 'BAHAYA');
        }
        header("Location: kelola-eskul.php");
        exit();
    }
}

// Fetch all eskul joining users table to get real pembina name
$eskul_list = $koneksi->query("
    SELECT e.*, u.nama as nama_pembina 
    FROM eskul e 
    LEFT JOIN users u ON e.pembina_id = u.id 
    ORDER BY e.nama_eskul ASC
")->fetchAll();

// Fetch all pembinas for the select dropdowns
$pembina_users = $koneksi->query("SELECT id, nama FROM users WHERE role = 'pembina' ORDER BY nama ASC")->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Kelola Ekstrakurikuler";
        $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i> Tambah Ekskul Baru
                        </button>';
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Ekskul</th>
                                <th>Pembina</th>
                                <th>Kuota</th>
                                <th>Jadwal</th>
                                <th>Lokasi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eskul_list as $row): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="../assets/uploads/eskul/<?php echo $row->gambar; ?>" class="rounded-3 me-3" width="50" height="50" style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row->nama_eskul); ?></div>
                                                <div class="small text-muted text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($row->deskripsi); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($row->nama_pembina): ?>
                                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($row->nama_pembina); ?></span>
                                        <?php elseif ($row->pembina): ?>
                                            <span class="text-dark fst-italic text-muted"><?php echo htmlspecialchars($row->pembina); ?> (Legacy)</span>
                                        <?php else: ?>
                                            <span class="text-danger small fst-italic"><i class="fas fa-exclamation-circle"></i> Belum ada pembina</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge bg-secondary rounded-pill px-3"><?php echo $row->kuota; ?> Siswa</span></td>
                                    <td><?php echo htmlspecialchars($row->jadwal); ?></td>
                                    <td><?php echo htmlspecialchars($row->lokasi); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal<?php echo $row->id; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-circle" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?php echo $row->id; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?php echo $row->id; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Ekskul</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-body text-start">
                                                    <input type="hidden" name="action" value="edit">
                                                    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Ekskul</label>
                                                        <input type="text" name="nama_eskul" class="form-control" value="<?php echo htmlspecialchars($row->nama_eskul); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Pembina</label>
                                                        <select name="pembina_id" class="form-select" required>
                                                            <option value="">-- Pilih Akun Pembina --</option>
                                                            <?php foreach ($pembina_users as $p): ?>
                                                                <option value="<?php echo $p->id; ?>" <?php echo ($row->pembina_id == $p->id) ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($p->nama); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Jadwal</label>
                                                            <input type="text" name="jadwal" class="form-control" value="<?php echo htmlspecialchars($row->jadwal); ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kuota</label>
                                                            <input type="number" name="kuota" class="form-control" value="<?php echo $row->kuota; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Lokasi</label>
                                                            <input type="text" name="lokasi" class="form-control" value="<?php echo htmlspecialchars($row->lokasi); ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Target Pertemuan</label>
                                                            <input type="number" name="target_pertemuan" class="form-control" value="<?php echo isset($row->target_pertemuan) ? $row->target_pertemuan : 16; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Ganti Foto (Kosongkan jika tidak diubah)</label>
                                                        <input type="file" name="gambar" class="form-control" accept="image/*">
                                                        <small class="text-muted">Format: JPG, PNG, JPEG. Maks 2MB.</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi</label>
                                                        <textarea name="deskripsi" class="form-control" rows="3"><?php echo htmlspecialchars($row->deskripsi); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-maroon rounded-pill px-4">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal<?php echo $row->id; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-body text-center py-4">
                                                <i class="fas fa-exclamation-triangle text-warning display-4 mb-3"></i>
                                                <h5 class="fw-bold">Hapus Ekskul?</h5>
                                                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                                                <form method="POST">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-3">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Ekskul Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Nama Ekskul</label>
                        <input type="text" name="nama_eskul" class="form-control" placeholder="Contoh: Basket" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pembina</label>
                            <select name="pembina_id" class="form-select" required>
                                <option value="">-- Pilih Akun Pembina --</option>
                                <?php foreach ($pembina_users as $p): ?>
                                    <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->nama); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kuota</label>
                            <input type="number" name="kuota" class="form-control" value="30" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jadwal</label>
                        <input type="text" name="jadwal" class="form-control" placeholder="Selasa, 15:00" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Lapangan Basket" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target Pertemuan</label>
                            <input type="number" name="target_pertemuan" class="form-control" value="16" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Ekskul</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, JPEG. Maks 2MB.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan singkat mengenai ekskul ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon rounded-pill px-4">Tambahkan</button>
                </div>
            </form>
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
