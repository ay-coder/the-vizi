<?php
    set_time_limit(0);
    require '../config.php';
    global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['user_id']) || $_REQUEST['user_id'] == '') {
        $ret['message'] = 'User id can not be blank!';
    }

    if (!isset($_REQUEST['title']) || $_REQUEST['title'] == '') {
        $ret['message'] = 'Title can not be blank!';
    }

    if (!isset($_REQUEST['lat']) || $_REQUEST['lat'] == '') {
        $ret['message'] = 'Latitude can not be blank!';
    }

    if (!isset($_REQUEST['lon']) || $_REQUEST['lon'] == '') {
        $ret['message'] = 'Logitude can not be blank!';
    }

    if (!isset($_REQUEST['category_id']) || $_REQUEST['category_id'] == '') {
        $ret['message'] = 'Category id can not be blank!';
    }

    if ($ret['message'] == '') {

        $geocode = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$_REQUEST['lat'].','.$_REQUEST['lon'].'&sensor=false');
        $output= json_decode($geocode);
        $city = $country = '';
        for($j=0;$j<count($output->results[0]->address_components);$j++) {
            
            $cn=array($output->results[0]->address_components[$j]->types[0]);
            
            if(in_array("country", $cn)) {
                $country = $output->results[0]->address_components[$j]->long_name;
            }

            if(in_array("locality", $cn)) {
                $city = $output->results[0]->address_components[$j]->long_name;
            }
        }
        $add = $city != '' ? $city . ', ' . $country : $country;
        $ins = array(
            'user_id' => $_REQUEST['user_id'],
            'title' => $_REQUEST['title'],
            'category_id' => $_REQUEST['category_id'],
            'lat' => $_REQUEST['lat'],
            'lon' => $_REQUEST['lon'],
            'address' => $add,
            'note' => $_REQUEST['note']
        );

        $title      = $_REQUEST['title'];
        $categoryId = $_REQUEST['category_id'];
        $lat        = $_REQUEST['lat'];
        $lon        = $_REQUEST['lon'];
        $address    = $add;
        $note       =  $_REQUEST['note'];

        $pin_id = $_REQUEST['pin_id'];

        $sql = 'UPDATE pins 
                SET
                title = "'.$title.'",
                lat = "'.$lat.'",
                lon = "'.$lon.'",
                category_id = "'. $categoryId .'",
                address = "'.$address.'",
                note = "'.$note.'"
                WHERE id = "'.$pin_id.'"
                ';
        $db->query($sql);

        $deleteMediaQuery = "DELETE FROM media where pin_id = '".$pin_id."'";
        $db->query($deleteMediaQuery);
        
        if (isset($_FILES) && isset($_FILES['image']) && isset($_FILES['image']['name']) && count($_FILES['image']['name']) > 0) {
            foreach ($_FILES['image']['name'] as $k => $file_name) {
                if ($_FILES['image']['error'][$k] == 0) {
                    $file = pathinfo($file_name);
                    $name = $file['filename'];
                    $ext = $file['extension'];
                    $rand = time();
                    $image = $name . '-' . $rand . '.' . $ext;
                    $upload_to = PUBLIC_PATH . 'pins/' . $image;
                    $done = move_uploaded_file($_FILES['image']['tmp_name'][$k], $upload_to);
                    /*var_dump($done);
                    die();*/
                    $db->query('INSERT INTO media (pin_id, image) VALUES(:pin_id, :image)', array('pin_id' => $pin_id, 'image' => PUBLIC_URL . 'pins/' . $image) );
                }
            }
        }

        /*$db->query('INSERT INTO notifications (user_id, type, obj_id) VALUES(:uid, :type, :obj_id) ', array('uid' => $_REQUEST['user_id'], 'type' => 'PIN', 'obj_id' => $pin_id));*/

        $ins['pind_id'] = $pin_id;

        $ret['message'] = 'Pin updated successfully';
        $ret['status'] = 'success';
        $ret['data'] = $ins;
    }

    echo json_encode($ret);
    die();
?>