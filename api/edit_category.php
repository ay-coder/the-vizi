<?php
	require '../config.php';
	global $db;

	$category_id = $_REQUEST['category_id'];

	$ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if (!isset($_REQUEST['category_id']) || $_REQUEST['category_id'] == '') {
    	$ret['message'] = 'Category id can not be blank!';
    }

    if ($ret['message'] == '') 
    {
		$_POST['image'] = '';
	    if (isset($_FILES) && isset($_FILES['image']) && count($_FILES['image']) > 0) 
	    {
	        $file = pathinfo($_FILES['image']['name']);
	        $name = $file['filename'];
	        $ext = $file['extension'];
	        $rand = time();
	        $image = $name . '-' . $rand . '.' . $ext;
	        $upload_to = PUBLIC_PATH . 'categories/' . $image;
	        move_uploaded_file($_FILES['image']['tmp_name'], $upload_to);
	        $imageName = PUBLIC_URL . 'categories/' . $image;
	    }

	    $isPrivate 	= isset($_REQUEST['private']) ? $_REQUEST['private'] : 0;
	    $name 		= isset($_REQUEST['name']) ? $_REQUEST['name'] : 'Default Category';

    	$sql = 'UPDATE categories 
    			SET
    			name 		= "'.$name.'",
    			is_private 	= "'.$isPrivate.'",
    			image 		= "'.$imageName.'"
    			WHERE id = "'.$category_id.'" ';
	    
	    $result = $db->query($sql);

	    $ret['status'] = 'Success';
	    $ret['message'] = 'Category Updated !';
	    $ret['data'] = array('id' => $category_id, 'name' => $_REQUEST['name'], 'image' => $imageName);
	    
	}
	echo json_encode($ret);
	die();
?>