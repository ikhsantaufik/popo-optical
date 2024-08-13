<?php include 'header.php'?> 
<!--content-->
	<div class="section">
		<div class="container-utama">
		<h3 class="text-center line">LENSA</h3>
		<?php
			// Query untuk mengambil data dari tabel 'lensa' dan 'jenis_lensa' dengan menggabungkannya
			$lensa_query = mysqli_query($conn, "SELECT lensa.*, jenis_lensa.jenis_lensa 
			FROM lensa 
			LEFT JOIN jenis_lensa ON lensa.jenis_id = jenis_lensa.id_jenis_lensa 
			ORDER BY lensa.kode_lensa DESC");

			// Periksa apakah query berhasil dijalankan
			if (!$lensa_query) {
			die("Error: " . mysqli_error($conn));
			}

			// Periksa apakah ada baris yang dikembalikan oleh query
			if(mysqli_num_rows($lensa_query) > 0) {
			//looping data
			while ($p = mysqli_fetch_array($lensa_query)) {
		?>
		
			<div class="col-4">
				<a href="detail_lensa.php?id=<?= $p['kode_lensa']?>" class="thumbnaill-link">
					<div class="thumbnaill-box">
						<div class="thumbnaill-img" style="	background-image:url('upload/lensa/<?= $p['galeri']?>');" >
						</div>
						<div class="thumbnaill-text text-center">
						<?= substr($p['jenis_lensa'],0,50) ?><br>
						<?= substr($p['nama_lensa'],0,50) ?><br>
						<?php 
							if($p['stok_lensa']<1 || $p['status_lensa']=="Tidak Tersedia"){
								echo "Tidak Tersedia";
							}else{
								echo 'Rp ' . number_format($p['harga_lensa'], 0, ',', '.') ;
							}
							
						?>
						</div>
					</div>
				</a>
			</div>
			<?php }}else{ ?>
				
				Tidak Ada Data
				
			<?php } ?>
		</div>
	</div>
<?php include 'footer.php'?>