<?php
session_start();
require_once 'config/database.php';

// Cek apakah ID produk ada
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id_produk = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data produk berdasarkan ID
$query = "SELECT * FROM produk WHERE id_produk = '$id_produk'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

$produk = mysqli_fetch_assoc($result);

// Proses tambah ke keranjang
if(isset($_POST['tambah_keranjang'])) {
    $jumlah = (int)$_POST['jumlah'];
    
    if($jumlah > 0 && $jumlah <= $produk['stok']) {
        // Inisialisasi cart jika belum ada
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        // Cek apakah produk sudah ada di keranjang
        $found = false;
        foreach($_SESSION['cart'] as $key => $item) {
            if($item['id_produk'] == $id_produk) {
                $_SESSION['cart'][$key]['jumlah'] += $jumlah;
                $found = true;
                break;
            }
        }
        
        // Jika produk belum ada, tambahkan baru
        if(!$found) {
            $_SESSION['cart'][] = array(
                'id_produk' => $produk['id_produk'],
                'nama_produk' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'jumlah' => $jumlah,
                'gambar' => $produk['gambar']
            );
        }
        
        header('Location: cart.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produk['nama_produk']; ?> - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .product-image {
            max-height: 500px;
            object-fit: cover;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-btn {
            width: 40px;
            height: 40px;
        }
        #jumlah {
            width: 80px;
            text-align: center;
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

    <!-- Product Detail Section -->
    <div class="container my-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active"><?php echo $produk['nama_produk']; ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-6 mb-4">
                <img src="assets/img/<?php echo $produk['gambar']; ?>" 
                     class="img-fluid rounded product-image w-100" 
                     alt="<?php echo $produk['nama_produk']; ?>"
                     onerror="this.src='https://via.placeholder.com/500x500?text=<?php echo urlencode($produk['nama_produk']); ?>'">
            </div>
            
            <div class="col-md-6">
                <h2 class="fw-bold mb-3"><?php echo $produk['nama_produk']; ?></h2>
                
                <div class="mb-4">
                    <h3 class="text-primary fw-bold">
                        Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?>
                    </h3>
                </div>

                <div class="mb-4">
                    <h5>Deskripsi Produk</h5>
                    <p class="text-muted"><?php echo $produk['deskripsi']; ?></p>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-success p-2">
                            <i class="bi bi-box-seam"></i> Stok Tersedia: <?php echo $produk['stok']; ?>
                        </span>
                    </div>
                </div>

                <form method="POST" class="mb-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah</label>
                        <div class="quantity-control">
                            <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="kurangJumlah()">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="1" min="1" max="<?php echo $produk['stok']; ?>" required>
                            <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="tambahJumlah()">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="tambah_keranjang" class="btn btn-primary btn-lg">
                            <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                        </a>
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
    <script>
        const maxStok = <?php echo $produk['stok']; ?>;
        
        function tambahJumlah() {
            let jumlah = document.getElementById('jumlah');
            if(parseInt(jumlah.value) < maxStok) {
                jumlah.value = parseInt(jumlah.value) + 1;
            }
        }
        
        function kurangJumlah() {
            let jumlah = document.getElementById('jumlah');
            if(parseInt(jumlah.value) > 1) {
                jumlah.value = parseInt(jumlah.value) - 1;
            }
        }
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>