<?php
	require '../config.php';
	if (isset($_SESSION['user']) && count($_SESSION['user']) > 0)
		header('Location: ' . HOST . 'admin/dashboard.php');
?>