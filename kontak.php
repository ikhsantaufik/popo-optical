<?php include 'header.php'?> 
<!--content-->
	<div class="section">
		<div class="container-utama">
		<h3 class="text-center line">KONTAK</h3>
			<div class="col-4">
			<p style="margin-bottom:15px"><b>Alamat:</b> <br><?= $d->alamat?></p><br>
			<p style="margin-bottom:15px"><b>No hp :</b> <br><?= $d->telp?></p><br>
			</div>
			<div class="box-gmaps">
			<iframe src="<?= $d->google_map?>" width="100%" height="450" style="border:0;" allowfullscreen="" 
			loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</div>
	</div>
<?php include 'footer.php'?>