<?php
	session_start();
	//untuk menghapus sistem
	session_destroy();
	header("location:../admin/login_admin.php");
?>
