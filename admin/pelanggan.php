<?php include 'header.php'; ?>

<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                pelanggan
            </div>
            <div class="box-body">
                <?php
                if (isset($_GET['success']))
                    echo '<div class="alert alert-success">Edit Data Berhasil</div>';
                ?>
				<form action="">
							<div class="input-grup">
								<input type="text" name="nama" placeholder="Pencarian Nama">
								<input type="text" name="Email" placeholder="Pencarian Email">
								<input type="text" name="alamat" placeholder="Pencarian alamat">
								<input type="date" name="tanggal" placeholder="Pilih Tanggal">
								<button type="submit"><i class="fa fa-magnifying-glass"></i></button>
							</div>
						</form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Nomor Antian</th>
                            <th>Keterangan</th>
                            <th>Tempat Cek</th>
                            <th>Waktu Pendaftaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $where = "WHERE 1=1 ";

                        if (isset($_GET['nama'])) {
                            $nama = mysqli_real_escape_string($conn, $_GET['nama']);
                            // $escaped_input sekarang aman untuk digunakan dalam pernyataan SQL
                            $where.="AND name LIKE '%".addslashes($_GET['nama'])."%' ";
                        }

                        if (isset($_GET['email'])) {
                            $email = mysqli_real_escape_string($conn, $_GET['email']);
                            $where.="AND email LIKE '%".addslashes($_GET['email'])."%' ";
                        }

						if (isset($_GET['alamat'])) {
                            $alamat = mysqli_real_escape_string($conn, $_GET['alamat']);
                            $where.="AND alamat LIKE '%".addslashes($_GET['alamat'])."%' ";
                        }

                        if (isset($_GET['tanggal'])) {
                            $tanggal = mysqli_real_escape_string($conn, $_GET['tanggal']);
                            $where .= "AND DATE(waktu_pendaftaran) = '$tanggal' ";
                        }

                        $pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan $where ORDER BY id DESC");

                        if (mysqli_num_rows($pelanggan) > 0) {
                            while ($p = mysqli_fetch_array($pelanggan)) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $p['name'] ?></td>
                                <td><?= $p['email'] ?></td>
                                <td><?= $p['alamat'] ?></td>
                                <td><?= $p['telepone'] ?></td>
                                <td><?= $p['nomor_antrian'] ?></td>
                                <td><?= $p['keterangan'] ?></td>
                                <td><?= $p['tempat_cek'] ?></td>
                                <td><?= $p['waktu_pendaftaran'] ?></td>
                                <td>
                                    <a href="edit_pelanggan.php?id=<?= $p['id'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
                                    <a href="hapus.php?idpelanggan=<?= $p['id'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                        <?php }} else { ?>
                            <tr>
                                <td colspan="10">Data Tidak Ada</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

