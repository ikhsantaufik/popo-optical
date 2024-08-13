<?php include 'header.php'?>	
<?php
// Periksa apakah parameter 'username' sudah diatur
if(isset($_GET['username'])) {
    $username = $_GET['username'];

    // Gunakan prepared statement untuk mencegah SQL injection
    $pengguna = mysqli_prepare($conn, "SELECT * FROM akun_pelanggan WHERE username = ?");
    mysqli_stmt_bind_param($pengguna, "s", $username);
    mysqli_stmt_execute($pengguna);

    $result = mysqli_stmt_get_result($pengguna);

    if(mysqli_num_rows($result) == 0) {
        header("location:akun_pelanggan.php");
        exit();
    }

    $p = mysqli_fetch_array($result);
    mysqli_stmt_close($pengguna);
}else {
    // Tangani kasus ketika parameter 'username' tidak diatur
    header("location:akun_pelanggan.php");
    exit();
}
?>	
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							EDIT AKUN PELANGGAN
						</div>
						<div class="box-body">
						<form action="" method="post">
							<div class="form-group">
								<label>Username	:</label><br><br>
								<input type="text" name="username" placeholder="Username" class="input-control" value="<?= $p['username']?>"readonly>
							</div><br>
							<div class="form-group">
								<label>Nama		:</label><br><br>
								<input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?= $p['nama']?>">
							</div>
							<div class="form-group">
								<label>Alamat	:</label><br><br>
								<input type="text" name="alamat" placeholder="Alamat" class="input-control" value="<?= $p['alamat']?>">
							</div><br>
							<div class="form-group">
								<label>Telepone	:</label><br><br>
								<input type="number" name="telp" placeholder="Telepone" class="input-control" value="<?= $p['telp']?>">
							</div><br>
							<div class="form-group">
								<label>Email	:</label><br><br>
								<input type="text" name="email" placeholder="Email" class="input-control" value="<?= $p['email']?>">
							</div><br>
								<button type="button"class="btn" onclick="window.location ='akun_pelanggan.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">SIMPAN</button>
							</form>
							
							<?php
							//ketika tombol submit di klik apa yang akan terjadi 
							if(isset($_POST['submit'])){
								//user id dan name, lavel yang ada di database dalam tabel pengguna
								$nama 	= htmlspecialchars(addslashes(ucwords($_POST['nama'])));
								$username  = htmlspecialchars(addslashes($_POST['username']));  
                                $alamat    = htmlspecialchars(addslashes(ucwords($_POST['alamat'])));
                                $telp      = htmlspecialchars(addslashes($_POST['telp']));
                                $email     = htmlspecialchars(addslashes($_POST['email']));

								$update = mysqli_prepare($conn, "UPDATE akun_pelanggan SET 
								nama = ?,
								username = ?,
								alamat = ?,
								telp = ?,
								email = ?
								WHERE username = ?");
							
							if ($update) {
								mysqli_stmt_bind_param($update, "ssssss", $nama, $username, $alamat, $telp, $email, $p['username']);
								mysqli_stmt_execute($update);
							
								if (mysqli_stmt_affected_rows($update) > 0) {
									echo "<script>window.location='akun_pelanggan.php?success=Edit Data Berhasil'</script>";
								} else {
									echo 'Gagal Update';
								}
							
								mysqli_stmt_close($update);
							} else {
								echo 'Gagal Prepare Statement: ' . mysqli_error($conn);
							}
						}						
							?>
						</div>
					</div>
				</div>
			</div>
<?php include 'footer.php'?>