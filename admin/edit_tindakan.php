<?php
include 'header.php';
$no = 1;
if(isset($_GET['id'])) {
    $deskripsi_tindakan="";
    $lampiran="";
    $id_pendaftaran = $_GET['id'];
    $check_query = "SELECT * FROM tindakan WHERE pendaftaran_id = '$id_pendaftaran'";
    $check_result = mysqli_query($conn, $check_query);
    $aTindakan = mysqli_fetch_object($check_result);
    // print_r($aTindakan);
    //data pendaftaran
    $queryPendaftaran = "SELECT * FROM pendaftaran WHERE id_pendaftaran = '$id_pendaftaran'";
    $resultPendaftaran = mysqli_query($conn, $queryPendaftaran);
    $aPendaftaran = mysqli_fetch_object($resultPendaftaran);
    //print_r($aPendaftaran);
    if (mysqli_num_rows($check_result) > 0) {
        $deskripsi_tindakan= $aTindakan->deskripsi_tindakan;
        $lampiran= $aTindakan->file_tindakan;
    }else{
        $insert_query = "INSERT INTO tindakan 
        (id_tindakan, pendaftaran_id, deskripsi_tindakan, file_tindakan, tgl_tindakan, admin_by, user_id, created_date) 
        VALUES 
        ('$id_pendaftaran', '$id_pendaftaran','','',NOW(), '" . $_SESSION['username'] . "', '" . $aPendaftaran->user_id . "', NOW())";
        $query_result = mysqli_query($conn, $insert_query);
    }
}

    // Handle form submission
    if (isset($_POST['submit'])) {
        $deskripsi = addslashes($_POST['deskripsi']);
        //menampung dan validasi data fle tindkan
        if ($_FILES['lampiran_baru']['name'] != '') {
            $filename_lampiran = $_FILES['lampiran_baru']['name'];
            $tmpname_lampiran = $_FILES['lampiran_baru']['tmp_name'];
            $filesize_lampiran = $_FILES['lampiran_baru']['size'];
        
            $formatfile_lampiran = pathinfo($filename_lampiran, PATHINFO_EXTENSION);
            $rename_lampiran = 'lampiran' . time() . '.' . $formatfile_lampiran;
        
            // Type apa saja yang dipakai
            $allowedtype_lampiran = array('png', 'jpg', 'jpeg', 'pdf');
        
            // Jika format tidak sesuai
            if (!in_array($formatfile_lampiran, $allowedtype_lampiran)) {
                echo "<script>window.location='edit_tindakan.php?id=$id_pendaftaran&error=Format File Lampiran Tidak Ditemukan'</script>";
                exit();
            } elseif ($filesize_lampiran > 1000000) {
                echo "<script>window.location='edit_tindakan.php?id=$id_pendaftaran&error=Ukuran File Lampiran Tidak Boleh Lebih Dari 1 MB'</script>";
                exit();
            } else {
                // Jika file baru memiliki nama yang sama dengan file yang sudah ada
                if (file_exists("../upload/lampiran/" . $rename_lampiran)) {
                    echo "<script>window.location='edit_tindakan.php?id=$id_pendaftaran&error=File dengan nama yang sama sudah ada'</script>";
                    exit();
                }
        
                // Hapus file lama jika ada
                if (!empty($lampiran) && file_exists("../upload/lampiran/" . $lampiran)) {
                    if (!unlink("../upload/lampiran/" . $lampiran)) {
                    }
                }     
                // Pindahkan file baru
                move_uploaded_file($tmpname_lampiran, "../upload/lampiran/" . $rename_lampiran);
            }
        } else {
            // Jika tidak ada file yang diunggah, gunakan nama lampiran lama
            $rename_lampiran = $_POST['lampiran_lama'];
        }              
        // Sanitize inputs
        $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
        // Check if id_tindakan exists in the database
        $queryUpdate = "UPDATE tindakan SET deskripsi_tindakan = '$deskripsi', file_tindakan = '$rename_lampiran' WHERE pendaftaran_id = '$id_pendaftaran'";
        $resultUpdate = mysqli_query($conn, $queryUpdate);
        if ($resultUpdate) {
            $deskripsi_tindakan =$deskripsi;
            $lampiran =$rename_lampiran;
           
        } else {
            echo 'Gagal Update: ' . mysqli_error($conn);
        }
    }
