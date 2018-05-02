<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }

    $usr = array();
    if ($ret['message'] == '') {
    	$users = $db->query('SELECT id, user_name, image, lat, lon, address FROM users WHERE id IN (SELECT following_id FROM follow WHERE follower_id = '.$_REQUEST['user_id'].' )');
    	if (count($users) > 0) {
    		if (count($users) > 0) {
	            foreach ($users as $u) {


	            	$geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$u['lat'].','.$u['lon'].'&sensor=false');

	                $output = json_decode($geocode);
	                $city   = '';
	                
	                if(!empty($output->results[0]->address_components[2]->long_name))
	                {
	                    $city = $output->results[0]->address_components[2]->long_name;
	                }
	                else
	                {
	                    $city = $output->results[0]->address_components[3]->long_name;    
	                }

	                $u['distance'] = getTimeDiff($u['lat'], $u['lon'], $_REQUEST['lat'], $_REQUEST['lon']);
	                $u['following'] = 1;
	                $u['user_city'] = $city;
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
	    else {
	    	$ret['status'] = 'fail';
	        $ret['message'] = 'No followers found!';
	    }
        $ret['data'] = $usr;
	}

	echo json_encode($ret);
    die();
?>