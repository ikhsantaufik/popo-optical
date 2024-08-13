<?php
include 'header.php';
$no = 1;

// Memeriksa status login
if(!isset($_SESSION['status_login'])){
    header("location:../login.php?msg=Harap Login Terlebih Dahulu!");
}
if (!$_SESSION['status_login'] == true) {
    header("location: login.php?msg=Harap Login Terlebih Dahulu!");
    exit();
}

// Ambil data pelanggan dari database
$queryPelanggan = mysqli_query($conn, "SELECT * FROM akun_pelanggan WHERE username = '$_SESSION[username]' LIMIT 1");
if (mysqli_num_rows($queryPelanggan) > 0) {
    $pelanggan = mysqli_fetch_assoc($queryPelanggan);
    $namaPelanggan = $pelanggan['nama'];
    $alamatPelanggan = $pelanggan['alamat'];
    $telpPelanggan = $pelanggan['telp'];
} else {
    $namaPelanggan = '';
    $alamatPelanggan = '';
    $telpPelanggan = '';
}

// Ambil data detail pesanan frame dari database
$queryFrame = mysqli_query($conn, "SELECT * FROM pesanan_frame
                                   JOIN frame ON pesanan_frame.frame_kode = frame.kode_frame
                                   WHERE pesanan_frame.pesanan_kode = '$p->kode_pesanan'");

// Ambil data detail pesanan lensa dari database
$queryLensa = mysqli_query($conn, "SELECT lensa.nama_lensa, pesanan_lensa.jumlah_barang AS jumlah_barang, pesanan_lensa.keterangan 
                                   FROM pesanan_lensa
                                   JOIN lensa ON pesanan_lensa.lensa_kode = lensa.kode_lensa
                                   WHERE pesanan_lensa.pesanan_kode = '$p->kode_pesanan'"); 

// Array untuk menyimpan data frame
$frames = [];
// Array untuk menyimpan data lensa
$lensas = [];

// Memasukkan data frame ke dalam array
while ($frame = mysqli_fetch_assoc($queryFrame)) {
    $frames[] = $frame;
}

// Memasukkan data lensa ke dalam array
while ($lensa = mysqli_fetch_assoc($queryLensa)) {
    $lensas[] = $lensa;
}

// Mengambil data pelanggan
$queryPelanggan = mysqli_query($conn, "SELECT * FROM pesanan WHERE kode_pesanan = '$id_pesanan' LIMIT 1");

// Memeriksa apakah data pelanggan ditemukan
if (mysqli_num_rows($queryPelanggan) > 0) {
    $pelanggan = mysqli_fetch_assoc($queryPelanggan);
    $frame = $pelanggan['nama_frame'];
    $lensa = $pelanggan['nama_lensa'];
    $qtyFrame = $pelanggan['jumlah_barang_frame'];
    $qtyLensa = $pelanggan['jumlah_barang_lensa'];
    $catatanFrame = $pelanggan['keterangan_frame'];
    $catatanLensa = $pelanggan['keterangan_lensa'];
} else {
    // Jika tidak ada data pelanggan
    $frame = '';
    $lensa = '';
    $qtyFrame = '';
    $qtyLensa = '';
    $catatanFrame = '';
    $catatanLensa = '';
}
?>

<!--content-->
<div class="container-utama">
    <div class="box">
        <div class="box-header">
            RIWAYAT PESANAN
        </div>
        <div class="box-body">
            <form action="" id="searchForm" onsubmit="clearDateIfEmpty()">
                <div class="input-grup">
                    <input type="text" name="all" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['all']) ? $_GET['all'] : ''; ?>">
                    <input type="date" name="tanggal" placeholder="Pilih Tanggal pendaftaran" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
                    <button type="submit"><i class="fa fa-magnifying-glass"></i></button>
                    <button type="button" class="btn" onclick="clearForm()">Clear</button>
                </div>
            </form>
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
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $no = 1;
                        $where = " WHERE user_id = '$_SESSION[username]' ";
                        if (isset($_GET['tanggal'])) {
                            $tanggal = mysqli_real_escape_string($conn, $_GET['tanggal']);
                            $where .= "AND DATE(created_date) = '$tanggal' ";
                        }
                        $queryPesanan = mysqli_query($conn, " SELECT * FROM pesanan $where ORDER BY user_id");
                        if (mysqli_num_rows($queryPesanan) > 0) {
                            while ($p = mysqli_fetch_array($queryPesanan)) {
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p['kode_pesanan']?></td>
                                    <td><?= $p['created_date']?></td>
                                    <td>Rp.<?= number_format($p['biaya_lain'], 0, ",", ".")?></td>
                                    <td>Rp.<?= number_format($p['total_biaya'], 0, ",", ".")?></td>
                                    <td><?= $p['status_pesanan']?></td>
                                    <td>
                                        <a href="detail_pesanan.php?idDetail=<?= $p['kode_pesanan'] ?>" title="Detail Pesanan"><i class="fa-solid fa-circle-info"></i></a>
                                        <a href="javascript:void(0);" title="Cetak Dokumen" onclick="printDiv('<?= $p['kode_pesanan'] ?>')"><i class="fa-solid fa-file"></i></a>
                                    </td>
                                </tr>
                            <?php }}else{ ?>
                                <td colspan="8">Data Tidak Ada</td>
                        <?php }?>
                    </tbody>
                </table>
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
    function printDiv(kodePesanan) {
    $.ajax({
        url: 'getOrderDetails.php',
        type: 'GET',
        data: { kode_pesanan: kodePesanan },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.error) {
                alert(data.error);
                return;
            }

            var frames = data.frames;
            var lenses = data.lensas;

            var w = window.open();
            w.document.write('<html><head><title>Laporan Pesanan</title>');
            w.document.write('<style>');
            w.document.write('@page { size: A5; margin: 1cm; }');
            w.document.write('body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.6; }');
            w.document.write('img { max-width: 50px; height: auto; }');
            w.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
            w.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; font-size: 8pt; }');
            w.document.write('th { background-color: #f2f2f2; }');
            w.document.write('h3 { margin: 10px 0; font-size: 12pt; }');
            w.document.write('h5 { margin: 5px 0; font-size: 10pt; }');
            w.document.write('.header, .footer { width: 100%; text-align: center; margin-top: 20px; }');
            w.document.write('.flex-container { display: flex; align-items: center; justify-content: space-between; }');
            w.document.write('.company-info { margin-left: 20px; }');
            w.document.write('</style>');
            w.document.write('</head><body>');

            w.document.write('<div class="flex-container">');
            w.document.write('<img src="../upload/identitas/<?= $d->logo ?>" alt="Logo Perusahaan">');
            w.document.write('<div class="header">');
            w.document.write('<br><br><h3><?php echo $d->nama; ?></h3>');
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

            var namaPelanggan = "<?php echo $namaPelanggan; ?>";
            var alamatPelanggan = "<?php echo $alamatPelanggan; ?>";
            var telpPelanggan = "<?php echo $telpPelanggan; ?>";
            w.document.write('<div>');
            w.document.write('<h5>Nama : ' + namaPelanggan + '</h5>');
            w.document.write('<h5>Alamat : ' + alamatPelanggan + '</h5>');
            w.document.write('<h5>No telfone : ' + telpPelanggan + '</h5>');
            w.document.write('</div>');

            w.document.write('<h3 style="text-align:center;">Laporan Pesanan</h3>');

            w.document.write('<table>');

            w.document.write('<tr>');
            w.document.write('<th>Kode Pesanan</th>');
            w.document.write('<th>Tanggal Pesanan</th>');
            w.document.write('<th>Biaya Lain</th>');
            w.document.write('<th>Total</th>');
            w.document.write('</tr>');

            $('#responsiveTable tbody tr').each(function() {
                var kodePesananBaris = $(this).find('td:eq(1)').text();
                if (kodePesananBaris === kodePesanan) {
                    var cols = $(this).find('td');
                    w.document.write('<tr>');
                    w.document.write('<td>' + cols.eq(1).text() + '</td>');
                    w.document.write('<td>' + cols.eq(2).text() + '</td>');
                    w.document.write('<td>' + cols.eq(3).text() + '</td>');
                    w.document.write('<td>' + cols.eq(4).text() + '</td>');
                    w.document.write('</tr>');
                }
            });

            w.document.write('</table>');
            w.document.write('<br><h3>Informasi Pesanan</h3>');

            w.document.write('<table>');
            w.document.write('<tr>');
            w.document.write('<th>Nama Frame</th>');
            w.document.write('<th>Jumlah Barang</th>');
            w.document.write('<th>Keterangan</th>');
            w.document.write('</tr>');
            frames.forEach(function(frame) {
                w.document.write('<tr>');
                w.document.write('<td>' + frame.nama_frame + '</td>');
                w.document.write('<td>' + frame.jumlah_barang + '</td>');
                w.document.write('<td>' + frame.keterangan + '</td>');
                w.document.write('</tr>');
            });
            w.document.write('</table>');

            w.document.write('<table>');
            w.document.write('<tr>');
            w.document.write('<th>Nama Lensa</th>');
            w.document.write('<th>Jumlah Barang</th>');
            w.document.write('<th>Keterangan</th>');
            w.document.write('</tr>');
            lenses.forEach(function(lensa) {
                w.document.write('<tr>');
                w.document.write('<td>' + lensa.nama_lensa + '</td>');
                w.document.write('<td>' + lensa.jumlah_barang + '</td>');
                w.document.write('<td>' + lensa.keterangan + '</td>');
                w.document.write('</tr>');
            });
            w.document.write('</table>');

            w.document.write('<div class="footer">');
            w.document.write('<hr style="border: 1px solid black; height: 1px; background-color: #000; margin: 20px 0;">');
            w.document.write('<p>&copy; ' + new Date().getFullYear() + ' <?php echo $d->nama; ?>. All rights reserved.</p>');
            w.document.write('</div>');
            w.document.write('</body></html>');
            w.document.close();
            w.print();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
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

function clearForm() {
    document.getElementById('searchForm').reset();
    window.location.href = 'riwayat_pesanan.php';
}
</script>
<?php include 'footer.php'?>
