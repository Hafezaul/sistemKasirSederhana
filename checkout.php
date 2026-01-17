<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<h2>Keranjang</h2>

<?php foreach ($cart as $item) {
    $total += $item['harga'] * $item['jumlah'];
    echo $item['nama'] . " x " . $item['jumlah'] . "<br>";
} ?>

<p>Total: Rp <?php echo number_format($total); ?></p>

<form action="proses_pesan.php" method="post">
    Nama:
    <input type="text" name="nama" required><br>
    Email:
    <input type="email" name="email" required><br><br>
    <button type="submit">Konfirmasi Pesanan</button>
</form>
