<?php
	require '../config.php';
    
    global $db;

if(isset($_POST['token']))
{
    require 'push.php';

     //To the user to whome current user is now following
            $token = $_POST['token'];

            if ($token) 
            {
                $msg_payload = array (
                    'mtitle'        => 'Vizi-Test',
                    'mdesc'         => 'This is Test Notification',
                    'badgeCount'    => 0
                );
                
                PushNotifications::iOS($msg_payload, $token, false);
            }

}
?>

<form action="#" method="POST">
<table>
    <tr>
        <td>
        Enter Device Token
        </td>
        <td>
            <textarea class="form-control" name="token" cols="60" rows="4"></textarea>
        </td>
    </tr>
    <tr>
    <td colspan="2">
        <input type="submit" name="submit" value="Submit">
    </td>
    </tr>
</table>
</form>
