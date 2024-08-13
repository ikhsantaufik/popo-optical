<?php
session_start();
include 'connection.php';

$identitas = mysqli_query($conn, "SELECT * FROM setting ORDER BY id DESC LIMIT 1");
$d = mysqli_fetch_object($identitas);

if(isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    header("Location: register.php?msg=Email tidak ditemukan.");
    exit();
}

// Fungsi untuk mengirim ulang OTP
function resendOTP($conn, $email) {
    // Generate OTP baru
    $new_otp = rand(100000, 999999);
    $_SESSION['otp'] = $new_otp;
    $_SESSION['otp_time'] = time(); // Simpan waktu OTP terakhir dikirim

    // Kirim email OTP baru
    $to = $email;  
    $subject = "Konfirmasi OTP Baru";
    $message = "Kode OTP Anda yang baru: $new_otp";
    $headers = "From: popooptikal2@gmail.com"; // Sesuaikan dengan email Anda

    // Kirim email
    if(mail($to, $subject, $message, $headers)) {
        return true; // Berhasil mengirim ulang OTP
    } else {
        return false; // Gagal mengirim ulang OTP
    }
}

// Jika tombol "Kirim Ulang OTP" diklik
if(isset($_POST['resend'])) {
    // Kirim ulang OTP secara otomatis
    if(resendOTP($conn, $email)) {
        // Berhasil mengirim ulang OTP
        $success_msg = "OTP baru telah dikirim ke email Anda.";
    } else {
        // Gagal mengirim ulang OTP
        $error_msg = "Gagal mengirim ulang OTP. Silakan coba lagi.";
    }
}

if(isset($_POST['submit'])) {
    $otp_entered = $_POST['otp'];

    if(isset($_SESSION['otp']) && $_SESSION['otp'] == $otp_entered) {
        // OTP cocok, aktifkan akun
        $activate_query = "UPDATE akun_pelanggan SET status_user = 'active', konfirmasi_otp = '$otp_entered' WHERE email = '$email'";
        mysqli_query($conn, $activate_query);
    
        // Hapus sesi OTP
        unset($_SESSION['otp']);
        unset($_SESSION['email']);
    
        // Redirect ke halaman login
        header("Location: login.php?berhasil=Akun berhasil diaktifkan. Silakan masuk.");
        exit();
    } else {
        // OTP tidak cocok
        $error_msg = "Kode OTP tidak valid. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verifikasi OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta charset="utf-8">  
    <link rel="icon" href="upload/identitas/<?= $d->favicon ?>">
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body class="body">
    <div class="login">
        <div class="box box-login">
            <div class="box-header text-center">
                <h1>VERIFIKASI OTP</h1>
            </div>
            <div class="box-body">
                <?php if(isset($error_msg)): ?>
                <div class='alert alert-error'><?= $error_msg ?></div>
                <?php endif; ?>
                <?php if(isset($success_msg)): ?>
                <div class='alert alert-success'><?= $success_msg ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Masukkan Kode OTP yang Anda terima melalui email:</label><br><br>
                        <input type="text" name="otp" placeholder="OTP" class="input-control" required>
                    </div><br>
                    <button type="submit" name="submit" class="btn">Verifikasi OTP</button>
                </form><br>
                <form action="" method="POST">
                    <div class="form-group">
                        <button type="submit" name="resend" class="btn">Kirim Ulang OTP</button>
                    </div>
                </form>
            </div>
            <div class="box-footer text-center">
                <a href="register.php">Halaman Daftar</a>
            </div>
        </div>
    </div>
</body>
</html>
