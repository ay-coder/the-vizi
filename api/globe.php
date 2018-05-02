<?php
	require '../config.php';
	global $db;

	$ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') {
	    $categories = $db->column('SELECT distinct(address) as city FROM pins WHERE user_id = '.$_REQUEST['user_id'] . ' order by address');

	    if(! isset($categories) || count($categories) < 1)
	    {
	    	$categories[] = 'Las Vegas, NV 89101, USA';
	    }
	    
	    $ret['status'] = 'Success';
	    $ret['message'] = 'Categoies found!';
	    $ret['data'] = $categories;
	}
	echo json_encode($ret);
	die();
?>