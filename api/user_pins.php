<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') {
        $all_pins = $db->query('SELECT *, categories.name as categoryName, pins.id as id FROM pins 
                                LEFT JOIN categories
                                ON categories.id = pins.category_id
                                WHERE pins.user_id = '.$_REQUEST['user_id'] );

        $pins = array();
        if (count($all_pins) > 0) {
            $u = $db->query('SELECT id,name,user_name, image FROM users WHERE id = ' . $_REQUEST['user_id']);
            foreach ($all_pins as $p) {
                
                $userImage = DEFAULT_VIZI_IMAGE;

                if(strlen($u[0]['image']) > 4)
                {
                    $userImage = $u[0]['image'];
                }

                $pins[] = array(
                        'userId'        => $u[0]['id'],
                        'name'          => isset($u[0]['name']) ? $u[0]['name'] : '',
                        'user'          => $u[0]['user_name'],
                        'image'         => $userImage,
                        'title'         => $p['title'],
                        'pinId'         => (int) $p['id'],
                        'categoryId'    => (int) $p['category_id'],
                        'categoryName'  => $p['categoryName'],
                        'lat'           => $p['lat'],
                        'lon'           => $p['lon'],
                        'address'       => $p['address']
                    );
            }
            $ret['status'] = 'success';
            $ret['message'] = 'Pin found!';
        }
        else {
            $ret['status'] = 'fail';
            $ret['message'] = 'No pins found!';
        }

        $ret['status'] = 'success';
        $ret['message'] = 'Followers found!';
        $ret['data'] = $pins;
    }

    echo json_encode($ret);
    die();
?>