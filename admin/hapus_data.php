<?php
// Lakukan koneksi ke database
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah 'id_pesanan_produk' disetel dalam $_POST
        // Ambil nilai dari 'id_pesanan_produk'
        $id_pesanan_produk = $_POST['id_pesanan_produk'];
        $typeHapus = $_POST['typeHapus'];
        // Periksa jenis barang berdasarkan indeks
    if(isset($_POST['id_pesanan_produk'])) {
        echo $typeHapus;
        if ($typeHapus === "frame") {
            // Update atau insert data ke tabel pesanan_frame
            $query_delete = "DELETE FROM pesanan_frame WHERE id_pesanan_frame = $id_pesanan_produk";
        } elseif ($typeHapus === "lensa") {
            // Update or insert data into pesanan_lensa table
            $query_delete = "DELETE FROM pesanan_lensa WHERE id_pesanan_lensa = $id_pesanan_produk";
        } 
        // Eksekusi query jika tidak kosong
        if(!empty($query_delete)) {
            $result = mysqli_query($conn, $query_delete);
            if (!$result) {
                echo "Error: " . mysqli_error($conn); // Tampilkan kesalahan MySQL
            }
        } else {
            echo "Tidak ada query yang valid untuk dieksekusi."; // Debugging
        }
    }
}
?>



