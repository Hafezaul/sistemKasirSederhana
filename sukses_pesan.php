<?php
session_start();
if(!isset($_SESSION['pesanan_sukses'])) {
    header('Location: index.php');
    exit;
}

$pesanan = $_SESSION['pesanan_sukses'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- EmailJS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <style>
        .success-icon {
            font-size: 5rem;
            color: #28a745;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-cart-check-fill"></i> TechStore
            </a>
        </div>
    </nav>

    <!-- Success Section -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-check-circle-fill success-icon"></i>
                        <h2 class="mt-4 mb-3">Pesanan Berhasil!</h2>
                        <p class="lead text-muted">Terima kasih atas pesanan Anda</p>
                        
                        <div class="alert alert-info mt-4" role="alert">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Email notifikasi sedang dikirim ke <?php echo htmlspecialchars($pesanan['email']); ?></strong>
                        </div>

                        <div class="card mt-4 text-start">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Detail Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6"><strong>No. Pesanan:</strong></div>
                                    <div class="col-6">#<?php echo str_pad($pesanan['id_pesanan'], 5, '0', STR_PAD_LEFT); ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Nama Pemesan:</strong></div>
                                    <div class="col-6"><?php echo htmlspecialchars($pesanan['nama_pemesan']); ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Email:</strong></div>
                                    <div class="col-6"><?php echo htmlspecialchars($pesanan['email']); ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Total Pembayaran:</strong></div>
                                    <div class="col-6 text-primary fw-bold">
                                        Rp <?php echo number_format($pesanan['total_harga'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Status:</strong></div>
                                    <div class="col-6">
                                        <span class="badge bg-warning">Sedang Diproses</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-success mt-4" role="alert">
                            <i class="bi bi-envelope-check"></i> 
                            Pesanan Anda sedang diproses dan dikemas. Anda akan menerima notifikasi melalui email.
                        </div>

                        <div class="mt-4">
                            <a href="index.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-house-fill"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
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
    
    <script type="text/javascript">

        (function(){
            emailjs.init("WWokYAKaKvD1Qfx2E"); 
        })();

        // Kirim email notifikasi
        window.addEventListener('load', function() {
            const templateParams = {
                to_email: '<?php echo $pesanan['email']; ?>',
                to_name: '<?php echo $pesanan['nama_pemesan']; ?>',
                order_id: '#<?php echo str_pad($pesanan['id_pesanan'], 5, '0', STR_PAD_LEFT); ?>',
                total_price: 'Rp <?php echo number_format($pesanan['total_harga'], 0, ',', '.'); ?>',
                message: 'Pesanan Anda sedang diproses dan dikemas. Kami akan mengirimkan notifikasi lebih lanjut melalui email.'
            };

           
            emailjs.send('service_tpabzfg', 'template_ebrx88p', templateParams)
                .then(function(response) {
                    console.log('Email berhasil dikirim!', response.status, response.text);
                }, function(error) {
                    console.log('Gagal mengirim email:', error);
                });
        });
    </script>
</body>
</html>
<?php 
unset($_SESSION['pesanan_sukses']);
?>