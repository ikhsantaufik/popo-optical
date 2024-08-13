<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							AKUN PELANGGAN
						</div>
						<div class="box-body">
						<a href="tambah_pelanggan.php"><i class="fa fa-plus">Tambah</i></a>
						<?php
						if(isset($_GET['success']))
							echo '<div class ="alert alert-success">Edit Data Berhasil</div>';
						?>
						<form action="" method="GET" id="searchForm">
							<div class="input-grup">
								<input type="text" name="key" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['key'])?$_GET['key']:""; ?>">
								<button type="button" class="btn" onclick="clearForm()">Clear</button>
							</div>
						</form>
							<table class="table" id="responsiveTable">
								<!--untuk membungkus konten bagian judul atau kepala tabel-->
								<thead>
									<!--(tabel row)-->
									<tr>
										<th>No</th>
										<th>Username</th>
										<th>Name</th>
										<th>Alamat</th>
										<th>Telepone</th>
										<th>Email</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$no =1;
								
									$where = "WHERE 1=1 ";
									if(isset($_GET['key'])){
										//dan dari nama yang di cari
										$where.="AND nama LIKE '%".addslashes($_GET['key'])."%' ";
									}
									//cari dari tabel pengguna variabel where
									$pengguna = mysqli_query($conn, "SELECT * FROM akun_pelanggan $where ORDER BY username DESC");
									if(mysqli_num_rows($pengguna)>0){	
									// looping data 
									while ($p = mysqli_fetch_array($pengguna)){	
									
								?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $p['username']?></td>
									<td><?= $p['nama']?></td>
									<td><?= $p['alamat']?></td>
									<td><?= $p['telp']?></td>
									<td><?= $p['email']?></td>
									<td>
										<a href="edit_pelanggan.php?username=<?= $p['username'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
										<a href="hapus.php?usernamepelanggan=<?= $p['username'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
									</td>
								</tr>
									<?php }}else{ 
										echo "<tr id='noResultsRow'><td colspan='7'>Data Tidak Ada</td></tr>"?>
							<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Add jQuery library -->
<script>
$(document).ready(function(){
    function toggleTableClass() {
        if ($(window).width() < 600) {
            $('#responsiveTable').addClass('responsive');
        } else {
            $('#responsiveTable').removeClass('responsive');
        }
    }

    toggleTableClass();

    $(window).resize(function(){
        toggleTableClass();
    });

    // Script for search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#responsiveTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
        if ($('#responsiveTable tbody tr:visible').length == 0) {
            $('#noResultsRow').show();
        } else {
            $('#noResultsRow').hide();
        }
    });
});

function clearForm() {
    document.getElementById('searchForm').reset();
    window.location.href = 'akun_pelanggan.php';
}

</script>
<?php include 'footer.php'?>

