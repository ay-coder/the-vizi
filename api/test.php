<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

	if (can_send($_REQUEST['user_id'])) {
        require 'push.php';
        $msg_payload = array (
            'mtitle' => $_REQUEST['title'],
            'mdesc' => $_REQUEST['text']
        );
        //$deviceToken = '2304683DF9E4AB7268125D32CAA1D227EF0D2C41504B0CF7ABFAAD3438E3A540';
        $deviceToken = $_REQUEST['token'];
        PushNotifications::iOS($msg_payload, $deviceToken);
    }
    $ret['status'] = 'success';
    $ret['message'] = 'Sent!';
	echo json_encode($ret);
    die();
?>