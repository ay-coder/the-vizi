<?php
	require '../../config.php';
	require DOC_ROOT . 'admin/header.php';

	$categories = $db->query('SELECT * FROM trending_places WHERE  user_id IN (SELECT id FROM users WHERE role LIKE "admin" ) ');
?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>All Trending Categories</h2>
			<ol class="breadcrumb">
				<li><a href="<?php echo HOST ?>admin/dashboard.php">Dashboard</a></li>
				<li class="active"><strong>Trending Categories</strong></li>
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
			                                Trending Place deleted successfully!
			                            </div>';
			                }
			                else {
			                	echo '<div class="alert alert-danger alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Something went wrong in category deletion!
			                            </div>';
			                }
                		}

                		if (isset($_GET['updated'])) {
                			if ($_GET['updated'] == "true") {
	                			echo '<div class="alert alert-success alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Trending Place updated successfully!
			                            </div>';
			                }
			                else {
			                	echo '<div class="alert alert-danger alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Something went wrong in category updation!
			                            </div>';
			                }
                		}
                	?>
                    <div class="ibox-title">
                        <h5>List of all admin categories</h5>
                    </div>
                    <div class="ibox-content">
	                    <table class="table table-striped table-bordered table-hover dataTables-example" >
		                    <thead>
			                    <tr>
			                        <th>Id</th>
			                        <th>Trending Place</th>
			                        <th>Image</th>
			                        <th>Action</th>
			                    </tr>
		                    </thead>
		                    <tbody>
		                    	<?php
		                    	if (count($categories) > 0) {
		                    		foreach ($categories as $cat) {
		                    			echo '<tr>';
		                    				echo '<td>'.$cat['id'].'</td>';
		                    				echo '<td>'.$cat['name'].'</td>';
		                    				echo '<td>' . ($cat['image'] != '' ? '<img src="'.$cat['image'].'" style="width: 100px;" />' : '' ) . '</td>';
		                    				echo '<td><a href="'.HOST.'admin/trending-places/edit.php?id='.$cat['id'].'"><i class="fa fa-edit"></i></a> &nbsp; <a onclick="return sure();" href="'.HOST.'admin/trending-places/delete.php?id='.$cat['id'].'"><i class="fa fa-trash-o"></i></a></td>';
		                    			echo '</tr>';
		                    		}
		                    	}
		                    	else {
		                    		echo '<tr><td></td><td></td><td>No Records Found</td><td></td></tr>';
		                    	}
		                    	?>
			                </tbody>
		                    <tfoot>
		                    	<tr>
			                        <th>Id</th>
			                        <th>Trending Place</th>
			                        <th>Image</th>
			                        <th>Action</th>
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
			return confirm('Are you sure you want to delete this category? There is no undo!');
		}
		$('.dataTables-example').dataTable({
            responsive: true
        });
	</script>
<?php require DOC_ROOT . 'admin/footer.php'; ?>