<?php include 'header.php'?>		
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							UBAH PASSWORD
						</div>
						<div class="box-body">
						<form action="" method="post">
								<div class="form-group">
									<label>Password:</label><br><br>
									<input type="password" name="pass1" placeholder="Password" class="input-control" required>
								</div><br>
								<div class="form-group">
									<label>Ulangi Passwaord:</label><br><br>
									<input type="password" name="pass2" placeholder="Ulangi Password" class="input-control">
								</div><br>
								<button type="submit" name="submit" class="btn">UBAH PASSWORD</button>
							</form>
							
							<?php
							//ketika tombol submit di klik apa yang akan terjadi 
							if(isset($_POST['submit'])){
								$pass1  	= htmlspecialchars(addslashes($_POST["pass1"]));
								$pass2  	= htmlspecialchars(addslashes($_POST["pass2"]));
								
								//jika pass2 tidak sama dengan pass1 maka
								if ($pass2 != $pass1) {
									echo '<div class="alert alert-danger">Ulangi Password, Tidak Sesuai</div>';
								} else {
									$update = mysqli_query($conn, "UPDATE akun_admin SET 
									password = '".MD5($pass1)."'
									WHERE username = '".$_SESSION['username']."'");
									if ($update) {
										echo '<div class="alert alert-success">Ubah Password berhasil</div>';
									} else {
										echo 'Gagal Update' . mysqli_error($conn);
									}
								}		
							}
							?>
						</div>
					</div>
				</div>
			</div>
<?php include 'footer.php'?>