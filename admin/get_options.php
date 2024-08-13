<?php
// Lakukan koneksi ke database
include '../connection.php';

if(isset($_GET['jenis'])) {
    $jenis = $_GET['jenis'];
    // Lakukan query ke database untuk mendapatkan opsi yang sesuai berdasarkan jenis dan status ketersediaan
    $query = "";
    if ($jenis === "frame") {
        $query = "SELECT kode_frame as kode, nama_frame as nama, harga_frame as harga FROM frame WHERE status_frame = 'tersedia'";
    } else if ($jenis === "lensa") {
        $query = "SELECT kode_lensa as kode, nama_lensa as nama, harga_lensa as harga FROM lensa WHERE status_lensa = 'tersedia'";
    }
    $result = mysqli_query($conn, $query);

    $options = array();
    while($row = mysqli_fetch_assoc($result)) {
        // Buat array opsi
        $option = array(
            'kode' => $row['kode'],
            'nama' => $row['nama'],
            'harga' => $row['harga']
        );
        $options[] = $option;
    }
    // Kembalikan opsi dalam format JSON
    echo json_encode($options);
}
?>


