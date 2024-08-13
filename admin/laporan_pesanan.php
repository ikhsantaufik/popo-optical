<?php include 'header.php'; ?>
<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                LAPORAN PESANAN
            </div>
            <div class="box-body">
                <?php
                if (isset($_GET['success'])) {
                    echo "<div class='alert alert-success'>" . $_GET['success'] . "</div>";
                }
                ?>
                <form action="" method="GET" id="searchForm" onsubmit="clearDateIfEmpty()">
                    <div class="input-grup">
                        <input type="text" name="all" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['all']) ? $_GET['all'] : ''; ?>">
                        <input type="date" name="tanggal" id="tanggal" value="<?php echo isset($_GET['tanggal']) ? date('Y-m-d', strtotime($_GET['tanggal'])) : ''; ?>">
                        <button type="submit"><i class="fa fa-magnifying-glass"></i></button>
                        <button type="button" class="btn" onclick="clearForm()">Clear</button>
                        <button id="ExportExcel" type="button" class="btn" name="button">Export Laporan</button>
                        <button id="PrintExcel" type="button" class="btn" name="button" onclick="printDiv('responsiveTable')">Print Laporan</button>
                    </div>
                </form>
                <br>
                <div class="horizontal">
                    <table class="table horizontal" id="responsiveTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Pesanan</th>
                                <th>Kode Pesanan</th>
                                <th>Produk Pesanan</th>
                                <th>Biaya Lain</th>
                                <th>Total Biaya</th>
                                <th>Pelanggan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $where = "WHERE 1=1 ";

                            if (isset($_GET['tanggal'])) {
                                $tanggal = mysqli_real_escape_string($conn, $_GET['tanggal']);
                                if (!empty($tanggal)) {
                                    $where .= "AND DATE(created_date) = '$tanggal' ";
                                }
                            }

                            $queryPesanan = mysqli_query($conn, "SELECT * FROM pesanan $where ORDER BY created_date DESC");

                            if (mysqli_num_rows($queryPesanan) > 0) {
                                while ($p = mysqli_fetch_array($queryPesanan)) {
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $p['created_date']?></td>
                                        <td><?= $p['kode_pesanan']?></td>
                                        <td>
                                            <?php 
                                            $produk_pesanan = '';

                                            // Query untuk mendapatkan daftar frame berdasarkan kode pesanan
                                            $queryFrame = mysqli_query($conn, "SELECT frame.nama_frame FROM pesanan_frame
                                                                            JOIN frame ON pesanan_frame.frame_kode = frame.kode_frame
                                                                            WHERE pesanan_frame.pesanan_kode = '".$p['kode_pesanan']."'");                    

                                            // Menyimpan nama frame ke dalam array
                                            while ($frame = mysqli_fetch_array($queryFrame)) {
                                                $produk_pesanan .= $frame['nama_frame'] . ', ';
                                            }

                                            // Query untuk mendapatkan daftar lensa berdasarkan kode pesanan
                                            $queryLensa = mysqli_query($conn, "SELECT lensa.nama_lensa FROM pesanan_lensa
                                                                            JOIN lensa ON pesanan_lensa.lensa_kode = lensa.kode_lensa
                                                                            WHERE pesanan_lensa.pesanan_kode = '".$p['kode_pesanan']."'");

                                            // Menyimpan nama lensa ke dalam array
                                            while ($lensa = mysqli_fetch_array($queryLensa)) {
                                                $produk_pesanan .= $lensa['nama_lensa'] . ', ';
                                            }

                                            // Menghapus koma ekstra di akhir
                                            $produk_pesanan = rtrim($produk_pesanan, ', ');
                                            
                                            echo $produk_pesanan;
                                            ?>
                                        </td>                   
                                        <td>Rp.<?= number_format($p['biaya_lain'], 0, ",", ".")?></td>
                                        <td>Rp.<?= number_format($p['total_biaya'], 0, ",", ".")?></td>
                                        <td><?= $p['user_id']?></td>
                                    </tr>
                                    <?php 
                                }
                            } else {
                                echo "<tr id='noResultsRow'><td colspan='8'>Data Tidak Ada</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="keuntungan">
                    <?php
                    // Pemasukan Harian
                    $queryKeuntunganHarian = mysqli_query($conn, "SELECT SUM(total_biaya) as total_harian FROM pesanan WHERE DATE(created_date) = CURDATE()");
                    $dataHarian = mysqli_fetch_assoc($queryKeuntunganHarian);
                    $totalHarian = $dataHarian['total_harian'] ?: 0;

                    // Pemasukan Bulanan
                    $queryKeuntunganBulanan = mysqli_query($conn, "SELECT YEAR(created_date) as tahun, MONTH(created_date) as bulan, DAY(created_date) as hari, SUM(total_biaya) as total_bulanan FROM pesanan WHERE YEAR(created_date) = YEAR(CURDATE()) GROUP BY YEAR(created_date), MONTH(created_date), DAY(created_date)");

                    // Pemasukan Tahunan
                    $queryKeuntunganTahunan = mysqli_query($conn, "SELECT SUM(total_biaya) as total_tahunan FROM pesanan WHERE YEAR(created_date) = YEAR(CURDATE())");
                    $dataTahunan = mysqli_fetch_assoc($queryKeuntunganTahunan);
                    $totalTahunan = $dataTahunan['total_tahunan'] ?: 0;
                    ?>
                    <br>
                    <form action="" method="GET" id="searchForm" onsubmit="clearDateIfEmpty()">
                        <div class="input-grup">
                            <button id="ExportExcelPemasukan" type="button" class="btn" name="button">Export Pemasukan</button>
                            <button id="PrintExcelPemasukan" type="button" class="btn" name="button" onclick="printDiv('responsiveTablePemasukan')">Print Pemasukan</button>
                        </div>
                    </form><br>
                    <h3>Pemasukan</h3><br>
                    <div class="horizontal">
                        <table class="table horizontal" id="responsiveTablePemasukan">
                            <thead>
                                <tr>
                                    <th>Tahun</th>
                                    <th>Bulan</th>
                                    <th>Tanggal</th>
                                    <th>Total Pemasukan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $bulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                                $currentYear = null;
                                $currentMonth = null;

                                while ($dataBulanan = mysqli_fetch_assoc($queryKeuntunganBulanan)) {
                                    $tahun = $dataBulanan['tahun'];
                                    $bulan = $dataBulanan['bulan'];
                                    $hari = $dataBulanan['hari'];
                                    $totalBulanan = $dataBulanan['total_bulanan'] ?: 0;

                                    if ($tahun !== $currentYear) {
                                        $currentYear = $tahun;
                                        echo "<tr><td><strong>Tahun: $tahun</strong></td><td></td><td></td><td></td></tr>";
                                    }

                                    if ($bulan !== $currentMonth) {
                                        $currentMonth = $bulan;
                                        echo "<tr><td></td><td><strong>Bulan: " . $bulanIndo[$bulan - 1] . "</strong></td><td></td><td></td></tr>";
                                    }
                                ?>
                                <tr>
                                    <td><?= $tahun ?></td>
                                    <td><?= $bulanIndo[$bulan - 1] ?></td>
                                    <td><?= $hari ?></td>
                                    <td>Rp.<?= number_format($totalBulanan, 0, ",", ".") ?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="3">Pemasukan Tahun <?php echo date('Y'); ?></td>
                                    <td>Rp.<?= number_format($totalTahunan, 0, ",", ".") ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    function toggleTableClass() {
        if ($(window).width() < 600) {
            $('#responsiveTable').addClass('responsive');
            $('#responsiveTablePemasukan').addClass('responsive');
        } else {
            $('#responsiveTable').removeClass('responsive');
            $('#responsiveTablePemasukan').removeClass('responsive');
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

$("#ExportExcel").click(function(e) {
    exportToExcel('responsiveTable', 'laporan_pesanan.xls');
});

$("#ExportExcelPemasukan").click(function(e) {
    exportToExcel('responsiveTablePemasukan', 'laporan_pemasukan.xls');
});

function printDiv(tableId) {
    var table_div = document.getElementById(tableId);
    var w = window.open();
    w.document.write('<html><head><title>Laporan</title>');
    w.document.write('<style>');
    w.document.write('<link href="../css/style.css" rel="stylesheet" type="text/css">');
    w.document.write('body { font-family: Arial, sans-serif; }');
    w.document.write('img { max-width: 70px; }');
    w.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
    w.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
    w.document.write('th { background-color: #f2f2f2; }');
    w.document.write('h3, h5 { margin: 5px 0; }');
    w.document.write('.header, .footer { width: 100%; text-align: center; margin-top: 20px; }');
    w.document.write('.flex-container { display: flex; align-items: center; justify-content: space-between; }');
    w.document.write('.horizontal { overflow-x: auto; }'); // Add horizontal scrolling
    w.document.write('.horizontal table { min-width: 100%; }'); // Ensure table takes full width
    w.document.write('</style>');
    w.document.write('</head><body>');
    w.document.write('<div class="flex-container">');
    w.document.write('<img src="../upload/identitas/<?= $d->logo ?>" alt="Logo Perusahaan">');
    w.document.write('<div class="header">');
    w.document.write('<h3><?php echo $d->nama; ?></h3>');
    w.document.write('<p>J. Wijaya Kusuman 229,RT 003, RW 005, Kel.Sribasuki, Kec.Kotabumi, Kab.Lampung Utara, Lampung. Telp.0895620652076</p>');
    w.document.write('</div>');
    w.document.write('</div>');
    w.document.write('<hr style="border: 1px solid black; height: 1px; background-color: #000; margin: 20px 0;">');
    var currentDate = new Date();
    var dateStr = currentDate.toLocaleDateString();
    var timeStr = currentDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    w.document.write('<div style="text-align:right; margin-bottom:20px;">');
    w.document.write('Tanggal: ' + dateStr + ' | Jam: ' + timeStr);
    w.document.write('</div>');
    w.document.write('<table>');
    var rows = table_div.querySelectorAll('tr');
    for (var i = 0; i < rows.length; i++) {
        w.document.write('<tr>');
        var cols = rows[i].querySelectorAll('td, th');
        for (var j = 0; j < cols.length; j++) {
            var cellContent = cols[j].innerText || cols[j].textContent;
            if (i === 0) {
                w.document.write('<th>' + cellContent + '</th>');
            } else {
                w.document.write('<td>' + cellContent + '</td>');
            }
        }
        w.document.write('</tr>');
    }
    w.document.write('</table>');
    w.document.write('<div class="footer">');
    w.document.write('<p>&copy; ' + new Date().getFullYear() + ' <?php echo $d->nama; ?>. All rights reserved.</p>');
    w.document.write('</div>');
    w.document.write('</body></html>');
    w.document.close();
    w.print();
}

function exportToExcel(tableId, fileName) {
    var a = document.createElement('a');
    var data_type = 'data:application/vnd.ms-excel';
    var table_div = document.getElementById(tableId);
    var table_html = table_div.outerHTML.replace(/ /g, '%20');
    a.href = data_type + ', ' + table_html;
    a.download = fileName;
    a.click();
}

function clearForm() {
    document.getElementById('searchForm').reset();
    window.location.href = 'laporan_pesanan.php';
}

function clearDateIfEmpty() {
    var tanggal = document.getElementById('tanggal').value;
    if (tanggal === '') {
        document.getElementById('tanggal').removeAttribute('name');
    }
}
</script>
<?php include 'footer.php'; ?>
