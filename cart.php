<?php
session_start();
require_once 'config/database.php';

// Proses hapus item dari keranjang
if(isset($_GET['hapus'])) {
    $index = (int)$_GET['hapus'];
    if(isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reset array index
    }
    header('Location: cart.php');
    exit;
}

// Proses update jumlah
if(isset($_POST['update_cart'])) {
    foreach($_POST['jumlah'] as $index => $jumlah) {
        if(isset($_SESSION['cart'][$index])) {
            $_SESSION['cart'][$index]['jumlah'] = max(1, (int)$jumlah);
        }
    }
    header('Location: cart.php');
    exit;
}

// Hitung total
$total = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) {
        $total += $item['harga'] * $item['jumlah'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .cart-item-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .quantity-input {
            width: 70px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-cart-check-fill"></i> TechStore
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-fill"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cart.php">
                            <i class="bi bi-cart-fill"></i> Keranjang
                            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="badge bg-danger"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/dashboard.php">
                            <i class="bi bi-gear-fill"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Cart Section -->
    <div class="container my-5">
        <h2 class="mb-4"><i class="bi bi-cart-fill"></i> Keranjang Belanja</h2>

        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        
        <!-- Cart Items -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST">
                            <?php foreach($_SESSION['cart'] as $index => $item): ?>
                            <div class="row align-items-center border-bottom py-3">
                                <div class="col-md-2">
                                    <img src="assets/img/<?php echo $item['gambar']; ?>" 
                                         class="img-fluid rounded cart-item-img" 
                                         alt="<?php echo $item['nama_produk']; ?>"
                                         onerror="this.src='https://via.placeholder.com/100?text=<?php echo urlencode($item['nama_produk']); ?>'">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-0"><?php echo $item['nama_produk']; ?></h6>
                                    <small class="text-muted">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></small>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="jumlah[<?php echo $index; ?>]" 
                                           class="form-control quantity-input" 
                                           value="<?php echo $item['jumlah']; ?>" 
                                           min="1">
                                </div>
                                <div class="col-md-2">
                                    <strong>Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></strong>
                                </div>
                                <div class="col-md-1">
                                    <a href="cart.php?hapus=<?php echo $index; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Hapus produk ini dari keranjang?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <div class="mt-3">
                                <button type="submit" name="update_cart" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise"></i> Update Keranjang
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Lanjut Belanja
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Total</h5>
                            <h5 class="text-primary">Rp <?php echo number_format($total, 0, ',', '.'); ?></h5>
                        </div>
                        
                        <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <i class="bi bi-check-circle"></i> Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #ddd;"></i>
            <h3 class="mt-3">Keranjang Belanja Kosong</h3>
            <p class="text-muted">Belum ada produk yang ditambahkan ke keranjang</p>
            <a href="index.php" class="btn btn-primary mt-3">
                <i class="bi bi-shop"></i> Mulai Belanja
            </a>
        </div>
        
        <?php endif; ?>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses_pesan.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_pemesan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" name="email" required>
                            <small class="text-muted">Notifikasi pesanan akan dikirim ke email ini</small>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6>Detail Pesanan:</h6>
                            <?php if(isset($_SESSION['cart'])): ?>
                                <?php foreach($_SESSION['cart'] as $item): ?>
                                <div class="d-flex justify-content-between small">
                                    <span><?php echo $item['nama_produk']; ?> (x<?php echo $item['jumlah']; ?>)</span>
                                    <span>Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></span>
                                </div>
                                <?php endforeach; ?>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong class="text-primary">Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Konfirmasi Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-cart-check-fill"></i> TechStore</h5>
                    <p class="small">Toko online terpercaya untuk kebutuhan teknologi Anda.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Kontak Kami</h5>
                    <p class="small">
                        <i class="bi bi-envelope"></i> info@techstore.com<br>
                        <i class="bi bi-telephone"></i> +62 812-3456-7890
                    </p>
                </div>
            </div>
            <hr>
            <p class="text-center small mb-0">&copy; 2024 TechStore. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>