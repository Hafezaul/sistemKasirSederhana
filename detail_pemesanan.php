<?php
require_once '../config/database.php';

if(isset($_GET['id'])) {
    $id_pesanan = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data pesanan
    $query_pesanan = "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan'";
    $result_pesanan = mysqli_query($conn, $query_pesanan);
    $pesanan = mysqli_fetch_assoc($result_pesanan);
    
    // Ambil detail pesanan
    $query_detail = "SELECT * FROM detail_pesanan WHERE id_pesanan = '$id_pesanan'";
    $result_detail = mysqli_query($conn, $query_detail);
    
    if($pesanan):
?>
    <div class="row mb-3">
        <div class="col-md-6">
            <h6>Informasi Pemesan</h6>
            <table class="table table-sm">
                <tr>
                    <td><strong>No. Pesanan:</strong></td>
                    <td>#<?php echo str_pad($pesanan['id_pesanan'], 5, '0', STR_PAD_LEFT); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama:</strong></td>
                    <td><?php echo $pesanan['nama_pemesan']; ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo $pesanan['email']; ?></td>
                </tr>
                <tr>
                    <td><strong>Tanggal:</strong></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($pesanan['created_at'])); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h6>Status Pesanan</h6>
            <?php
            $badge_class = '';
            switch($pesanan['status_pesanan']) {
                case 'diproses': $badge_class = 'bg-warning'; break;
                case 'dikemas': $badge_class = 'bg-info'; break;
                case 'dikirim': $badge_class = 'bg-primary'; break;
                case 'selesai': $badge_class = 'bg-success'; break;
            }
            ?>
            <span class="badge <?php echo $badge_class; ?> p-2">
                <?php echo ucfirst($pesanan['status_pesanan']); ?>
            </span>
        </div>
    </div>

    <h6>Detail Produk</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($detail = mysqli_fetch_assoc($result_detail)): ?>
                <tr>
                    <td><?php echo $detail['nama_produk']; ?></td>
                    <td>Rp <?php echo number_format($detail['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $detail['jumlah']; ?></td>
                    <td>Rp <?php echo number_format($detail['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="table-light">
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong>Rp <?php echo number_format($pesanan['total_harga'], 0, ',', '.'); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php
    else:
        echo '<div class="alert alert-danger">Pesanan tidak ditemukan</div>';
    endif;
}

mysqli_close($conn);
?>