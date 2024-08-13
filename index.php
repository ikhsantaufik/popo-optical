<?php include 'header.php';?> 
<!--content-->
		<!--bagian banner-->
		<div class="banner" style="	background-image:url('upload/identitas/<?= $d->foto?>">
			<div class="text">
					<h2>Selamat Datang Di <?= $d->nama?></h2>
					<p><?= $d->tagline?></p>
			</div>
		</div><hr size="5" noshade>
		<!--informasi-->
		<div class="section">
			<div class="container-utama text-center">
				<h3 class="line">INFORMASI TERBARU</h3>
				<?php
					$informasi = mysqli_query($conn, "SELECT * FROM informasi ORDER BY id_informasi DESC LIMIT 8");
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
			</div><a href="informasi.php" style="display: block; text-align: right; font-size: 20px; padding:0px 30px;">Lebih Banyak  <i class="fa-solid fa-arrow-right" title="Tampilkan Lebih Banyak"></i></a>
		</div><hr size="5" noshade>
		<!--bagian lensa-->
		<div class="section" id="lensa">
			<div class="container-utama text-center">
				<h3 class="line">BERBAGAI LENSA</h3>
					<?php
					$lensa = mysqli_query($conn, "SELECT * FROM lensa ORDER BY kode_lensa DESC limit 8");
					if(mysqli_num_rows($lensa) > 0){
					// looping data 
					while ($l = mysqli_fetch_array($lensa)){	
					?>
						<div class="col-4">
							<a href="detail_lensa.php?id=<?= $l['kode_lensa']?>" class="thumbnaill-link">
								<div class="thumbnaill-box">
									<div class="thumbnaill-img" style="	background-image:url('upload/lensa/<?= $l['galeri']?>');" >
									</div>
									<div class="thumbnaill-text text-center">
									<?= $l['nama_lensa'] ?><br>
									<?php 
										if($l['stok_lensa']<1 || $l['status_lensa']=="Tidak Tersedia"){
											echo "Tidak Tersedia";
										}else{
											echo 'Rp ' . number_format($l['harga_lensa'], 0, ',', '.') ;
										}
										
									?>
									</div>
								</div>
								</a>
						</div>
				<?php }}else{ ?>
				
				Tidak Ada Data
				
				<?php } ?>
			</div><a href="lensa.php" style="display: block;text-align: right; font-size: 20px; padding:0px 30px;">Lebih Banyak  <i class="fa-solid fa-arrow-right" title="Tampilkan Lebih Banyak"></i></a>
		</div><hr size="5" noshade >
		<!--bagian frame--> 
		<div class="section">
			<div class="container-utama text-center">
				<h3 class="line">BERBAGAI FRAME</h3>
					<?php
						$frame = mysqli_query($conn, "SELECT * FROM frame ORDER BY frame.kode_frame DESC LIMIT 8");
						if(mysqli_num_rows($frame)>0){	
						//looping data		 
						while ($f = mysqli_fetch_array($frame)){
					?>
						<div class="col-4">
							<a href="detail_frame.php?id=<?= $f['kode_frame']?>" class="thumbnaill-link">
								<div class="thumbnaill-box">
									<div class="thumbnaill-img" style="	background-image:url('upload/frame/<?= $f['galeri']?>');" >
									</div>
									<div class="thumbnaill-text text-center">
									<?= $f['nama_frame'] ?><br>
									<?php 
										if($f['stok_frame']<1 || $f['status_frame']=="Tidak Tersedia"){
											echo "Tidak Tersedia";
										}else{
											echo 'Rp ' . number_format($f['harga_frame'], 0, ',', '.') ;
										}
										
									?>
									</div>
								</div>
								</a>
						</div>
				<?php }}else{ ?>
				
				Tidak Ada Data
				
				<?php } ?>
			</div><a href="frame.php" style="display: block; text-align: right; font-size: 20px; padding:0px 30px;">Lebih Banyak  <i class="fa-solid fa-arrow-right" title="Tampilkan Lebih Banyak"></i></a>
		</div><hr size="5" noshade>
<?php include 'footer.php'?>