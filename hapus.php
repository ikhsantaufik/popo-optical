<?php include 'connection.php'?>	
<?php

if (isset($_SESSION['pesanan']) && isset($_GET['idproduk'])) {
    // Ambil id produk yang akan dihapus
    $id_produk_hapus = $_GET['idproduk'];

    // Ambil user_id dari session
    $user_id = $_SESSION['username'];

    // Periksa apakah pesanan pengguna ada di session
    if (isset($_SESSION['pesanan'][$user_id])) {
        // Loop melalui setiap item dalam pesanan
        foreach ($_SESSION['pesanan'][$user_id] as $key => $item) {
            // Jika id produk sesuai dengan yang akan dihapus
            if ($item['id_produk'] == $id_produk_hapus) {
                // Hapus item dari pesanan
                unset($_SESSION['pesanan'][$user_id][$key]);
                // Jika tidak ada item lagi dalam pesanan, hapus key pesanan dari session
                if (empty($_SESSION['pesanan'][$user_id])) {
                    unset($_SESSION['pesanan'][$user_id]);
                }
                // Redirect kembali ke halaman pesanan.php setelah menghapus
                header("Location: pesanan.php");
                exit();
            }
        }
    }
}

// Jika tidak ada pesanan atau id produk tidak ditemukan, redirect ke halaman pesanan.php
header("Location: pesanan.php");
exit();
?>

