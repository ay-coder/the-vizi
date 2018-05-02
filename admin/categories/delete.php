<?php
	require '../../config.php';

	if (isset($_GET) && isset($_GET['id']) ) {
		global $db;
		$db->query("DELETE FROM categories WHERE id = :id ", $_GET);
		header('Location: ' . HOST . 'admin/categories/index.php?deleted=true');
	}
	else
		header('Location: ' . HOST . 'admin/categories/index.php?deleted=false');
	die();
?>