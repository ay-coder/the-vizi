<?php
	require '../config.php';
	global $db;
	
	$terms  = $db->single('SELECT data_value FROM settings where data_key = "terms"' );
    $ret 	= array('status' => 'success', 'message' => 'Terms & Condition', 'data' => $terms);
    echo json_encode($ret);
    die();
?>