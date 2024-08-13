<?php include 'header.php'?>		
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							KONTAK
						</div>
						<div class="box-body">
						<?php
						if(isset($_GET['success']))
							echo '<div class ="alert alert-success">Edit Data Berhasil</div>';
						?>
						<form action="" method="post" enctype="multipart/form-data">
								<div class="form-group">
								<!--elemen yang dapat menyimpan kata dan dapat diperluas jika pengguna memasukkan lebih banyak text sehingga dapat dimasukkan pada elemen text area-->
									<label>Alamat		:</label><br><br>
									<textarea name="alamat" placeholder="Alamat" class="input-control" id="keterangan"><?= $d->alamat?></textarea>
								</div><br>
								<div class="form-group">
									<label>Telepon		:</label><br><br>
									<input type="number" name="telepon" placeholder="Telepon" class="input-control" value="<?= $d->telp?>" required>
								</div><br>
								<div class="form-group">
									<label>Whatsapp		:</label><br><br>
									<input type="number" name="wa" placeholder="whatsapp" class="input-control" value="<?= $d->wa?>" required>
								</div><br>
								<div class="form-group">
									<label>Instagram	:</label><br><br>
									<textarea name="ig" placeholder="Instagram" class="input-control"><?= $d->ig?></textarea>
								</div><br>
								<div class="form-group">
								<!--elemen yang dapat menyimpan kata dan dapat diperluas jika pengguna memasukkan lebih banyak text sehingga dapat dimasukkan pada elemen text area-->
									<label>Google Map	:</label><br><br>
									<textarea name="google_map" placeholder="Google Map" class="input-control"><?= $d->google_map?></textarea>
								</div><br>
								<button type="submit" name="submit" class="btn">SIMPAN PERUBAHAN</button>
							</form>
							
							<?php
							//ketika tombol submit di klik apa yang akan terjadi 
							if(isset($_POST['submit'])){
								$alamat 	= (addslashes($_POST['alamat']));
								$telepon 	= htmlspecialchars(addslashes(ucwords($_POST['telepon'])));
                                $wa 		= htmlspecialchars(addslashes(ucwords($_POST['wa'])));
								$ig = $_POST['ig'];
								$google_map = $_POST['google_map'];
								$currdate 	= date('Y-m-d H:i:s');
								//jika didalam  name gambar ada name yang ada di gambar jika tidak sama dengan 0 maka ada 
			
                                
								$update = mysqli_query($conn, "UPDATE setting SET 
									alamat ='".$alamat."',
									telp ='".$telepon."',
									wa ='".$wa."',
									ig ='".$ig."',
									google_map ='".$google_map."',
									updated_at ='".$currdate."'
									WHERE id =".$d->id."
								");
		
								if($update){
									echo "<script>window.location='kontak.php?success=Edit Data Berhasil'</script>";
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

