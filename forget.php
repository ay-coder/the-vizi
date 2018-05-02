<?php
    require 'config.php';

    global $db;
    
    if (isset($_POST) && isset($_POST['user'])) {
        $user = array();
        if ($_POST['user'] != '') {
            global $db;
            $user = $db->row('SELECT * FROM users WHERE email like :email ' . $and, array("email" => $_POST['user'] ));
            if ($user == '') {
                $msg['user'] = 'Invalid details!';
            }
            else if ($user['status'] != 1) {
                $msg['user'] = 'Your account is blocked!';
            }
            else {
                $reset = generateRandomString(20);
                $db->query('UPDATE users SET reset = :reset WHERE id = :id', array("id" => $user['id'], "reset" => $reset));
                
                $em = $db->single('SELECT meta_value FROM settings WHERE meta_key like "site_email" ' . $and);
                $tl = $db->single('SELECT meta_value FROM settings WHERE meta_key like "site_title" ' . $and);

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: '.$title.' <'.$em.'>' . "\r\n";

                $message = "
                        <html>
                            <body>
                                Hello ".$user['name'].", looks like you need to reset your password. <br /><br />
                                Just <a href='".HOST."reset/".$reset."'>CLICK HERE</a> and you can set a new password for your account. <br /><br />
                                Or you can copy paste the url below into your browser...<br />
                                ".HOST."reset/".$reset." <br /><br />
                                It's that easy. <br /><br />
                                Cheers<br />
                                Team ".$tl."
                            </body>
                        </html>";
                //pr($message);
                mail($_POST['user'], "Reset Your Password at " . $tl, $message, $headers);
                $msg['success'] = 'Password reset email sent! Please check your email';
            }
        }
        else {
            if ($_POST['user'] == '')
                $msg['user'] = 'Email is required!';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vizi - Forget Password</title>
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
                    <!-- <img src="<?php echo $logo_path ?>" style="max-width: 100%;"> -->
                    <h1>Vizi</h1>
                </h1>
            </div>
            <p>Please provide your email address. We will send you details on that.</p>
            <form class="m-t" role="form" action="" method="post">
                <div class="form-group <?php echo isset($msg['user']) ? 'has-error' : ''; ?>">
                    <input type="text" class="form-control" name="user" placeholder="Email">
                    <?php 
                        if (isset($msg['user']))
                            echo '<span class="help-block m-b-none text-left">'.$msg['user'].'</span>';
                    ?>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Reset Password</button>

                <a href="<?php echo HOST ?>login.php"><small>Login</small></a>
            </form>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?php echo PUBLIC_URL; ?>js/jquery-2.1.1.js"></script>
    <script src="<?php echo PUBLIC_URL; ?>js/bootstrap.min.js"></script>

    <?php
    if (isset($msg) && $msg['success'] != '') {
    ?>
        <!-- Toastr style -->
        <link href="<?php echo PUBLIC_URL ?>css/plugins/toastr/toastr.min.css" rel="stylesheet">
        <!-- Toastr script -->
        <script src="<?php echo PUBLIC_URL ?>js/plugins/toastr/toastr.min.js"></script>

        <script>
            var redirect = function() {
                location.href = "<?php echo HOST ?>login.php";
            };

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "onclick": redirect,
                "showDuration": "400",
                "hideDuration": "0",
                "timeOut": "0",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr['success']("<?php echo $msg['success'] ?>", 'Password reset link');
        </script>
        <style>
            #toast-container > .toast {
                background-image: none !important;
            }
        </style>
    <?php
    }
    ?>

</body>
</html>