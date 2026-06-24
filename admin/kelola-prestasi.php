<?php
require_once '../includes/auth_check.php';
check_auth('admin');
$pageTitle = "Kelola Prestasi - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

$is_admin = (isset($_SESSION['admin_data']) && $_SESSION['admin_data']['role'] === 'admin');
$is_pembina = (isset($_SESSION['pembina_data']) && $_SESSION['pembina_data']['role'] === 'pembina');

$pembina_eskul_id = null;
if ($is_pembina) {
    $pembina_eskul_id = get_pembina_eskul_id($koneksi, $_SESSION['pembina_data']['user_id']);
}

// Handle CRUD logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $user_id = $_POST['user_id'];
            $eskul_id = $is_admin ? $_POST['eskul_id'] : $pembina_eskul_id;
            $nama_prestasi = $_POST['nama_prestasi'];
            $tahun = $_POST['tahun'];
            $deskripsi = $_POST['deskripsi'];

            $stmt = $koneksi->prepare("INSERT INTO prestasi (user_id, eskul_id, nama_prestasi, tahun, deskripsi) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $eskul_id ?: null, $nama_prestasi, $tahun, $deskripsi]);
            log_activity($koneksi, 'Tambah Prestasi', "Nama: $nama_prestasi, Siswa ID: $user_id", 'SUKSES');

        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'];
            $user_id = $_POST['user_id'];
            $eskul_id = $is_admin ? $_POST['eskul_id'] : $pembina_eskul_id;
            $nama_prestasi = $_POST['nama_prestasi'];
            $tahun = $_POST['tahun'];
            $deskripsi = $_POST['deskripsi'];

            // Security check for Pembina
            $can_edit = false;
            if ($is_admin) {
                $can_edit = true;
            } elseif ($is_pembina && $pembina_eskul_id) {
                $stmt_chk = $koneksi->prepare("SELECT id FROM prestasi WHERE id = ? AND eskul_id = ?");
                $stmt_chk->execute([$id, $pembina_eskul_id]);
                if ($stmt_chk->fetch()) {
                    $can_edit = true;
                }
            }

            if ($can_edit) {
                $stmt = $koneksi->prepare("UPDATE prestasi SET user_id=?, eskul_id=?, nama_prestasi=?, tahun=?, deskripsi=? WHERE id=?");
                $stmt->execute([$user_id, $eskul_id ?: null, $nama_prestasi, $tahun, $deskripsi, $id]);
                log_activity($koneksi, 'Update Prestasi', "ID: $id", 'INFO');
            }

        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];

            // Security check for Pembina
            $can_delete = false;
            if ($is_admin) {
                $can_delete = true;
            } elseif ($is_pembina && $pembina_eskul_id) {
                $stmt_chk = $koneksi->prepare("SELECT id FROM prestasi WHERE id = ? AND eskul_id = ?");
                $stmt_chk->execute([$id, $pembina_eskul_id]);
                if ($stmt_chk->fetch()) {
                    $can_delete = true;
                }
            }

            if ($can_delete) {
                $stmt = $koneksi->prepare("DELETE FROM prestasi WHERE id = ?");
                $stmt->execute([$id]);
                log_activity($koneksi, 'Hapus Prestasi', "ID: $id", 'BAHAYA');
            }
        }
        header("Location: kelola-prestasi.php");
        exit();
    }
}

// Fetch achievements based on role
if ($is_admin) {
    $prestasi_list = $koneksi->query("
        SELECT p.*, e.nama_eskul, u.nama as nama_siswa 
        FROM prestasi p 
        LEFT JOIN eskul e ON p.eskul_id = e.id 
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.tahun DESC, p.id DESC
    ")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_list = $koneksi->prepare("
            SELECT p.*, e.nama_eskul, u.nama as nama_siswa 
            FROM prestasi p 
            LEFT JOIN eskul e ON p.eskul_id = e.id 
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.eskul_id = ?
            ORDER BY p.tahun DESC, p.id DESC
        ");
        $stmt_list->execute([$pembina_eskul_id]);
        $prestasi_list = $stmt_list->fetchAll();
    } else {
        $prestasi_list = [];
    }
}

// Fetch active eskul list for dropdown based on role
if ($is_admin) {
    $eskul_list = $koneksi->query("SELECT id, nama_eskul FROM eskul ORDER BY nama_eskul ASC")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_e = $koneksi->prepare("SELECT id, nama_eskul FROM eskul WHERE id = ?");
        $stmt_e->execute([$pembina_eskul_id]);
        $eskul_list = $stmt_e->fetchAll();
    } else {
        $eskul_list = [];
    }
}

