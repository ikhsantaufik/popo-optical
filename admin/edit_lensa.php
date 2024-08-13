<?php
include 'header.php';
//echo "asd".$_GET['id'];
$lensa = mysqli_query($conn, "SELECT * FROM lensa WHERE kode_lensa ='$_GET[id]'");
//print_r($frame);
if (!$lensa) {
    die("Query lensa gagal: " . mysqli_error($conn));
}
if (mysqli_num_rows($lensa) > 0) {
    $p = mysqli_fetch_array($lensa);
} else {
    header("location:lensa.php");
    exit();
}
?>
<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                EDIT LENSA
            </div>
            <div class="box-body">
            <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Kode Lensa:</label><br><br>
                        <input type="text" name="kode" placeholder="Kode Lensa" class="input-control" value="<?= $p['kode_lensa'] ?>">
                    </div><br>
                    <div class="form-group">
                        <label>Nama Lensa:</label><br><br>
                        <input type="text" name="nama" placeholder="Nama Lensa" class="input-control" value="<?= $p['nama_lensa'] ?>">
                    </div><br>
                    <div class="form-group">
                        <label>Deskripsi Lensa:</label><br><br>
                        <textarea type="text" name="deskripsi" placeholder="Deskripsi Lensa" class="input-control" id="keterangan" required><?= $p['deskripsi_lensa'] ?></textarea>
                    </div><br>
                    <div class="form-group">
                        <label>Stok Lensa :</label><br><br>
                        <input type="number" name="stok" class="input-control" value="<?= $p['stok_lensa'] ?>" required>
                    </div><br>
                    <div class="form-group">
                        <label>Harga Lensa :</label><br><br>
                        <input type="number" name="harga" class="input-control" value="<?= $p['harga_lensa'] ?>" required>
                    </div><br>
                    <div class="form-group">
                        <label>Status:</label>
                        <select name="status" class="input-control" required>
                            <option value="">pilih</option>
                            <option value="Tidak Tersedia" <?= ($p['status_lensa'] == 'Tidak Tersedia') ? 'selected' : ''; ?>>Tidak Tersedia</option>
                            <option value="Tersedia" <?= ($p['status_lensa'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                        </select>
                    </div><br>
                    <div class="form-group">
                        <label for="jenis-lensa">Jenis Lensa: </label><br><br>
                        <select name="jenis" class="input-control" required>
                            <?php
                            // Menampilkan opsi untuk data yang sudah ada
                            echo "<option value='{$p['jenis_id']}'>{$p['jenis_id']}</option>";

                            // Mengambil data dari database untuk opsi tambahan
                            $jenisQuery = mysqli_query($conn, "SELECT * FROM jenis_lensa ORDER BY id_jenis_lensa DESC");
                            while ($jenisData = mysqli_fetch_array($jenisQuery)) {
                                $selected = ($jenisData['id_jenis_lensa'] == $p['jenis_id']) ? 'selected' : '';
                                echo "<option value='{$jenisData['id_jenis_lensa']}' $selected>{$jenisData['jenis_lensa']}</option>";
                            }
                            ?>
                        </select>
                    </div><br>
                    <div class="form-group">
                        <label>Galeri</label><br><br>
                        <img src="../upload/lensa/<?= $p['galeri'] ?>" width="200px" class="image">
                        <input type="hidden" name="galeri2" value="<?= $p['galeri'] ?>" class="input-control" required>
                        <input type="file" name="galeri" class="input-control">
                    </div><br>

                    <button type="button" class="btn" onclick="window.location ='lensa.php'">KEMBALI</button>
                    <button type="submit" name="submit" class="btn">SIMPAN</button>
                </form>

                <?php
                //ketika tombol submit di klik apa yang akan terjadi 
                if (isset($_POST['submit'])) {
                    $kode =  htmlspecialchars(addslashes($_POST['kode']));
                    $nama =  htmlspecialchars(addslashes(ucwords($_POST['nama'])));
                    $deskripsi = addslashes($_POST['deskripsi']);
                    $stok = addslashes($_POST['stok']);
                    $harga = addslashes($_POST['harga']);
                    $status = addslashes($_POST['status']);
                    $jenis_lensa = addslashes($_POST['jenis']);
                    $currdate = date('Y-m-d H:i:s');

                    //jika didalam name galeri ada name yang ada di galeri jika tidak sama dengan 0 maka ada 
                    if ($_FILES['galeri']['name'] != '') {
                        $filename = $_FILES['galeri']['name'];
                        $tmpname = $_FILES['galeri']['tmp_name'];
                        $filesize = $_FILES['galeri']['size'];

                        $formatfile = pathinfo($filename, PATHINFO_EXTENSION);
                        $rename = 'lensa' . time() . '.' . $formatfile;

                        $allowedtype = array('png', 'jpg', 'jpeg', 'gif');
                        if (!in_array($formatfile, $allowedtype)) {
                            echo '<div class ="alert alert-error">Format File Tidak Ditemukan</div>';
                            return false;
                        } elseif ($filesize > 1000000) {
                            echo '<div class ="alert alert-error">Ukuran File Tidak Boleh Lebih Dari 1 MB</div>';
                            return false;
                        } else {
                            if (file_exists("../upload/lensa/" . $p['galeri'])) {
                                unlink("../upload/lensa/" . $p['galeri']);
                            }
                            move_uploaded_file($tmpname, "../upload/lensa/" . $rename);
                        }
                    } else {
                        $rename = $p['galeri'];
                    }

                    $update = mysqli_query($conn, "UPDATE lensa SET 
                        kode_lensa = '" . $kode . "',
                        nama_lensa = '" . $nama . "',
                        deskripsi_lensa = '" . $deskripsi . "',
                        stok_lensa = '" . $stok . "',
                        harga_lensa = '" . $harga . "',
                        status_lensa = '" . $status . "',
                        galeri = '" . $rename . "',
                        created_date = '" . $currdate . "',
                        jenis_id = '" . $jenis_lensa . "'
                        WHERE kode_lensa = '" . $_GET['id'] . "'"
                    );

                    // Periksa apakah update berhasil
                    if ($update) {
                        echo "<script>window.location='lensa.php?success=Edit Data Berhasil'</script>";
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
