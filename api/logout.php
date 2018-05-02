<?php
    require '../config.php';
    global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') {
        $db->query('UPDATE users SET device_id = "" WHERE id = ' . $_REQUEST['user_id']);
        $ret['message'] = 'User logged out!';
    }

    echo json_encode($ret);
    die();
?>