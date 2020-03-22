<?php
	session_start();

	$h_desc = 'logout';
	$h_tbl = 'user';
	$sessionid = $_SESSION['admin_id'];
	$sessiontype = $_SESSION['admin_type'];

	session_destroy();
	header('Location: ../../index');
?>