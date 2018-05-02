<?php
	 require '../config.php';
    global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());


	if(isset($_POST['user_id']))
	{	
		$userId = $_REQUEST['user_id'];

		$sql = 'SELECT count(id) as notificationCount from notifications WHERE is_read = 0 AND `type` LIKE  "%follow%" AND user_id = '.$userId;
		$notificationCount = $db->query($sql);

		if(isset($notificationCount))
		{
			$notificationCount 	= $notificationCount[0]['notificationCount'];
			$data 				= array(
				'notificationCount' => $notificationCount
			);	
			$ret['data'] 	= $data;
		    $ret['message'] = 'Notifications found';
		    $ret['status'] 	= 'success';
		}
	}
	else 
	{
        $ret['message'] = 'No notifications found';
        $ret['status'] = 'fail';
    }

 	echo json_encode($ret);
    die();
?>