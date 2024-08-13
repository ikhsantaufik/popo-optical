<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							INFORMASI
						</div>
						<div class="box-body">
						<a href="tambah_informasi.php"><i class="fa fa-plus">Tambah</i></a>
						<?php
						if(isset($_GET['success'])){
							echo "<div class='alert alert-success'>".$_GET['success']."</div>";
						}
						?>
						<form action="" method="GET" id="searchForm">
							<div class="input-grup">
								<input type="text" name="key" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['key']) ? $_GET['key'] :""; ?>">
								<button type="button" class="btn" onclick="clearForm()">Clear</button>
							</div>
						</form>
						<br>
            				<div class="horizontal">
							<table class="table horizontal" id="responsiveTable">
								<!--untuk membungkus konten bagian judul atau kepala tabel-->
								<thead>
									<!--(tabel row)-->
									<tr>
										<th>No</th>
										<th>Judul</th>
										<th>Deskripsi</th>
										<th>Galeri</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$no = 1;
									$where = "WHERE 1=1 ";
									//cari dari tabel informasi variabel where
									$informasi = mysqli_query($conn, "SELECT * FROM informasi $where ORDER BY id_informasi DESC");

									if (mysqli_num_rows($informasi) > 0) {
										while ($i = mysqli_fetch_array($informasi)) {
                       			 ?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $i['judul']?></td>
									<td><?= $i['deskripsi']?></td>
									<td><img src="../upload/informasi/<?= $i['galeri']?>" width="100px"></td>
									<td>
										<a href="edit_informasi.php?id=<?= $i['id_informasi'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
										<a href="hapus.php?idinformasi=<?= $i['id_informasi'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
									</td>
								</tr>
									<?php }}else{ ?>
									<td colspan="5">Data Tidak Ada</td>	
							<?php } ?>
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
			</div>
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
			window.location.href = 'informasi.php';
		}
	</script>
		
<?php include 'footer.php'?>

