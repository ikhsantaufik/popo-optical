<?php
include 'header.php';
// Memeriksa status login
if(!isset($_SESSION['status_login'])){
    header("location:../login.php?msg=Harap Login Terlebih Dahulu!");
}
if (!$_SESSION['status_login']== true) {
    header("location: login.php?msg=Harap Login Terlebih Dahulu!");
    exit();
}
$pelanggan = mysqli_query($conn, "SELECT * FROM akun_pelanggan ORDER BY username DESC");
if ($pelanggan === false || mysqli_num_rows($pelanggan) == 0) {
    header("location:ubah_profil.php");
    exit(); // Ensure script stops here after redirect
}

$p = mysqli_fetch_array($pelanggan);
?>
<div class="container-utama">
    <div class="box">
        <div class="box-header">
            UBAH PROFIL
        </div>
        <div class="box-body">
            <?php
            if(isset($_GET['success']))
                echo '<div class ="alert alert-success">Ubah Profil Berhasil</div>';
            ?>
            <form action="" method="post">
                <div class="form-group">
                    <label>Nama :</label><br><br>
                    <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?= $p['nama']?>">
                </div><br>
                <div class="form-group">
                    <label>Alamat :</label><br><br>
                    <input type="text" name="alamat" placeholder="Alamat" class="input-control" value="<?= $p['alamat']?>">
                </div><br>
                <div class="form-group">
                    <label>Telepone :</label><br><br>
                    <input type="number" name="telp" placeholder="Telepone" class="input-control" value="<?= $p['telp']?>">
                </div><br>
                <div class="form-group">
                    <label>Email :</label><br><br>
                    <input type="text" name="email" placeholder="Email" class="input-control" value="<?= $p['email']?>">
                </div><br>
                <button type="button" class="btn" onclick="window.location ='index.php'">KEMBALI</button>
                <button type="submit" name="submit" class="btn">SIMPAN</button>
            </form>

            <?php
            // When the submit button is clicked
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Sanitize and validate input
                $nama         = htmlspecialchars(addslashes(ucwords($_POST['nama'])));
                $alamat       = htmlspecialchars(addslashes(ucwords($_POST['alamat'])));
                $telp         = htmlspecialchars(addslashes($_POST['telp'])); // Assuming it's a valid number
                $email        = htmlspecialchars(addslashes($_POST['email'])); // Assuming it's a valid email

                // Update the profile information
                $update = mysqli_prepare($conn, "UPDATE akun_pelanggan SET 
                    nama = ?,
                    alamat = ?,
                    telp = ?,
                    email = ?
                    WHERE username = ?");

                if ($update) {
                    mysqli_stmt_bind_param($update, "sssss", $nama, $alamat, $telp, $email, $p['username']);
                    mysqli_stmt_execute($update);

                    if (mysqli_stmt_affected_rows($update) > 0) {
                        echo "<script>window.location='ubah_profil.php?success=Ubah Profil Berhasil'</script>";
                    } else {
                        echo 'Gagal Update';
                    }

                    mysqli_stmt_close($update);
                } else {
                    echo 'Gagal Prepare Statement: ' . mysqli_error($conn);
                }
            }
            ?>
        </div>
    </div>
</div>
</div>
<?php
include 'footer.php';
?>
