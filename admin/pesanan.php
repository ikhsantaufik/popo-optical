<?php include 'header.php'; 
$no = 1;?>			
<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                HISTORY PESANAN
            </div>
            <div class="box-body">
                <form action="" id="searchForm" onsubmit="clearDateIfEmpty()">
                    <div class="input-grup" >
                        <input type="text" name="key" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['key']) ? $_GET['key'] : ''; ?>">
                        <input type="date" name="tanggal" placeholder="Pilih Tanggal pendaftaran" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
                        <button type="submit"><i class="fa fa-magnifying-glass"></i></button>
                        <button type="button" class="btn" onclick="clearForm()">Clear</button>
                    </div>
                </form>
                <br>
            	<div class="horizontal">
				<table class="table horizontal" id="responsiveTable">
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
                    $no = 1;
                    $where = "WHERE 1=1 ";

                    if (isset($_GET['tanggal'])) {
                        $tanggal = mysqli_real_escape_string($conn, $_GET['tanggal']);
                        $where .= "AND DATE(created_date) = '$tanggal' ";
                    }
                        $queryPesanan = mysqli_query($conn, "SELECT * FROM pesanan $where ORDER BY kode_pesanan ");
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
    var key = document.getElementById('key').value;
    if (key === '') {
        document.getElementById('key').removeAttribute('name');
    }
}
    function clearForm() {
        document.getElementById('searchForm').reset(); // Gunakan metode reset() untuk mengosongkan form
        window.location.href = 'pesanan.php'; // Ganti 'namafile.php' dengan nama file PHP yang berisi kode
    }
</script>
<?php include 'footer.php'?>
