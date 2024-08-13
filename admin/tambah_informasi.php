<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							TAMBAH INFORMASI
						</div>
						<div class="box-body">
							<form action="" method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<label>Judul		:</label><br><br>
									<input type="text" name="judul" placeholder="Judul" class="input-control" required>
								</div><br>
								<div class="form-group">
								<!--elemen yang dapat menyimpan kata dan dapat diperluas jika pengguna memasukkan lebih banyak text sehingga dapat dimasukkan pada elemen text area-->
									<label>Keterangan	:</label><br><br>
									<textarea name="keterangan" placeholder="Keterangan" class="input-control" id="keterangan"></textarea>
								</div><br>
								<div class="form-group">
									<label>Gambar		:</label><br><br>
									<input type="file" name="gambar" class="input-control" required>								
								</div><br>
								<button type="button"class="btn" onclick="window.location ='informasi.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">SIMPAN</button>
							
							</form>
							<?php
							if (isset($_POST['submit'])) {
								// print_r($_FILES['gambar']);
								$judul = addslashes(ucwords($_POST['judul']));
								$ket = addslashes($_POST['keterangan']);

								$filename = $_FILES['gambar']['name'];
								$tmpname = $_FILES['gambar']['tmp_name'];
								$filesize = $_FILES['gambar']['size'];

								$formatfile = pathinfo($filename, PATHINFO_EXTENSION);
								$rename = 'informasi' . time() . '.' . $formatfile;

								$allowedtype = array('png', 'jpg', 'jpeg', 'gif');

								if (!in_array($formatfile, $allowedtype)) {
									echo '<div class ="alert alert-error">Format File Tidak Ditemukan</div>';
								} elseif ($filesize > 1000000) {
									echo '<div class ="alert alert-error">Ukuran File Tidak Boleh Lebih Dari 1 MB</div>';
								} else {
									move_uploaded_file($tmpname, "../upload/informasi/" . $rename);

									$simpan = mysqli_query($conn, "INSERT INTO informasi VALUES (
										UUID(),
										'" . $judul . "',
										'" . $ket . "',
										'" . $rename . "',
										NOW(),
										'" . $_SESSION['username'] . "'
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