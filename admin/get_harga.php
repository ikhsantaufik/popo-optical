<?php
// Lakukan koneksi ke database
include '../connection.php';

if(isset($_GET['jenis'])) {
    $jenis = $_GET['jenis'];
    // Lakukan query ke database untuk mendapatkan opsi yang sesuai berdasarkan jenis
    $query = "";
    if ($jenis === "frame") {
        $query = "SELECT harga_frame as harga FROM frame";
    } else if ($jenis === "lensa") {
        $query = "SELECT harga_lensa as harga FROM lensa";
    }
    $result = mysqli_query($conn, $query);

    $inputs = array();
    while($row = mysqli_fetch_assoc($result)) {
        // Buat array opsi
        $input = array(
            'harga' => $row['harga']
        );
        $inputs[] = $input;
    }
    // Kembalikan opsi dalam format JSON
    echo json_encode($inputs);
}
?>
