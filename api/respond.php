<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['notification_id']) || $_REQUEST['notification_id'] == '') {
    	$ret['message'] = 'Notification id can not be blank!';
    }

    if (!isset($_REQUEST['accept']) || $_REQUEST['accept'] == '') {
    	$ret['message'] = 'Accept can not be blank!';
    }

    if ($ret['message'] == '') {
        $ret['status'] = 'success';
        if ($_REQUEST['accept'] == 'accept') {
    	   $db->query('UPDATE follow SET requested = 0 WHERE id = ' . $_REQUEST['notification_id']);
           $ret['message'] = 'Follow request accepted sucessfully!';
        }
        else {
            $db->query('DELETE FROM follow WHERE id = ' . $_REQUEST['notification_id']);
            $ret['message'] = 'Follow request rejected sucessfully!';
        }
    	
    }
    echo json_encode($ret);
    die();
?>