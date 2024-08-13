<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							TAMBAH JENIS LENSA
						</div>
						<div class="box-body">
							<form action="" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label>Kode Lensa			:</label><br><br>
									<input type="text" name="id_jenis_lensa" placeholder="Kode Lensa" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Jenis Lensa		:</label><br><br>
									<input type="text" name="nama" placeholder="Jenis Lensa" class="input-control" required>
								</div><br>
								<div class="form-group">
								<!--elemen yang dapat menyimpan kata dan dapat diperluas jika pengguna memasukkan lebih banyak text sehingga dapat dimasukkan pada elemen text area-->
									<label>Deskripsi Lensa	:</label><br><br>
									<textarea name="deskripsi" placeholder="Deskripsi Lensa" class="input-control" id="keterangan"></textarea>
								</div><br>
								<button type="button"class="btn" onclick="window.location ='jenis_lensa.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">SIMPAN</button>
							
							</form>
							<?php
							if (isset($_POST['submit'])) {
								// print_r($_FILES['gambar']);
								$id_jenis_lensa = htmlspecialchars(addslashes($_POST['id_jenis_lensa']));
								$nama = htmlspecialchars(addslashes(ucwords($_POST['nama'])));
								$deskripsi = addslashes(ucwords($_POST['deskripsi']));{
									$simpan = mysqli_query($conn, "INSERT INTO jenis_lensa VALUES (
										'" . $id_jenis_lensa . "',
										'" . $nama . "',
										'" . $deskripsi . "',
										null
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