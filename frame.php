<?php include 'header.php'?> 
<!--content-->
	<div class="section">
		<div class="container-utama">
		<h3 class="text-center line">FRAME</h3>
		<?php
			// Query untuk mengambil data dari tabel 'frame' dan 'jenis_frame' dengan menggabungkannya
			$frame_query = mysqli_query($conn, "SELECT frame.*, jenis_frame.jenis_frame
			FROM frame 
			LEFT JOIN jenis_frame ON frame.jenis_id = jenis_frame.id_jenis 
			ORDER BY frame.kode_frame DESC");

			// Periksa apakah query berhasil dijalankan
			if (!$frame_query) {
			die("Error: " . mysqli_error($conn));
			}

			// Periksa apakah ada baris yang dikembalikan oleh query
			if(mysqli_num_rows($frame_query) > 0) {
			//looping data
			while ($p = mysqli_fetch_array($frame_query)) {
		?>
			<div class="col-4">
				<a href="detail_frame.php?id=<?= $p['kode_frame']?>" class="thumbnaill-link">
					<div class="thumbnaill-box">
						<div class="thumbnaill-img" style="	background-image:url('upload/frame/<?= $p['galeri']?>');" >
						</div>
						<div class="thumbnaill-text text-center">
						<?= substr($p['jenis_frame'],0,50) ?><br>
						<?= substr($p['nama_frame'],0,50) ?><br>
						<?php 
							if($p['stok_frame']<1 || $p['status_frame']=="Tidak Tersedia"){
								echo "Tidak Tersedia";
							}else{
								echo 'Rp ' . number_format($p['harga_frame'], 0, ',', '.') ;
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