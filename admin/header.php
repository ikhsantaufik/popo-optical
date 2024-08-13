<?php
	session_start();
	include '../connection.php';
	date_default_timezone_set("Asia/Jakarta");
	if(!isset($_SESSION['status_login'])){
		header("location:../admin/login_admin.php?msg=Harap Login Terlebih Dahulu!");
	}
	if($_SESSION['urole'] == 'pemilik' || $_SESSION['urole'] == 'admin' ){
	
	}else{
		header("location:../admin/login_admin.php?msg=Harap Login Terlebih Dahulu!");
	}
	$identitas =mysqli_query($conn, "SELECT * FROM setting ORDER BY id  DESC LIMIT 1");
	$d = mysqli_fetch_object($identitas );
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="icon" href="../upload/identitas/<?= $d->favicon?>">
		<title>panel Admin - <?= $d->nama?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">  
		<meta charset ="utf-8">  
		<link href="../css/style.css" rel="stylesheet" type="text/css">
		<!--menampilkan tanda panah di class dropdown nama class="fa fa-caret-down"-->
		<!--Ajax-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
		<!--href pengaturan icon-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
		integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
		crossorigin="anonymous" referrerpolicy="no-referrer" />
		<!--pengaturan tint editor untuk keterangan-->
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script src="https://cdn.tiny.cloud/1/bbsi9ju4ebqbi67sod54b9xm6xktbvztpgvvbldtmq5pavai/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
		<script>
		tinymce.init({
			selector: '#keterangan',
			height: 300, // Set your preferred height
			plugins: 'autoresize link',
			toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor',
			menubar: false,
			statusbar: false,
			resize: 'both',
			autoresize_bottom_margin: 16,
			content_style: 'a, .pointer { cursor: pointer; }', // Changes cursor to pointer for links and points
			setup: function (editor) {
				editor.ui.registry.addToggleButton('forecolor', {
					icon: 'forecolor',
					tooltip: 'Text Color',
					onAction: function (_) {
						editor.execCommand('mceToggleFormat', false, 'forecolor');
					},
					onSetup: function (api) {
						var pickerCallback = function (value) {
							return function () {
								editor.execCommand('ForeColor', false, value);
							};
						};
						api.onRenderToggleMenu(function (buttonApi) {
							var menu = buttonApi.menu;
							var colorPicker = new tinymce.ui.ColorPicker({
								onSelect: pickerCallback('#$value'),
								columns: 5,
								presets: [
									'000000', '993300', '333300', '003300', '003366', '000066', '333399', '333333',
									'800000', 'FF6600', '808000', '008000', '008080', '0000FF', '666699', '808080',
									'FF0000', 'FF9900', '99CC00', '339966', '33CCCC', '3366FF', '800080', '999999',
									'FF00FF', 'FFCC00', 'FFFF00', '00FF00', '00FFFF', '00CCFF', '993366', 'C0C0C0',
									'FF99CC', 'FFCC99', 'FFFF99', 'CCFFCC', 'CCFFFF', '99CCFF', 'CC99FF', 'FFFFFF'
								]
							});
							menu.add(colorPicker).show();
						});
						editor.on('init', function (e) {
							editor.formatter.register('forecolor', {
								inline: 'span',
								styles: { color: '%value' },
								remove_similar: true
							});
						});
						return function () {
							var nodeChangeHandler = function (api) {
								return function (e) {
									var color = editor.dom.getStyle(editor.selection.getNode(), 'color', true);
									var isDifferent = function (c1, c2) {
										var rgb1 = tinymce.util.ColorMap.colorToRgb(c1);
										var rgb2 = tinymce.util.ColorMap.colorToRgb(c2);
										return (
											rgb1.r !== rgb2.r || rgb1.g !== rgb2.g || rgb1.b !== rgb2.b || rgb1.a !== rgb2.a
										);
									};
									if (color) {
										api.icon(color !== 'transparent' ? 'forecolor' : 'forecolor-trans');
										var items = menu.getItems();
										for (var i = 0; i < items.length; i++) {
											var item = items[i];
											if (item.settings.value && isDifferent(color, item.settings.value)) {
												item.active(isDifferent(color, item.settings.value));
											} else {
												item.active(
													item.settings.value === color ||
													item.settings.value === tinymce.util.ColorMap.tint(color, '#ffffff', 20)
												);
											}
										}
									} else {
										api.icon('forecolor');
									}
								};
							};
							editor.on('NodeChange', nodeChangeHandler(api));
						};
					}
				});

				editor.ui.registry.addButton('link', {
					icon: 'link',
					tooltip: 'Insert/edit link',
					onAction: function (_) {
						var selected_text = editor.selection.getContent({format: 'text'});
						var selected_html = editor.selection.getContent({format: 'html'});
						editor.windowManager.open({
							title: 'Insert/edit link',
							body: [
								{
									type: 'textbox',
									name: 'href',
									label: 'URL',
									value: '',
									autofocus: true
								},
								{
									type: 'textbox',
									name: 'text',
									label: 'Link text',
									value: selected_text
								},
								{
									type: 'textbox',
									name: 'title',
									label: 'Title',
									value: '',
								}
							],
							onsubmit: function (e) {
								if (e.data.text.trim() !== '') {
									if (e.data.href.trim() === '') {
										editor.execCommand('mceInsertContent', false, '<a href="' + e.data.href + '" title="' + e.data.title + '">' + e.data.text + '</a>');
									} else {
										editor.execCommand('mceInsertContent', false, '<a href="' + e.data.href + '" title="' + e.data.title + '">' + e.data.text + '</a>');
									}
								}
							}
						});
					}
				});

				editor.ui.registry.addMenuButton('list', {
					text: 'List',
					fetch: function (callback) {
						var items = [
							{
								type: 'menuitem',
								text: 'Numbers',
								onAction: function (_) {
									editor.execCommand('InsertOrderedList');
								}
							},
							{
								type: 'menuitem',
								text: 'Letters',
								onAction: function (_) {
									editor.execCommand('InsertUnorderedList');
								}
							},
							{
								type: 'menuitem',
								text: 'Symbols',
								onAction: function (_) {
									editor.execCommand('InsertUnorderedList', false, { 'style': 'list-style-type: none' });
									editor.execCommand('mceInsertContent', false, '<li>&#8226; </li><li>&#9679; </li><li>&#10003; </li>');
								}
							}
						];
						callback(items);
					}
				});
			}
		});
	</script>
	<style>
      @media screen and (max-width: 689px) {
				.container {
				width: 100%;
				height: auto; /* Atur kembali tinggi menjadi otomatis */
				position: relative; /* Kembalikan posisi menjadi relatif */
				display: block; /* Atur tampilan menjadi blok */
				z-index: auto; /* Kembalikan z-index ke nilai default */
			}
			.container1 {
				margin-left: auto; /* Mengatur margin kiri menjadi otomatis */
				margin-right: auto; /* Mengatur margin kanan menjadi otomatis */
				width: 90%; /* Contoh lebar konten menjadi 90% dari lebar layar */
			}
			.nav-menu {
            display: none;
			}

			.mobile-menu-btn {
				display: block;
				text-align: right;
			}

			/* Gaya untuk menu mobile */
			.box-menu-mobile {
				display: none;
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-color: rgba(0, 0, 0, 0.5); /* Warna latar belakang semi transparan */
				z-index: 9999;
				overflow-y: auto; /* Biarkan konten dapat di-scroll jika terlalu panjang */
			}

			.box-menu-mobile ul {
				list-style: none;
				padding: 0;
			}

			.box-menu-mobile ul li {
				margin-bottom: 10px;
			}

			.box-menu-mobile ul li a {
				color: #ffffff; /* Warna teks */
				text-decoration: none;
			}

			/* Style untuk ikon "Close" */
			.box-menu-mobile span {
				color: #ffffff; /* Warna teks */
				cursor: pointer;
			}

			/* Style untuk tautan menu */
			.box-menu-mobile ul li a {
				display: block;
				padding: 10px;
				border-radius: 5px;
				background-color: #007bff; /* Warna latar belakang */
				transition: background-color 0.3s ease; /* Efek transisi */
			}

			.box-menu-mobile ul li a:hover {
				background-color: #0056b3; /* Warna latar belakang saat digulir */
			}

			/* Style untuk dropdown */
			.box-menu-mobile ul ul {
				margin-left: 20px;
			}

			/* Style untuk dropdown tautan */
			.box-menu-mobile ul ul li a {
				background-color: #0056b3; /* Warna latar belakang */
			}
			.content {
				padding-top: 130px;
			}
			#responsiveTable {
				overflow-x: auto; /* Memungkinkan pengguna untuk menggulir tabel secara horizontal */
				width: 100%; /* Lebar tabel akan menyesuaikan dengan lebar parent element */
			}
        }
    </style>
	</head>
	<body class="bg-light">
	<div class="box-menu-mobile" id="mobileMenu">
			<span onClick="hideMobileMenu()">Close</span><br><br>
        <ul class="text-center" id="mobileMenu">
        <!-- Mobile menu items -->
        <li><a href="index.php"><i class="fa-solid fa-house"></i> DASHBOARD</a></li>
        <?php if($_SESSION['urole'] == 'pemilik'){?>
            <li><a href="akun_admin.php"><i class="fa-solid fa-user"></i> AKUN ADMIN</a></li>
            <li><a href="akun_pelanggan.php"><i class="fa-solid fa-user"></i> AKUN PELANGGAN</a></li>
            <li>
                <a href="#"><i class="fa-regular fa-file-lines"></i> LAPORAN<i class="fa fa-caret-down"></i></a>
                <ul class="dropdown">
                    <li><a href="laporan_pendaftaran.php">LAPORAN PENDAFTARAN</a></li>
                    <li><a href="laporan_pesanan.php">LAPORAN PESANAN</a></li>
                </ul>
            </li>
        <?php }elseif($_SESSION['urole'] == 'admin'){?>
            <li><a href="pendaftaran.php"><i class="fa-solid fa-file-pen"></i> PENDAFTARAN</a></li>
            <li><a href="pesanan.php"><i class="fa-solid fa-cart-shopping"></i> HISTORY PESANAN</a></li>
            <li>
                <a href="#"><i class="fa-solid fa-glasses"></i> FRAME<i class="fa fa-caret-down"></i></a>
                <ul class="dropdown">
                    <li><a href="frame.php">FRAME</a></li>
                    <li><a href="jenis_frame.php">JENIS FRAME</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa-solid fa-circle"></i> LENSA<i class="fa fa-caret-down"></i></a>
                <ul class="dropdown">
                    <li><a href="lensa.php">LENSA</a></li>
                    <li><a href="jenis_lensa.php">JENIS LENSA</a></li>
                </ul>
            </li>
            <li><a href="informasi.php"><i class="fa-solid fa-circle-info"></i> INFORMASI</a></li>
            <li>
                <a href="#"><i class="fa-solid fa-gear"></i> PENGATURAN<i class="fa fa-caret-down"></i></a>
                <!--sub menu-->
                <ul class="dropdown">
                    <li><a href="identitas.php">IDENTITAS OPTIK</a></li>
                    <li><a href="kontak.php">KONTAK</a></li>
                </ul>
            </li>
        <?php }?>
        <li>
            <a href="#"><i class="fa-solid fa-user"></i> <?= $_SESSION['unama']?>(<?= $_SESSION['urole']?>)<i
                        class="fa fa-caret-down"></i></a>
            <!--sub menu-->
            <ul class="dropdown">
                <li><a href="ubah_password.php">UBAH PASSWORD</a></li>
                <li><a href="logout.php">KELUAR</a></li>
            </ul>
        </li>
    </ul>
