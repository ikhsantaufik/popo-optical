<?php 
// Pastikan koneksi ke database sudah dibuat sebelumnya
include '../connection.php';

// Periksa apakah metode yang digunakan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah semua data yang diperlukan ada dalam $_POST
    if (isset($_POST['id_tindakan']) && isset($_POST['deskripsi'])) {
        // Escape data input untuk mencegah serangan SQL injection
        $id_tindakan = mysqli_real_escape_string($conn, $_POST['id_tindakan']);
        $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
        
        // Query untuk memperbarui deskripsi tindakan
        $query = "UPDATE tindakan SET deskripsi='$deskripsi' WHERE id_tindakan='$id_tindakan'";
        
        // Eksekusi query
        if (mysqli_query($conn, $query)) {
            // Jika pengeditan berhasil, kembalikan ke halaman sebelumnya dengan pesan sukses
            header("Location: {$_SERVER['HTTP_REFERER']}?success=1");
            exit();
        } else {
            // Jika terjadi kesalahan dalam eksekusi query, tampilkan pesan kesalahan
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    } else {
        // Jika data yang diperlukan tidak lengkap, tampilkan pesan kesalahan
        echo "Data yang diperlukan tidak lengkap";
    }
} else {
    // Jika metode yang digunakan bukan POST, tampilkan pesan kesalahan
    echo "Metode yang digunakan bukan POST";
}
?>