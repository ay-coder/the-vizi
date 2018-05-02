<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if (!isset($_REQUEST['current_user_id']) || $_REQUEST['current_user_id'] == '') {
    	$ret['message'] = 'Current user id can not be blank!';
    }

    if (!isset($_REQUEST['reason']) || $_REQUEST['reason'] == '') {
        $ret['message'] = 'Reason can not be blank!';
    }

    if ($ret['message'] == '') {
    	$db->query('INSERT INTO report (user_id, current_user_id, reason) VALUES ("'.$_REQUEST['user_id'].'", "'.$_REQUEST['current_user_id'].'", "'.$_REQUEST['reason'].'") ');
    	$ret['status'] = 'success';
    	$ret['message'] = 'User reported sucessfully!';
    }
    echo json_encode($ret);
    die();
?>