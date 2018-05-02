<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_name']) || $_REQUEST['user_name'] == '') {
    	$ret['message'] = 'User name can not be blank!';
    }

    if (!isset($_REQUEST['email']) || $_REQUEST['email'] == '') {
    	$ret['message'] = 'Email can not be blank!';
    }

    if (!isset($_REQUEST['device_id']) || $_REQUEST['device_id'] == '') {
        $ret['message'] = 'Device id can not be blank!';
    }

    if (!isset($_REQUEST['fbid']) || $_REQUEST['fbid'] == '') {
        $ret['message'] = 'Facebook id can not be blank!';
    }

    if (!isset($_REQUEST['lat']) || $_REQUEST['lat'] == '') {
        $ret['message'] = 'Latitude can not be blank!';
    }

    if (!isset($_REQUEST['lon']) || $_REQUEST['lon'] == '') {
        $ret['message'] = 'Longitude can not be blank!';
    }

    if ($ret['message'] == '') {
        //Case 1 - User exists
        $address = getAddress($_REQUEST['lat'], $_REQUEST['lon']);

        $fbexi = $db->query('SELECT * FROM users WHERE fbid LIKE :fbid AND email LIKE :email', array('email' => $_POST['email'], 'fbid' => $_POST['fbid'] ));
        if (count($fbexi) > 0) {
            $exi = $fbexi[0];
            $db->query('UPDATE users SET lat = :lat, lon = :lon, address = :address WHERE fbid LIKE :fbid AND email LIKE :email ', array('email' => $_POST['email'], 'fbid' => $_POST['fbid'], 'lat' => $_REQUEST['lat'], 'lon' => $_REQUEST['lon'], "address" => $address ));
            if ((int)$exi['status'] == 1 ) {
                unset($exi['password']);
                unset($exi['reset']);
                unset($exi['role']);
                $ret['status'] = 'success';
                $ret['data'] = $exi;
                $ret['message'] = 'User loggedin successfully!';
            }
            else {
                $ret['message'] = 'User is blocked!';
            }
            echo json_encode($ret);
            die();
        }

        //Case 2 - Email is there, fbid is missing
        $emexi = $db->query('SELECT * FROM users WHERE fbid = "" AND email LIKE :email', array('email' => $_POST['email'] ));
        if (count($emexi) > 0) {
            $exi = $emexi[0];
            if ((int)$exi['status'] == 1 ) {
                $db->query('UPDATE users SET fbid = :fbid, lat = :lat, lon = :lon, address = :address WHERE email LIKE :email', array('email' => $_POST['email'], 'fbid' => $_POST['fbid'], 'lat' => $_REQUEST['lat'], 'lon' => $_REQUEST['lon'], 'address' => $address));
                $exi['fbid'] = $_POST['fbid'];
                unset($exi['password']);
                unset($exi['reset']);
                unset($exi['role']);
                $ret['status'] = 'success';
                $ret['data'] = $exi;
                $ret['message'] = 'User loggedin successfully!';
            }
            else {
                $ret['message'] = 'User is blocked!';
            }
            echo json_encode($ret);
            die();
        }

        //Case 3 - Create new user
        $ins = array(
                'user_name' => $_REQUEST['user_name'],
                'email' => $_REQUEST['email'],
                'password' => md5($_REQUEST['user_name']),
                'fbid' => $_REQUEST['fbid'],
                'device_id' => $_REQUEST['device_id'],
                'image' => isset($_REQUEST['image']) ? $_REQUEST['image'] : '',
                'lat' => $_REQUEST['lat'],
                'lon' => $_REQUEST['lon'],
                'address' => $address
            );

            $db->query('INSERT INTO users(user_name, email, password, device_id, fbid, image, lat, lon, address) VALUES (:user_name, :email, :password, :device_id, :fbid, :image, :lat, :lon, :address) ', $ins);
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
            echo json_encode($ret);
            die();
    }
    echo json_encode($ret);
    die();
?>