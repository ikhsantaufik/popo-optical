<!--bagian footer-->
		<footer>
			<div class="container1 text-center">
			<p class="mt-5 mb-3 text-muted"><b>Copyright <i class="fas fa-copyright"></i> 2024-<?= $d->nama ?></b></p>
			<div class="social-icons">
				<a href="index.php"><i class="fas fa-home" title="HOME"></i></a>
				<a href="kontak.php"><i class="fas fa-envelope" title="KONTAK"></i></a>
				<a style="margin-bottom: 15px;" href="https://api.whatsapp.com/send?phone=<?= $d->wa ?>&text=Saya%20membutuhkan%20bantuan%20🙏" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp" title="WHATSAPP"></i></a>
				<a style="margin-bottom: 15px;" href="<?= $d->ig ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram" title="INSTAGRAM"></i></a>
			</div>
			</div>
		</footer>
			<script type="text/javascript">
			var mobileMenu = document.getElementById("mobileMenu")
			function showMobileMenu(){
				mobileMenu.style.display = "block"
			}
			function hideMobileMenu(){
				mobileMenu.style.display = "none"
			}
			</script>
		</body>
</html>