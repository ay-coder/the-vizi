<?php
    require '../config.php';
    global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['category_id']) || $_REQUEST['category_id'] == '') {
        $ret['message'] = 'Category id can not be blank!';
    }

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') {

        if(isset($_REQUEST['city_name']) || $_REQUEST['city_name'] != '') 
        {
            $city = $_REQUEST['city_name'];


            $city = str_replace(" ", '+', $city);
            #Find latitude and longitude
             
            $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city";
            $json_data = file_get_contents($url);
            $result = json_decode($json_data, TRUE);

            $latitude = $result['results'][0]['geometry']['location']['lat'];
            $longitude = $result['results'][0]['geometry']['location']['lng'];
            
           $all_pins = $db->query('SELECT * FROM pins WHERE category_id = '.$_REQUEST['category_id'] . ' AND user_id = ' . $_REQUEST['user_id']);

            $pins = array();
            if (count($all_pins) > 0) 
            {
                foreach ($all_pins as $p) 
                {
                    $u = $db->query('SELECT user_name, image FROM users WHERE id = ' . $p['user_id']);

                    $disatance = (int) (distanceInteger($p['lat'], $p['lon'], $latitude, $longitude)) ? distanceInteger( $p['lat'], $p['lon'], $latitude, $longitude) : -1;

                    if((int) $disatance < 100)
                    {
                        $img = $db->single('SELECT image FROM media WHERE pin_id = ' . $p['id'] . ' LIMIT 1');
                        $img = $img != '' ? $img : DEFAULT_VIZI_IMAGE;
                        $pins[] = array('pin_id' => $p['id'], 'user' => $u[0]['user_name'], 'image' => $img, 'title' => $p['title'], 'lat' => $p['lat'], 'lon' => $p['lon'], 'address' => $p['address'], 'distance' => $disatance);
                    }
                }

                $result = array();
                foreach ($pins as $key => $row)
                {
                    $result[$key] = $row['distance'];
                }
                array_multisort($result, SORT_ASC, $pins);
                
            }
            
            $ret['data'] = $pins;
            $ret['status'] = 'success';
            $ret['message'] = 'Pin found!';
            echo json_encode($ret);
            die();
        }
       
        $all_pins = $db->query('SELECT * FROM pins WHERE category_id = '.$_REQUEST['category_id'] . ' AND user_id = ' . $_REQUEST['user_id']);

            $pins = array();
            if (count($all_pins) > 0) 
            {
                foreach ($all_pins as $p) 
                {
                    $u = $db->query('SELECT user_name, image FROM users WHERE id = ' . $p['user_id']);
                    $img = $db->single('SELECT image FROM media WHERE pin_id = ' . $p['id'] . ' LIMIT 1');
                    $img = $img != '' ? $img : DEFAULT_VIZI_IMAGE;
                    $pins[] = array('pin_id' => $p['id'], 'user' => $u[0]['user_name'], 'image' => $img, 'title' => $p['title'], 'lat' => $p['lat'], 'lon' => $p['lon'], 'address' => $p['address']);
                }
                $ret['status'] = 'success';
                $ret['message'] = 'Pin found!';    
            }
        else 
        {
            $ret['status'] = 'fail';
            $ret['message'] = 'No locations found!';
        }

        $ret['data'] = $pins;
    }

    echo json_encode($ret);
    die();
?>