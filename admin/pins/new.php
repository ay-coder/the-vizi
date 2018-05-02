<?php
	require '../../config.php';
	require DOC_ROOT . 'admin/header.php';

	$msg = array();
    global $db;

	if (isset($_POST) && count($_POST) > 0) 
    {
        $sql = 'INSERT INTO pins (title, user_id, category_id, lat, lon, address, note)
                VALUES (
                "'. $_POST['title'] .'",
                "'. $_POST['user_id'] .'",
                "'. $_POST['category_id'] .'",
                "'. $_POST['lat'] .'",
                "'. $_POST['long'] .'",
                "'. $_POST['address'] .'",
                "'. $_POST['note'] .'"
                )';
        $pins = $db->query($sql);

        $pin_id = $db->lastInsertId();

        if (isset($_FILES) && isset($_FILES['image']) && isset($_FILES['image']['name']) && count($_FILES['image']['name']) > 0) 
        {
            foreach ($_FILES['image']['name'] as $k => $file_name) 
            {
                if ($_FILES['image']['error'][$k] == 0) 
                {
                    $file = pathinfo($file_name);
                    $name = $file['filename'];
                    $ext = $file['extension'];
                    $rand = time();
                    $image = $name . '-' . $rand . '.' . $ext;
                    $upload_to = PUBLIC_PATH . 'pins/' . $image;
                    $done = move_uploaded_file($_FILES['image']['tmp_name'][$k], $upload_to);
                    
                    $db->query('INSERT INTO media (pin_id, image) VALUES(:pin_id, :image)', array('pin_id' => $pin_id, 'image' => PUBLIC_URL . 'pins/' . $image) );
                }
            }
        }
        ?>
        <script>
            alert('New Pin Created Successfully');

                setTimeout(function()
                {
                    window.location.assign('<?php echo HOST ?>admin/dashboard.php');
                }, 10);
        </script>
        <?php
	}
?>
  <style>
       #map {
        height: 300px;
        width: 100%;
       }
    </style>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Add New Pin</h2>
			<ol class="breadcrumb">
				<li><a href="<?php echo HOST ?>admin/dashboard.php">Dashboard</a></li>
				<li><a href="<?php echo HOST ?>admin/pins/">Pins</a></li>
				<li class="active"><strong>New</strong></li>
                <li class="text-right"><a href="<?php echo HOST ?>admin/pins">View All</a></li>
            </ol>

		</div>
		<div class="col-lg-2"> </div>
	</div>
	<div class="wrapper wrapper-content">
		<div class="row">
            <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Create new Pin</h5>
                </div>
                <div class="ibox-content">
                	
                	
                    <div id="map"></div>
                    <div class="clearfix"><br></div>
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Select Category</label>
                            <div class="col-sm-10">
                                <select name="category_id" class="form-control" id="category_id">
                                <?php
                                    $pins = $db->query('SELECT DISTINCT(id), name FROM categories order by id');

                                    foreach($pins as $pin)
                                    {
                                    ?>

                                        <option value='<?php echo $pin['id'];?>'><?php echo $pin['name'];?></option>
                                    <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" name="title"  class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-10">
                                <input type="text" name="address" id="address" value="200 N Casino Center Blvd, Las Vegas, NV 89101, USA" class="form-control"  required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Notes</label>
                            <div class="col-sm-10">
                                <textarea name="note" class="form-control" required="required"></textarea>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-sm-2 control-label">Select Pin IMage</label>
                            <div class="col-sm-10">
                                <input type="file" name="image[]" multiple="true">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a href="<?php echo HOST ?>admin/categories/" class="btn btn-white">Cancel</a>
                                <input type="hidden" id="lat" name="lat" value="36.1699">
                                <input type="hidden" id="long" name="long" value="-115.1398">
                                <input type="hidden" id="user_id" name="user_id" value="1">
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
    <script>
        var geocoder;
        var map;
        var marker;
          function initMap() 
          {
            var uluru = {lat: 36.1699, lng: -115.1398};
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 10,
              center: uluru
            });
            var marker = new google.maps.Marker({
             draggable:true,
              position: uluru,
              map: map
            });

               google.maps.event.addListener(marker, 'dragend', function (event) 
               {
                    geocodePosition(marker.getPosition());
                document.getElementById("lat").value = event.latLng.lat();
                document.getElementById("long").value = event.latLng.lng();
                //infoWindow.open(map, marker);
            });
          }


          function geocodePosition(pos) 
          {
             geocoder = new google.maps.Geocoder();
              geocoder.geocode({
                latLng: pos
              }, function(responses) {
                if (responses && responses.length > 0) 
                {
                    jQuery("#address").val(responses[0].formatted_address);
                } else 
                {
                    jQuery("#address").val('');
                    
                }
            });
        }
     </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDquec3hXP96UH0iNVWoRNK2QltdJsi1kQ&callback=initMap">
    </script>
<?php require DOC_ROOT . 'admin/footer.php'; ?>