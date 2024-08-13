<?php 
include 'connection.php';
session_start();
// Redirect ke halaman login jika pengguna tidak logged in
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("location: login.php?msg=Harap Login Terlebih Dahulu!");
    exit();
}

// Ambil data dari formulir
$kriteria = $_POST['kriteria'];
$tempat_cek = $_POST['tempat_cek'];
$keluhan = $_POST['keluhan'];
$waktu_pendaftaran = $_POST['waktu_pendaftaran'];
$jam_kunjugan = $_POST['jam_kunjugan'];

// Mendapatkan nomor antrian berikutnya
$today = date("Y-m-d");
$result = $conn->query("SELECT MAX(no_antrian) AS max_antrian, tgl_pendaftaran FROM pendaftaran WHERE tgl_pendaftaran = '$waktu_pendaftaran'");
$row = $result->fetch_assoc();
if ($row["max_antrian"] === null || date("Y-m-d", strtotime($waktu_pendaftaran)) != $row["tgl_pendaftaran"]) {
    // Jika belum ada pendaftaran pada hari itu atau tanggal pendaftaran tidak sama dengan tanggal hari ini
    $next_number = 1;
} else {
    // Jika sudah ada pendaftaran pada hari itu, nomor antrian adalah nomor antrian terakhir ditambah 1
    $next_number = $row["max_antrian"] + 1;
    // Cek apakah nomor antrian yang didapat sudah digunakan, jika sudah, tambahkan 1 lagi
    $result_check = $conn->query("SELECT no_antrian FROM pendaftaran WHERE tgl_pendaftaran = '$today' AND no_antrian = $next_number");
    if ($result_check->num_rows > 0) {
        $next_number++;
    }
}

// Menyimpan data pendaftaran ke database
$sql = "INSERT INTO pendaftaran (user_id, kereterian, tempat_cek, keluhan, no_antrian, tgl_pendaftaran, jam_kunjugan) VALUES ('" . $_SESSION['username'] . "', '$kriteria', '$tempat_cek', '$keluhan', $next_number, '$waktu_pendaftaran', '$jam_kunjugan')";
if ($conn->query($sql) === TRUE) {
    $queryPelanggan = $conn->query("SELECT * FROM akun_pelanggan WHERE username = '" . $_SESSION['username'] . "' LIMIT 1");
    if ($queryPelanggan->num_rows > 0) {
        $pelanggan = $queryPelanggan->fetch_assoc();
        $namaPelanggan = $pelanggan['nama'];
        $alamatPelanggan = $pelanggan['alamat'];
        $telpPelanggan = $pelanggan['telp'];
        $emailPelanggan = $pelanggan['email'];
    } else {
        $namaPelanggan = '';
        $alamatPelanggan = '';
        $telpPelanggan = '';
        $emailPelanggan = '';
    }

    $to_email = 'popooptikal2@gmail.com';
    $subject = 'Konfirmasi Pendaftaran';
    $message = "Pelanggan atas nama: $namaPelanggan\n";
    $message .= "Alamat Pelanggant: $alamatPelanggan\n";
    $message .= "Nomor Telepon: $telpPelanggan\n";
    $message .= "Email: $emailPelanggan\n";
    $message .= "Nomor Antrian: $next_number\n";
    $message .= "Kriteria: $kriteria\n";
    $message .= "Tempat Cek: $tempat_cek\n";
    $message .= "Keluhan: $keluhan\n";
    $message .= "Waktu Pendaftaran: $waktu_pendaftaran\n";
    $message .= "Jam Kunjungan: $jam_kunjugan\n";
    $headers = "From: $emailPelanggan";

    if (mail($to_email, $subject, $message, $headers)) {
        header("Location: riwayat_pendaftaran.php?msg=Pendaftaran berhasil! Nomor Antrian Anda adalah: $next_number");
    } else {
        echo "Error saat mengirim email konfirmasi.";
    }
}
$conn->close();
