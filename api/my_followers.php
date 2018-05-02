<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }

    $usr = array();
    if ($ret['message'] == '') {
    	$users = $db->query('SELECT id, user_name, image, lat, lon, address FROM users WHERE id IN (SELECT follower_id FROM follow WHERE following_id = '.$_REQUEST['user_id'].' )');

	    if (count($users) > 0) {
            foreach ($users as $u) {
                $u['distance'] = getTimeDiff($u['lat'], $u['lon'], $_REQUEST['lat'], $_REQUEST['lon']);
                $if_follow = $db->single('SELECT id FROM follow WHERE follower_id = ' . $_REQUEST['user_id'] . ' AND following_id = ' . $u['id']);
                $u['following'] = $if_follow != '' ? 1 : 0;
                $usr[] = $u;
            }
	    	$ret['status'] = 'success';
	        $ret['message'] = 'Followers found!';
	        $ret['data'] = $usr;
	    }
	    else {
	    	$ret['status'] = 'fail';
	        $ret['message'] = 'No followers found!';
	    }
	}
	echo json_encode($ret);
    die();
?>