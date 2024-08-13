<?php
include 'header.php';
$no=1;
// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    $query_pesanan = "SELECT * FROM pesanan WHERE kode_pesanan = '$id_pesanan'";
    $result_pesanan = mysqli_query($conn, $query_pesanan);
    $pesanan = mysqli_fetch_assoc($result_pesanan);
}
?>
<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                EDIT PESANAN
            </div>
            <div class="box-body">
            <?php
				if(isset($_GET['success'])){
					echo "<div class='alert alert-success'>".$_GET['success']."</div>";
				}
			?>
                <form action="" method="POST">
                    <div class="form-group">
                        <input type="hidden" name="kode_pesanan" value="<?= $pesanan['kode_pesanan']?>" class="input-control" readonly>
                        <label>Status</label><br><br>
                        <select name="status_pesanan" class="input-control" required>
                            <option value="">Pilih</option>
                            <option value="Menunggu Pembayaran" <?php echo ($pesanan['status_pesanan'] == 'Menunggu Pembayaran') ? 'selected' : ''; ?>>Menunggu Pembayaran</option>
                            <option value="Pembayaran Diterima" <?php echo ($pesanan['status_pesanan'] == 'Pembayaran Diterima') ? 'selected' : ''; ?>>Pembayaran Diterima</option>
                            <option value="Pesanan Selesai" <?php echo ($pesanan['status_pesanan'] == 'Pesanan Selesai') ? 'selected' : ''; ?>>Pesanan Selesai</option>
                            <option value="Pesanan Dibatalkan" <?php echo ($pesanan['status_pesanan'] == 'Pesanan Dibatalkan') ? 'selected' : ''; ?>>Pesanan Dibatalkan</option>
                        </select>
                    </div><br>
                    <!-- Form tambah pesanan -->
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
                                    <thead>
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th>Catatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="myTable">
                                    <?php
                                        // Array untuk menyimpan nama frame dan lensa
                                        $countRow=1;
                                        $frames = [];
                                        $lensas = [];


                                        // Query untuk mendapatkan daftar frame berdasarkan kode pesanan
                                        $queryFrame = mysqli_query($conn, "SELECT pesanan_frame.id_pesanan_frame, frame.nama_frame, pesanan_frame.harga_frame, pesanan_frame.jumlah_barang AS jumlah_barang, pesanan_frame.keterangan, pesanan_frame.frame_kode FROM pesanan_frame
                                                                        JOIN frame ON pesanan_frame.frame_kode = frame.kode_frame
                                                                        WHERE pesanan_frame.pesanan_kode = '$id_pesanan'");                    
                                                                        // Menyimpan nama frame ke dalam array
                                        while ($frame = mysqli_fetch_array($queryFrame)) {
                                            $frames[] = $frame;
                                        }

                                        // Menampilkan nama frame
                                        foreach ($frames as $frame) {
                                            //print_r($frame);
                                            ?>
                                           <tr>
                                           <td>
                                                <select class='btn' name='jenis[]' onchange='getOptions(this)'>
                                                    <option value='frame'>Frame</option>
                                                </select>
                                            </td>
                                                <td><input type='hidden' name='id_pesanan_produk[]' value='<?=$frame['id_pesanan_frame']?>'><input type="text" name="nama[]" class="input-control" value="<?= $frame['nama_frame'] ?>" readonly></td>
                                                <td><input type="text" name="harga[]" class="input-control" value="<?= $frame['harga_frame'] ?>" readonly></td>
                                                <td>
                                                    <button class="btn" type='button' onclick="updateQtyMinus(<?=$countRow?>)">-</button>
                                                    <input type="text" class="input-qty" id="qty<?=$countRow?>" name="qty[]" value="<?= $frame['jumlah_barang'] ?>" min="1" step="1" readonly onchange="hitungTotalBaris(this)">
                                                    <button class="btn" type="button" onclick="updateQty(<?=$countRow?>, 1)">+</button>
                                                </td>
                                                <td>
                                                    <input type="text" id="total<?=$countRow?>" name="total[]"value="<?= $frame['jumlah_barang'] * $frame['harga_frame'] ?>" class="input-control" readonly>
                                                </td>
                                                <td><textarea name="catatan[]"><?= $frame['keterangan'] ?></textarea></td>
                                                <td>
                                                <button type="button" onclick="hapusBaris(this, 'frame', <?= $frame['id_pesanan_frame'] ?>)" class="btn"><i class="fa fa-trash"></i></button>
                                            </tr>
                                    <?php $countRow++; }

                                        // Query untuk mendapatkan daftar lensa berdasarkan kode pesanan
                                        $queryLensa = mysqli_query($conn, "SELECT pesanan_lensa.id_pesanan_lensa, lensa.nama_lensa ,pesanan_lensa.harga_lensa, pesanan_lensa.jumlah_barang AS jumlah_barang, pesanan_lensa.keterangan, pesanan_lensa.lensa_kode FROM pesanan_lensa
                                                                        JOIN lensa ON pesanan_lensa.lensa_kode = lensa.kode_lensa
                                                                        WHERE pesanan_lensa.pesanan_kode = '$id_pesanan'");
                                        // Menyimpan nama lensa ke dalam array
                                        while ($lensa = mysqli_fetch_array($queryLensa)) {
                                            $lensas[] = $lensa;
                                        }

                                        // Menampilkan nama lensa
                                        foreach ($lensas as $lensa) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <select class="btn" name="jenis[]" desibel>
                                                        <option value="lensa">Lensa</option>
                                                    </select>
                                                </td>
                                                <td><input type="hidden" name="id_pesanan_produk[]" value='<?=$lensa['id_pesanan_lensa']?>'><input type="text" name="nama[]" class="input-control" value="<?= $lensa['nama_lensa'] ?>" readonly></td>
                                                <td><input type="text" name="harga[]" class="input-control" value="<?= $lensa['harga_lensa'] ?>" readonly></td>
                                                <td>
                                                    <button class="btn" type='button' onclick="updateQtyMinus(<?=$countRow?>)">-</button>
                                                    <input type="text" class="input-qty" id="qty<?=$countRow?>" name="qty[]" value="<?= $lensa['jumlah_barang'] ?>" min="1" step="1" readonly onchange="hitungTotalBaris(this)">
                                                    <button class="btn" type="button" onclick="updateQty(<?=$countRow?>, 1)">+</button>
                                                </td>
                                                <td>
                                                    <input type="text" id="total<?=$countRow?>" name="total[]"  value="<?= $lensa['jumlah_barang'] * $lensa['harga_lensa'] ?>" class="input-control" readonly>
                                                </td>
                                                <td><textarea name="catatan[]"><?= $lensa['keterangan'] ?></textarea></td>
                                                <td><button type="button" onclick="hapusBaris(this, 'lensa', <?= $lensa['id_pesanan_lensa'] ?>)" class="btn"><i class="fa fa-trash"></i></button></td>
                                            </tr>
                                    <?php $countRow++; } ?>
                                    <tr>
                                            <td colspan="5" style="text-align: right;"><strong>Total Biaya:</strong></td>
                                            <td colspan="2"><input type="text" name="total_harga" id="total_harga" class="input-control" value="<?= $pesanan['biaya_pesanan'] ?>" readonly></td> <!-- Perbaikan pada perhitungan total biaya -->
                                        </tr>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><br>
                    <div class="form-group">
                        <label>Biaya Lain</label><br><br>
                        <input type="number" id="biaya_lain" name="biaya_lain" class="input-control" value="<?= $pesanan['biaya_lain'] ?>" oninput="updateTotal()">
                    </div><br>
                    <div class="form-group">
                        <label>Total Biaya</label><br><br>
                        <input type="number" id="total_biaya" name="total_biaya" value="<?= $pesanan['biaya_pesanan'] + $pesanan['biaya_lain'] ?>" class="input-control" readonly>
                    </div><br>
                    <!-- Tombol Tambah Baris dan Simpan -->
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn">SIMPAN</button>
                        <?php 
                        if (isset($_POST['submit'])) {
                            $jenis = $_POST['jenis'];
                            $postKodePesanan = $_POST['kode_pesanan'];
                            $nama = $_POST['nama'];
                            $harga = $_POST['harga'];
                            $qty = $_POST['qty'];
                            $catatan = $_POST['catatan'];
                            $id_pesanan_produk = $_POST['id_pesanan_produk'];
                            // echo "idpesanan:";
                            //print_r($jenis);
                            // print_r($id_pesanan_produk);
                            foreach ($jenis as $index => $value) {

                                if ($jenis[$index] === "frame") {
                                    // Update atau insert data ke tabel pesanan_frame
                                    if ($id_pesanan_produk[$index] != 0) {
                                        // echo "UPDATE pesanan_frame
                                        // SET jumlah_barang = '".$qty[$index]."', 
                                        //     keterangan = '".$catatan[$index]."' 
                                        //     WHERE id_pesanan_frame = '".$id_pesanan_produk[$index]."'";
                                        // Jika produk frame sudah ada dalam pesanan, lakukan update

                                        $update = mysqli_query($conn, "UPDATE pesanan_frame
                                                                        SET jumlah_barang = '".$qty[$index]."', 
                                                                            keterangan = '".$catatan[$index]."' 
                                                                            WHERE id_pesanan_frame = '".$id_pesanan_produk[$index]."'");
                                    } else {
                                        // Jika produk frame belum ada dalam pesanan, lakukan insert baru
                                        $query = "INSERT INTO pesanan_frame (frame_kode, jumlah_barang, harga_frame, created_date, pesanan_kode, keterangan) 
                                                  VALUES ('$nama[$index]', '$qty[$index]', '$harga[$index]', NOW(), '$postKodePesanan', '$catatan[$index]')";
                                        $result = mysqli_query($conn, $query);
                                    }
                                } elseif ($jenis[$index] === "lensa") {
                                    
                                        // echo "UPDATE pesanan_lensa
                                        // SET jumlah_barang = '".$qty[$index]."', 
                                        //     keterangan = '".$catatan[$index]."' 
                                        //     WHERE id_pesanan_lensa = '".$id_pesanan_produk[$index]."'";
                                    // Update atau insert data ke tabel pesanan_lensa
                                    if ($id_pesanan_produk[$index] != 0) {
                                        $update = mysqli_query($conn, "UPDATE pesanan_lensa 
                                                                        SET jumlah_barang = '".$qty[$index]."', 
                                                                            keterangan = '".$catatan[$index]."' 
                                                                        WHERE id_pesanan_lensa = '".$id_pesanan_produk[$index]."'");
                                    } else {
                                        // Jika produk lensa belum ada dalam pesanan, lakukan insert baru
                                        $query = "INSERT INTO pesanan_lensa (lensa_kode, jumlah_barang, harga_lensa, created_date, pesanan_kode, keterangan) 
                                                  VALUES ('$nama[$index]', '$qty[$index]', '$harga[$index]', NOW(), '$postKodePesanan', '$catatan[$index]')";
                                        $result = mysqli_query($conn, $query);
                                    }
                                }
                            }
                            // echo "submit";
                            $status_pesanan = $_POST['status_pesanan'];
                            $biaya_lain = $_POST['biaya_lain'];
                            $total_harga = $_POST['total_harga'];
                            $total_biaya = $_POST['total_biaya'];
                        
                            $query_update_pesanan = "UPDATE pesanan SET status_pesanan = '$status_pesanan', biaya_lain = '$biaya_lain', biaya_pesanan = '$total_harga', total_biaya = '$total_biaya' WHERE kode_pesanan = '$id_pesanan'";
                            $result_update_pesanan = mysqli_query($conn, $query_update_pesanan);
                            if ($status_pesanan == 'Pembayaran Diterima') {
                                foreach ($frames as $frame) {
                                    //$frame['frame_kode'];
                                    $queryStok=mysqli_query($conn, "SELECT * from frame where kode_frame='$frame[frame_kode]'");
                                    $resFrameStok= mysqli_fetch_assoc($queryStok);
                                    $lastStokFrame= $resFrameStok["stok_frame"]-$frame["jumlah_barang"];
                                    $query_update_pesanan = "UPDATE frame SET stok_frame = '$lastStokFrame' WHERE kode_frame = '$frame[frame_kode]'";
                                    $result_update_pesanan = mysqli_query($conn, $query_update_pesanan);
                                    // echo $queryStok;
                                    //print_r($resFrameStok);
                                    // echo "<br/>";
                                }
                    
                                foreach ($lensas as $lensa) {
                                    //$lensa['lensa_kode'];
                                    $queryStokLensa=mysqli_query($conn, "SELECT * from lensa where kode_lensa='$lensa[lensa_kode]'");
                                    $resLensaStok= mysqli_fetch_assoc($queryStokLensa);
                                    $lastStokLensa= $resLensaStok["stok_lensa"]-$lensa["jumlah_barang"];
                                    $query_update_pesanan = "UPDATE lensa SET stok_lensa = '$lastStokLensa' WHERE kode_lensa = '$lensa[lensa_kode]'";
                                    $result_update_pesanan = mysqli_query($conn, $query_update_pesanan);
                                    // echo $queryStok;
                                    //print_r($resFrameStok);
                                    // echo "<br/>";
                                }
                            } 
                        
                            if ($result_update_pesanan) {
                                echo "<script>window.location='edit_pesanan.php?id=$id_pesanan&success=Data berhasil diperbarui!'</script>";
                            } else {
                                echo "Error saat memperbarui pesanan: " . $conn->error;
                            }
                                                                                   
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    let countRow = <?=$countRow?>;

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
        cellQty.innerHTML = `<button class='btn' type='button' onclick='updateQtyMinus(${countRow})'>-</button>
            <input type="text" class='input-qty' id='qty${countRow}' name="qty[]" value='1' min="1" step="1" readonly onchange="hitungTotalBaris(this)">
            <button class="btn" type="button" onclick="updateQty(${countRow}, 1)">+</button>
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
            options.forEach(function(option) {
                var opt = document.createElement('option');
                opt.value = option.kode;
                opt.innerHTML = option.nama;

                // Tambahkan harga dan kode sebagai atribut data
                opt.setAttribute('data-harga', option.harga);

                selectNama.appendChild(opt);
                getHarga(selectNama);
            });
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

    function hapusBaris(button, typeData, idData) {
    if (confirm('Yakin ingin menghapus?')) {
        var row = button.parentNode.parentNode;
        var id_pesanan_produk = row.querySelector('input[name="id_pesanan_produk[]"]').value; // Ambil ID data yang akan dihapus
        
        // Kirim permintaan AJAX untuk menghapus data dari database
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'hapus_data.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200) {
                // Hapus baris dari tabel HTML setelah data dihapus dari database
                row.parentNode.removeChild(row);
                // Ambil nilai type data dan id data
                var typeData = typeData; // Perbaikan: Gunakan nilai yang diteruskan sebagai parameter
                var idData = idData; // Perbaikan: Gunakan nilai yang diteruskan sebagai parameter
                updateTotal(); // Update total biaya setelah penghapusan
                console.log(this.responseText);
                updateTH(); 
            } else {
                console.error('Error saat menghapus data:', xhr.responseText);
            }
        };
        xhr.send('id_pesanan_produk=' + id_pesanan_produk + '&typeHapus=' + typeData); // Kirim ID data ke server
    }
}
</script>

<?php include 'footer.php'; ?>
