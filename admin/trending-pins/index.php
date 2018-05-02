<?php
	require '../../config.php';
	require DOC_ROOT . 'admin/header.php';
	$pins = $db->query('SELECT *, pins.id as id FROM trending_pins as pins LEFT JOIN trending_places ON trending_places.id = pins.category_id WHERE pins.user_id = 1 ');
?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>All Trending Pins</h2>
			<ol class="breadcrumb">
				<li><a href="<?php echo HOST ?>admin/dashboard.php">Dashboard</a></li>
				<li class="active"><strong>Trending Pins</strong></li>
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
			                                Pin deleted successfully!
			                            </div>';
			                }
			                else {
			                	echo '<div class="alert alert-danger alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Something went wrong in Pin deletion!
			                            </div>';
			                }
                		}

                		if (isset($_GET['updated'])) {
                			if ($_GET['updated'] == "true") {
	                			echo '<div class="alert alert-success alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Pin updated successfully!
			                            </div>';
			                }
			                else {
			                	echo '<div class="alert alert-danger alert-dismissable">
			                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			                                Something went wrong in Pin updation!
			                            </div>';
			                }
                		}
                	?>
                    <div class="ibox-title">
                        <h5>List of all Pins</h5>
                    </div>
                    <div class="ibox-content">
	                    <table class="table table-striped table-bordered table-hover dataTables-example" >
		                    <thead>
			                    <tr>
			                        <th>Id</th>
			                        <th>Title</th>
			                        <th>Trending Place</th>
			                        <th>Address</th>
			                        <th>Lat</th>
			                        <th>Long</th>
			                        <th>Actions</th>
			                    </tr>
		                    </thead>
		                    <tbody>
		                    	<?php
		                    	if (count($pins) > 0) {
		                    		foreach ($pins as $pin) {
		                    			echo '<tr>';
		                    				echo '<td>'.$pin['id'].'</td>';
		                    				echo '<td>'.$pin['title'].'</td>';
		                    				echo '<td>'.$pin['name'].'</td>';
		                    				echo '<td>'.$pin['address'].'</td>';
		                    				echo '<td>'.$pin['lat'].'</td>';
		                    				echo '<td>'.$pin['lon'].'</td>';
		                    				echo '<td><a href="'.HOST.'admin/trending-pins/edit.php?id='.$pin['id'].'"><i class="fa fa-edit"></i></a> 
		                    				&nbsp;
		                    						<a onclick="return sure();" href="'.HOST.'admin/trending-pins/delete.php?id='.$pin['id'].'"><i class="fa fa-trash-o"></i></a></td>';
		                    			echo '</tr>';
		                    		}
		                    	}
		                    	else {
		                    		echo '<tr><td></td><td></td><td>No Records Found</td><td></td><td></td></tr>';
		                    	}
		                    	?>
			                </tbody>
		                </table>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<script>
		function sure() {
			return confirm('Are you sure you want to delete this Pin? There is no undo!');
		}
		$('.dataTables-example').dataTable({
            responsive: true
        });
	</script>
<?php require DOC_ROOT . 'admin/footer.php'; ?>