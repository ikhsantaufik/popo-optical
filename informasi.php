<?php include 'header.php'?> 
<!--content-->
	<div class="section">
		<div class="container-utama">
		<h3 class="text-center line">INFORMASI</h3>
		<?php
			$informasi = mysqli_query($conn, "SELECT * FROM informasi ORDER BY id_informasi DESC");
			if(mysqli_num_rows($informasi)>0){	
			//looping data		 
			while ($p = mysqli_fetch_array($informasi)){
		?>
			<div class="col-4">
				<a href="detail_informasi.php?id=<?= $p['id_informasi']?>" class="thumbnaill-link">
					<div class="thumbnaill-box">
						<div class="thumbnaill-img" style="	background-image:url('upload/informasi/<?= $p['galeri']?>');" >
						</div>
						<div class="thumbnaill-text text-center">
						<?= substr($p['judul'],0,50) ?>
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