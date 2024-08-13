<?php
include 'header.php';
//echo "asd".$_GET['id'];
$frame = mysqli_query($conn, "SELECT * FROM frame WHERE kode_frame ='$_GET[id]'");
//print_r($frame);
if (!$frame) {
    die("Query frame gagal: " . mysqli_error($conn));
}
if (mysqli_num_rows($frame) > 0) {
    $p = mysqli_fetch_array($frame);
} else {
    header("location:frame.php");
    exit();
}
?>
<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                EDIT FRAME
            </div>
            <div class="box-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Kode Frame:</label><br><br>
                        <input type="text" name="kode" placeholder="Kode Frame" class="input-control" value="<?= $p['kode_frame'] ?>">
                    </div><br>
                    <div class="form-group">
                        <label>Nama Frame:</label><br><br>
                        <input type="text" name="nama" placeholder="Nama Frame" class="input-control" value="<?= $p['nama_frame'] ?>">
                    </div><br>
                    <div class="form-group">
                        <label>Deskripsi Frame:</label><br><br>
                        <textarea type="text" name="deskripsi" placeholder="Deskripsi Frame" class="input-control" id="keterangan" required><?= $p['deskripsi_frame'] ?></textarea>
                    </div><br>
                    <div class="form-group">
                        <label>Stok Frame :</label><br><br>
                        <input type="number" name="stok" class="input-control" value="<?= $p['stok_frame'] ?>" required>
                    </div><br>
                    <div class="form-group">
                        <label>Harga Frame :</label><br><br>
                        <input type="number" name="harga" class="input-control" value="<?= $p['harga_frame'] ?>" required>
                    </div><br>
                    <div class="form-group">
                        <label>Status:</label>
                        <select name="status" class="input-control" required>
                            <option value="">pilih</option>
                            <option value="Tidak Tersedia" <?= ($p['status_frame'] == 'Tidak Tersedia') ? 'selected' : ''; ?>>Tidak Tersedia</option>
                            <option value="Tersedia" <?= ($p['status_frame'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                        </select>
                    </div><br>
                    <div class="form-group">
                        <label for="jenis-frame">Jenis Frame: </label><br><br>
                        <select name="jenis" class="input-control" required>
                            <?php
                            // Menampilkan opsi untuk data yang sudah ada
                            echo "<option value='{$p['jenis_id']}'>{$p['jenis_id']}</option>";

                            // Mengambil data dari database untuk opsi tambahan
                            $jenisQuery = mysqli_query($conn, "SELECT * FROM jenis_frame ORDER BY id_jenis DESC");
                            while ($jenisData = mysqli_fetch_array($jenisQuery)) {
                                $selected = ($jenisData['id_jenis_lensa'] == $p['jenis_id']) ? 'selected' : '';
                                echo "<option value='{$jenisData['id_jenis_lensa']}' $selected>{$jenisData['jenis_lensa']}</option>";
                            }
                            ?>
                        </select>
                    </div><br>
                    <div class="form-group">
                        <label>Galeri</label><br><br>
                        <img src="../upload/frame/<?= $p['galeri'] ?>" width="200px" class="image">
                        <input type="hidden" name="galeri2" value="<?= $p['galeri'] ?>" class="input-control" required>
                        <input type="file" name="galeri" class="input-control">
                    </div><br>

                    <button type="button" class="btn" onclick="window.location ='frame.php'">KEMBALI</button>
                    <button type="submit" name="submit" class="btn">SIMPAN</button>
                </form>

                <?php
                //ketika tombol submit di klik apa yang akan terjadi 
                if (isset($_POST['submit'])) {
                    $kode =  htmlspecialchars(addslashes(ucwords($_POST['kode'])));
                    $nama =  htmlspecialchars(addslashes(ucwords($_POST['nama'])));
                    $deskripsi = addslashes($_POST['deskripsi']);
                    $stok = addslashes($_POST['stok']);
                    $harga = addslashes($_POST['harga']);
                    $status = addslashes($_POST['status']);
                    $jenis_frame = addslashes($_POST['jenis']);
					
                    //jika didalam name galeri ada name yang ada di galeri jika tidak sama dengan 0 maka ada 
                    if ($_FILES['galeri']['name'] != '') {
                        $filename = $_FILES['galeri']['name'];
                        $tmpname = $_FILES['galeri']['tmp_name'];
                        $filesize = $_FILES['galeri']['size'];

                        $formatfile = pathinfo($filename, PATHINFO_EXTENSION);
                        $rename = 'frame' . time() . '.' . $formatfile;

                        $allowedtype = array('png', 'jpg', 'jpeg', 'gif');
                        if (!in_array($formatfile, $allowedtype)) {
                            echo '<div class ="alert alert-error">Format File Tidak Ditemukan</div>';
                            return false;
                        } elseif ($filesize > 1000000) {
                            echo '<div class ="alert alert-error">Ukuran File Tidak Boleh Lebih Dari 1 MB</div>';
                            return false;
                        } else {
                            if (file_exists("../upload/frame/" . $p['galeri'])) {
                                unlink("../upload/frame/" . $p['galeri']);
                            }
                            move_uploaded_file($tmpname, "../upload/frame/" . $rename);
                        }
                    } else {
                        $rename = $p['galeri'];
                    }

                    $update = mysqli_query($conn, "UPDATE frame SET 
                        kode_frame = '" . $kode . "',
                        nama_frame = '" . $nama . "',
                        deskripsi_frame = '" . $deskripsi . "',
                        stok_frame = '" . $stok . "',
                        harga_frame = '" . $harga . "',
                        status_frame = '" . $status . "',
                        galeri = '" . $rename . "',
                        jenis_id = '" . $jenis_frame . "'
                        WHERE kode_frame = '" . $_GET['id'] . "'"
                    );

                    // Periksa apakah update berhasil
                    if ($update) {
                        echo "<script>window.location='frame.php?success=Edit Data Berhasil'</script>";
                    } else {
                        echo 'Gagal Update' . mysqli_error($conn);
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>
