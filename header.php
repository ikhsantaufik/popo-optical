<?php 
session_start(); // Start the session
include 'connection.php';
// Cek apakah ada cookie pesanan

if(isset($_COOKIE['pesanan'])){
    // Ambil data pesanan dari cookie dan simpan ke dalam session
    $_SESSION['pesanan'] = json_decode($_COOKIE['pesanan'], true);
}
// Memeriksa apakah pengguna memiliki peran atau status yang tidak diizinkan
if(isset($_SESSION['urole']) && ($_SESSION['urole'] == 'admin' || $_SESSION['urole'] == 'pemilik')) {
    // Jika pengguna memiliki peran admin atau pemilik, arahkan mereka kembali atau tampilkan pesan kesalahan
    header("location:index.php"); // Misalnya, arahkan kembali ke halaman utama
    exit();
}
date_default_timezone_set("Asia/Jakarta");
$identitas = mysqli_query($conn, "SELECT * FROM setting ORDER BY id DESC LIMIT 1");
$d = mysqli_fetch_object($identitas);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="icon" href="upload/identitas/<?= $d->favicon ?>">
    <title>Website <?= $d->nama ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <!--pengaturan tint editor untuk keterangan-->
    <script src="https://cdn.tiny.cloud/1/bbsi9ju4ebqbi67sod54b9xm6xktbvztpgvvbldtmq5pavai/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#keterangan',
            height: 300, // Set your preferred height
            plugins: 'autoresize',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            menubar: false,
            statusbar: false,
            resize: 'both',
            autoresize_bottom_margin: 16
        });
    </script>
    <style>
       .dropdown a {
        color: #f2f2f2;
        margin-top: 15px;
    }
    </style>
</head>

<body>
    <!-- box menu mobile-->
    <div class="box-menu-mobile" id="mobileMenu">
        <span onClick="hideMobileMenu()">Close</span><br><br>
        <ul class="text-center" id="mobileMenu">
            <li><a href="index.php">BERANDA</a></li>
            <li><a href="frame.php">FRAME</a></li>
            <li><a href="lensa.php">LENSA</a></li>
            <li><a href="informasi.php">INFORMASI</a></li>
            <li><a href="kontak.php">KONTAK</a></li>
            <?php if (!isset($_SESSION['status_login'])) { ?>
                <li><a href="login.php">MASUK</a></li>
            <?php } else { ?>
                <?php if ($_SESSION['status_login'] == true) { ?>
                    <div class="dropdown">
                        <a href="#"><?= $_SESSION['unama'] ?><i class="fa fa-caret-down"></i></a>
                        <!--sub menu-->
                        <ul class="dropdown-utama">
                            <li><a href="ubah_profil.php">UBAH PROFIL</a></li>
                            <li><a href="pendaftaran.php">PENDAFTARAN</a></li>
                            <li><a href="riwayat_pendaftaran.php">RIWAYAT PENDAFARAN</a></li>
                            <li><a href="riwayat_pesanan.php">RIWAYAT PESANAN</a></li>
                            <li><a href="ubah_password.php">UBAH PASSWORD</a></li>
                            <li><a href="logout.php">LOGOUT</a></li>
                        </ul>
                    </div>
                <?php }} ?>
        </ul>
    </div>
    <div class="header">
        <!-- container-->
        <div class="container-utama">
            <div class="header-logo">
                <img src="upload/identitas/<?= $d->logo ?>" width="70">
                <h2><a href="index.php"><?= $d->nama ?></a></h2>
            </div>
            <ul class="header-menu">
                <li><a href="index.php">BERANDA</a></li>
                <li><a href="frame.php">FRAME</a></li>
                <li><a href="lensa.php">LENSA</a></li>
                <li><a href="informasi.php">INFORMASI</a></li>
                <li><a href="kontak.php">KONTAK</a></li>
                <?php if (!isset($_SESSION['status_login'])) { ?>
                    <li><a href="login.php">MASUK</a></li>
                <?php } else { ?>
                    <?php if ($_SESSION['status_login'] == true) { ?>
                        <div class="dropdown">
                            <a href="#"><?= $_SESSION['unama'] ?><i class="fa fa-caret-down"></i></a>
                            <!--sub menu-->
                            <ul class="dropdown-utama">
                                <li><a href="ubah_profil.php">UBAH PROFIL</a></li>
                                <li><a href="pendaftaran.php">PENDAFTARAN</a></li>
                                <li><a href="riwayat_pendaftaran.php">RIWAYAT PENDAFARAN</a></li>
                                <li><a href="riwayat_pesanan.php">RIWAYAT PESANAN</a></li>
                                <li><a href="ubah_password.php">UBAH PASSWORD</a></li>
                                <li><a href="logout.php">LOGOUT</a></li>
                            </ul>
                        </div>
                    <?php }} ?>
            </ul>
        </div>
        <div class="mobile-menu-btn text-center">
            <a href="#" onClick="showMobileMenu()">MENU</a>
        </div>
    </div>
    <script>
        function showMobileMenu() {
            var mobileMenu = document.getElementById("mobileMenu");
            if (mobileMenu) {
                mobileMenu.style.display = "block";
            }
        }
    </script>
