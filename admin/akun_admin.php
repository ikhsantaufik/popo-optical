<?php include 'header.php'?>			
			<!--content-->
			<div class="content">
				<div class="container1">
					<div class="box">
						<div class="box-header">
							AKUN ADMIN
						</div>
						<div class="box-body">
						<a href="tambah_admin.php"><i class="fa fa-plus">Tambah</i></a>
						<?php
						if(isset($_GET['success']))
							echo '<div class ="alert alert-success">Edit Data Berhasil</div>';
						if(isset($_GET['hapus']))
							echo '<div class ="alert alert-error">Hapus Data Berhasil</div>';
						?>
						<form action="" methode="GET" id="searchForm">
							<div class="input-grup">
								<input type="text" id="searchInput"name="key" placeholder="Pencarian">
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
										<th>Username</th>
										<th>Name</th>
										<th>Alamat</th>
										<th>Telepone</th>
										<th>Email</th>
										<th>Role</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$no =1;
								
									$where = "WHERE 1=1 ";
									//cari dari tabel pengguna variabel where
									$pengguna = mysqli_query($conn, "SELECT * FROM akun_admin $where ORDER BY username DESC");
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
									<td><?= $p['role']?></td>
									<td>
										<a href="edit_admin.php?username=<?= $p['username'] ?>" title="Edit Data"><i class="fa fa-edit"></i></a>
										<a href="hapus.php?usernameadmin=<?= $p['username'] ?>" title="Hapus Data" onclick="return confirm('yakin ingin dihapus?')"><i class="fa fa-times"></i></a>
									</td>
								</tr>
									<?php }} else {
										echo "<tr id='noResultsRow'><td colspan='8'>Data Tidak Ada</td></tr>";
									}?>
								</tbody>
							</table>
						</div>
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
    window.location.href = 'akun_admin.php';
}

</script>
<?php include 'footer.php'?>