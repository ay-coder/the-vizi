<?php
    require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['pin_id']) || $_REQUEST['pin_id'] == '') {
    	$ret['message'] = 'Pin id can not be blank!';
    }

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }    

    if (!isset($_REQUEST['image_id']) || $_REQUEST['image_id'] == '') 
    {
        $ret['message'] = 'Unable to Delete Image';
    }    
    if ($ret['message'] == '') 
    {
        $sql = $db->query('DELETE FROM media WHERE id = ' . $_REQUEST['image_id']);

        if($sql)
        {
            $ret['status'] = 'success';
            $ret['message'] = 'Pin Image found!';
            $ret['data']['response'] = "Image Deleted Successfully !";
        }
    }

    echo json_encode($ret);
    die();
?>