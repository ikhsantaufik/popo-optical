<?php
	session_start();
	//untuk menghapus sistem
	session_destroy();
	header("location: login.php");
?>
