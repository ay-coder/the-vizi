<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if (!isset($_REQUEST['current_password']) || $_REQUEST['current_password'] == '') {
    	$ret['message'] = 'Current password can not be blank!';
    }

    if (!isset($_REQUEST['new_password']) || $_REQUEST['new_password'] == '') {
        $ret['message'] = 'New assword can not be blank!';
    }

    if ($ret['message'] == '') {
        $exi = $db->single('SELECT email FROM users WHERE id = :id AND password LIKE :pass', array('id' => $_REQUEST['user_id'], 'pass' => md5($_REQUEST['current_password'])) );
        if ($exi != '') {
            $db->query('UPDATE users SET password = :pass WHERE id = :id', array('id' => $_REQUEST['user_id'], 'pass' => md5($_REQUEST['new_password']) ));
            $ret['status'] = 'success';
            $ret['message'] = 'Password updateed successfully!';
        }
        else {
            $ret['message'] = 'No user found with current details!';
        }
    }
    echo json_encode($ret);
    die();
?>