// Fetch student list for dropdown based on role
if ($is_admin) {
    $student_list = $koneksi->query("SELECT id, nama, kelas FROM users WHERE role = 'siswa' ORDER BY nama ASC")->fetchAll();
} else {
    if ($pembina_eskul_id) {
        $stmt_s = $koneksi->prepare("
            SELECT u.id, u.nama, u.kelas 
            FROM pendaftaran p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.eskul_id = ? AND p.status = 'diterima' AND u.role = 'siswa'
            ORDER BY u.nama ASC
        ");
        $stmt_s->execute([$pembina_eskul_id]);
        $student_list = $stmt_s->fetchAll();
    } else {
        $student_list = [];
    }
}
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = $is_admin ? "Kelola Prestasi Sekolah" : "Prestasi Ekskul Binaan";
        $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i> Tambah Prestasi
                        </button>';
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Prestasi</th>
                                <th>Siswa</th>
                                <th>Ekskul</th>
                                <th>Tahun</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($prestasi_list)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data prestasi.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($prestasi_list as $row): ?>
                                    <tr>
                                        <td class="fw-bold text-dark">
                                            <?php echo htmlspecialchars($row->nama_prestasi); ?>
                                            <div class="extra-small text-muted font-monospace"><?php echo htmlspecialchars($row->deskripsi); ?></div>
                                        </td>
                                        <td class="text-dark">
                                            <div class="fw-bold small"><?php echo htmlspecialchars($row->nama_siswa ?? 'Umum'); ?></div>
                                        </td>
                                        <td><span class="badge bg-maroon-light px-3 rounded-pill"><?php echo htmlspecialchars($row->nama_eskul ?? 'Umum'); ?></span></td>
                                        <td class="text-dark"><code><?php echo htmlspecialchars($row->tahun); ?></code></td>
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
                                            <div class="modal-content text-dark text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Prestasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Prestasi</label>
                                                            <input type="text" name="nama_prestasi" class="form-control" value="<?php echo htmlspecialchars($row->nama_prestasi); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Siswa Peraih</label>
                                                            <select name="user_id" class="form-select" required>
                                                                <option value="">-- Pilih Siswa --</option>
                                                                <?php foreach ($student_list as $s): ?>
                                                                    <option value="<?php echo $s->id; ?>" <?php echo ($s->id == $row->user_id) ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($s->nama . " (" . $s->kelas . ")"); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-8 mb-3">
                                                                <label class="form-label">Ekskul Terkait</label>
                                                                <?php if ($is_admin): ?>
                                                                    <select name="eskul_id" class="form-select">
                                                                        <option value="">Umum / Tidak Ada</option>
                                                                        <?php foreach ($eskul_list as $e): ?>
                                                                            <option value="<?php echo $e->id; ?>" <?php echo ($e->id == $row->eskul_id) ? 'selected' : ''; ?>>
                                                                                <?php echo htmlspecialchars($e->nama_eskul); ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                <?php else: ?>
                                                                    <select class="form-select" disabled>
                                                                        <?php foreach ($eskul_list as $e): ?>
                                                                            <option value="<?php echo $e->id; ?>" selected>
                                                                                <?php echo htmlspecialchars($e->nama_eskul); ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <input type="hidden" name="eskul_id" value="<?php echo $pembina_eskul_id; ?>">
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">Tahun</label>
                                                                <input type="number" name="tahun" class="form-control" value="<?php echo htmlspecialchars($row->tahun); ?>" min="2000" max="<?php echo date('Y'); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Deskripsi</label>
                                                            <textarea name="deskripsi" class="form-control" rows="3"><?php echo htmlspecialchars($row->deskripsi); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-maroon rounded-pill px-4">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $row->id; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-sm text-dark">
                                            <div class="modal-content">
                                                <div class="modal-body text-center py-4">
                                                    <i class="fas fa-trophy text-danger display-4 mb-3"></i>
                                                    <h5 class="fw-bold">Hapus Prestasi?</h5>
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
                            <?php endif; ?>
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
        <div class="modal-content text-dark text-start">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Prestasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Nama Prestasi</label>
                        <input type="text" name="nama_prestasi" class="form-control" placeholder="Contoh: Juara 1 Futsal Cup" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Siswa Peraih</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach ($student_list as $s): ?>
                                <option value="<?php echo $s->id; ?>"><?php echo htmlspecialchars($s->nama . " (" . $s->kelas . ")"); ?></option>
                            <?php endforeach; ?>
                            <?php if(empty($student_list)): ?>
                                <option value="" disabled>Belum ada anggota terdaftar</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Ekskul Terkait</label>
                            <?php if ($is_admin): ?>
                                <select name="eskul_id" class="form-select">
                                    <option value="">Umum / Tidak Ada</option>
                                    <?php foreach ($eskul_list as $e): ?>
                                        <option value="<?php echo $e->id; ?>"><?php echo htmlspecialchars($e->nama_eskul); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <select class="form-select" disabled>
                                    <?php foreach ($eskul_list as $e): ?>
                                        <option value="<?php echo $e->id; ?>" selected><?php echo htmlspecialchars($e->nama_eskul); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="eskul_id" value="<?php echo $pembina_eskul_id; ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="<?php echo date('Y'); ?>" min="2000" max="<?php echo date('Y'); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan singkat mengenai prestasi ini..."></textarea>
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
