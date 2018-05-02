<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') {
        $exi = $db->query('UPDATE users SET is_private = "'.$_REQUEST['private'].'" WHERE id =  ' . $_REQUEST['user_id'] );
        $ret['status'] = 'success';
        $ret['message'] = 'Profile updated!';
	}
	echo json_encode($ret);
    die();
?>