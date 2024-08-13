<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							JENIS LENSA
						</div>
						<div class="box-body">
						<a href="tambah_jenis_lensa.php"><i class="fa fa-plus">Tambah</i></a>
						<?php
						if(isset($_GET['success'])){
							echo "<div class='alert alert-success'>".$_GET['success']."</div>";
						}
						?>
						<form action="" id="searchForm">
							<div class="input-grup">
								<input type="text" name="key" id="searchInput" placeholder="Jenis lensa" value="<?php echo isset($_GET['key']) ? $_GET['key'] : ''; ?>">
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
										<th>Id Jenis Lensa</th>
										<th>Jenis Lensa</th>
										<th>Deskripsi Lensa</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$no =1;
								
								$where = "WHERE 1=1 ";
								if(isset($_GET['key'])){
									// search based on the 'jenis_lensa' column
									$where.="AND jenis_lensa LIKE '%".addslashes($_GET['key'])."%' ";
								}
									//cari dari tabel frame variabel where
									$frame = mysqli_query($conn, "SELECT * FROM jenis_lensa $where ORDER BY id_jenis_lensa DESC");
									if(mysqli_num_rows($frame)>0){	
									// looping data 
									while ($p = mysqli_fetch_array($frame)){	
									
								?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $p['id_jenis_lensa']?></td>
									<td><?= $p['jenis_lensa']?></td>
									<td><?= $p['deskripsi_jenis']?></td>
									<td>
										<a href="edit_jenis_lensa.php?id=<?= $p['id_jenis_lensa'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
										<a href="hapus.php?idjenislensa=<?= $p['id_jenis_lensa'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
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
			window.location.href = 'jenis_lensa.php';
		}
	</script>
<?php include 'footer.php'?>

