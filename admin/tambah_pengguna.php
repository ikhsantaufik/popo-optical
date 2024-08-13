<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							TAMBAH PENGGUNA
						</div>
						<div class="box-body">
						
							<form action="" method="POST">
							
								<div class="form-grup">
									<label>Nama</label>
									<input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" required>
								</div>
								<div class="form-grup">
									<label>username</label>
									<input type="text" name="user" placeholder="username" class="input-control" required>
								</div>
								<div class="form-grup">
									<label>level</label>
									<select name="level"class="input-control" required>
										<option value="">pilih</option>
										<option value="pemilik">Pemilik</option>
										<option value="admin">Admin</option>
										<option value="pelanggan">Pelanggan</option>
									</select>									
								</div>
								
								<button type="button"class="btn" onclick="window.location ='pengguna.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">SIMPAN</button>
							
							</form>
							
							<?php
								
								if(isset($_POST['submit'])){
									
									$nama 	= htmlspecialchars(addslashes(ucwords($_POST['nama'])));
									$user 	= htmlspecialchars(addslashes(ucwords($_POST['user'])));
									$level 	= $_POST['level'];
									$pass 	= '123456';
									$cek 	= mysqli_query($conn, "SELECT username FROM pengguna WHERE username ='".$user."'");
									//mysqli_num_rows apakah datanya ada atau tidak
									if(mysqli_num_rows($cek)> 0){
										echo '<div class ="alert alert-error">Username Sudah Digunakan</div>';
									}else{
										$simpan = mysqli_query($conn, "INSERT INTO pengguna VALUES(
										null,
										'".$nama."',
										'".$user."',
										'".MD5($pass)."',
										'".$level."',
										null,
										null
									
									)");
									
										if ($simpan){
											echo '<div class ="alert alert-success">simpan berhasil</div>';
										}else{
											echo 'gagal simpan'.mysqli_error($conn);
										}
									}
								
								}
							
							?>
						
							
						</div>
					</div>
				</div>
			</div>
			
	<?php include 'footer.php' ?>