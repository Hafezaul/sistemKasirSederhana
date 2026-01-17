<?php
session_start();
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    
    $query = "INSERT INTO produk (nama_produk, harga, gambar, deskripsi, stok) 
              VALUES ('$nama_produk', '$harga', '$gambar', '$deskripsi', '$stok')";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Produk berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan produk: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
header('Location: dashboard.php');
exit;
?>