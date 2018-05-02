<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
    	$ret['message'] = 'User id can not be blank!';
    }

    if (!isset($_REQUEST['name']) || $_REQUEST['name'] == '') {
    	$ret['message'] = 'Title can not be blank!';
    }

    if ($ret['message'] == '') 
    {
    	$userImage = '';
        if (isset($_FILES) && isset($_FILES['image']) && count($_FILES['image']) > 0) 
        {
            $file = pathinfo($_FILES['image']['name']);
            $name = $file['filename'];
            $ext = $file['extension'];
            $rand = time();
            $image = $name . '-' . $rand . '.' . $ext;
            $upload_to = PUBLIC_PATH . 'categories/' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_to);
            $userImage = $_POST['image'] = PUBLIC_URL . 'categories/' . $image;
        }

        $private = $_POST['private'] ? $_POST['private'] : 0;
        $sql = 'INSERT INTO  categories(name, user_id, image, is_private) VALUES
                (
                    "'.$_POST['name'].'",
                    "'.$_POST['user_id'].'",
                    "'.$userImage.'",
                    "'.$private.'"
                )';
        
        //$insert = $db->query("INSERT INTO categories(name, user_id, image, is_private) VALUES(:name, :user_id, :image, :private)", $_POST);
        
        $insert = $db->query($sql);
        $new_cat_id = $db->lastInsertId();
        $ret['message'] = 'Category created successfully';
        $ret['status'] = 'success';
        $ret['data'] = array('id' => $new_cat_id, 'name' => $_REQUEST['name'], 'image' => $_POST['image']);
    }
    echo json_encode($ret);
    die();
?>