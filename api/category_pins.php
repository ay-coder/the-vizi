<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['category_id']) || $_REQUEST['category_id'] == '') {
    	$ret['message'] = 'Category id can not be blank!';
    }

    if ($ret['message'] == '') {
    	$q = isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '' ? 'SELECT * FROM pins WHERE category_id = ' . $_REQUEST['category_id'] . ' AND user_id = ' . $_REQUEST['user_id'] : 'SELECT * FROM pins WHERE category_id = ' . $_REQUEST['category_id'];
    	$all_pins = $db->query($q);
    	$title = $db->single('SELECT name FROM categories WHERE id = ' . $_REQUEST['category_id']);

    	$pins = array();
    	if (count($all_pins) > 0) {
    		foreach ($all_pins as $p) {
    			$img = $db->single('SELECT image FROM media WHERE pin_id = ' . $p['id'] . ' LIMIT 1 ');
    			$img = $img ? $img : DEFAULT_VIZI_IMAGE;
    			$pins[] = array('id' => $p['id'], 'title' => $p['title'], 'address' => $p['address'], 'image' => $img);
    		}
    	}

    	$ret['status'] = 'success';
    	$ret['message'] = 'Category pins found!';
    	$ret['data']['title'] = $title;
    	$ret['data']['pins'] = $pins;
    }

    echo json_encode($ret);
    die();
?>