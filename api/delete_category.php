<?php
	require '../config.php';
	global $db;

	$ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }

    if (!isset($_REQUEST['category_id']) || $_REQUEST['category_id'] == '') {
        $ret['message'] = 'Category id can not be blank!';
    }

    $ret['status'] = 'Fail';
    $ret['data'] = array();

    if ($ret['message'] == '') {
 		$cid = $db->single('SELECT id FROM categories WHERE user_id = ' . $_REQUEST['user_id'] . ' AND id = ' . $_REQUEST['category_id']);
        if ($cid != '') {
            $ret['status'] = 'Success';
 			$ret['message'] = 'Category deleted!';

            $images = $db->query('SELECT image from media WHERE pin_id IN (SELECT id FROM pins WHERE user_id = "'.$_REQUEST['user_id'].'" AND category_id = "'.$cid.'" ) ');

            $cat_img = $db->single('SELECT image FROM categories WHERE id = ' . $cid);
            if ($cat_img != '') {
                $tp = explode("/", $cat_img);
                unlink(PUBLIC_PATH . 'categories/' . end($tp));
            }

            if (count($images) > 0) {
                $dels = array();
                foreach ($images as $k => $img) {
                    $image = explode("/", $img['image']);
                    $dels[] = PUBLIC_PATH . 'pins/' . end($image);
                    unlink(PUBLIC_PATH . 'pins/' . end($image));
                }
            }

            //delete everything
            $db->query('DELETE FROM media WHERE pin_id IN (SELECT id FROM pins WHERE user_id = "'.$_REQUEST['user_id'].'" AND category_id = "'.$cid.'" ) ');
            $db->query('DELETE FROM pins WHERE user_id = "'.$_REQUEST['user_id'].'" AND category_id = "'.$cid.'" ');
            $db->query('DELETE FROM categories WHERE id = ' . $cid);
    	}
    	else {
    		$ret['message'] = 'No Category found!';
    	}
 	}
 	echo json_encode($ret);
 	die();
?>