<?php
	session_start();
	include 'connection.php';
	$identitas = mysqli_query($conn, "SELECT * FROM setting ORDER BY id DESC LIMIT 1");
	$d = mysqli_fetch_object($identitas);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login user</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">  
		<meta charset="utf-8">  
		<link rel="icon" href="upload/identitas/<?= $d->favicon ?>">
		<link href="css/style.css" rel="stylesheet" type="text/css">
	</head>
	<body class="body">
	<!-- page login-->
		<div class="login">
			<!-- box-->
			<div class="box box-login">
				<!-- box header-->
				<div class="box-header text-center" >
					<h1>Login</h1>
				</div>
				<!-- box body-->
				<div class="box-body">
					<?php 
					// jika ada variabel msg maka tampilkan parameter msg
						if(isset($_GET['msg'])){
							echo "<div class='alert alert-error'>".$_GET['msg']."</div>";
						}
						if(isset($_GET['berhasil'])){
							echo "<div class='alert alert-success'>".$_GET['berhasil']."</div>";
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
						<button type="submit" name="submit" class="btn">LOGIN</button>
					</form>
					<?php
					// ketika tombol submit di klik apa yang akan terjadi 
					if(isset($_POST['submit'])){
						// variabel user dan password
						$user = mysqli_real_escape_string($conn, $_POST['user']);
						$pass = mysqli_real_escape_string($conn, $_POST['pass']);
						
						$cek = mysqli_query($conn, "SELECT * FROM akun_pelanggan WHERE username = '".$user."' and status_user='active'");
						if(mysqli_num_rows($cek) > 0){
							$d = mysqli_fetch_object($cek);	
							if(md5($pass) == $d->password){
								$_SESSION['status_login'] = true;
								// user id dan name, level yang ada di database dalam tabel akun_pelanggan
								$_SESSION['username'] = $d->username;
								$_SESSION['unama'] = $d->nama;
								$_SESSION['urole'] = "user";
								$_SESSION['email'] = $d->$email_pelanggan;

								// Redirect all users to the same page (e.g., index.php)
								header("Location: index.php");
								exit();
							} else {
								echo '<div class="alert alert-error">Password Salah</div>';
							}
						} else {
							echo '<div class="alert alert-error">Username tidak ditemukan</div>';
						}
					}
					?>
					<br><p>Belum punya akun ? <a href="register.php">Buat Akun</a></p>
					<p><a href="lupa_password.php">Lupa Kata Sandi</a></p>
					<a href="admin/login_admin.php">A</a></p><br>
				</div>
				<!-- box footer-->
				<div class="box-footer text-center">
					<a href="index.php">Halaman Utama</a>
				</div>
			</div>
		</div>
	</body>
</html>


