<?php include '../connection.php'?>	
<?php 
	// Periksa apakah parameter 'usernameadmin' diatur
	if (isset($_GET['usernameadmin']) && $deleteStmt = $conn->prepare("DELETE FROM akun_admin WHERE username = ?")) {
		// Bind parameter dengan nilai 'usernameadmin' yang diberikan
		$deleteStmt->bind_param("s", $_GET['usernameadmin']);
	
		if ($deleteStmt->execute()) {
			header("location:akun_admin.php?pesan=hapus");
		} else {
			header("location:akun_admin.php?pesan=Berhasil_hapus");
		}	
		$deleteStmt->close();
	}
	if (isset($_GET['usernamepelanggan'])) {
		$deleteStmt = $conn->prepare("DELETE FROM akun_pelanggan WHERE username = ?");
		$deleteStmt->bind_param("s", $_GET['usernamepelanggan']);
	
		if ($deleteStmt->execute()) {
			header("location:akun_pelanggan.php?pesan=hapus");
		} else {
			header("location:akun_pelanggan.php?pesan=gagal_hapus");
		}
	
		$deleteStmt->close();
	}
?>
<?php
	if(isset($_GET['idlensa'])){
		$idlensa = $_GET['idlensa'];
		$lensa = mysqli_query($conn,"SELECT galeri FROM lensa WHERE kode_lensa = '$idlensa'");
		// jika ada variabel lensa 
		if(mysqli_num_rows($lensa) > 0){
			$p = mysqli_fetch_object($lensa);
			if(file_exists("../upload/lensa/".$p->galeri)){
				unlink("../upload/lensa/".$p->galeri);
			}
		}
		$delete = mysqli_query($conn,"DELETE FROM lensa WHERE kode_lensa ='$idlensa'");
		echo "<script>window.location='lensa.php?success=Hapus Data Berhasil'</script>";						
	}
	if(isset($_GET['idjenislensa'])){
		$idjenislensa = ($_GET['idjenislensa']); // Sanitasi idjenislensa

		$lensa = mysqli_query($conn,"SELECT galeri FROM lensa WHERE jenis_id = '$idjenislensa'");
		// jika ada variabel lensa 
		if(mysqli_num_rows($lensa) > 0){
			$p = mysqli_fetch_object($lensa);
			if(file_exists("../upload/lensa/".$p->galeri)){
				unlink("../upload/lensa/".$p->galeri);
			}
		}
		// Hapus data dari tabel frame
		$deleteLensa = mysqli_query($conn, "DELETE FROM lensa WHERE jenis_id = '$idjenislensa'");
		
		$delete = mysqli_query($conn, "DELETE FROM jenis_lensa WHERE id_jenis_lensa = '$idjenislensa'");
	
		if($delete){
			echo "<script>window.location='jenis_lensa.php?success=Hapus Data Berhasil'</script>";
		} else {
			header("location:jenis_lensa.php?pesan=Gagal_menghapus_jenis_lensa");
		}
	}

	if(isset($_GET['idframe'])){
		$idframe = $_GET['idframe'];
	
		// Query untuk mendapatkan nama galeri frame
		$frame = mysqli_query($conn, "SELECT galeri FROM frame WHERE kode_frame = '$idframe'");
		
		// Periksa apakah query berhasil dieksekusi dan hasilnya tidak kosong
		if($frame && mysqli_num_rows($frame) > 0){
			$p = mysqli_fetch_object($frame);
			
			// Hapus file gambar frame jika ada
			if(file_exists("../upload/frame/".$p->galeri)){
				unlink("../upload/frame/".$p->galeri);
			}
		}
		
		// Hapus data frame dari database
		$delete = mysqli_query($conn, "DELETE FROM frame WHERE kode_frame = '$idframe'");
	
		if($delete){
			echo "<script>window.location='frame.php?success=Hapus Data Berhasil'</script>";
		} else {
			header("location:frame.php?pesan=gagal");
		}
	}
	
	if(isset($_GET['idjenisframe'])){
		$idjenisframe = $_GET['idjenisframe'];
	
		
		// Query untuk mendapatkan nama galeri frame
		$frame = mysqli_query($conn, "SELECT galeri FROM frame WHERE jenis_id = '$idjenisframe'");
		
		// Periksa apakah query berhasil dieksekusi dan hasilnya tidak kosong
		if($frame && mysqli_num_rows($frame) > 0){
			$p = mysqli_fetch_object($frame);
			
			// Hapus file gambar frame jika ada
			if(file_exists("../upload/frame/".$p->galeri)){
				unlink("../upload/frame/".$p->galeri);
			}
		}
		
		// Hapus data frame dari database
		$delete = mysqli_query($conn, "DELETE FROM frame WHERE jenis_id = '$idjenisframe'");

		// Hapus data dari tabel jenis_frame
		$deleteJenisFrame = mysqli_query($conn, "DELETE FROM jenis_frame WHERE id_jenis = '$idjenisframe'");
	
		if($deleteJenisFrame){
			echo "<script>window.location='jenis_frame.php?success=Hapus Data Berhasil'</script>";
		} else {
			header("location:jenis_frame.php?pesan=Gagal");
		}
	}	
	
	if(isset($_GET['idinformasi'])){
		$informasi = mysqli_query($conn,"SELECT galeri FROM informasi WHERE id_informasi = ".$_GET['idinformasi']);
		// jika ada variabel informasi 
		if(mysqli_num_rows($informasi) > 0){
			$p = mysqli_fetch_object($informasi);
			if(file_exists("../upload/informasi/".$p->galeri)){
				unlink("../upload/informasi/".$p->galeri);
			}
		}
		$delete = mysqli_query($conn,"DELETE FROM informasi WHERE id_informasi =".$_GET['idinformasi']);
		echo "<script>window.location='informasi.php?success=Hapus Data Berhasil'</script>";				
	}

	if(isset($_GET['idpendaftaran'])){
		$idPendaftaran = $_GET['idpendaftaran'];
	
		// Hapus file terkait tindakan jika ada
		$deleteTindakanQuery = mysqli_query($conn, "SELECT file_tindakan FROM tindakan WHERE pendaftaran_id = '$idPendaftaran'");
		while($p = mysqli_fetch_object($deleteTindakanQuery)){
			if(file_exists("../upload/lampiran/".$p->file_tindakan)){
				unlink("../upload/lampiran/".$p->file_tindakan);
			}
		}
	
		// Hapus data dari tabel tindakan
		$deleteTindakan = mysqli_query($conn, "DELETE FROM tindakan WHERE pendaftaran_id = '$idPendaftaran'");
	
		// Hapus entri dari tabel pendaftaran
		$deletePendaftaran = mysqli_query($conn, "DELETE FROM pendaftaran WHERE id_pendaftaran = $idPendaftaran");
	
		if($deletePendaftaran){
			echo "<script>window.location='pendaftaran.php?success=Data Berhasil Dihapus'</script>"; 
		} else {
			echo "Gagal menghapus data pendaftaran.";
		}
		
	}	

	if(isset($_GET['idpesanan'])){
		$idPesanan = $_GET['idpesanan'];

		$check_query = "SELECT * FROM tindakan WHERE pendaftaran_id = '$idPesanan'";
		$check_result = mysqli_query($conn, $check_query);
		$aTindakan = mysqli_fetch_object($check_result);
	
		// Hapus data dari tabel pesanan_frame
		$deleteFrame = mysqli_query($conn, "DELETE FROM pesanan_frame WHERE pesanan_kode = '$idPesanan'");
		
		// Hapus data dari tabel pesanan_lensa
		$deleteLensa = mysqli_query($conn, "DELETE FROM pesanan_lensa WHERE pesanan_kode = '$idPesanan'");
		
		// Hapus data dari tabel pesanan
		$deletePesanan = mysqli_query($conn, "DELETE FROM pesanan WHERE kode_pesanan = '$idPesanan'");
	
		if($deletePesanan){
			echo "<script>window.location='pendaftaran.php?success=Data Berhasil Dihapus'</script>";	
		} else {
			echo "Gagal menghapus data pesanan.";
		}
	}	
?>
