<?php include 'header.php'; ?>

<!--content-->
<div class="section">
    <div class="container-utama">
        <?php
            // Periksa apakah parameter 'id' telah diterima
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                // Query untuk mendapatkan lensa berdasarkan 'id'
                $lensa = mysqli_query($conn, "SELECT * FROM lensa WHERE lensa.kode_lensa = '$id'");
                // Periksa apakah lensa dengan 'id' tersebut ada dalam database
                if(mysqli_num_rows($lensa) > 0) {
                    $p = mysqli_fetch_array($lensa); // Ambil data lensa dari hasil query
        ?>
                    <h3 class="text-center line"><?= $p['nama_lensa'] ?></h3>
                    <img src="upload/lensa/<?= $p['galeri'] ?>" width="50%" class="image" style="margin-top:5px">
                    <div class="produk">
                        HARGA     :<br>Rp.<?= number_format($p['harga_lensa'], 0, ",", ".") ?><br>
                        STOK :<br><?= $p['stok_lensa'] ?><br>
                        DESKRIPSI :<br><?= $p['deskripsi_lensa'] ?><br>
                        STATUS    :<br><?= $p['status_lensa'] ?><br>
                    </div>
        <?php
                } else {
                    // Jika tidak ada data lensa dengan 'id' yang diberikan, tampilkan pesan kesalahan
                    echo "Data lensa tidak ditemukan";
                }
            } else {
                // Jika parameter 'id' tidak terdefinisi atau kosong, tampilkan pesan kesalahan
                echo "Parameter 'id' tidak ditemukan.";
            }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>

