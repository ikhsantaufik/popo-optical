<?php include 'header.php'; ?>

<!--content-->
<div class="section">
    <div class="container-utama">
        <?php
            // Periksa apakah parameter 'id' telah diterima
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                // Query untuk mendapatkan frame berdasarkan 'id'
                $query = mysqli_query($conn, "SELECT * FROM frame WHERE frame.kode_frame = '$id'");
                // Periksa apakah frame dengan 'id' tersebut ada dalam database
                if(mysqli_num_rows($query) > 0) {
                    $p = mysqli_fetch_assoc($query); // Ambil data frame dari hasil query
        ?>
                    <h3 class="text-center line"><?= $p['nama_frame'] ?></h3>
                    <img src="upload/frame/<?= $p['galeri'] ?>" width="50%" class="image" style="margin-top:5px">
                    <div class="produk">
                        HARGA 	   :<br>Rp.<?= number_format($p['harga_frame'], 0, ",", ".") ?><br>
                        STOK 	   :<br><?= $p['stok_frame'] ?><br>
                        DESKRIPSI :<br><?= $p['deskripsi_frame'] ?><br>
                        STATUS 	   :<br><?= $p['status_frame'] ?><br>
                    </div>
        <?php
                } else {
                    // Jika tidak ada frame dengan 'id' yang diberikan, tampilkan pesan kesalahan
                    echo "Frame tidak ditemukan.";
                }
            } else {
                // Jika parameter 'id' tidak terdefinisi atau kosong, tampilkan pesan kesalahan
                echo "Parameter 'id' tidak ditemukan.";
            }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>


