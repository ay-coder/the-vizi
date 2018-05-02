<?php
	require '../../config.php';
	if (isset($_GET) && isset($_GET['id']) ) {
		global $db;
		$db->query("DELETE FROM users WHERE id = :id ", $_GET);
		header('Location: ' . HOST . 'admin/users/index.php?deleted=true');
	}
	else
		header('Location: ' . HOST . 'admin/users/index.php?deleted=false');
	die();
?>