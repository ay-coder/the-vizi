<?php
	require '../config.php';
	global $db;

	$ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    $ret['status'] = 'Fail';
    $ret['data'] = array();

    if ($ret['message'] == '') {
 		$categories = $db->query('SELECT id, name, image FROM categories WHERE user_id = ' . $_REQUEST['user_id']);

 		if (count($categories)) {
 			$ret['status'] = 'Success';
 			$ret['message'] = 'Categoies found!';
 			$ret['data'] = $categories;
    	}
    	else {
    		$ret['message'] = 'No Categoies found!';
    	}
 	}
 	echo json_encode($ret);
 	die();
?>