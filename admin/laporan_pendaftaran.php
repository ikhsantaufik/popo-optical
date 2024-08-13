<?php include 'header.php'; ?>

<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                LAPORAN PENDAFTARAN
            </div>
            <div class="box-body">
                <form action="" method="GET" id="searchForm" onsubmit="clearDateIfEmpty()">
                    <div class="input-grup">
                        <input type="text" name="all" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['all']) ? $_GET['all'] : ''; ?>">
                        <input type="date" name="tanggal" id="tanggal" value="<?php echo isset($_GET['tanggal']) ? date('Y-m-d', strtotime($_GET['tanggal'])) : ''; ?>">
                        <button type="submit"><i class="fa fa-search"></i></button>
                        <button type="button" class="btn" onclick="clearForm()">Clear</button>
                        <button id="ExportExcel" type="button" class="btn" name="button">Export</button>
                        <button id="PrintExcel" type="button" class="btn" name="button" onclick="printDiv()">Print</button>
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
                            <th>Nomor Antian</th>
                            <th>Pelanggan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $where = "WHERE 1=1 ";

                        if (isset($_GET['tanggal'])) {
                            $tanggal = mysqli_real_escape_string($conn, $_GET['tanggal']);
                            if (!empty($tanggal)) { // Add this condition
                                $where .= "AND DATE(tgl_pendaftaran) = '$tanggal' ";
                            }
                        }

                        $daftar = mysqli_query($conn, "SELECT * FROM pendaftaran $where ORDER BY id_pendaftaran ");

                        if (mysqli_num_rows($daftar) > 0) {
                            while ($p = mysqli_fetch_array($daftar)) {
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p['tgl_pendaftaran'] ?></td>
                                    <td><?= $p['kereterian'] ?></td>
                                    <td><?= $p['tempat_cek'] ?></td>
                                    <td><?= $p['keluhan'] ?></td>
                                    <td><?= $p['no_antrian'] ?></td>
                                    <td><?= $p['user_id'] ?></td>
                                </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr id='noResultsRow'><td colspan='8'>Data Tidak Ada untuk pencarian dan Tanggal yang Dipilih</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Add jQuery library -->
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
$("#ExportExcel").click(function(e) {
        var a = document.createElement('a');
        //getting data from our div that contains the HTML table
        var data_type = 'data:application/vnd.ms-excel';
        var table_div = document.getElementById('responsiveTable');
        var table_html = table_div.outerHTML.replace(/ /g, '%20');
        a.href = data_type + ', ' + table_html;
        //setting the file name
        a.download = 'Laporan_pendaftaran.xls';
        //triggering the function
        a.click();
        //just in case, prevent default behaviour
        e.preventDefault();
    });
    function printDiv() {
    var table_div = document.getElementById('responsiveTable');
    var w = window.open('', '_blank');
    w.document.write('<html><head><title>Laporan Pendaftaran</title>');
    // Menambahkan CSS agar logo muncul di sebelah kiri atas dan memperbaiki tampilan tabel
    w.document.write('<style>');
        w.document.write('body { font-family: Arial, sans-serif; }');
        w.document.write('img { max-width: 70px; }');
        w.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
        w.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
        w.document.write('th { background-color: #f2f2f2; }');
        w.document.write('h3, h5 { margin: 5px 0; }');
        w.document.write('.header, .footer { width: 100%; text-align: center; margin-top: 20px; }');
        w.document.write('.flex-container { display: flex; align-items: center; justify-content: space-between; }');
        w.document.write('</style>');
        w.document.write('</head><body>');

        // Menambahkan header dengan logo dan informasi perusahaan
        w.document.write('<div class="flex-container">');
        w.document.write('<img src="../upload/identitas/<?= $d->logo ?>" alt="Logo Perusahaan">');
        w.document.write('<div class="header">');
        w.document.write('<h3><?php echo $d->nama; ?></h3>');
        w.document.write('<p>J. Wijaya Kusuman 229,RT 003, RW 005, Kel.Sribasuki, Kec.Kotabumi, Kab.Lampung Utara, Lampung. Telp.0895620652076</p>');
        w.document.write('</div>');
        w.document.write('</div>');
        w.document.write('<hr style="border: 1px solid black; height: 1px; background-color: #000; margin: 20px 0;">');

        // Menambahkan tanggal dan waktu
        var currentDate = new Date();
        var dateStr = currentDate.toLocaleDateString();
        var timeStr = currentDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        w.document.write('<div style="text-align:right; margin-bottom:20px;">');
        w.document.write('Tanggal: ' + dateStr + ' | Jam: ' + timeStr);
        w.document.write('</div>');

    // Tabel data
    w.document.write('<table>');
    // Mengambil semua baris dari tabel yang ingin dicetak
    var rows = table_div.querySelectorAll('tr');
    // Menambahkan setiap baris ke dalam tabel pada laporan
    for (var i = 0; i < rows.length; i++) {
        w.document.write('<tr>');
        var cols = rows[i].querySelectorAll('td, th');
        for (var j = 0; j < cols.length; j++) {
            // Menambahkan sel ke dalam baris
            var cellContent = cols[j].innerText || cols[j].textContent;
            if (i === 0) {
                w.document.write('<th>' + cellContent + '</th>'); // Menambahkan header
            } else {
                w.document.write('<td>' + cellContent + '</td>'); // Menambahkan data sel
            }
        }
        w.document.write('</tr>');
    }
    w.document.write('</table>');

    // Footer laporan
    w.document.write('<div class="footer">');
    w.document.write('<p>&copy; ' + new Date().getFullYear() + ' <?php echo $d->nama; ?>. All rights reserved.</p>');
    w.document.write('</div>');

    w.document.write('</body></html>');
    w.document.close();
    w.print();
}

function clearForm() {
    document.getElementById('searchForm').reset();
    window.location.href = 'laporan_pendaftaran.php';
}

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
</script>

<?php include 'footer.php'; ?>



