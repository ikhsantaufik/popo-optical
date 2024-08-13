<?php include 'header.php'; ?>

<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                PENDAFTARAN
            </div>
            <div class="box-body">
                <a href="tambah_pendaftaran.php"><i class="fa fa-plus">Tambah</i></a>
                <?php
                if(isset($_GET['success'])){
                    echo "<div class='alert alert-success'>".$_GET['success']."</div>";
                }
                ?>
                <form action="" id="searchForm" onsubmit="clearDateIfEmpty()">
                    <div class="input-grup">
                        <input type="text" name="key" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['key']) ? $_GET['key'] : ''; ?>">
                        <input type="date" name="tanggal" placeholder="Pilih Tanggal"value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
                        <button type="submit"><i class="fa fa-magnifying-glass"></i></button>
                        <button type="button" class="btn" onclick="clearForm()">Clear</button>
                    </div>
                </form>
                <br>
            	<div class="horizontal">
				<table class="table horizontal" id="responsiveTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>Kriteria</th>
                            <th>Tempat Cek</th>
                            <th>Keluhan</th>
                            <th>Jam Kunjugan</th>
                            <th>Nomor Antian</th>
                            <th>Status</th>
                            <th>Pelanggan</th>
                            <th>Created</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $where = "WHERE 1=1 ";

                        if (isset($_GET['tanggal'])) {
                            $tanggal = mysqli_real_escape_string($conn, $_GET['tanggal']);
                            $where .= "AND DATE(created_date) = '$tanggal' ";
                        }

                        $daftar = mysqli_query($conn, "SELECT * FROM pendaftaran $where ORDER BY id_pendaftaran ");

                        if (mysqli_num_rows($daftar) > 0) {
                            while ($p = mysqli_fetch_array($daftar)) {

                                $color="#000";
                                if($p['status_pendaftaran']=="proses"){
                                    $color="#9febfc";
                                }elseif($p['status_pendaftaran']=="selesai"){
                                    $color="#a5fc9f";
                                }elseif($p['status_pendaftaran']=="dibatalkan"){
                                    $color="#fca29f";
                                }
                        ?>
                                <tr style="background-color: <?=$color?>;">
                                    <td><?= $no++ ?></td>
                                    <td><?= $p['tgl_pendaftaran'] ?></td>
                                    <td><?= $p['kereterian'] ?></td>
                                    <td><?= $p['tempat_cek'] ?></td>
                                    <td><?= $p['keluhan'] ?></td>
                                    <td><?= $p['jam_kunjugan'] ?></td>
                                    <td><?= $p['no_antrian'] ?></td>
                                    <td><?= $p['status_pendaftaran'] ?></td>
                                    <td><?= $p['user_id'] ?></td>
                                    <td><?= $p['created_date'] ?></td>
                                    <td>
                                        <a href="edit_pendaftaran.php?id=<?= $p['id_pendaftaran'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
                                        <a href="hapus.php?idpendaftaran=<?= $p['id_pendaftaran'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
                                        <a href="edit_tindakan.php?id=<?= $p['id_pendaftaran'] ?>" title="Detail Tindakan"><i class="fa-regular fa-file"></i></a>
                                    </td>
                                </tr>
                        <?php 
                            } // End of while loop
                        }?>
                            <tr>
                                <td colspan="10"></td>
                            </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
function clearDateIfEmpty() {
    var tanggal = document.getElementById('tanggal').value;
    if (tanggal === '') {
        document.getElementById('tanggal').removeAttribute('name');
    }
    var all = document.getElementById('all').value;
    if (all === '') {
        document.getElementById('all').removeAttribute('name');
    }
}
    function clearForm() {
        document.getElementById('searchForm').reset(); // Gunakan metode reset() untuk mengosongkan form
        window.location.href = 'pendaftaran.php'; // Ganti 'namafile.php' dengan nama file PHP yang berisi kode
    }
</script>
<?php include 'footer.php'; ?>


