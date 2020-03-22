<?php
	require_once "../class/config/config.php";
	session_start(); 
	
	if (!isset($_SESSION['member_id'])) {
		header('Location: login');
	}
?>