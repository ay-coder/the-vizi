<?php
	require '../../config.php';
	require DOC_ROOT . 'admin/header.php';
	//$users = $db->query('SELECT * FROM abuse WHERE abuse = 1 ');
	$users = array();
?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Abuse Users</h2>
			<ol class="breadcrumb">
				<li><a href="<?php echo HOST ?>admin/dashboard.php">Dashboard</a></li>
				<li class="active"><strong>Users</strong></li>
			</ol>
		</div>
		<div class="col-lg-2"> </div>
	</div>
	<div class="wrapper wrapper-content">
		<div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                	<?php
                		if (isset($_GET['deleted'])) {
                			if ($_GET['deleted'] == "true") {
	                			echo '<div class="alert alert-success alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Admin deleted successfully!
			                            </div>';
			                }
			                else {
			                	echo '<div class="alert alert-danger alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Something went wrong in admin deletion!
			                            </div>';
			                }
                		}

                		if (isset($_GET['updated'])) {
                			if ($_GET['updated'] == "true") {
	                			echo '<div class="alert alert-success alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Admin updated successfully!
			                            </div>';
			                }
			                else {
			                	echo '<div class="alert alert-danger alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Something went wrong in admin updation!
			                            </div>';
			                }
                		}
                	?>
                    <div class="ibox-title">
                        <h5>List of abuse users</h5>
                    </div>
                    <div class="ibox-content">
	                    <table class="table table-striped table-bordered table-hover dataTables-example" >
		                    <thead>
			                    <tr>
			                        <th>Id</th>
			                        <th>Abuse By</th>
			                        <th>Abusee</th>
			                        <th>Status</th>
			                        <th>Actions</th>
			                    </tr>
		                    </thead>
		                    <tbody>
		                    	<?php
		                    	if (count($users) > 0) {
		                    		foreach ($users as $user) {
		                    			echo '<tr>';
		                    				echo '<td>'.$user['id'].'</td>';
		                    				echo '<td>'.$user['user_name'].'</td>';
		                    				echo '<td>'.$user['email'].'</td>';
		                    				echo '<td>'.($user['status'] == 1 ? 'Active' : 'Inactive' ).'</td>';
		                    				echo '<td><a href="'.HOST.'admin/users/edit.php?id='.$user['id'].'"><i class="fa fa-edit"></i></a> &nbsp; <a onclick="return sure();" href="'.HOST.'admin/users/delete.php?id='.$user['id'].'"><i class="fa fa-trash-o"></i></a></td>';
		                    			echo '</tr>';
		                    		}
		                    	}
		                    	else {
		                    		echo '<tr><td></td><td></td><td>No Records Found</td><td></td><td></td></tr>';
		                    	}
		                    	?>
			                </tbody>
		                    <tfoot>
		                    	<tr>
			                        <th>Id</th>
			                        <th>Abuse By</th>
			                        <th>Abusee</th>
			                        <th>Status</th>
			                        <th>Actions</th>
			                    </tr>
	                    	</tfoot>
	                    </table>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<script>
		function sure() {
			return confirm('Are you sure you want to delete this admin? There is no undo!');
		}
		$('.dataTables-example').dataTable({
            responsive: true
        });
	</script>
<?php require DOC_ROOT . 'admin/footer.php'; ?>