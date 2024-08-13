<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							FRAME
						</div>
						<div class="box-body">
						<a href="tambah_frame.php"><i class="fa fa-plus">Tambah</i></a>
						<?php
						if(isset($_GET['success'])){
							echo "<div class='alert alert-success'>".$_GET['success']."</div>";
						}
						?>
						<form action="" methode="GET" id="searchForm" onsubmit="clearDateIfEmpty()">
							<div class="input-grup">
								<input type="text" name="key" id="searchInput" placeholder="Pencarian" value="<?php echo isset($_GET['key']) ? $_GET['key']:"";?>">
								<button type="button" class="btn" onclick="clearForm()">Clear</button>
							</div>
							<br>
            				<div class="horizontal">
							<table class="table horizontal" id="responsiveTable">
								<!--untuk membungkus konten bagian judul atau kepala tabel-->
								<thead>
									<!--(tabel row)-->
									<tr>
										<th>No</th>
										<th>Kode Frame</th>
										<th>Nama Frame</th>
										<th>Deskripsi Frame</th>
										<th>Stok Frame</th>
										<th>Harga Frame</th>
										<th>Status Frame</th>
										<th>Galeri</th>
										<th>Jenis Frame</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$no =1;
								
								$where = "WHERE 1=1 ";
									//cari dari tabel frame variabel where
									$frame = mysqli_query($conn, "SELECT * FROM frame $where ORDER BY kode_frame DESC");
									if (mysqli_num_rows($frame) > 0) {
										// looping data 
										while ($p = mysqli_fetch_array($frame)) {
											if ($p['stok_frame'] < 1) {
												$update_status = "UPDATE frame SET status_frame='Tidak Tersedia' WHERE kode_frame='".$p['kode_frame']."'";
												mysqli_query($conn, $update_status);
												$p['status_frame'] = 'Tidak Tersedia'; // Update the status in the current result set
											}
								?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $p['kode_frame']?></td>
									<td><?= $p['nama_frame']?></td>
									<td><?= $p['deskripsi_frame']?></td>
									<td><?= $p['stok_frame']?></td>
									<td>Rp.<?= number_format($p['harga_frame'], 0, ",", ".")?></td>
									<td><?= $p['status_frame']?></td>
									<td><img src="../upload/frame/<?= $p['galeri']?>" width="100px"></td>
									<td><?= $p['jenis_id']?></td>
									<td>
										<a href="edit_frame.php?id=<?= $p['kode_frame'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
										<a href="hapus.php?idframe=<?= $p['kode_frame'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
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
	function clearForm() {
		document.getElementById('searchForm').reset();
		window.location.href = 'frame.php';
	}
	</script>
<?php include 'footer.php'?>

