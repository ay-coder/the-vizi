<?php
	require '../config.php';
	global $db;

    $ret = array('status' => 'fail', 'message' => '', 'data' => array());

    if (!isset($_REQUEST['email']) || $_REQUEST['email'] == '') {
    	$ret['message'] = 'Email can not be blank!';
    }

    if ($ret['message'] == '') {
        $exi = $db->single('SELECT id FROM users WHERE email LIKE :email', array('email' => $_REQUEST['email']) );
        if ($exi != '') {
        	//code for send email and set rest option and create a page where user can reset passwrord!!!s
           

            $user = $db->query('SELECT * FROM users WHERE id = ' . $exi);
            $randomString = uniqid(rand());
            $newPassword = substr($randomString, 0, 6);
            $updateStatus =  $db->query('UPDATE users set password = "' . md5($newPassword) . '" WHERE id = '.$exi);

            $to = $_REQUEST['email'];
            $subject = "Reset Vizi Password";
            $message = "
                        <html>
                        <head>
                        <title>Reset Password </title>
                        </head>
                        <body>
                        <h3>The Vizi - Reset Password </h3>
                        <p> <strong> Login Email Id : </strong> ".  $_REQUEST['email'] ."</p>
                        <p> <strong> New Password : </strong> ". $newPassword ."</p>
                       
                        </body>
                        </html>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <admin@theviziapp.com>' . "\r\n";
            
            mail($to, $subject, $message,$headers);

        	$ret['status'] = 'success';
        	$ret['message'] = 'Email sent!';
        }
        else {
            $ret['message'] = 'No user found with current details!';
        }
	}
	echo json_encode($ret);
    die();
?>