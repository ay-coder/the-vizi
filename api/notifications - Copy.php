<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }
    $notifications = array();
    if ($ret['message'] == '') 
    {
        $nots = $db->query('SELECT * FROM notifications WHERE user_id IN (SELECT following_id FROM follow WHERE follower_id = ' . $_REQUEST['user_id'].') ORDER BY created_at DESC');
        if (count($nots) > 0) 
        {
            foreach ($nots as $n) 
            {
                $performed_by = $db->query('SELECT user_name, image, address FROM users WHERE id = ' . $n['user_id']);
                switch ($n['type']) {
                    case 'FOLLOW':
                        $performed_on = $db->query('SELECT id, user_name, image, address FROM users WHERE id IN ( SELECT following_id FROM follow WHERE id = ' . $n['obj_id'] . ')');
                        $private = $db->single('SELECT requested FROM follow WHERE id = ' . $n['obj_id']);

                        if($performed_on[0]['id'] == $_REQUEST['user_id'])
                        {
                             $statement = (int)$private == 0 ? 'You are now following ' : $performed_by[0]['user_name'] . ' sent follow request to ';
                        }
                        else
                        {

                            $statement = (int)$private == 0 ? $performed_by[0]['user_name'] . ' is now following ' : $performed_by[0]['user_name'] . ' sent follow request to ';
                        }
                        $requested = (int)$private == 0 ? 0 : 1;
                        $statement .= $performed_on[0]['id'] == $_REQUEST['user_id'] ? 'you.' : $performed_on[0]['user_name'];

                        $notifications[] = array('id' => $n['obj_id'], 'text' => $statement, 'image' => $performed_by[0]['image'], 'time' => time_elapsed_string($n['created_at']), 'date' => $n['created_at'], 'requested' => $requested );
                        break;
                }
            }
        }


        $nots = $db->query('SELECT * FROM notifications WHERE type LIKE "FOLLOW" and user_id = "'.$_REQUEST['user_id'].'" ORDER BY created_at DESC');
        if (count($nots) > 0) 
        {
            foreach ($nots as $n) {
                $performed_by = $db->query('SELECT user_name, image, address FROM users WHERE id = ' . $n['user_id']);
                switch ($n['type']) {
                    case 'FOLLOW':
                        $performed_on = $db->query('SELECT id, user_name, image, address FROM users WHERE id IN ( SELECT following_id FROM follow WHERE id = ' . $n['obj_id'] . ')');
                        if ($performed_on[0]['id'] == $_REQUEST['user_id']) {
                            $private = $db->single('SELECT requested FROM follow WHERE id = ' . $n['obj_id']);
                            $statement = (int)$private == 0 ? $performed_by[0]['user_name'] . ' is now following ' : $performed_by[0]['user_name'] . ' sent follow request to ';
                            $requested = (int)$private == 0 ? 0 : 1;
                            $statement .= $performed_on[0]['id'] == $_REQUEST['user_id'] ? 'you.' : $performed_on[0]['user_name'];

                            $notifications[] = array('id' => $n['obj_id'], 'text' => $statement, 'image' => $performed_by[0]['image'], 'time' => time_elapsed_string($n['created_at']), 'date' => $n['created_at'], 'requested' => $requested );
                        }
                        break;
                }
            }
        }

        usort($notifications, "cmp");

        function cmp($a, $b) {
            if ($a['date'] == $b['date']) {
                return 0;
            }
            return ($a['date'] < $b['date']) ? -1 : 1;
        }

        $ret['data'] = $notifications;
        $ret['message'] = 'Notifications found';
        $ret['status'] = 'success';
        /*else {
            $ret['message'] = 'No notifications found';
            $ret['status'] = 'fail';
        }*/
    }

    echo json_encode($ret);
    die();
?>