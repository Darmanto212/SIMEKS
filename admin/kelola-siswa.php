<?php
require_once '../includes/auth_check.php';
check_auth('admin');
$pageTitle = "Kelola Siswa - SIMEKS";
include '../config/koneksi.php';
include '../includes/header.php'; 

// Handle CRUD logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("INSERT INTO users (nama, nisn, email, password, kelas, role) VALUES (?, ?, ?, ?, ?, 'siswa')");
            $stmt->execute([$_POST['nama'], $_POST['nisn'], $_POST['email'], $pass, $_POST['kelas']]);
        } elseif ($_POST['action'] === 'edit') {
            $stmt = $koneksi->prepare("UPDATE users SET nama=?, nisn=?, email=?, kelas=? WHERE id=?");
            $stmt->execute([$_POST['nama'], $_POST['nisn'], $_POST['email'], $_POST['kelas'], $_POST['id']]);
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
        header("Location: kelola-siswa.php");
        exit();
    }
}

// Fetch all students
$students = $koneksi->query("SELECT * FROM users WHERE role = 'siswa' ORDER BY nama ASC")->fetchAll();
?>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100 bg-light">
        <?php 
        $navTitle = "Kelola Data Siswa";
        $extraAction = '<button class="btn btn-maroon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i> Tambah Siswa
                        </button>';
        include '../includes/admin_nav.php'; 
        ?>

        <div class="container-fluid p-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Email</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data siswa.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row->nama); ?>&background=random" class="rounded-circle me-3" width="35">
                                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($row->nama); ?></span>
                                            </div>
                                        </td>
                                        <td><code><?php echo htmlspecialchars($row->nisn); ?></code></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row->kelas); ?></span></td>
                                        <td><?php echo htmlspecialchars($row->email); ?></td>
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
                                                    <h5 class="modal-title">Edit Data Siswa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Lengkap</label>
                                                            <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($row->nama); ?>" required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">NISN</label>
                                                                <input type="text" name="nisn" class="form-control" value="<?php echo htmlspecialchars($row->nisn); ?>" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Kelas</label>
                                                                <input type="text" name="kelas" class="form-control" value="<?php echo htmlspecialchars($row->kelas); ?>" placeholder="Contoh: XII MIPA 1" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row->email); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                                                    <i class="fas fa-user-times text-danger display-4 mb-3"></i>
                                                    <h5>Hapus Siswa?</h5>
                                                    <p class="text-muted small">Data pendaftaran siswa ini juga akan terhapus.</p>
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control" placeholder="10 digit nomor" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" class="form-control" placeholder="X / XI / XII ..." required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Default</label>
                        <input type="password" name="password" class="form-control" value="siswa123" required>
                        <small class="text-muted">Password awal siswa (Bisa diganti nanti)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon">Tambahkan</button>
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
