<?php include 'header.php'; ?>
<?php
// Periksa apakah parameter id valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("location:pendaftaran.php");
    exit(); // Keluar dari skrip
}

// Query untuk mendapatkan data pendaftaran berdasarkan id
$pendaftaran = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_pendaftaran =" . $_GET['id']);

// Periksa apakah query berhasil dieksekusi dan data ditemukan
if ($pendaftaran === false || mysqli_num_rows($pendaftaran) == 0) {
    header("location:pendaftaran.php");
    exit(); // Keluar dari skrip
}

// Ambil data pendaftaran dari hasil query
$p = mysqli_fetch_array($pendaftaran);
?>
<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                EDIT PELANGGAN
            </div>
            <div class="box-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tanggal Pendaftaran :</label><br><br>
                        <input type="date" name="waktu_pendaftaran" value="<?= $p['tgl_pendaftaran'] ?>" class="input-control" readonly>
                    </div><br>
                    <div class="form-group">
                        <label>Keluhan :</label><br><br>
                        <textarea name="keluhan" placeholder="Keluhan" class="input-control" id="keterangan" readonly><?= $p['keluhan'] ?></textarea>
                    </div><br>
                    <div class="form-group">
                        <label>Status :</label><br><br>
                        <select name="status" class="input-control" required>
                            <option value="proses" <?= ($p['status_pendaftaran'] == 'proses') ? 'selected' : '' ?>>Proses</option>
                            <option value="selesai" <?= ($p['status_pendaftaran'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                            <option value="dibatalkan" <?= ($p['status_pendaftaran'] == 'dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                    </div><br>
                    <div class="form-group">
                        <label>Pelanggan :</label><br><br>
                        <!-- Assuming $p['user_id'] is the selected user -->
                        <select name="pelanggan" class="input-control" readonly>
                            <option value="<?= $p['user_id'] ?>"><?= $p['user_id'] ?></option>
                            <?php
                            $pengguna = mysqli_query($conn, "SELECT * FROM akun_pelanggan ORDER BY username DESC");
                            // looping data 
                            while ($data = mysqli_fetch_array($pengguna)) {
                            ?>
                                <option value="<?= $data['username'] ?>"><?= $data['nama'] ?></option>
                            <?php } ?>
                        </select>
                    </div><br>

                    <button type="button" class="btn" onclick="window.location ='pendaftaran.php'">KEMBALI</button>
                    <button type="submit" name="submit" class="btn">SIMPAN</button>
                </form>

                <?php
                // Fungsi untuk mengirim email
                function kirimEmail($emailPelanggan, $subject, $message) {
                    $headers = "From: popooptikal2@gmail.com\r\n";
                    $headers .= "Reply-To: popooptikal2@gmail.com\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                    if (mail($emailPelanggan, $subject, $message, $headers)) {
                        echo "Email berhasil dikirim ke $emailPelanggan";
                    } else {
                        echo "Email gagal dikirim.";
                    }
                }

                // Ketika tombol submit di klik
                if (isset($_POST['submit'])) {
                    $waktu_pendaftaran = $_POST['waktu_pendaftaran'];
                    $keluhan = $_POST['keluhan'];
                    $status = $_POST['status'];
                    $pelanggan = $_POST['pelanggan'];

                    // Lakukan update data pendaftaran
                    $update = mysqli_query($conn, "UPDATE pendaftaran SET 
                        tgl_pendaftaran = '$waktu_pendaftaran',
                        keluhan = '$keluhan',
                        status_pendaftaran = '$status',
                        user_id = '$pelanggan'
                        WHERE id_pendaftaran = " . $_GET['id']
                    );

                    if ($update) {
                        // Dapatkan email pelanggan dari akun_pelanggan berdasarkan pelanggan yang dipilih
                        $pengguna = mysqli_query($conn, "SELECT * FROM akun_pelanggan WHERE username = '$pelanggan'");
                        if (mysqli_num_rows($pengguna) > 0) {
                            $pelanggan_data = mysqli_fetch_assoc($pengguna);
                            $namaPelanggan = $pelanggan_data['nama'];
                            $emailPelanggan = $pelanggan_data['email'];

                            if ($status == 'dibatalkan') {
                                // Kirim email pembatalan
                                $subject = 'Pembatalan Pendaftaran';
                                $message = "Halo $namaPelanggan,\n\nPendaftaran Anda Pada popo optikal 2 dengan ID {$_GET['id']} telah dibatalkan di karenaka jadwal yang tidak memungkinkan.\n\nTerima kasih.";
                                kirimEmail($emailPelanggan, $subject, $message);
                            }
                        } else {
                            echo "Error: Pelanggan tidak ditemukan.";
                        }

                        echo "<script>window.location='pendaftaran.php?success=Edit Data Berhasil'</script>";
                    } else {
                        echo 'Gagal Update Data Pendaftaran: ' . mysqli_error($conn);
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
