<?php
include 'header.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_pendaftaran = $_GET['id'];
    $check_query = "SELECT * FROM tindakan WHERE id_tindakan = '$id_pendaftaran'";
    $check_result = mysqli_query($conn, $check_query);
    $aTindakan = mysqli_fetch_object($check_result);
    //data pesanan
    $queryPesanan = "SELECT * FROM pesanan WHERE tindakan_id = '$id_pendaftaran'";
    $resultPesanan = mysqli_query($conn, $queryPesanan);
    $aPesanan = mysqli_fetch_object($resultPesanan);
}

if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    unset($_SESSION['pesanan'][$index]);
    header("Location: tambah_pesanan.php?id=$id_tindakan");
    exit();
}
?>

<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                TAMBAH PESANAN
            </div>
            <div class="box-body">
                <form action="" method="POST">
                <?php
                        // Mendapatkan tanggal hari ini
                        $tanggal_hari_ini = date('Ymd');
                        // Menghitung jumlah pesanan pada tanggal hari ini
                        $query_jumlah_pesanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE DATE(created_date) = CURDATE()");
                        $data_jumlah_pesanan = mysqli_fetch_assoc($query_jumlah_pesanan);
                        $jumlah_pesanan = $data_jumlah_pesanan['total'];
                        // Menghasilkan nomor urut dengan panjang 4 digit
                        $nomor_urut = sprintf("%03d", $jumlah_pesanan + 1);
                        // Menggabungkan tanggal hari ini dan nomor urut
                        $kode_pesanan_otomatis = $tanggal_hari_ini . $nomor_urut;
                        ?>
                    <div class="form-group">
                        <label>Kode Pesanan</label><br><br>
                        <input type="text" name="kode_pesanan" value="<?php echo htmlspecialchars($kode_pesanan_otomatis); ?>" class="input-control" readonly>
                    </div><br>
                    <!-- Status pesanan -->
                    <div class="form-group">
                        <label>Status</label><br><br>
                        <select name="status_pesanan" class="input-control" required>
                            <option value="">Pilih</option>
                            <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                            <option value="Pembayaran Diterima">Pembayaran Diterima</option>
                            <option value="Pesanan Selesai">Pesanan Selesai</option>
                            <option value="Pesanan Dibatalkan">Pesanan Dibatalkan</option>
                        </select>
                    </div><br>
                    <!-- Tabel untuk daftar pesanan -->
                    <div class="form-group">
                        <div class="box">
                            <div class="box-header">
                                DAFTAR PESANAN
                            </div>
                            <div class="box-body">
                                <!-- Tombol Tambah Barang -->
                                <button type="button" class="btn" onclick="myFunction()"><i class="fa fa-plus"></i></button>
                                <!-- Tabel daftar pesanan -->
                                <table class="table" id="tabel-pesanan">
                                    <!-- Header tabel -->
                                    <thead>
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Barang</th>
                                            <th>Harga</th>
                                            <th>QTY</th>
                                            <th>Total</th>
                                            <th>Catatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <!-- Body tabel -->
                                    <tbody id="myTable">
                                        <tr>
                                            <td colspan="5" style="text-align: right;"><strong>Total Biaya:</strong></td>
                                            <td colspan="2"><input type="text" name="total_harga" id="total_harga"class="input-control" readonly></td> <!-- Perbaikan pada perhitungan total biaya -->
                                        </tr>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><br>
                    <!-- Input untuk biaya lain -->
                    <div class="form-group">
                        <label>Biaya Lain</label><br><br>
                        <input type="number" name="biaya_lain" id="biaya_lain" class="input-control" value="0" oninput="updateTotal()">
                    </div><br>
                    <!-- Total biaya -->
                    <div class="form-group">
                        <label>Total Biaya</label><br><br>
                        <input type="number" name="total_biaya" id="total_biaya" class="input-control" readonly>
                    </div><br>
                    <!-- Tombol Kembali dan Simpan -->
                    <button type="button" class="btn" onclick="window.location ='edit_tindakan.php?id=<?= $id_pendaftaran ?>'">KEMBALI</button>
                    <button type="submit" name="submit" class="btn">SIMPAN</button>
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    $status_pesanan = $_POST['status_pesanan'];
                    $biaya_lain = $_POST['biaya_lain'];
                    $total_biaya = $_POST['total_biaya'];
                    //print_r($jenis);
                    $total = $_POST['total_harga'];
                    // Query untuk menyimpan pesanan
                    $query_simpan_pesanan = "INSERT INTO pesanan (Kode_pesanan, user_id, admin_id, status_pesanan, tindakan_id, pendaftaran_id, created_date, biaya_pesanan, biaya_lain, total_biaya) VALUES (
                        '$kode_pesanan_otomatis',
                        '" . $aTindakan->user_id . "',
                        '" . $_SESSION['username'] . "',
                        '$status_pesanan',
                        '" . $aTindakan->pendaftaran_id. "',
                        '" . $aTindakan->pendaftaran_id. "',
                        NOW(), 
                        '$total',
                        '$biaya_lain',
                        '$total_biaya'
                    )";
                    $queryPesanan = mysqli_query($conn, $query_simpan_pesanan);    
                    if($queryPesanan){
                        echo '<div class ="alert alert-success">pesanan berhasil ditambahkan!</div>';
                    } else {
                        echo "Error saat menambahkan pesanan: " . $conn->error;
                    }
                    $jenis = $_POST['jenis'];
                    $postKodePesanan = $_POST['kode_pesanan'];
                    $nama = $_POST['nama'];
                    $harga = $_POST['harga'];
                    $qty = $_POST['qty'];
                    $catatan = $_POST['catatan'];
                    $id_pesanan_produk = $_POST['id_pesanan_produk'];
                    foreach ($jenis as $index => $value) {
                        if ($jenis[$index] === "frame") {
                            if ($id_pesanan_produk[$index] == 0) {
                                $query = "INSERT INTO pesanan_frame (id_pesanan_frame, frame_kode, jumlah_barang, harga_frame, created_date, pesanan_kode, keterangan) 
                                    VALUES
                                    ('', '$nama[$index]', '$qty[$index]','$harga[$index]', NOW(), '$postKodePesanan', '$catatan[$index]')";
                                $result = mysqli_query($conn, $query);
                            } 
                            /*else {
                                //update
                                //$update_query = mysqli_query($conn, "UPDATE pesanan SET jumlah_barang = [biaya_pesanan,jumlah_barang], total_biaya = [biaya_pesanan,total_biaya] WHERE username_user = '$_SESSION[id]' AND status_pesanan = 'Keranjang'");
                                $update = mysqli_query($conn, "UPDATE pesanan_frame SET 
                                    jumlah_barang = '".$qty[$index]."',
                                    keterangan = '".$catatan[$index]."'
                                    WHERE id_pesanan_frame = '".$value."'"
                                );
                            }*/
                        } else if ($jenis[$index] === "lensa") {
                            if ($id_pesanan_produk[$index] == 0) {
                                $query = "INSERT INTO pesanan_lensa (id_pesanan_lensa, lensa_kode, jumlah_barang, harga_lensa, created_date, pesanan_kode, keterangan) 
                                    VALUES
                                    ('', '$nama[$index]','$qty[$index]', '$harga[$index]', NOW(), '$postKodePesanan', '$catatan[$index]')";
                                $result = mysqli_query($conn, $query);
                            } 
                            /*else {
                                // Jika sudah ada pesanan lensa, lakukan UPDATE pada jumlah_barang
                                //update
                                $update = mysqli_query($conn, "UPDATE pesanan_lensa SET 
                                    jumlah_barang = jumlah_barang + '".$qty[$index]."',
                                    keterangan = keterangan + '".$catatan[$index]."'
                                    WHERE id_pesanan_lensa = '".$value."'"
                                );
                            }*/
                        }
                    }                               
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    let countRow = 1;

    function myFunction() {
        var tbody = document.getElementById("myTable");
        var row = tbody.insertRow(0);
        // Cell Jenis
        var cellJenis = row.insertCell(0);
        cellJenis.innerHTML = "<input type='hidden' name='id_pesanan_produk[]' id='id_pesanan_produk' value='0'><select class='btn' name='jenis[]' onchange='getOptions(this)' required><option value=''>Pilih</option><option value='lensa'>Lensa</option><option value='frame'>Frame</option></select>";

        // Cell Nama
        var cellNama = row.insertCell(1);
        cellNama.innerHTML = "<select class='btn' name='nama[]' onchange='getHarga(this)' required></select>";

        // Cell Harga
        var cellHarga = row.insertCell(2);
        cellHarga.innerHTML = "<input type='text' name='harga[]' class='input-control' readonly>";

        // Cell Qty
        var cellQty = row.insertCell(3);
        cellQty.innerHTML = `<button class='btn-qty' type='button' onclick='updateQtyMinus(${countRow})'>-</button>
            <input type="text" class='input-qty' id='qty${countRow}' name="qty[]" value='1' min="1" step="1" readonly onchange="hitungTotalBaris(this)">
            <button class="btn-qty" type="button" onclick="updateQty(${countRow}, 1)">+</button>
        `;

        // Cell Total
        var cellTotal = row.insertCell(4);
        cellTotal.innerHTML = `<input type='text' id='total${countRow}' name='total[]' class='input-control' readonly>`;
        
        // Cell Catatan
        var cellCatatan = row.insertCell(5);
        cellCatatan.innerHTML = `<textarea name='catatan[]'>`;

        // Cell Aksi
        var cellAksi = row.insertCell(6);
        cellAksi.innerHTML = "<button type='button' onclick='hapusBaris(this)' class='btn'><i class='fa fa-trash'></i></button>";

        // Increment countRow
        countRow++;
    }

    function getOptions(select) {
    var jenis = select.value;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_options.php?jenis=' + jenis, true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var options = JSON.parse(xhr.responseText);
            var selectNama = select.parentNode.nextElementSibling.querySelector('select');
            selectNama.innerHTML = ''; // Bersihkan opsi sebelum menambahkan yang baru
            if (options.length > 0) { // Periksa apakah ada barang yang tersedia
                options.forEach(function(option) {
                    var opt = document.createElement('option');
                    opt.value = option.kode;
                    opt.innerHTML = option.nama;

                    // Tambahkan harga dan kode sebagai atribut data
                    opt.setAttribute('data-harga', option.harga);

                    selectNama.appendChild(opt);
                });
                // Set harga dari barang pertama sebagai default
                var firstOption = selectNama.options[0];
                var hargaInput = selectNama.parentNode.nextElementSibling.querySelector('input[name="harga[]"]');
                hargaInput.value = firstOption.getAttribute('data-harga');
            } else {
                // Jika tidak ada barang yang tersedia, atur harga menjadi kosong
                var hargaInput = selectNama.parentNode.nextElementSibling.querySelector('input[name="harga[]"]');
                hargaInput.value = '';
            }
            // Hitung ulang total saat harga berubah
            hitungTotalBaris(select);
        }
    };
    xhr.send();
}


    function getHarga(select) {
        var selectedOption = select.options[select.selectedIndex];
        var harga = selectedOption.getAttribute('data-harga');
        var hargaInput = select.parentNode.nextElementSibling.querySelector('input[name="harga[]"]');
        hargaInput.value = harga;
        // Hitung ulang total saat harga berubah
        hitungTotalBaris(select);
    }

    // Fungsi untuk menghitung total setiap baris
    function hitungTotalBaris(select) {
        var row = select.parentNode.parentNode;
        var qty = parseInt(row.querySelector('.input-qty').value);
        var harga = parseInt(row.querySelector('input[name="harga[]"]').value);
        var total = qty * harga;
        row.querySelector('[name="total[]"]').value = total;
        updateTotal(); // Panggil fungsi updateTotal untuk mengupdate total
        updateTH();
    }

    // Fungsi untuk mengurangi QTY
    function updateQtyMinus(row) {
        var qtyInput = document.querySelector(`input[id="qty${row}"]`);
        var qty = parseInt(qtyInput.value);
        if (qty > 1) {
            qtyInput.value = qty - 1;
            hitungTotalBaris(qtyInput); // Hitung ulang total
        }
    }

    // Fungsi untuk menambah QTY
    function updateQty(row, value) {
        var qtyInput = document.querySelector(`input[id="qty${row}"]`);
        var qty = parseInt(qtyInput.value);
        qtyInput.value = qty + parseInt(value);
        hitungTotalBaris(qtyInput); // Hitung ulang total
    }
    function updateTH() {
    var total = 0;
    // Ambil semua subtotal dari setiap baris pesanan
    var subtotals = document.getElementsByName('total[]');
    for (var i = 0; i < subtotals.length; i++) {
        total += parseInt(subtotals[i].value);
    }
    // Set nilai total pada elemen dengan id total_harga
    var totalHargaElement = document.getElementById('total_harga');
    totalHargaElement.innerText = "Rp." + total.toLocaleString(); // Menampilkan total biaya dengan format rupiah

    // Masukkan nilai total harga ke dalam input total_harga untuk disertakan dalam submit form
    var totalHargaInput = document.querySelector('input[name="total_harga"]');
    totalHargaInput.value = total;
    }
    function updateTotal() {
    var total = 0;
    // Ambil semua subtotal dari setiap baris pesanan
    var subtotals = document.getElementsByName('total[]');
    for (var i = 0; i < subtotals.length; i++) {
        total += parseInt(subtotals[i].value);
    }
    // Ambil nilai biaya lainnya
    var biayaLain = parseInt(document.getElementById('biaya_lain').value);
    // Tambahkan biaya lainnya ke total
    total += biayaLain;
    // Set nilai total pada input total biaya

    document.getElementById('total_biaya').value = total;
    }

    function hapusBaris(button) {
        if (confirm('Yakin ingin menghapus?')) {
            // Panggil fungsi hapusBaris jika pengguna menekan OK dalam konfirmasi
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateTotal(); // Panggil fungsi updateTotal untuk mengupdate total
            updateTH();
        }
    }
</script>

<?php include 'footer.php'; ?>
