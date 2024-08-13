<?php
include '../connection.php';
session_start();
if (!isset($_SESSION['status_login'])) {
    header("location:../admin/login_admin.php?msg=Harap Login Terlebih Dahulu!");
}
if ($_SESSION['urole'] == 'pemilik' || $_SESSION['urole'] == 'admin') {
    // lanjutkan
} else {
    header("location:../admin/login_admin.php?msg=Harap Login Terlebih Dahulu!");
}

// Ambil data dari formulir
$kriteria = $_POST['kriteria'];
$tempat_cek = $_POST['tempat_cek'];
$keluhan = $_POST['keluhan'];
$pelanggan = $_POST['pelanggan'];
$status = $_POST['status'];
$waktu_pendaftaran = $_POST['waktu_pendaftaran'];
$jam_kunjugan = $_POST['jam_kunjugan'];

// Dapatkan email pelanggan dari akun_pelanggan berdasarkan pelanggan yang dipilih
$pengguna = mysqli_query($conn, "SELECT * FROM akun_pelanggan WHERE username = '$pelanggan'");
if (mysqli_num_rows($pengguna) > 0) {
    $pelanggan_data = mysqli_fetch_assoc($pengguna);
    $namaPelanggan = $pelanggan_data['nama'];
    $emailPelanggan = $pelanggan_data['email'];
} else {
    $namaPelanggan = '';
    $emailPelanggan = '';
}

// Mendapatkan nomor antrian berikutnya
$today = date("Y-m-d");
$result = $conn->query("SELECT MAX(no_antrian) AS max_antrian, tgl_pendaftaran FROM pendaftaran WHERE tgl_pendaftaran = '$waktu_pendaftaran'");
$row = $result->fetch_assoc();
if ($row["max_antrian"] === null || date("Y-m-d", strtotime($waktu_pendaftaran)) != $row["tgl_pendaftaran"]) {
    $next_number = 1;
} else {
    $next_number = $row["max_antrian"] + 1;
    $result_check = $conn->query("SELECT no_antrian FROM pendaftaran WHERE tgl_pendaftaran = '$today' AND no_antrian = $next_number");
    if ($result_check->num_rows > 0) {
        $next_number++;
    }
}

// Menyimpan data pendaftaran ke database
$sql = "INSERT INTO pendaftaran (user_id, kereterian, tempat_cek, keluhan, jam_kunjugan, no_antrian, status_pendaftaran, tgl_pendaftaran) VALUES ('$pelanggan', '$kriteria', '$tempat_cek', '$keluhan', '$jam_kunjugan', $next_number, '$status', '$waktu_pendaftaran')";
if ($conn->query($sql) === TRUE) {
    echo "<script>window.location='pendaftaran.php?success=Pendaftaran berhasil! Nomor Antrian Anda adalah: $next_number'</script>";

    $check_tindakan = $conn->query("SELECT * FROM tindakan WHERE user_id = '$pelanggan'");
    if ($check_tindakan->num_rows == 0) {
        $deskripsi = mysqli_real_escape_string($conn, $tempat_cek);
        $last_id = "INSERT INTO tindakan (user_id, tgl_tindakan, deskripsi_tindakan, admin_by) VALUES ('$pelanggan', NOW(), '$deskripsi', '" . $_SESSION['username'] . "')";
        if ($conn->query($last_id) === TRUE) {
            echo "Tindakan berhasil ditambahkan!";
        } else {
            echo "Error saat menambahkan tindakan: " . $conn->error;
        }
    }

    // Kirim email
    $to_email = $emailPelanggan;
    $subject = 'Konfirmasi Pendaftaran';

    $message = "Selamat Pelanggan atas nama: $namaPelanggan\n";
    $message .= "Sudah terdaftar dengan Nomor Antrian: $next_number\n";
    $message .= "Kriteria: $kriteria\n";
    $message .= "Tempat Cek: $tempat_cek\n";
    $message .= "Keluhan: $keluhan\n";
    $message .= "Waktu Pendaftaran: $waktu_pendaftaran\n";
    $message .= "Jam Kunjungan: $jam_kunjugan\n";

    $headers = "From: popooptikal2@gmail.com\r\n";
    $headers .= "Reply-To: popooptikal2@gmail.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to_email, $subject, $message, $headers)) {
        echo "Email berhasil dikirim ke $to_email";
    } else {
        echo "Email gagal dikirim.";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>