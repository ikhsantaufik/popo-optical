<?php include 'header.php'?>	
<?php
	$pengguna 	= mysqli_query($conn, "SELECT * FROM pengguna WHERE id =".$_GET['id']);
	if(mysqli_num_rows($pengguna) == 0){	
	header("location:pengguna.php");	
	}
	$p			= mysqli_fetch_array($pengguna);	
?>		
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							EDIT PENGGUNA
						</div>
						<div class="box-body">
						<form action="" method="post">
								<div class="form-grup">
									<label>Nama</label>
									<input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?= $p['nama']?>">
								</div>
								<div class="form-grup">
									<label>Username</label>
									<input type="text" name="user" placeholder="username" class="input-control" value="<?= $p['username']?>">
								</div>
								<div class="form-grup">
									<label>Level</label>
									<select name="level" class="input-control" required>
										<option value="">pilih</option>
										<option value="admin" <?= ($p['level'] == 'admin')?'selected':'';?>>Admin</option>
										<option value="pemilik" <?= ($p['level'] == 'pemilik')?'selected':'';?>>pemilik</option>
										<option value="pelanggan" <?= ($p['level'] == 'pelanggan')?'selected':'';?>>Pelanggan</option>
									</select>
								</div>
								<button type="button"class="btn" onclick="window.location ='pengguna.php'">KEMBALI</button>
								<button type="submit" name="submit" class="btn">SIMPAN</button>
							</form>
							
							<?php
							//ketika tombol submit di klik apa yang akan terjadi 
							if(isset($_POST['submit'])){
								//user id dan name, lavel yang ada di database dalam tabel pengguna
								$nama  	= ucwords($_POST["nama"]);
								$user  	= $_POST["user"];
								$level 	= $_POST["level"];
								//variabel waktu saat ini
								$currdate = date('Y-m-d H:i:s');
								// masih belum bisa di edit karena list kurang 
								$update	= mysqli_query($conn,"UPDATE pengguna SET 
								nama ='".$nama."',
								username ='".$user."',
								level ='".$level."',
								updated_at ='".$currdate."'
								WHERE id =".$_GET['id']."
								");		
								if($update){
									echo "<script>window.location='pengguna.php?success=Edit Data Berhasil'</script>";
								}else{
									echo'Gagal Update'.mysqli_error($conn);
								}
										
							}
							?>
						</div>
					</div>
				</div>
			</div>
<?php include 'footer.php'?>