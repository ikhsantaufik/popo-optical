<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							LENSA
						</div>
						<div class="box-body">
						<a href="tambah_lensa.php"><i class="fa fa-plus">Tambah</i></a>
						<?php
						if(isset($_GET['success']))
							echo '<div class ="alert alert-success">Edit Data Berhasil</div>';
						?>
						<form action="" method="GET" id="searchForm" onsubmit="clearDateIfEmpty()">
							<div class="input-grup">
								<input type="text" name="key" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['key']) ? $_GET['key']:"";?>">
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
										<th>Kode Lensa</th>
										<th>Nama Lensa</th>
										<th>Deskripsi Lensa</th>
										<th>Stok Lensa</th>
										<th>Harga Lensa</th>
										<th>Status Lensa</th>
										<th>Jenis Lensa</th>
										<th>Galeri</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$no =1;
								
									$where = "WHERE 1=1 ";
									if(isset($_GET['key'])){
										//dan dari nama yang di cari
										$where.="AND nama_lensa LIKE '%".addslashes($_GET['key'])."%' ";
									}
									if(isset($_GET['key'])){
										//dan dari nama yang di cari
										$where.="AND jenis_id LIKE '%".addslashes($_GET['lensa'])."%' ";
									}
									//cari dari tabel lensa variabel where
									$lensa = mysqli_query($conn, "SELECT * FROM lensa $where ORDER BY kode_lensa DESC");
									if (mysqli_num_rows($lensa) > 0) {
										// looping data 
										while ($p = mysqli_fetch_array($lensa)) {
											if ($p['stok_lensa'] < 1) {
												$update_status = "UPDATE lensa SET status_lensa='Tidak Tersedia' WHERE kode_lensa='".$p['kode_lensa']."'";
												mysqli_query($conn, $update_status);
												$p['status_lensa'] = 'Tidak Tersedia'; // Update the status in the current result set
											}
								?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $p['kode_lensa']?></td>
									<td><?= $p['nama_lensa']?></td>
									<td><?= $p['deskripsi_lensa']?></td>
									<td><?= $p['stok_lensa']?></td>
									<td>Rp.<?= number_format($p['harga_lensa'], 0, ",", ".")?></td>
									<td><?= $p['status_lensa']?></td>
									<td><?= $p['jenis_id']?></td>
									<td><img src="../upload/lensa/<?= $p['galeri']?>" width="100px"></td>
									<td>
										<a href="edit_lensa.php?id=<?= $p['kode_lensa'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
										<a href="hapus.php?idlensa=<?= $p['kode_lensa'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
									</td>
								</tr>
									<?php }}else{ ?>
									<td colspan="9">Data Tidak Ada</td>	
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
        document.getElementById('searchForm').reset(); // Gunakan metode reset() untuk mengosongkan form
        window.location.href = 'lensa.php'; // Ganti 'namafile.php' dengan nama file PHP yang berisi kode
    }
	function clearDateIfEmpty() {
    var key = document.getElementById('key').value;
    if (key === '') {
        document.getElementById('key').removeAttribute('name');
    }
    var lensa = document.getElementById('lensa').value;
    if (lensa === '') {
        document.getElementById('lensa').removeAttribute('name');
    }
}
</script>
	</script>
<?php include 'footer.php'?>

