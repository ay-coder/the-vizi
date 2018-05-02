<?php
	require '../../config.php';

	$msg = array();

	if (isset($_POST) && count($_POST) > 0) {
		global $db;
        if ($_POST['name'] != '' && $_POST['email'] != '') {

            $exi = $db->query('SELECT id FROM users WHERE id != :id AND email LIKE :email ', array("id" => $_GET['id'], 'email' => $_POST['email']));
            $exi_n = $db->query('SELECT id FROM users WHERE id != :id AND user_name LIKE :name ', array("id" => $_GET['id'], 'name' => $_POST['name']));

            if (count($exi) > 0) {
                $msg['email'] = 'Email is already in used!';
            }
            else if (count($exi_n) > 0) {
                $msg['name'] = 'User name is already in used!';
            }
            else {
                if ($_POST['password'] != '') {
                    $_POST['password'] = md5($_POST['password']);
                    $db->query("UPDATE users SET name = :given_name, user_name = :name, email = :email, password = :password, status = :status, role = :role WHERE id = :id ", $_POST);
                }
                else {
                    unset($_POST['password']);
                    $db->query("UPDATE users SET name = :given_name, user_name = :name, email = :email, status = :status, role = :role WHERE id = :id ", $_POST);
                }
                header('Location: ' . HOST . 'admin/users/index.php?updated=true');
            }
        }
        else {
            if ($_POST['name'] == '')
                $msg['name'] = 'Name is required!';

            if ($_POST['email'] == '')
                $msg['email'] = 'Email is required!';
        }
	}

	if (!isset($_GET) || !isset($_GET['id']) || $_GET['id'] == '')
		header('Location: ' . HOST . 'admin/users/');

	$user = $db->query('SELECT * FROM users WHERE id = :id ', $_GET);
	if (count($user) > 0)
		$user = $user[0];
	else
		header('Location: ' . HOST . 'admin/users');

    require DOC_ROOT . 'admin/header.php';
?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Edit User</h2>
			<ol class="breadcrumb">
				<li><a href="<?php echo HOST ?>admin/dashboard.php">Dashboard</a></li>
				<li><a href="<?php echo HOST ?>admin/users/">Users</a></li>
				<li class="active"><strong>Edit</strong></li>
			</ol>
		</div>
		<div class="col-lg-2"> </div>
	</div>
	<div class="wrapper wrapper-content">
		<div class="row">
            <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Edit user: <?php echo $user['user_name']; ?></h5>
                </div>
                <div class="ibox-content">
                	<form class="form-horizontal" action="" method="post">
                		<div class="form-group <?php echo isset($msg['name']) ? 'has-error' : ''; ?>">
                            <label class="col-sm-2 control-label">User Name</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="name" type="text" value="<?php echo $user['user_name']; ?>">
                            <?php 
                                if (isset($msg['name']))
                                    echo '<span class="help-block m-b-none">'.$msg['name'].'</span>';
                            ?>
                            </div>
                        </div>
                        <div class="form-group <?php echo isset($msg['email']) ? 'has-error' : ''; ?>">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="email" type="text" value="<?php echo $user['email'] ?>">
                            <?php 
                                if (isset($msg['email']))
                                    echo '<span class="help-block m-b-none">'.$msg['email'].'</span>';
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="password" type="password" placeholder="Leave blank if you do not want to update">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <div class="radio i-checks">
                                    <label> <input type="radio" <?php echo $user['status'] == 1 ? "checked" : ''; ?> value="1" name="status"> <i></i> Active </label>
                                    <label> <input type="radio" <?php echo $user['status'] == 0 ? "checked" : ''; ?> value="0" name="status"> <i></i> Inactive </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Role</label>
                            <div class="col-sm-10">
                                <div class="radio i-checks">
                                    <label> <input type="radio" <?php echo $user['role'] == 'user' ? "checked" : ''; ?> value="user" name="role"> <i></i> User </label>
                                    <label> <input type="radio" <?php echo $user['role'] == 'admin' ? "checked" : ''; ?> value="admin" name="role"> <i></i> Admin </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a href="<?php echo HOST ?>admin/levels/" class="btn btn-white">Cancel</a>
                                <button class="btn btn-primary" type="submit">Update</button>
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