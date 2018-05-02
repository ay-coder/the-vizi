<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') 
    {
        $ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') 
    {
        $users = $db->query('SELECT  (select count(id) from pins where user_id = users.id) as pincount,  users.name, users.id, users.user_name, users.image, users.lat, users.lon, users.address FROM users
                    LEFT JOIN pins on pins.user_id = users.id
                 WHERE users.id != ' . $_REQUEST['user_id'] . ' group by users.id ORDER BY pincount desc');
        
        $usr = array();
        if (count($users) > 0) 
        {
            foreach ($users as $u) 
            {
                $u['image'] = isset($u['image']) ? $u['image'] : DEFAULT_VIZI_IMAGE;
                $u['distance'] = getTimeDiff($u['lat'], $u['lon'], $_REQUEST['lat'], $_REQUEST['lon']);
                $if_follow = $db->single('SELECT id FROM follow WHERE follower_id = ' . $_REQUEST['user_id'] . ' AND following_id = ' . $u['id']);

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

                $u['user_city'] = $city;
                $u['name'] = isset($u['name']) ? $u['name'] : '';
                $u['following'] = $if_follow != '' ? 1 : 0;
                $usr[] = $u;
            }
        }

        $ret['status'] = 'success';
        $ret['message'] = 'All Users Found!';
        $ret['data'] = $usr;
    }

    echo json_encode($ret);
    die();
?>