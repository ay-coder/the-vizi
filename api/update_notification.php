<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') {
        $exi = $db->query('UPDATE users SET notification = "'.$_REQUEST['notification'].'" WHERE id =  ' . $_REQUEST['user_id'] );
        $ret['status'] = 'success';
        $ret['message'] = 'Notification updated!';
	}
	echo json_encode($ret);
    die();

?>