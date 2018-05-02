<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }
    $notifications = array();
    if ($ret['message'] == '') {
        $nots = $db->query('SELECT * FROM notifications WHERE user_id IN (SELECT following_id FROM follow WHERE follower_id = ' . $_REQUEST['user_id'].') ORDER BY created_at DESC');
        if (count($nots) > 0) {
            foreach ($nots as $n) {
                $performed_by = $db->query('SELECT user_name, image, address FROM users WHERE id = ' . $n['user_id']);
                switch ($n['type']) {
                    /*case 'FOLLOW':
                        $performed_on = $db->query('SELECT id, user_name, image, address FROM users WHERE id IN ( SELECT following_id FROM follow WHERE id = ' . $n['obj_id'] . ')');
                        $statement = $performed_by[0]['user_name'] . ' is now following ';
                        $statement .= $performed_on[0]['id'] == $_REQUEST['user_id'] ? 'you.' : $performed_on[0]['user_name'];

                        $notifications[] = array('text' => $statement, 'image' => $performed_by[0]['image'], 'time' => time_elapsed_string($n['created_at']) );
                        break;*/
                    case 'PIN':
                        $pin_det = $db->query('SELECT title, address FROM pins WHERE id = ' . $n['obj_id']);
                        $statement = $performed_by[0]['user_name'] . " just saved '".$pin_det[0]['title']."' in " . $pin_det[0]['address'];

                        $notifications[] = array('id' => $n['obj_id'], 'text' => $statement, 'image' => $performed_by[0]['image'] ? $performed_by[0]['image'] : DEFAULT_VIZI_IMAGE, 'time' => time_elapsed_string($n['created_at']) );
                        break;
                    case 'ADMIN':
                        
                        break;
                }
            }
            $ret['data'] = $notifications;
            $ret['message'] = 'Notifications found';
            $ret['status'] = 'success';
        }
        else {
            $ret['message'] = 'No notifications found';
            $ret['status'] = 'fail';
        }
    }

    echo json_encode($ret);
    die();
?>