<?php
include 'header.php';
//echo "asd".$_GET['id'];
$jenis_frame = mysqli_query($conn, "SELECT * FROM jenis_frame WHERE id_jenis  ='$_GET[id]'");
//print_r($frame);
if (!$jenis_frame) {
    die("Query lensa gagal: " . mysqli_error($conn));
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
                        <label>Id Frame:</label><br><br>
                        <!-- Input untuk kode frame -->
                        <input type="text" name="id_jenis" placeholder="Kode Frame" class="input-control" value="<?= $f['id_jenis']?>">
                    </div>
                    <div class="form-group">
                        <label>Jenis Frame:</label><br><br>
                        <!-- Input untuk jenis frame -->
                        <input type="text" name="jenis_frame" placeholder="Jenis Frame" class="input-control" value="<?= $f['jenis_frame'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Frame:</label><br><br>
                        <!-- Textarea untuk deskripsi frame -->
                        <textarea name="deskripsi_frame" placeholder="Deskripsi Frame" class="input-control" id="keterangan"><?= $f['deskripsi_frame'] ?></textarea>
                    </div><br>

                    <!-- Tombol untuk kembali ke halaman jenis_frame.php -->
                    <button type="button" class="btn" onclick="window.location ='jenis_frame.php'">KEMBALI</button>
                    <!-- Tombol untuk menyimpan perubahan -->
                    <button type="submit" name="submit" class="btn">SIMPAN</button>
                </form>

                <?php
                // Ketika tombol submit diklik, apa yang akan terjadi
                if (isset($_POST['submit'])) {
                   // Ambil nilai input
                   $id_jenis = htmlspecialchars(addslashes($_POST['id_jenis']));
                   $nama = htmlspecialchars(addslashes($_POST['jenis_frame']));
                   $deskripsi = addslashes($_POST['deskripsi_frame']);

                    $update = mysqli_query($conn, "UPDATE jenis_frame SET 
                        id_jenis = '" . $id_jenis . "',
                        jenis_frame = '" . $nama . "',
                        deskripsi_frame = '" . $deskripsi . "'
                        WHERE id_jenis = '" . $_GET['id'] . "'"
                    );
                    // Periksa apakah update berhasil
                    if ($update) {
                        echo "<script>window.location='jenis_frame.php?success=Edit Data Berhasil'</script>";
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
