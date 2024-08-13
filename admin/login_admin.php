<?php
	session_start();
	include '../connection.php';
	$identitas = mysqli_query($conn, "SELECT * FROM setting ORDER BY id DESC");
	$d = mysqli_fetch_object($identitas );
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login Administrator</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">  
		<meta charset="utf-8">  
		<link rel="icon href=" href="../upload/identitas/<?= $d->favicon?>">
		<link href="../css/style.css" rel="stylesheet" type="text/css">
	</head>
	<body class="body">

	<!-- page login-->
		<div class="login">
			<!-- box-->
			<div class="box box-login">
				<!-- box header-->
				<div class="box-header text-center" >
					<h1>Login Administrator</h1>
				</div>
				<!-- box body-->
				<div class="box-body">
					<?php 
					//jika ada variabel msg maka tammpilkan parameter msg
						if(isset($_GET['msg'])){
							echo "<div class='alert alert-error'>".$_GET['msg']."</div>";
						}
					?>
					<form action="" method="POST">
						<div class="form-grup">
							<label>Username</label>
							<input type="text" name="user" placeholder="username" class="input-control">
						</div>
						<div class="form-grup">
							<label>Password</label>
							<input type="password" name="pass" placeholder="password" class="input-control">
						</div>
						<p><a href="lupa_password.php">Lupa Kata Sandi</a></p><br>
						<button type="submit" name="submit" class="btn">LOGIN</button>
					</form>
					<?php
					//ketika tombol submit di klik apa yang akan terjadi 
					if(isset($_POST['submit'])){
						//variabel user dan password
						$user = mysqli_real_escape_string($conn, $_POST['user']);
						$pass = mysqli_real_escape_string($conn,$_POST['pass']);
						
						$cek = mysqli_query($conn, "SELECT * FROM akun_admin WHERE username = '".$user."' ");
						if(mysqli_num_rows($cek)> 0){
								$d = mysqli_fetch_object($cek);	
								if(md5($pass) == $d->password){
									$_SESSION['status_login']= true;
									//user id dan name, lavel yang ada di database dalam tabel pengguna
									$_SESSION['username']	 = $d->username;
									$_SESSION['unama']		 = $d->nama;
									$_SESSION['urole']		 = $d->role;

									// Cek tingkat akses pengguna
									if ($_SESSION['urole'] == 'admin') {
										// Jika pengguna adalah admin, arahkan ke halaman admin
										header("Location: index.php");
										exit();
									} else if ($_SESSION['urole'] == 'pemilik') {
										// Jika pengguna adalah pemilik, arahkan ke halaman admin
										header("Location: index.php");
										exit();
									} else {
										echo '<div class="alert alert-error">Anda tidak memiliki izin akses.</div>';
									}
								} else {
									echo '<div class="alert alert-error">Password Salah</div>';
								}
							} else {
								echo '<div class="alert alert-error">Username tidak ditemukan</div>';
							}
						}
						?>
				</div>
	
				<!-- box footer-->
				<div class="box-footer text-center">
					<a href="../index.php">Halaman Utama</a>
				</div>
			</div>
		</div>
	</body>
</html>