<?php
session_start();
include 'connection.php';

$identitas = mysqli_query($conn, "SELECT * FROM setting ORDER BY id DESC LIMIT 1");
$d = mysqli_fetch_object($identitas);

// Periksa apakah sesi OTP sudah diatur
if(!isset($_SESSION['otp']) || !isset($_SESSION['username'])) {
    // Jika sesi belum diatur, redirect kembali ke halaman lupa password
    header("Location: lupa_password.php");
    exit();
}
// echo $_SESSION['email'];
// Ketika tombol submit diklik
if(isset($_POST['submit'])) {
    // Ambil username yang ingin direset password-nya dari sesi
    $username = $_SESSION['username'];

    $pass1 = mysqli_real_escape_string($conn, $_POST["pass1"]);
    $pass2 = mysqli_real_escape_string($conn, $_POST["pass2"]);

    // Validasi password
    // Jika pass2 tidak sama dengan pass1
    if($pass2 != $pass1){
        echo '<div class="alert alert-danger">Ulangi Password, Tidak Sesuai</div>';
    } else {
        // Perbarui password
        $pass1 = mysqli_real_escape_string($conn, $pass1); // Pastikan untuk mengamankan input
        $hashed_password = md5($pass1); // Hash password menggunakan MD5
        $update = mysqli_query($conn, "UPDATE akun_pelanggan SET password ='$hashed_password' WHERE email='$_SESSION[email]'");		
        if($update){
            echo '<div class="alert alert-success">Ubah Password berhasil</div>';
        } else {
            echo '<div class="alert alert-danger">Gagal Update: '.mysqli_error($conn).'</div>';
        }        
    }		
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ubah Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta charset="utf-8">  
    <link rel="icon" href="upload/identitas/<?= $d->favicon ?>">
    <title>Website <?= $d->nama ?></title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body class="body">

    <div class="login">
        <div class="box box-login">
            <div class="box-header text-center" >
                <h1>UBAH SANDI</h1>
            </div>
            <div class="box-body">
                <form action="" method="post">
                    <div class="form-group">
                        <input type="hidden" name="username" value="<?= $_SESSION['username'] ?>" class="input-control" required>
                    </div><br>
                    <div class="form-group">
                        <label>Password:</label><br><br>
                        <input type="password" name="pass1" placeholder="Password" class="input-control" required>
                    </div><br>
                    <div class="form-group">
                        <label>Ulangi Password:</label><br><br>
                        <input type="password" name="pass2" placeholder="Ulangi Password" class="input-control" required>
                    </div><br>
                    <button type="submit" class="btn" name="submit">Ubah Password</button>
                </form>
            </div>
            <div class="box-footer text-center">
                <a href="login.php">Kembali ke Login</a>
            </div>
        </div>
    </div>
</body>
</html>
