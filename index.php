<?php
session_start();
require_once 'config/database.php';

// Ambil semua produk dari database
$query = "SELECT * FROM produk ORDER BY id_produk DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Mini E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .product-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-img {
            height: 250px;
            object-fit: cover;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
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
                        <a class="nav-link active" href="index.php">
                            <i class="bi bi-house-fill"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
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

    <!-- Hero Section -->
    <div class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Selamat Datang di TechStore</h1>
            <p class="lead">Temukan produk teknologi terbaik dengan harga terjangkau</p>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container my-5">
        <h2 class="text-center mb-5 fw-bold">Produk Kami</h2>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="col">
                <div class="card product-card h-100">
                    <img src="assets/img/<?php echo $row['gambar']; ?>" 
                         class="card-img-top product-img" 
                         alt="<?php echo $row['nama_produk']; ?>"
                         onerror="this.src='https://via.placeholder.com/300x250?text=<?php echo urlencode($row['nama_produk']); ?>'">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['nama_produk']; ?></h5>
                        <p class="card-text text-muted small"><?php echo substr($row['deskripsi'], 0, 80); ?>...</p>
                        <div class="mt-auto">
                            <h4 class="text-primary fw-bold">
                                Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                            </h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-box-seam"></i> Stok: <?php echo $row['stok']; ?>
                                </small>
                                <a href="pemesanan.php?id=<?php echo $row['id_produk']; ?>" 
                                   class="btn btn-primary">
                                    <i class="bi bi-cart-plus"></i> Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Belum ada produk tersedia.
        </div>
        <?php endif; ?>
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