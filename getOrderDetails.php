<?php
// Lakukan koneksi ke database
include 'connection.php';

if (!isset($_GET['kode_pesanan'])) {
    echo json_encode(['error' => 'Kode pesanan tidak ditemukan']);
    exit();
}

$kodePesanan = $_GET['kode_pesanan'];

// Ambil data frame
$queryFrame = mysqli_query($conn, "SELECT * FROM pesanan_frame
                                   JOIN frame ON pesanan_frame.frame_kode = frame.kode_frame
                                   WHERE pesanan_frame.pesanan_kode = '$kodePesanan'");

// Ambil data lensa
$queryLensa = mysqli_query($conn, "SELECT lensa.nama_lensa, pesanan_lensa.jumlah_barang AS jumlah_barang, pesanan_lensa.keterangan 
                                   FROM pesanan_lensa
                                   JOIN lensa ON pesanan_lensa.lensa_kode = lensa.kode_lensa
                                   WHERE pesanan_lensa.pesanan_kode = '$kodePesanan'");

// Array untuk menyimpan data frame dan lensa
$frames = [];
$lensas = [];

// Memasukkan data frame ke dalam array
while ($frame = mysqli_fetch_assoc($queryFrame)) {
    $frames[] = $frame;
}

// Memasukkan data lensa ke dalam array
while ($lensa = mysqli_fetch_assoc($queryLensa)) {
    $lensas[] = $lensa;
}

echo json_encode(['frames' => $frames, 'lensas' => $lensas]);
?>
