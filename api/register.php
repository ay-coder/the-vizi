<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_name']) || $_REQUEST['user_name'] == '') {
    	$ret['message'] = 'User name can not be blank!';
    }

    if (!isset($_REQUEST['password']) || $_REQUEST['password'] == '') {
    	$ret['message'] = 'Password can not be blank!';
    }

    if (!isset($_REQUEST['device_id']) || $_REQUEST['device_id'] == '') {
        $ret['message'] = 'Device id can not be blank!';
    }

    if (!isset($_REQUEST['lat']) || $_REQUEST['lat'] == '') {
        $ret['message'] = 'Latitude can not be blank!';
    }

    if (!isset($_REQUEST['lon']) || $_REQUEST['lon'] == '') {
        $ret['message'] = 'Longitude can not be blank!';
    }

    if ($ret['message'] == '') {
        $exi = $db->query('SELECT email, user_name FROM users WHERE email LIKE :email OR user_name LIKE :name LIMIT 1', array('email' => $_POST['email'], 'name' => $_POST['user_name']));
	    if (count($exi) > 0) {
	    	if (isset($exi[0]['email']) && $exi[0]['email'] == $_REQUEST['user_name'])
	        	$ret['message'] = 'Email is already in used!';
	        else if (isset($exi[0]['user_name']) && $exi[0]['user_name'] == $_REQUEST['user_name'])
	        	$ret['message'] = 'Username is already in used!';
	    }
	    else {
	    	$address = getAddress($_REQUEST['lat'], $_REQUEST['lon']);

	    	$ins = array(
	    		'user_name' => $_REQUEST['user_name'],
	    		'email' => $_REQUEST['email'],
	    		'name' => isset($_REQUEST['name']) ? $_REQUEST['name'] : null,
	    		'password' => md5($_REQUEST['password']),
	    		'device_id' => $_REQUEST['device_id'],
	    		'lat' => $_REQUEST['lat'],
	    		'lon' => $_REQUEST['lon'],
	    		'address' => $address
	    	);

	    	$db->query('INSERT INTO users(user_name, name, email, password, device_id, lat, lon, address) VALUES (:user_name, :name, :email, :password, :device_id, :lat, :lon, :address) ', $ins);
	    	unset($ins['password']);
	    	$ins['id'] = $db->lastInsertId();

	    	if (isset($_FILES) && count($_FILES) > 0) {
		    	if (isset($_FILES['image'])) {
		    		$file = pathinfo($_FILES['image']['name']);
	                $name = $file['filename'];
	                $ext = $file['extension'];
	                $rand = time();
	                $image = $name . '-' . $rand . '.' . $ext;
	                $upload_to = PUBLIC_PATH . 'img/users/' . $image;
	                move_uploaded_file($_FILES['image']['tmp_name'], $upload_to);
	                $db->query('UPDATE users SET image = :image WHERE id = :id ', array('id' => $ins['id'], 'image' => PUBLIC_URL . 'img/users/' . $image) );
	                $ins['image'] = PUBLIC_URL . 'img/users/' . $image;
	                //unlink(PUBLIC_PATH . 'img/' . $_REQUEST['user_id'] . '');
		    	}
		    }

		    $user = $db->query('SELECT * FROM users WHERE id = :id', array('id' => $ins['id'] ));
		    $user = $user[0];

		    unset($user['password']);
		    unset($user['reset']);
		    unset($user['role']);

	    	$ret['message'] = 'New user is created!';
	    	$ret['status'] = 'success';
	    	$ret['data'] = $user;
	    }
	    echo json_encode($ret);
    	die();
    }
    echo json_encode($ret);
    die();
?>