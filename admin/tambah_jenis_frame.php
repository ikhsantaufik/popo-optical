<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							TAMBAH JENIS FRAME
						</div>
						<div class="box-body">
							<form action="" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label>Kode Frame			:</label><br><br>
									<input type="text" name="id_jenis" placeholder="Kode Frame" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Jenis Frame		:</label><br><br>
									<input type="text" name="nama" placeholder="Jenis Frame" class="input-control" required>
								</div><br>
								<div class="form-group">
								<!--elemen yang dapat menyimpan kata dan dapat diperluas jika pengguna memasukkan lebih banyak text sehingga dapat dimasukkan pada elemen text area-->
									<label>Deskripsi Frame	:</label><br><br>
									<textarea name="deskripsi" placeholder="Deskripsi Frame" class="input-control" id="keterangan"></textarea>
								</div><br>
								<button type="button"class="btn" onclick="window.location ='jenis_frame.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">SIMPAN</button>
							
							</form>
							<?php
							if (isset($_POST['submit'])) {
								// print_r($_FILES['gambar']);
								$id_jenis =htmlspecialchars(addslashes($_POST['id_jenis']));
								$nama = htmlspecialchars(addslashes(ucwords($_POST['nama'])));
								$deskripsi = addslashes(ucwords($_POST['deskripsi']));{
									$simpan = mysqli_query($conn, "INSERT INTO jenis_frame VALUES (
										'" . $id_jenis . "',
										'" . $nama . "',
										'" . $deskripsi . "'
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