<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							TAMBAH FRAME
						</div>
						<div class="box-body">
							<form action="" method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<label>Kode Frame		:</label><br><br>
									<input type="text" name="kode" placeholder="Kode Frame " class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Nama Frame		:</label><br><br>
									<input type="text" name="nama" placeholder="Nama Frame " class="input-control" required>
								</div><br>
								<div class="form-group">
								<!--elemen yang dapat menyimpan kata dan dapat diperluas jika pengguna memasukkan lebih banyak text sehingga dapat dimasukkan pada elemen text area-->
									<label>Deskripsi Frame	:</label><br><br>
									<textarea name="deskripsi" placeholder="Keterangan" class="input-control" id="keterangan"></textarea>
								</div><br>
								<div class="form-group">
									<label>Stok Frame		:</label><br><br>
									<input type="number" name="stok" placeholder="Stok" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Harga Frame		:</label><br><br>
									<input type="number" name="harga" placeholder="Harga" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Status Frame</label><br><br>
									<select name="status"class="input-control" required>
										<option value="">pilih</option>
										<option value="Tidak Tersedia">Tidak Tersedia</option>
										<option value="Tersedia">Tersedia</option>
									</select>	
								</div><br>
								<div class="form-group">
									<label>Jenis Frame			:	</label><br><br>
									<select name="jenis"class="input-control" required>
									<option value="">pilih</option>
								<?php
									$jenis = mysqli_query($conn, "SELECT * FROM jenis_frame $where ORDER BY id_jenis DESC");
									// looping data 
									while ($j = mysqli_fetch_array($jenis)){
									
								?>
									<option value="<?php echo $j['id_jenis']?>"><?php echo $j['jenis_frame']?></option>
									<?php }?>
									</select>
								</div><br>
								<div class="form-group">
									<label>Gambar</label><br><br>
									<input type="file" name="galeri" class="input-control" required>								
								</div><br>
								<button type="button"class="btn" onclick="window.location ='frame.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">SIMPAN</button>
							
							</form>
							<?php
								if (isset($_POST['submit'])) {
									$kode = htmlspecialchars(addslashes($_POST['kode']));
									$nama = htmlspecialchars(addslashes(ucwords($_POST['nama'])));
									$deskripsi = addslashes($_POST['deskripsi']);
									$stok = htmlspecialchars(addslashes($_POST['stok']));
									$harga = htmlspecialchars(addslashes($_POST['harga']));
									$status = htmlspecialchars(addslashes(ucwords($_POST['status'])));
									$jenis = htmlspecialchars(addslashes(ucwords($_POST['jenis'])));

									$filename = $_FILES['galeri']['name'];
									$tmpname = $_FILES['galeri']['tmp_name'];
									$filesize = $_FILES['galeri']['size'];

									$formatfile = pathinfo($filename, PATHINFO_EXTENSION);
									$rename = 'frame' . time() . '.' . $formatfile;

									$allowedtype = array('png', 'jpg', 'jpeg', 'gif');

									if (!in_array($formatfile, $allowedtype)) {
										echo '<div class ="alert alert-error">Format File Tidak Ditemukan</div>';
									} elseif ($filesize > 1000000) {
										echo '<div class ="alert alert-error">Ukuran File Tidak Boleh Lebih Dari 1 MB</div>';
									} else {
										move_uploaded_file($tmpname, "../upload/frame/" . $rename);

										$simpan = mysqli_query($conn, "INSERT INTO frame VALUES(
											'" . $kode . "',
											'" . $nama . "',
											'" . $deskripsi . "',
											'" . $stok . "',
											'" . $harga . "',
											'" . $status . "',
											'" . $rename . "',
											'" . $_SESSION['username'] . "',
											'" . $jenis . "'
										)");

										if ($simpan) {
											echo '<div class="alert alert-success">Simpan berhasil</div>';
										} else {
											echo 'Gagal simpan' . mysqli_error($conn);
										}
									}
								} else {
									echo '<div class="alert alert-error">Semua field harus diisi</div>';
}
?>
						</div>
					</div>
				</div>
			</div>
			
	<?php include 'footer.php' ?>