<?php include 'header.php'?>
<?php
session_start();

// Jika pengguna sudah login, tampilkan isi keranjang atau halaman keranjang
?>
			<!--content-->
			<div class="content"> 
            <div class="container1"> 
            <button type="button"class="btn" onclick="window.location ='index.php'">KEMBALI</button>
                <button type="submit" name="submit" class="btn">SIMPAN</button>
							<?php
                            // Periksa apakah pengguna sudah login
                            if (!isset($_SESSION['submit'])) {
                                // Jika belum login, arahkan ke halaman login
                                header("Location: login.php");
                                exit();
                            }
                            ?>
                </div>
            </div>
			
	<?php include 'footer.php' ?>