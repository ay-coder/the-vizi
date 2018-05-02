<?php
	require '../../config.php';
	if (isset($_GET) && isset($_GET['id']) ) 
	{
		global $db;
		$db->query("DELETE FROM pins WHERE id = :id ", $_GET);

		header('Location: ' . HOST . 'admin/pins/index.php?deleted=true');
	}
	else
		header('Location: ' . HOST . 'admin/pins/index.php?deleted=false');
	die();
?>