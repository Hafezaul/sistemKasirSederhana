<?php
session_start();
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produk = mysqli_real_escape_string($conn, $_POST['id_produk']);
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    
    $query = "UPDATE produk SET 
              nama_produk = '$nama_produk',
              harga = '$harga',
              gambar = '$gambar',
              deskripsi = '$deskripsi',
              stok = '$stok'
              WHERE id_produk = '$id_produk'";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Produk berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate produk: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
header('Location: dashboard.php');
exit;
?>