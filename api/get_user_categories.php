<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    $cats = array();
    if ($ret['message'] == '') {
    	$cats = $db->query('SELECT id, name FROM categories WHERE user_id = '.$_REQUEST['user_id'].' OR user_id IN (SELECT id FROM users WHERE role LIKE "admin" ) ');
    	$ret['status'] = 'success';
    	$ret['message'] = 'Categories found!';
    	$ret['data'] = $cats;
    }
    echo json_encode($ret);
    die();

?>