<?php
session_start();
require_once 'config/database.php';

// Cek apakah ada data POST dan keranjang tidak kosong
if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header('Location: cart.php');
    exit;
}

// Ambil data dari form
$nama_pemesan = mysqli_real_escape_string($conn, $_POST['nama_pemesan']);
$email = mysqli_real_escape_string($conn, $_POST['email']);

// Hitung total harga
$total_harga = 0;
foreach($_SESSION['cart'] as $item) {
    $total_harga += $item['harga'] * $item['jumlah'];
}

// Mulai transaksi database
mysqli_begin_transaction($conn);

try {
    // Insert data pesanan
    $query_pesanan = "INSERT INTO pesanan (nama_pemesan, email, total_harga, status_pesanan) 
                      VALUES ('$nama_pemesan', '$email', '$total_harga', 'diproses')";
    
    if(!mysqli_query($conn, $query_pesanan)) {
        throw new Exception("Gagal menyimpan pesanan");
    }
    
    $id_pesanan = mysqli_insert_id($conn);
    
    // Insert detail pesanan
    foreach($_SESSION['cart'] as $item) {
        $id_produk = $item['id_produk'];
        $nama_produk = mysqli_real_escape_string($conn, $item['nama_produk']);
        $harga = $item['harga'];
        $jumlah = $item['jumlah'];
        $subtotal = $harga * $jumlah;
        
        $query_detail = "INSERT INTO detail_pesanan (id_pesanan, id_produk, nama_produk, harga, jumlah, subtotal) 
                        VALUES ('$id_pesanan', '$id_produk', '$nama_produk', '$harga', '$jumlah', '$subtotal')";
        
        if(!mysqli_query($conn, $query_detail)) {
            throw new Exception("Gagal menyimpan detail pesanan");
        }
        
        // Update stok produk
        $query_update_stok = "UPDATE produk SET stok = stok - $jumlah WHERE id_produk = $id_produk";
        mysqli_query($conn, $query_update_stok);
    }
    
    // Commit transaksi
    mysqli_commit($conn);
    
    // Simpan data untuk halaman sukses
    $_SESSION['pesanan_sukses'] = array(
        'id_pesanan' => $id_pesanan,
        'nama_pemesan' => $nama_pemesan,
        'email' => $email,
        'total_harga' => $total_harga
    );
    
    // Kosongkan keranjang
    unset($_SESSION['cart']);
    
    // Redirect ke halaman sukses
    header('Location: sukses_pesan.php');
    exit;
    
} catch(Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($conn);
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    header('Location: cart.php');
    exit;
}

mysqli_close($conn);
?>