<?php
    require '../config.php';
    global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }

    if ($ret['message'] == '') 
    {
        $buttonTxt = "Follow";
        $user      = $db->query('SELECT * FROM users WHERE id = :id', array('id' => $_REQUEST['user_id']));

        if (count($user) > 0) 
        {
            $user = $user[0];
            unset($user['reset'], $user['created_at'], $user['status'], $user['role'], $user['password'], $user['visibility'], $user['device_id']);

            $followers = $db->single('SELECT count(id) AS followers FROM follow WHERE requested = 0 AND following_id = ' . $_REQUEST['user_id']);
            $followings = $db->single('SELECT count(id) AS followings FROM follow WHERE  requested = 0 AND follower_id = ' . $_REQUEST['user_id']);
            $pins = $db->single('SELECT count(id) AS pins FROM pins WHERE user_id = ' . $_REQUEST['user_id']);
            //$cats = $db->query('SELECT id, name, image, user_id FROM categories WHERE id IN (SELECT category_id FROM pins WHERE user_id = ' . $_REQUEST['user_id'] . ')');
            //$cats = $db->query('SELECT id, name, image, user_id FROM categories WHERE user_id = ' . $_REQUEST['user_id'] . '  ');
            if (isset($_REQUEST['city']) && $_REQUEST['city'] != '') 
            {
                if($_REQUEST['current_user_id'] != $_REQUEST['user_id'])                
                {
                    $cats = $db->query('SELECT id, name, image, user_id, is_private FROM categories WHERE id IN (SELECT category_id FROM pins WHERE address LIKE "'.$_REQUEST['city'].'" AND is_private = 0 AND user_id = '.$_REQUEST['user_id'].' )');
                }
                else
                {
                    $cats = $db->query('SELECT id, name, image, user_id, is_private FROM categories WHERE id IN (SELECT category_id FROM pins WHERE address LIKE "'.$_REQUEST['city'].'" AND user_id = '.$_REQUEST['user_id'].' )');    
                }
            }
            else 
            {
                if($_REQUEST['current_user_id'] != $_REQUEST['user_id'])                
                {
                    $cats = $db->query('SELECT id, name, image, user_id, is_private FROM categories WHERE is_private = 0 AND user_id IN (SELECT id FROM users WHERE role LIKE "admin" OR id = '.$_REQUEST['user_id'].' )');
                }
                else
                {
                    $cats = $db->query('SELECT id, name, image, user_id, is_private FROM categories WHERE user_id IN (SELECT id FROM users WHERE role LIKE "admin" OR id = '.$_REQUEST['user_id'].' )');    
                }
            }

            $current_id = $_REQUEST['current_user_id'];
            $is_following = "0";
            if ($current_id != '') 
            {
                $ex_following = $db->query('SELECT * FROM follow WHERE follower_id =  ' . $current_id . ' AND following_id = ' . $_REQUEST['user_id']);

                if(isset($ex_following))
                {
                    $ex_following = $ex_following[0];
                }

                $userProfile  = $db->single('SELECT * FROM users WHERE id =  ' . $current_id );

                if ($ex_following != '')
                {
                    $is_following = "1";
                    $buttonTxt = "Following";
                }

                if($is_following == "1")
                {
                   if($ex_following['requested'] == 1 )
                   {
                        $buttonTxt = "Requested";
                   }
                }
            }

            if (count($cats) > 0) 
            {
                foreach ($cats as $k => $c) {
                    if ($current_id == $_REQUEST['user_id'])
                        $cats[$k]['show_delete'] = $c['user_id'] == $_REQUEST['user_id'] ? 1 : 0;
                    $city = $db->query('SELECT distinct(address) FROM pins WHERE category_id = ' . $c['id'] . ' AND user_id = ' . $_REQUEST['user_id']);
                    $cats[$k]['city'] = $city;
                }
            }

            $user['notification'] = $user['notification'] == 'on' ? 1 : 0;

            
            $geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$user['lat'].','.$user['lon'].'&sensor=false');

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

            if(!isset($user['address']))
            {
                $user['address'] = $city;    
            }

            $overridePrivate = "0";

            if($buttonTxt == "Requested" || $buttonTxt == "Follow")
            {
                $overridePrivate = "1";
            }
            
            $user['user_city'] = $city;
            $ret['status'] = 'success';
            $user['is_private'] = $overridePrivate;
            $ret['data']['user'] = $user;
            $ret['data']['is_following'] = $is_following;
            $ret['data']['followBtnTxt'] = $buttonTxt;
            $ret['data']['followers'] = $followers;
            $ret['data']['followings'] = $followings;
            $ret['data']['pins'] = $pins;
            $ret['data']['categories'] = $cats;
            $ret['message'] = 'User found!';
        }
        else {
            $ret['message'] = 'User not found!';
        }
    }

    echo json_encode($ret);
    die();
?>