</div>

<!-- navbar-->
<div class="navbar">
    <!-- container-->
    <div class="container">
        <!-- navbar brand-->
        <nav>
            <h2 class="nav-brand float-left text-center">
                <img src="../upload/identitas/<?= $d->logo ?>" width="50">
                <a href="index.php"><?= $d->nama ?></a>
				<div class="mobile-menu-btn text-center">
					<a href="#" onClick="showMobileMenu()">MENU</a>
				</div>
            </h2>
            <!--navbar menu-->
            <ul class="nav-menu float-left" id="navbarMenu">
                <li><a href="index.php"><i class="fa-solid fa-house"></i> DASHBOARD</a></li>
                <?php if($_SESSION['urole'] == 'pemilik'){?>
                    <li><a href="akun_admin.php"><i class="fa-solid fa-user"></i> AKUN ADMIN</a></li>
                    <li><a href="akun_pelanggan.php"><i class="fa-solid fa-user"></i> AKUN PELANGGAN</a></li>
                    <li>
                        <a href="#"><i class="fa-regular fa-file-lines"></i> LAPORAN<i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="laporan_pendaftaran.php">LAPORAN PENDAFTARAN</a></li>
                            <li><a href="laporan_pesanan.php">LAPORAN PESANAN</a></li>
                        </ul>
                    </li>
                <?php }elseif($_SESSION['urole'] == 'admin'){?>
                    <li><a href="pendaftaran.php"><i class="fa-solid fa-file-pen"></i> PENDAFTARAN</a></li>
                    <li><a href="pesanan.php"><i class="fa-solid fa-cart-shopping"></i> HISTORY PESANAN</a></li>
                    <li>
                        <a href="#"><i class="fa-solid fa-glasses"></i> FRAME<i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="frame.php">FRAME</a></li>
                            <li><a href="jenis_frame.php">JENIS FRAME</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa-solid fa-circle"></i> LENSA<i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="lensa.php">LENSA</a></li>
                            <li><a href="jenis_lensa.php">JENIS LENSA</a></li>
                        </ul>
                    </li>
                    <li><a href="informasi.php"><i class="fa-solid fa-circle-info"></i> INFORMASI</a></li>
                    <li>
                        <a href="#"><i class="fa-solid fa-gear"></i> PENGATURAN<i class="fa fa-caret-down"></i></a>
                        <!--sub menu-->
                        <ul class="dropdown">
                            <li><a href="identitas.php">IDENTITAS OPTIK</a></li>
                            <li><a href="kontak.php">KONTAK</a></li>
                        </ul>
                    </li>
                <?php }?>
                <li>
                    <a href="#"><i class="fa-solid fa-user"></i> <?= $_SESSION['unama']?>(<?= $_SESSION['urole']?>)<i
                                class="fa fa-caret-down"></i></a>
                    <!--sub menu-->
                    <ul class="dropdown">
                        <li><a href="ubah_password.php">UBAH PASSWORD</a></li>
                        <li><a href="logout.php">KELUAR</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </div>
		<script>
    function showMobileMenu() {
        var mobileMenu = document.getElementById("mobileMenu");
        if (mobileMenu) {
            mobileMenu.style.display = "block";
        }
    }
	function hideMobileMenu() {
    var mobileMenu = document.getElementById("mobileMenu");
    if (mobileMenu) {
        mobileMenu.style.display = "none";
    }
}
</script>
