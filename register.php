<?php
session_start();
include 'connection.php';
$identitas = mysqli_query($conn, "SELECT * FROM setting ORDER BY id DESC LIMIT 1");
$d = mysqli_fetch_object($identitas);

if(isset($_POST['submit'])) {
    $username = htmlspecialchars(addslashes($_POST['username']));
    $nama = htmlspecialchars(addslashes(ucwords($_POST['nama'])));
    $alamat = addslashes($_POST['alamat']);
    $email = htmlspecialchars($_POST['email']);
    $telp = htmlspecialchars(addslashes($_POST['telp']));
    $pass = htmlspecialchars(addslashes($_POST['pass']));

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?msg=Email tidak valid.");
        exit();
    }

    // Check if username or email already exists
    $check_query = "SELECT * FROM akun_pelanggan WHERE username='$username' OR email='$email'";
    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0) {
        header("Location: register.php?msg=User atau email sudah terdaftar.");
        exit();
    } else {
        header("Location: verifikasi_otp.php?");
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time(); // Simpan waktu OTP terakhir dikirim

        $hashed_password = md5($pass); // Hash password menggunakan MD5

        $insert_query = "INSERT INTO akun_pelanggan (username, password, nama, alamat, telp, email, status_user, konfirmasi_otp) VALUES ('$username', '$hashed_password', '$nama','$alamat', '$telp', '$email', 'register','')";
        mysqli_query($conn, $insert_query);        

        // Kirim email OTP
        $to = $email;
        $subject = "konfirmasi OTP";
        $message = "$otp ini adalah kode verifikasi. Untuk keamanan, jangan sebarkan kode ini: ";
        $headers = "From: popooptikal2@gmail.com"; // Replace with your email

        // Kirim email
        if(mail($to, $subject, $message, $headers)) {
            // Email terkirim, alihkan ke halaman verifikasi OTP
            header("Location: verifikasi_otp.php?email=$email");
            exit();
        } else {
            // Email gagal terkirim, tampilkan pesan kesalahan
            echo 'Email gagal terkirim!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registrasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta charset="utf-8">  
    <link rel="icon" href="upload/identitas/<?= $d->favicon ?>">
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body class="body">
    <div class="login">
        <div class="box box-login">
            <div class="box-header text-center">
                <h1>Daftar</h1>
            </div>
            <div class="box-body">
                <?php if(isset($_GET['msg'])): ?>
                <div class='alert alert-error'><?= $_GET['msg'] ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Username</label><br><br>
                        <input type="text" name="username" placeholder="Username" class="input-control">
                    </div><br>
                    <div class="form-group">
                        <label>Nama:</label><br><br>
                        <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" required>
                    </div><br>
                    <div class="form-group">
                        <label>Alamat:</label><br><br>
                        <textarea name="alamat" placeholder="Alamat" class="input-control" id="keterangan"></textarea>
                    </div><br>
                    <div class="form-group">
                        <label>Email:</label><br><br>
                        <input type="text" name="email" placeholder="Email" class="input-control" required>
                    </div><br>
                    <div class="form-group">
                        <label>Telepone:</label><br><br>
                        <input type="number" name="telp" placeholder="Telepone" class="input-control" required>
                    </div><br>
                    <div class="form-group">
                        <label>Password</label><br><br>
                        <input type="password" name="pass" placeholder="password" class="input-control">
                    </div><br>
                    <button type="submit" name="submit" class="btn">DAFTAR</button>
                </form>
            </div>
            <div class="box-footer text-center">
                <a href="index.php">Halaman Utama</a>
            </div>
        </div>
    </div>
</body>
</html>