?>
<!--Content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                EDIT TINDAKAN PENDAFTARAN
            </div>
            <div class="box-body">
            <?php
		    if(isset($_GET['error'])){
				echo "<div class='alert alert-error'>".$_GET['error']."</div>";
			}
			?>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Deskripsi Tindakan:</label><br><br>
                        <textarea name="deskripsi" placeholder="Deskripsi Tindakan" class="input-control" required><?= isset($deskripsi_tindakan) ? $deskripsi_tindakan : '' ?></textarea>
                    </div><br>
                    <div class="form-group">
                        <label>File Lampiran:</label><br>
                        <div class="horizontal">
                        <?php
                            if(isset($lampiran) && !empty($lampiran)) {
                                $extension = pathinfo($lampiran, PATHINFO_EXTENSION);
                                if($extension === 'pdf') {
                                    // Jika file adalah PDF, gunakan tag <embed> untuk menampilkannya
                                    echo '<embed src="../upload/lampiran/'.$lampiran.'" type="application/pdf" width="500" height="300">';
                                } else {
                                    // Jika bukan PDF, tampilkan sebagai gambar
                                    echo '<img src="../upload/lampiran/'.$lampiran.'" width="100" class="image">';
                                }
                            } else {
                            }
                        ?>
                        </div>
                        <input type="hidden" name="lampiran_lama" value="<?= isset($lampiran_baru) ? $lampiran_baru : '' ?>" class="input-control" required><br>
                        <input type="file" name="lampiran_baru" class="input-control">
                    </div>
                    <button type="button" class="btn" onclick="window.location ='pendaftaran.php'">KEMBALI</button>
                    <button type="submit" name="submit" class="btn">SIMPAN</button>
                </form><br>
            </div>
             <!--content-->
        <div class="box">
            <div class="box-header">
                PESANAN
            </div>
            <div class="box-body">
                <a href="tambah_pesanan.php?id=<?= $id_pendaftaran ?>" title="Tambah Pesanan" type="simpan"><i class="fa fa-plus"></i></a>
                <?php
                if(isset($_GET['success']))
                    echo '<div class ="alert alert-success">Edit Data Berhasil</div>';
                ?>
                <form action="">
                    <div class="input-grup" id="searchForm">
                        <input type="text" id="searchInput" name="pelanggan" placeholder="Pencarian">
                    </div>
                </form>
                <br>
            	<div class="horizontal">
				<table class="table horizontal" id="responsiveTable">
                    <!--untuk membungkus konten bagian judul atau kepala tabel-->
                    <thead>
                        <!--(tabel row)-->
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Tanggal Pesanan</th>
                            <th>biaya_lain</th>
                            <th>Total</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $queryPesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE tindakan_id = '$id_pendaftaran'");
                        if (mysqli_num_rows($queryPesanan) > 0) {
                            while ($p = mysqli_fetch_array($queryPesanan)) {
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p['kode_pesanan']?></td>
                                    <td><?= $p['created_date']?></td>
                                    <td>Rp.<?= number_format($p['biaya_lain'], 0, ",", ".")?></td>
                                    <td>Rp.<?= number_format($p['total_biaya'], 0, ",", ".")?></td>
                                    <td><?= $p['user_id']?></td>
                                    <td><?= $p['status_pesanan']?></td>
                                    <td>
                                        <a href="edit_pesanan.php?id=<?= $p['kode_pesanan'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
                                        <a href="hapus.php?idpesanan=<?= $p['kode_pesanan'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
                                        <a href="detail_pesanan.php?idDetail=<?= $p['kode_pesanan'] ?>" title="Detail Pesanan"><i class="fa-solid fa-circle-info"></i></a>
                                    </td>
                                </tr>
                            <?php }}else{ ?>
                                <td colspan="9">Data Tidak Ada</td>
                        <?php }?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>	
<script>
	$(document).ready(function(){
    function toggleTableClass() {
        if ($(window).width() < 600) {
            $('#responsiveTable').addClass('responsive');
        } else {
            $('#responsiveTable').removeClass('responsive');
        }
    }

    toggleTableClass();

    $(window).resize(function(){
        toggleTableClass();
    });

    // Script for search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#responsiveTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
        if ($('#responsiveTable tbody tr:visible').length == 0) {
            $('#noResultsRow').show();
        } else {
            $('#noResultsRow').hide();
        }
    });
});
</script>
<?php include 'footer.php'?>
