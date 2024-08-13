<?php
include 'header.php';

// Memeriksa status login
if (!isset($_SESSION['status_login'])) {
    header("location:../login.php?msg=Harap Login Terlebih Dahulu!");
    exit();
}

if ($_SESSION['status_login'] !== true) {
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
?>
<div class="container-utama">
    <div class="box">
        <div class="box-header">
            RIWAYAT PENDAFTARAN
        </div>
        <div class="box-body">
            <!-- Form untuk pencarian -->
            <form action="" id="searchForm" onsubmit="clearDateIfEmpty()">
                <?php
                if (isset($_GET['msg'])) {
                    echo "<div class='alert alert-success'>" . $_GET['msg'] . "</div>";
                }
                ?>
                <div class="input-grup">
                    <input type="text" id="searchInput" name="all" placeholder="Pencarian" value="<?php echo isset($_GET['all']) ? $_GET['all'] : ''; ?>">
                    <input type="date" id="tanggal" name="tanggal" placeholder="Pilih Tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
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
                            <th>Nomor Antrian</th>
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
                            $where .= "AND DATE(tgl_pendaftaran) = '$tanggal' ";
                        }
                        $daftar = mysqli_query($conn, "SELECT * FROM pendaftaran $where ORDER BY id_pendaftaran");

                        if (mysqli_num_rows($daftar) > 0) {
                            while ($p = mysqli_fetch_array($daftar)) {
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p['tgl_pendaftaran'] ?></td>
                                    <td><?= $p['kriteria'] ?></td>
                                    <td><?= $p['tempat_cek'] ?></td>
                                    <td><?= $p['keluhan'] ?></td>
                                    <td><?= $p['no_antrian'] ?></td>
                                    <td><?= $p['status_pendaftaran'] ?></td>
                                    <td>
                                        <a href="javascript:void(0);" title="Cetak Dokumen" onclick="printDiv('<?= $p['tgl_pendaftaran'] ?>')"><i class="fa-solid fa-file"></i></a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="8">Data Tidak Ada</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        function toggleTableClass() {
            if ($(window).width() < 600) {
                $('#responsiveTable').addClass('responsive');
            } else {
                $('#responsiveTable').removeClass('responsive');
            }
        }

        toggleTableClass();

        $(window).resize(function () {
            toggleTableClass();
        });

        // Script for search functionality
        $('#searchInput').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#responsiveTable tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
            if ($('#responsiveTable tbody tr:visible').length == 0) {
                $('#noResultsRow').show();
            } else {
                $('#noResultsRow').hide();
            }
        });
    });

    function printDiv(pendaftaran) {
        var table_div = document.getElementById('responsiveTable');
        var w = window.open();
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

        // Menambahkan informasi pelanggan
        var namaPelanggan = "<?php echo $namaPelanggan; ?>";
        var alamatPelanggan = "<?php echo $alamatPelanggan; ?>";
        var telpPelanggan = "<?php echo $telpPelanggan; ?>";
        w.document.write('<div>');
        w.document.write('<h5>Nama : ' + namaPelanggan + '</h5>');
        w.document.write('<h5>Alamat : ' + alamatPelanggan + '</h5>');
        w.document.write('<h5>No telepon : ' + telpPelanggan + '</h5>');
        w.document.write('</div>');

        // Menambahkan judul laporan
        w.document.write('<h3 style="text-align:center;">Laporan Pendaftaran</h3>');

        // Membuat tabel untuk menyusun data
        w.document.write('<table>');

        // Menambahkan header tabel secara manual tanpa kolom No, Status, dan Aksi
        w.document.write('<tr>');
        var headers = table_div.querySelectorAll('th');
        var statusIndex = -1; // Untuk menyimpan indeks kolom Status
        for (var j = 1; j < headers.length - 1; j++) { // Skip kolom No (index 0) dan kolom Aksi (kolom terakhir)
            if (headers[j].innerText === "Status") {
                statusIndex = j; // Menyimpan indeks kolom Status
            } else {
                var headerContent = headers[j].innerText || headers[j].textContent;
                w.document.write('<th>' + headerContent + '</th>');
            }
        }
        w.document.write('</tr>');

        // Mengambil semua baris dari tabel yang ingin dicetak
        var rows = table_div.querySelectorAll('tr');
        // Menambahkan setiap baris ke dalam tabel pada laporan
        for (var i = 1; i < rows.length; i++) { // Mulai dari 1 untuk melewati baris header yang sudah ditangani
            var cols = rows[i].querySelectorAll('td');
            var pendaftaranBaris = cols[1].innerText || cols[1].textContent; // Ambil pendaftaran dari kolom kedua
            if (pendaftaranBaris === pendaftaran) {
                // Jika kode pesanan di baris ini cocok dengan pendaftaran yang diberikan, tambahkan ke laporan
                w.document.write('<tr>');
                for (var j = 1; j < cols.length - 1; j++) { // Skip kolom No (index 0) dan kolom Aksi (kolom terakhir)
                    if (j !== statusIndex) { // Skip kolom Status
                        var cellContent = cols[j].innerText || cols[j].textContent;
                        w.document.write('<td>' + cellContent + '</td>');
                    }
                }
                w.document.write('</tr>');
            }
        }
        w.document.write('</table>');
        w.document.write('</body></html>');
        w.document.close();
        w.print();
    }

    function clearDateIfEmpty() {
        var tanggal = document.getElementById('tanggal').value;
        if (tanggal === '') {
            document.getElementById('tanggal').removeAttribute('name');
        }
        var all = document.getElementById('searchInput').value;
        if (all === '') {
            document.getElementById('searchInput').removeAttribute('name');
        }
    }

    function clearForm() {
        document.getElementById('searchForm').reset(); // Gunakan metode reset() untuk mengosongkan form
        window.location.href = 'riwayat_pendaftaran.php'; // Ganti 'riwayat_pendaftaran.php' dengan nama file PHP yang berisi kode
    }
</script>
<?php include 'footer.php'; ?>
