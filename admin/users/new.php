<?php
	require '../../config.php';
	require DOC_ROOT . 'admin/header.php';

	$msg = array();
    global $db;

	if (isset($_POST) && count($_POST) > 0) {
            if ($_POST['name'] != '' && $_POST['email'] != '' && $_POST['password'] != '') {
                
                $exi = $db->query('SELECT id FROM users WHERE email LIKE :email ', array('email' => $_POST['email']));
                $exi_n = $db->query('SELECT id FROM users WHERE user_name LIKE :name ', array('name' => $_POST['name']));
                if (count($exi) > 0) {
                    $msg['email'] = 'Email is already in used!';
                }
                else if (count($exi_n) > 0) {
                    $msg['name'] = 'User name is already in used!';
                }
                else {
                    $tmp = $_POST['password'];
                    $_POST['password'] = md5($_POST['password']);
                    $insert = $db->query("INSERT INTO users(user_name, email, password, status, role) VALUES(:name, :email, :password, :status, :role)", $_POST);
        			if($insert > 0 ) {
        				$msg['success'] = 'New user has been created!';

                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= 'From: Vizi <info@vizi.com>' . "\r\n";

                        $message = "
                                <html>
                                    <body>
                                        Hey ".$_POST['name'].", <br /><br />
                                        Congrats on your new account at ".$tl." <br /><br />
                                        We are excited to have you. <br /><br />
                                        Here are your login details to get you started... <br /><br />
                                        Member Area Login URL : ".HOST."login.php <br />
                                        User Name : ".$_POST['email']." <br />
                                        Password : ".$tmp." <br /><br />
                                        If you need any help you can simply write to ".$em." and ask us anything. <br /><br />
                                        Cheers <br />
                                        Team Vizi
                                    </body>
                                </html>";
                        mail($_POST['email'], "Welcome to Vizi", $message, $headers);
                    }
        			else
        				$msg['error'] = 'Something went wrong!';
                }
    		}
    		else {
                if ($_POST['name'] == '')
                    $msg['name'] = 'User name is required!';

                if ($_POST['email'] == '')
                    $msg['email'] = 'Email is required!';

                if ($_POST['password'] == '')
                    $msg['password'] = 'Password is required!';
            }
	}
?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Add New User</h2>
			<ol class="breadcrumb">
				<li><a href="<?php echo HOST ?>admin/dashboard.php">Dashboard</a></li>
				<li><a href="<?php echo HOST ?>admin/users/">Users</a></li>
				<li class="active"><strong>New</strong></li>
			</ol>
		</div>
		<div class="col-lg-2"> </div>
	</div>
	<div class="wrapper wrapper-content">
		<div class="row">
            <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Create new user</h5>
                </div>
                <div class="ibox-content">
                	<?php
                		if (isset($msg['success'])) {
	                		echo '<div class="alert alert-success alert-dismissable">
		                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		                                '.$msg['success'].'
		                            </div>';
                		}

                         if (isset($msg['max'])) {
                            echo '<div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        '.$msg['max'].'
                                    </div>';
                        }
                	?>
                	<form class="form-horizontal" action="" method="post">
                		<div class="form-group <?php echo isset($msg['name']) ? 'has-error' : ''; ?>">
                            <label class="col-sm-2 control-label">User Name</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="name" type="text" value="<?php echo isset($_POST) && isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                            <?php 
                                if (isset($msg['name']))
                                    echo '<span class="help-block m-b-none">'.$msg['name'].'</span>';
                            ?>
                            </div>
                        </div>
                        <div class="form-group <?php echo isset($msg['email']) ? 'has-error' : ''; ?>">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="email" type="text" value="<?php echo isset($_POST) && isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                            <?php 
                                if (isset($msg['email']))
                                    echo '<span class="help-block m-b-none">'.$msg['email'].'</span>';
                            ?>
                            </div>
                        </div>
                        <div class="form-group <?php echo isset($msg['password']) ? 'has-error' : ''; ?>">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="password" type="password">
                            <?php 
                                if (isset($msg['password']))
                                    echo '<span class="help-block m-b-none">'.$msg['password'].'</span>';
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <div class="radio i-checks">
                                    <label> <input type="radio" checked="" value="1" name="status"> <i></i> Active </label>
                                    <label> <input type="radio" value="0" name="status"> <i></i> Inactive </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Role</label>
                            <div class="col-sm-10">
                                <div class="radio i-checks">
                                    <label> <input type="radio" checked="" value="user" name="role"> <i></i> User </label>
                                    <label> <input type="radio" value="admin" name="role"> <i></i> Admin </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a href="<?php echo HOST ?>admin/categories/" class="btn btn-white">Cancel</a>
                                <button class="btn btn-primary" type="submit">Create</button>
                            </div>
                        </div>
                	</form>
                </div>
            </div>
        </div>
    </div>
    <!-- iCheck -->
    <link href="<?php echo PUBLIC_URL; ?>css/plugins/iCheck/custom.css" rel="stylesheet">
    <script src="<?php echo PUBLIC_URL; ?>js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
<?php require DOC_ROOT . 'admin/footer.php'; ?>