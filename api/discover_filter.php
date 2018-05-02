<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if (!isset($_REQUEST['lat']) || $_REQUEST['lat'] == '') {
        $ret['message'] = 'Latitude can not be blank!';
    }

    if (!isset($_REQUEST['lon']) || $_REQUEST['lon'] == '') {
        $ret['message'] = 'Longitude can not be blank!';
    }

    $user_ids = array();
    if (isset($_REQUEST['user_ids']) && $_REQUEST['user_ids'] != '') {
        $user_ids = explode(",", $_REQUEST['user_ids']);
    }

    if ($ret['message'] == '') {
        /*if (count($user_ids) > 0)
            $followers = $db->query('SELECT id, user_name, image, lat, lon, address FROM users WHERE id IN ('.$user_ids.') ');
        else
    	   $followers = $db->query('SELECT id, user_name, image, lat, lon, address FROM users WHERE id IN (SELECT following_id FROM follow WHERE follower_id = '.$_REQUEST['user_id'].' ) ');
        */

        //$followers = $db->query('SELECT id, user_name, image, lat, lon, address FROM users WHERE id IN ('.$_REQUEST['user_id'].')' );
        $all_pins = $db->query('SELECT * FROM pins WHERE user_id IN ('.$_REQUEST['user_id'] . ')');

        $pins = array();
        if (count($all_pins) > 0) {
            foreach ($all_pins as $p) {
                $u = $db->query('SELECT user_name, image FROM users WHERE id = ' . $p['user_id']);
                $pins[] = array('user' => $u[0]['user_name'], 'image' => $u[0]['image'], 'title' => $p['title'], 'lat' => $p['lat'], 'lon' => $p['lon'], 'address' => $p['address'], 'id' => $p['id']);
            }
            $ret['status'] = 'success';
            $ret['message'] = 'Pin found!';
        }
        else {
            $ret['status'] = 'fail';
            $ret['message'] = 'No pins found!';
        }
        $ret['data'] = $pins;
    }

    echo json_encode($ret);
    die();
?>