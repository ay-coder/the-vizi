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
        $resetNotificationCount = $db->query('UPDATE notifications SET is_read = 1 WHERE user_id = ' . $_REQUEST['user_id']);
        
        $usernotifications = $db->query('SELECT * FROM notifications WHERE type LIKE "%FOLLOW%" and user_id = ' . $_REQUEST['user_id'].' ORDER BY created_at DESC');

        $otherNotifications = $db->query('SELECT notifications.* FROM follow LEFT JOIN notifications on notifications.obj_id = follow.id WHERE notifications.type LIKE "%follow%"  AND ( follower_id = ' . $_REQUEST['user_id'].' OR following_id = ' . $_REQUEST['user_id'].') order by id desc');

        $allnotifications   = array_merge($usernotifications, $otherNotifications);


        if(isset($allnotifications) && count($allnotifications))
        {

            $sort = array();
            foreach($allnotifications as $k=>$v) {
                $sort['id'][$k] = $v['id'];
                
            }
            $sort = implode(',', $sort['id']);
            
            $notifications =  $db->query('SELECT * FROM notifications where id IN ('.$sort.') order by id desc');

            $processed = [];

            $userInfo = $db->query('SELECT user_name, image, address FROM users WHERE id = ' . $_REQUEST['user_id']);   
            if($notifications)
            {
                
                foreach($notifications as $notification)
                {
                    if(in_array($notification['id'], $processed))
                        continue;

                    $processed[] = $notification['id'];

                    if($notification['type'] == 'FOLLOW' || $notification['type'] == 'follow' && $userInfo)   
                    {
                        $followInfo     = $db->query('SELECT * FROM follow WHERE id = ' . $notification['obj_id']);


                        if(! $followInfo)
                            continue;
                        
                        $followInfo     = $followInfo[0];


                        if($followInfo['follower_id'] == $_REQUEST['user_id'])
                        {
                            $otherUserId = $followInfo['following_id'];
                        }
                        else
                        {
                            $otherUserId = $followInfo['follower_id'];
                        }

                        $otherUser = $db->query('SELECT id, user_name, name, image, address FROM users WHERE id  = ' . $otherUserId);
                        $otherUser = $otherUser[0];

                        if($otherUser)
                        {
                            $otherUserName = $otherUser['name'] ? $otherUser['name'] : $otherUser['user_name'];


                            if($followInfo['requested'] == 0)
                            {
                                if($followInfo['following_id'] == $_REQUEST['user_id'])
                                {
                                    $statement = $otherUserName . ' is now following You';
                                }
                                else
                                {
                                    $statement = 'You are now following '.  $otherUserName;
                                }
                            }
                            else
                            {
                                if($followInfo['following_id'] == $_REQUEST['user_id'])
                                {
                                    $statement = $otherUserName . ' has requested to follow You';
                                }
                                else
                                {
                                    $statement = 'You have sent follow request to '. $otherUserName;
                                }
                            }

                            $data[] = array(
                                'id'        => (int) $notification['obj_id'],
                                'toUserId'  => $otherUserId,
                                'text'      => $statement,
                                'image'     => $otherUser['image'] ? $otherUser['image'] : DEFAULT_VIZI_IMAGE,
                                'time'      => isset($notification['created_at']) ? time_elapsed_string($notification['created_at']) : '',
                                'date'      => $notification['created_at'],
                                'requested' => (bool) $followInfo['requested'] 
                            );
                        }

                    }
                }
            }

            usort($data, "cmp");

            function cmp($a, $b) {
                if ($a['date'] == $b['date']) {
                    return 0;
                }
                return ($a['date'] < $b['date']) ? -1 : 1;
            }
            $ret['data'] = isset($data) ? $data : array();
            $ret['message'] = 'Notifications found';
            $ret['status'] = 'success';
        }

        else {
            $ret['message'] = 'No notifications found';
            $ret['status'] = 'fail';
            $ret['data'] = array();

        }
    }

    echo json_encode($ret);
    die();
?>