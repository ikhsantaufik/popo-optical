<?php
include 'header.php';
$no = 1;
if(isset($_GET['idDetail'])) {
    $deskripsi_tindakan="";
    $id_pesanan = $_GET['idDetail'];
    $check_query = "SELECT * FROM pesanan WHERE kode_pesanan = '$id_pesanan'";
    $check_result = mysqli_query($conn, $check_query);
    $aPesanan = mysqli_fetch_object($check_result);
}

?>
<!--Content-->
<div class="container-utama">
    <div class="box">
            <div class="box-header">
                DETAIL PESANAN
            </div>
            <div class="box-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                    <div class="input-grup">
                        <input type="text" name="pelanggan" placeholder="Pencarian nama">
                        <input type="date" name="tanggal" placeholder="Pilih Tanggal pendaftaran">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Catatan</th>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // Array untuk menyimpan nama frame dan lensa
                        $frames = [];
                        $lensas = [];

                        // Query untuk mendapatkan daftar frame berdasarkan kode pesanan
                        $queryFrame = mysqli_query($conn, "SELECT frame.nama_frame, pesanan_frame.jumlah_barang AS jumlah_barang, pesanan_frame.keterangan FROM pesanan_frame
                                                        JOIN frame ON pesanan_frame.frame_kode = frame.kode_frame
                                                        WHERE pesanan_frame.pesanan_kode = '$id_pesanan'");                    
                                                        // Menyimpan nama frame ke dalam array
                        while ($frame = mysqli_fetch_array($queryFrame)) {
                            $frames[] = $frame;
                        }

                        // Menampilkan nama frame
                        foreach ($frames as $frame) {
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $frame['keterangan'] ?></td>
                                <td><?= $frame['nama_frame'] ?></td>
                                <td><?= $frame['jumlah_barang'] ?></td>
                            </tr>
                    <?php }

                        // Query untuk mendapatkan daftar lensa berdasarkan kode pesanan
                        $queryLensa = mysqli_query($conn, "SELECT lensa.nama_lensa, pesanan_lensa.jumlah_barang AS jumlah_barang, pesanan_lensa.keterangan FROM pesanan_lensa
                                                        JOIN lensa ON pesanan_lensa.lensa_kode = lensa.kode_lensa
                                                        WHERE pesanan_lensa.pesanan_kode = '$id_pesanan'");
                        // Menyimpan nama lensa ke dalam array
                        while ($lensa = mysqli_fetch_array($queryLensa)) {
                            $lensas[] = $lensa;
                        }

                        // Menampilkan nama lensa
                        foreach ($lensas as $lensa) {
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $lensa['keterangan'] ?></td>
                                <td><?= $lensa['nama_lensa'] ?></td>
                                <td><?= $lensa['jumlah_barang'] ?></td>
                            </tr>
                    <?php }
                        
                        // Menampilkan pesan jika tidak ada data
                        if (empty($frames) && empty($lensas)) { ?>
                            <tr>
                                <td colspan="3">Data Tidak Ada</td>
                            </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>  
<?php include 'footer.php'?>

