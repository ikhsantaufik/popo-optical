<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							TAMBAH AKUN PELANGGAN
						</div>
						<div class="box-body">
						
							<form action="" method="POST">
								<div class="form-group">
									<label>Username :</label><br><br>
									<input type="text" name="username" placeholder="Username" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Nama     :</label><br><br>
									<input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Alamat   :</label><br><br>
									<input type="text" name="alamat" placeholder="Alamat" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Email    :</label><br><br>
									<input type="text" name="email" placeholder="Email" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Telepone :</label><br><br>
									<input type="number" name="telp" placeholder="Telepone" class="input-control" required>
								</div><br>
								
								<button type="button"class="btn" onclick="window.location ='akun_pelanggan.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">SIMPAN</button><br>
							
							</form>
							
							<?php
							if(isset($_POST['submit'])){
								$nama     = htmlspecialchars(addslashes(ucwords($_POST['nama'])));
								$username = htmlspecialchars(addslashes($_POST['username']));
								$alamat   = htmlspecialchars(addslashes(ucwords($_POST['alamat'])));
								$telp     = htmlspecialchars(addslashes($_POST['telp']));
								$email    = htmlspecialchars(addslashes($_POST['email']));
								$pass     = '123456';
								$cek      = mysqli_query($conn, "SELECT username FROM akun_pelanggan WHERE username ='".$username."'");

								if(mysqli_num_rows($cek) > 0){
									echo '<div class="alert alert-error">Username Sudah Digunakan</div>';
								} else {
									$simpan = mysqli_query($conn, "INSERT INTO akun_pelanggan VALUES (
										'".$username."',
										'".MD5($pass)."',
										'".$nama."',
										'".$alamat."',
										'".$telp."',
										'".$email."',
										'active',
										''
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