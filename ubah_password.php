<?php include 'header.php'?>
<?php
// Memeriksa status login
if(!isset($_SESSION['status_login'])){
    header("location:../login.php?msg=Harap Login Terlebih Dahulu!");
}
if (!$_SESSION['status_login']== true) {
    header("location: login.php?msg=Harap Login Terlebih Dahulu!");
    exit();
}	
?>
		<div class="container-utama">
					<div class="box">
						<div class="box-header">
							UBAH PASSWORD
						</div>
						<div class="box-body">
						<form action="" method="post">
								<div class="form-group">
									<label>Password</label><br><br>
									<input type="password" name="pass1" placeholder="Password" class="input-control">
								</div><br>
								<div class="form-group">
									<label>Ulangi Passwaord</label><br><br>
									<input type="password" name="pass2" placeholder="Ulangi Password" class="input-control">
								</div><br>
								
								<button type="button"class="btn" onclick="window.location ='index.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">UBAH PASSWORD</button>
							</form>
							
							<?php
							//ketika tombol submit di klik apa yang akan terjadi 
							if(isset($_POST['submit'])){
								$pass1  	= htmlspecialchars(addslashes($_POST["pass1"]));
								$pass2  	= htmlspecialchars(addslashes($_POST["pass2"]));
								
								//variabel waktu saat ini
								$currdate = date('Y-m-d H:i:s');
								
								//jika pass2 tidak sama dengan pass1 maka
								if($pass2 != $pass1){
									echo '<div class ="alert alert-error">Ulangi Password, Tidak Sesuai</div>';
								}else{
									//masih salah dibagian update_atnya
									$update	= mysqli_query($conn,"UPDATE akun_pelanggan SET 
									Password ='".MD5($pass1)."',
									updated_at ='".$currdate."'
									WHERE id =".$_SESSION['uid']."
									");		
									if($update){
										echo '<div class ="alert alert-success">Ubah Password berhasil</div>';
									}else{
										echo'Gagal Update'.mysqli_error($conn);
									}
								}		
							}
							?>
						</div>
					</div>
				</div>
<?php include 'footer.php'?>