<?php
	require '../config.php';
	global $db;

	$ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') {
	    $categories = $db->query('SELECT id, name, image FROM categories WHERE user_id IN (SELECT id FROM users WHERE role LIKE "admin" OR id = '.$_REQUEST['user_id'].' )');
	    $ret['status'] = 'Success';
	    $ret['message'] = 'Categoies found!';
	    $ret['data'] = $categories;
	}
	echo json_encode($ret);
	die();
?>