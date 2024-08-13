<?php
session_start();
include '../connection.php';
$identitas = mysqli_query($conn, "SELECT * FROM setting ORDER BY id DESC LIMIT 1");
$d = mysqli_fetch_object($identitas);

// Periksa apakah sesi OTP sudah diset
if(isset($_SESSION['otp'])) {
    $otp = $_SESSION['otp'];
    $email = $_SESSION['email'];
    $otp_time = $_SESSION['otp_time']; // Timestamp saat OTP dibuat
    $current_time = time(); // Timestamp saat ini
    $elapsed_time = $current_time - $otp_time; // Hitung waktu yang berlalu
    // Jika waktu yang berlalu sama degan 2 menit, buat OTP baru dan perbarui timestamp
    if($elapsed_time == 120) {
        // Buat OTP baru
        $new_otp = rand(100000, 999999);
        // Perbarui variabel sesi
        $_SESSION['username'] = $username;
        $_SESSION['otp'] = $new_otp;
        $_SESSION['otp_time'] = time(); // Perbarui timestamp
        // Alihkan kembali ke halaman verifikasi OTP
        header("Location: verify_otp.php");
        exit();
    }
} else {
    // Jika sesi tidak diset, Alihkan kembali ke halaman lupa kata sandi
    header("Location: lupa_password.php");
    exit();
}

// Periksa apakah formulir sudah disubmit
if(isset($_POST['submit'])) {
    $user_otp = $_POST['otp'];

    // Verifikasi OTP
    if($user_otp == $otp) {
        // Jika OTP benar, Alihkan ke halaman reset kata sandi
        header("Location: reset_password.php");
        exit();
    } else {
        // Jika OTP salah, tampilkan pesan kesalahan
        $error = "OTP Salah!";
    }
}

function sendNewOTP() {
    global $email, $conn;
    // Generate new OTP
    $new_otp = rand(100000, 999999);
    
    // Get username from the database
    $query = "SELECT username FROM akun_pelanggan WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    
    // Update session variables
    $_SESSION['username'] = $username;
    $_SESSION['otp'] = $new_otp;
    $_SESSION['otp_time'] = time(); // Update timestamp
    
    // Send new OTP to user via email
    $to = $email;
    $subject = "Ubah Password OTP";
    $message = "$new_otp ini adalah kode verifikasi. Untuk keamanan, jangan sebarkan kode ini: ";
    $headers = "From: popooptikal2@gmail.com"; // Replace with your email
    mail($to, $subject, $message, $headers);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Verifikasi OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta charset="utf-8">  
    <link rel="icon" href="../upload/identitas/<?= $d->favicon ?>">
    <title>Website <?= $d->nama ?></title>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<body class="body">
    <div class="login">
        <div class="box box-login">
            <div class="box-header text-center">
                <h1>VERIFIKASI OTP</h1>
            </div>
            <div class="box-body">
                <h2>Masukkan OTP</h2>
                <form action="" method="post">
                    <input type="text" class="input-control" name="otp" placeholder="Masukkan OTP" required>
                    <button type="submit" class="btn" name="submit">Kirim</button>
                </form>
                <?php
                // Tampilkan pesan kesalahan jika OTP salah
                if(isset($error)) {
                    echo "<p>$error</p>";
                }
                ?>
            </div>
        </div>
        <div class="box-footer text-center">
                <form action="" method="post">
                    <button type="submit" class="btn" name="send_email">Kirim Email OTP Baru</button>
                </form>
            </div>
    </div>
    <?php
    // Jika tombol "Kirim Email" ditekan, kirim email OTP baru
    if(isset($_POST['send_email'])) {
        sendNewOTP();
        echo "<p>Email OTP baru telah dikirimkan!</p>";
    }
    ?>
</body>
</html>
