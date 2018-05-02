<?php
	require '../../config.php';

	$msg = array();

	if (isset($_POST) && count($_POST) > 0) 
    {
        if ($_POST['name'] != '') 
        {
            $_POST['image'] = '';
            if (isset($_FILES) && isset($_FILES['image']) && count($_FILES['image']) > 0) {
                $file = pathinfo($_FILES['image']['name']);
                $name = $file['filename'];
                $ext = $file['extension'];
                $rand = time();
                $image = $name . '-' . $rand . '.' . $ext;
                $upload_to = PUBLIC_PATH . 'categories/' . $image;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_to);
                $_POST['image'] = PUBLIC_URL . 'categories/' . $image;
            }

            $userId     = $_POST['user_id'];
            $userInfo   = $db->query('SELECT * FROM users WHERE id = "'. $userId .'"');

            if($userInfo[0]['role'] == 'admin')
            {
                $getAllUsers = $db->query('SELECT * FROM users WHERE role != "admin"');
                foreach($getAllUsers as $singleUser)
                {
                    $categoryInfo = [
                        'name'      => $_POST['name'],
                        'user_id'   => $singleUser['id'],
                        'image'     => $_POST['image']
                    ];
                    $insert = $db->query("INSERT INTO categories(name, user_id, image) VALUES(:name, :user_id, :image)", $categoryInfo);
                }
            }
            
			$insert = $db->query("INSERT INTO categories(name, user_id, image) VALUES(:name, :user_id, :image)", $_POST);
			if($insert > 0 )
				$msg['success'] = 'New category has been created!';
			else
				$msg['error'] = 'Something went wrong!';
		}
		else
			$msg['error'] = 'Category name is required!';
	}
    $users = $db->query('SELECT id, user_name FROM users');
    require DOC_ROOT . 'admin/header.php';
?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Add New Category</h2>
			<ol class="breadcrumb">
				<li><a href="<?php echo HOST ?>admin/dashboard.php">Dashboard</a></li>
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
                    <h5>Create new category</h5>
                </div>
                <div class="ibox-content">
                	<?php
                		if (isset($msg['success'])) {
	                		echo '<div class="alert alert-success alert-dismissable">
		                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		                                '.$msg['success'].'
		                            </div>';
                		}
                	?>
                	<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                		<div class="form-group <?php echo isset($msg['error']) ? 'has-error' : ''; ?>">
                			<label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                            	<input class="form-control" name="name" type="text">
                            <?php 
                            	if (isset($msg['error']))
                            		echo '<span class="help-block m-b-none">'.$msg['error'].'</span>';
                            ?>
                            </div>
                        </div>
                        <div class="form-group <?php echo isset($msg['user_id']) ? 'has-error' : ''; ?>">
                            <label class="col-sm-2 control-label">User</label>
                            <div class="col-sm-7">
                                <select name="user_id" class="form-control">
                            <?php
                                foreach ($users as $u) {
                                    echo '<option value="'.$u['id'].'">'.$u['user_name'].'</option>';
                                }
                            ?>
                                </select>
                            <?php 
                                if (isset($msg['user_id']))
                                    echo '<span class="help-block m-b-none">'.$msg['user_id'].'</span>';
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-7">
                                <input type="file" name="image" class="control-label" style="text-align: left;" />
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
<?php require DOC_ROOT . 'admin/footer.php'; ?>
