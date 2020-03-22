<?php
	require_once "../class/config/config.php";
	session_start(); 
	
	if (!isset($_SESSION['admin_id'])) {
		header('Location: ../index');
	}
?>