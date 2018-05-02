<?php
	require '../config.php';
	global $db;

	$ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }

    if (!isset($_REQUEST['pin_id']) || $_REQUEST['pin_id'] == '') {
        $ret['message'] = 'Pin id can not be blank!';
    }

    $ret['status'] = 'Fail';
    $ret['data'] = array();

    if ($ret['message'] == '') {
 		$cid = $db->single('SELECT id FROM pins WHERE user_id = ' . $_REQUEST['user_id'] . ' AND id = ' . $_REQUEST['pin_id']);
        if ($cid != '') {
            $ret['status'] = 'Success';
 			$ret['message'] = 'Pin deleted!';

            $images = $db->query('SELECT image from media WHERE pin_id = ' . $_REQUEST['pin_id']);

            if (count($images) > 0) {
                $dels = array();
                foreach ($images as $k => $img) {
                    $image = explode("/", $img['image']);
                    $dels[] = PUBLIC_PATH . 'pins/' . end($image);
                    unlink(PUBLIC_PATH . 'pins/' . end($image));
                }
            }

            //delete everything
            $db->query('DELETE FROM media WHERE pin_id = ' . $_REQUEST['pin_id']);
            $db->query('DELETE FROM pins WHERE user_id = "'.$_REQUEST['user_id'].'" AND id = "'.$_REQUEST['pin_id'].'" ');
    	}
    	else {
    		$ret['message'] = 'No Pin found!';
    	}
 	}
 	echo json_encode($ret);
 	die();
?>