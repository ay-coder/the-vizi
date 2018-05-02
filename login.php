<?php
    require 'config.php';

    if (isset($_POST) && isset($_POST['user'])) {
        $user = array();
        if ($_POST['user'] != '' && $_POST['password'] != '') {
            global $db;

            $args = array("email" => $_POST['user'], "password" => md5($_POST['password']) );
            
            $user = $db->row('SELECT * FROM users WHERE email LIKE :email AND password LIKE :password AND role LIKE "admin"', $args);
            if ($user == '') {
                $msg['user'] = 'Invalid details!';
            }
            else if ($user['status'] != 1) {
                $msg['user'] = 'Your account is blocked!';
            }
            else {
                unset($user['password']);
                $_SESSION['user'] = $user;
                header('Location: ' . HOST . 'admin/dashboard.php');
                die();
            }
        }
        else {
            if ($_POST['user'] == '')
                $msg['user'] = 'Email is required!';

            if ($_POST['password'] == '')
                $msg['password'] = 'Password is required!';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vizi</title>
        <link href="<?php echo PUBLIC_URL; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo PUBLIC_URL; ?>font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="<?php echo PUBLIC_URL; ?>css/animate.css" rel="stylesheet">
        <link href="<?php echo PUBLIC_URL; ?>css/style.css" rel="stylesheet">
    </head>

<body class="gray-bg">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">
                    <?php
                        $logo_path = PUBLIC_URL . 'img/logo.png';
                    ?>
                    <h1>Vizi</h1>
                    <!-- <img src="<?php echo $logo_path; ?>" style="max-width: 100%;"> -->
                </h1>
            </div>
            <h3>Welcome to Vizi</h3>
            <form class="m-t" role="form" action="" method="post">
                <div class="form-group <?php echo isset($msg['user']) ? 'has-error' : ''; ?>">
                    <input type="text" class="form-control" name="user" placeholder="Email">
                    <?php 
                        if (isset($msg['user']))
                            echo '<span class="help-block m-b-none text-left">'.$msg['user'].'</span>';
                    ?>
                </div>
                <div class="form-group <?php echo isset($msg['password']) ? 'has-error' : ''; ?>">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <?php 
                        if (isset($msg['password']))
                            echo '<span class="help-block m-b-none text-left">'.$msg['password'].'</span>';
                    ?>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                <a href="<?php echo HOST ?>forget.php"><small>Forgot password?</small></a>
            </form>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?php echo PUBLIC_URL; ?>js/jquery-2.1.1.js"></script>
    <script src="<?php echo PUBLIC_URL; ?>js/bootstrap.min.js"></script>
</body>
</html>
