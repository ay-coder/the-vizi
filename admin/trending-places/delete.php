<?php
	require '../../config.php';

	if (isset($_GET) && isset($_GET['id']) ) {
		global $db;
		$db->query("DELETE FROM trending_places WHERE id = :id ", $_GET);
		header('Location: ' . HOST . 'admin/trending-places/index.php?deleted=true');
	}
	else
		header('Location: ' . HOST . 'admin/trending-places/index.php?deleted=false');
	die();
?>