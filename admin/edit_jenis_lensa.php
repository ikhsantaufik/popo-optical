<?php
include 'header.php';
//echo "asd".$_GET['id'];
$jenis_frame = mysqli_query($conn, "SELECT * FROM jenis_lensa WHERE id_jenis_lensa  ='$_GET[id]'");
//print_r($frame);
if (!$jenis_frame) {
    die("Query Jenis Lensa gagal: " . mysqli_error($conn));
}
if (mysqli_num_rows($jenis_frame) > 0) {
    $f = mysqli_fetch_array($jenis_frame);
} else {
    header("location:jenis_frame.php");
    exit();
}
?>

<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                EDIT JENIS FRAME
            </div>
            <div class="box-body">
                <!-- Form untuk mengedit jenis frame -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Id Lensa:</label><br><br>
                        <!-- Input untuk kode frame -->
                        <input type="text" name="id_jenis_lensa" placeholder="Kode Lensa" class="input-control" value="<?= $f['id_jenis_lensa'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Jenis Lensa:</label><br><br>
                        <!-- Input untuk jenis frame -->
                        <input type="text" name="jenis_lensa" placeholder="Jenis Lensa" class="input-control" value="<?= $f['jenis_lensa'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Lensa:</label><br><br>
                        <!-- Textarea untuk deskripsi frame -->
                        <textarea name="deskripsi_jenis" placeholder="Deskripsi Lensa" class="input-control" id="keterangan"><?= $f['deskripsi_jenis'] ?? '' ?></textarea>
                    </div><br>

                    <!-- Tombol untuk kembali ke halaman jenis_lensa.php -->
                    <button type="button" class="btn" onclick="window.location ='jenis_lensa.php'">KEMBALI</button>
                    <!-- Tombol untuk menyimpan perubahan -->
                    <button type="submit" name="submit" class="btn">SIMPAN</button>
                </form>

                <?php
                // Ketika tombol submit diklik, apa yang akan terjadi
                if (isset($_POST['submit'])) {
                   // Ambil nilai input
                   $id_jenis_lensa = htmlspecialchars(addslashes($_POST['id_jenis_lensa']));
                   $nama = htmlspecialchars(addslashes($_POST['jenis_lensa']));
                   $deskripsi = addslashes($_POST['deskripsi_jenis']);

                   $update = mysqli_query($conn, "UPDATE jenis_lensa SET 
                        id_jenis_lensa = '" . $id_jenis_lensa . "',
                        jenis_lensa = '" . $nama . "',
                        deskripsi_jenis = '" . $deskripsi . "'
                        WHERE id_jenis_lensa = '" . $_GET['id'] . "'"
                    );
                    // Periksa apakah update berhasil
                    if ($update) {
                        echo "<script>window.location='jenis_lensa.php?success=Edit Data Berhasil'</script>";
                    } else {
                        echo 'Gagal Update' . mysqli_error($conn);
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
