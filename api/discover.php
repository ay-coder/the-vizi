<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') 
    {
    	$all_pins = $db->query('SELECT * FROM pins WHERE user_id IN (SELECT following_id FROM follow WHERE follower_id = '.$_REQUEST['user_id'].' ) ');

        $pins = array();
        if(isset($_REQUEST['lat']) && strlen($_REQUEST['lat']) > 2 && isset($_REQUEST['lon']) && strlen($_REQUEST['lon']) > 2)
        {
            if (count($all_pins) > 0) 
            {
                foreach ($all_pins as $p) 
                {
                    $u = $db->query('SELECT id,user_name, image, lat, lon FROM users WHERE id = ' . $p['user_id']);
                   
                    $disatance = (distance($_REQUEST['lat'], $_REQUEST['lon'], $p['lat'], $p['lon'])) ? distance($_REQUEST['lat'], $_REQUEST['lon'], $p['lat'], $p['lon']) : -1;

                    /*$disatance = (distance($u[0]['lat'], $u[0]['lon'], $p['lat'], $p['lon'])) ? distance($u[0]['lat'], $u[0]['lon'], $p['lat'], $p['lon']) : -1;*/

                    $pins[] = array('id' => $p['id'], 'userId' => $u[0]['id'], 'user' => $u[0]['user_name'], 'image' => $u[0]['image'], 'title' => $p['title'], 'lat' => $p['lat'], 'lon' => $p['lon'], 'address' => $p['address'], 'distance' => $disatance);
                }
            }

            $result = array();
            foreach ($pins as $key => $row)
            {
                $result[$key] = $row['distance'];
            }
            array_multisort($result, SORT_ASC, $pins);
        }
        else
        {
            if (count($all_pins) > 0) {
                foreach ($all_pins as $p) 
                {
                    $u = $db->query('SELECT user_name, image FROM users WHERE id = ' . $p['user_id']);
                    $pins[] = array('id' => $p['id'], 'user' => $u[0]['user_name'], 'image' => $u[0]['image'], 'title' => $p['title'], 'lat' => $p['lat'], 'lon' => $p['lon'], 'address' => $p['address'], 'distance' =>0);
                }
            }
        }


       


        $ret['status'] = 'success';
        $ret['message'] = 'Pin found!';
        $ret['data'] = $pins;
    }

    echo json_encode($ret);
    die();
?>