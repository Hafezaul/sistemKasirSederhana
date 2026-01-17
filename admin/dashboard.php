<?php
session_start();
require_once '../config/database.php';

// Ambil data produk
$query_produk = "SELECT * FROM produk ORDER BY id_produk DESC";
$result_produk = mysqli_query($conn, $query_produk);

// Ambil data pesanan
$query_pesanan = "SELECT * FROM pesanan ORDER BY id_pesanan DESC";
$result_pesanan = mysqli_query($conn, $query_pesanan);


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .product-img-small {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar text-white p-4">
                <h4 class="mb-4">
                    <i class="bi bi-speedometer2"></i> Admin Panel
                </h4>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="#produk" class="nav-link text-white active" data-bs-toggle="tab">
                            <i class="bi bi-box-seam"></i> Kelola Produk
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#pesanan" class="nav-link text-white" data-bs-toggle="tab">
                            <i class="bi bi-cart-check"></i> Daftar Pesanan
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="../index.php" class="nav-link text-white">
                            <i class="bi bi-house"></i> Kembali ke Website
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="tab-content">
                    <!-- Tab Produk -->
                    <div class="tab-pane fade show active" id="produk">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2><i class="bi bi-box-seam"></i> Kelola Produk</h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahProdukModal">
                                <i class="bi bi-plus-circle"></i> Tambah Produk
                            </button>
                        </div>

                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php 
                                echo $_SESSION['success']; 
                                unset($_SESSION['success']);
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Gambar</th>
                                                <th>Nama Produk</th>
                                                <th>Harga</th>
                                                <th>Stok</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(mysqli_num_rows($result_produk) > 0): ?>
                                                <?php while($row = mysqli_fetch_assoc($result_produk)): ?>
                                                <tr>
                                                    <td><?php echo $row['id_produk']; ?></td>
                                                    <td>
                                                        <img src="../assets/img/<?php echo $row['gambar']; ?>" 
                                                             class="rounded product-img-small"
                                                             onerror="this.src='https://via.placeholder.com/60'">
                                                    </td>
                                                    <td><?php echo $row['nama_produk']; ?></td>
                                                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $row['stok'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                            <?php echo $row['stok']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning" 
                                                                onclick="editProduk(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <a href="hapus_produk.php?id=<?php echo $row['id_produk']; ?>" 
                                                           class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Belum ada produk</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Pesanan -->
                    <div class="tab-pane fade" id="pesanan">
                        <h2 class="mb-4"><i class="bi bi-cart-check"></i> Daftar Pesanan</h2>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No. Pesanan</th>
                                                <th>Nama Pemesan</th>
                                                <th>Email</th>
                                                <th>Total Harga</th>
                                                <th>Status</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(mysqli_num_rows($result_pesanan) > 0): ?>
                                                <?php while($row = mysqli_fetch_assoc($result_pesanan)): ?>
                                                <tr>
                                                    <td>#<?php echo str_pad($row['id_pesanan'], 5, '0', STR_PAD_LEFT); ?></td>
                                                    <td><?php echo $row['nama_pemesan']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                    <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                                    <td>
                                                        <?php
                                                        $badge_class = '';
                                                        switch($row['status_pesanan']) {
                                                            case 'diproses': $badge_class = 'bg-warning'; break;
                                                            case 'dikemas': $badge_class = 'bg-info'; break;
                                                            case 'dikirim': $badge_class = 'bg-primary'; break;
                                                            case 'selesai': $badge_class = 'bg-success'; break;
                                                        }
                                                        ?>
                                                        <span class="badge <?php echo $badge_class; ?>">
                                                            <?php echo ucfirst($row['status_pesanan']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" 
                                                                onclick="lihatDetail(<?php echo $row['id_pesanan']; ?>)">
                                                            <i class="bi bi-eye"></i> Detail
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">Belum ada pesanan</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="tambahProdukModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="tambah_produk.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar (nama file)</label>
                            <input type="text" class="form-control" name="gambar" placeholder="contoh: produk.jpg" required>
                            <small class="text-muted">Upload file gambar ke folder assets/img/ terlebih dahulu</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" name="stok" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Produk -->
    <div class="modal fade" id="editProdukModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="edit_produk.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_produk" id="edit_id_produk">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" id="edit_nama_produk" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" name="harga" id="edit_harga" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar (nama file)</label>
                            <input type="text" class="form-control" name="gambar" id="edit_gambar" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="edit_deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" name="stok" id="edit_stok" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    <div class="modal fade" id="detailPesananModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailPesananContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editProduk(data) {
            document.getElementById('edit_id_produk').value = data.id_produk;
            document.getElementById('edit_nama_produk').value = data.nama_produk;
            document.getElementById('edit_harga').value = data.harga;
            document.getElementById('edit_gambar').value = data.gambar;
            document.getElementById('edit_deskripsi').value = data.deskripsi;
            document.getElementById('edit_stok').value = data.stok;
            
            const modal = new bootstrap.Modal(document.getElementById('editProdukModal'));
            modal.show();
        }

        function lihatDetail(id_pesanan) {
            const modal = new bootstrap.Modal(document.getElementById('detailPesananModal'));
            modal.show();
            
            fetch(`detail_pesanan.php?id=${id_pesanan}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('detailPesananContent').innerHTML = html;
                });
        }
    </script>
</body>
</html>
<?php mysqli_close($conn); 

?>