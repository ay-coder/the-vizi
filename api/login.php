<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_name']) || $_REQUEST['user_name'] == '') {
    	$ret['message'] = 'User name can not be blank!';
    }

    if (!isset($_REQUEST['password']) || $_REQUEST['password'] == '') {
    	$ret['message'] = 'Password can not be blank!';
    }

    if (!isset($_REQUEST['device_id']) || $_REQUEST['device_id'] == '') {
        $ret['message'] = 'Device id can not be blank!';
    }

    if (!isset($_REQUEST['lat']) || $_REQUEST['lat'] == '') {
        $ret['message'] = 'Latitude can not be blank!';
    }

    if (!isset($_REQUEST['lon']) || $_REQUEST['lon'] == '') {
        $ret['message'] = 'Longitude can not be blank!';
    }

    //mail('smit.v.shah@gmail.com', 'Params', json_encode($_REQUEST));

    if ($ret['message'] == '') {
        $exi = $db->query('SELECT * FROM users WHERE user_name LIKE :user_name AND password LIKE :password', array('user_name' => $_POST['user_name'], 'password' => md5($_POST['password']) ));

        if (count($exi) > 0) {
        	$exi = $exi[0];
            $address = getAddress($_REQUEST['lat'], $_REQUEST['lon']);
            
            $db->query('UPDATE users SET fbid = :fbid, lat = :lat, lon = :lon, address = :address, device_id = :device_id WHERE id = :id', array('id' => $exi['id'], 'fbid' => $_POST['fbid'], 'lat' => $_REQUEST['lat'], 'lon' => $_REQUEST['lon'], "address" => $address, "device_id" => $_REQUEST['device_id']));
        	if ((int)$exi['status'] == 1 ) {
	        	unset($exi['password']);
	        	unset($exi['reset']);
	        	unset($exi['role']);
	        	$ret['status'] = 'success';
	        	$ret['data'] = $exi;
	        	$ret['message'] = 'User loggedin successfully!';
	        }
	        else {
	        	$ret['message'] = 'User is blocked!';
	        }
        }
        else {
        	$ret['status'] = 'fail';
        	$ret['message'] = 'Invalid login details!';
        }
    }
    echo json_encode($ret);
    die();
?>