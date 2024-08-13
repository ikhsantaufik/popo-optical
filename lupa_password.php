<?php
session_start();
include 'connection.php';
$identitas = mysqli_query($conn, "SELECT * FROM setting ORDER BY id DESC LIMIT 1");
$d = mysqli_fetch_object($identitas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lupa Kata Sandi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta charset="utf-8">  
    <link rel="icon" href="upload/identitas/<?= $d->favicon ?>">
    <title>Website <?= $d->nama ?></title>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<body class="body">
    <div class="login">
        <div class="box box-login">
            <div class="box-header text-center" >
                <h1>LUPA KATA SANDI</h1>
            </div>
          
            <div class="box-body">
                <form action="send_email.php" method="post">
                    <div class="form-group">
                        <label>Masukkan username :</label><br><br>
                        <input type="text" name="username" placeholder="Username" class="input-control" required><br><br>
                        <label>Masukkan Email :</label><br><br>
                        <input type="text" name="email" placeholder="Email" class="input-control" required>
                    </div><br>
                    <button type="submit" name="submit" class="btn">KIRIM OTP</button>
                </form>
                <?php
                // Display error message if username and/or email not found
                if(isset($_SESSION['error'])) {
                    echo "<p>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                ?>
            </div>
            <div class="box-footer text-center">
                <a href="login.php">Kembali ke Login</a>
            </div>
        </div>
    </div>
</body>
</html>
