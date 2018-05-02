<?php
    require '../../config.php';
    require DOC_ROOT . 'admin/header.php';

    $msg = array();
    global $db;

    if (isset($_POST) && count($_POST) > 0) 
    {
        $pinId = $_POST['pin_id'];

        $sql = 'UPDATE pins set
         
                
                title       = "'. $_POST['title'] .'",
                category_id = "'. $_POST['category_id'] .'",
                lat         = "'. $_POST['lat'] .'",
                lon         = "'. $_POST['long'] .'",
                address     = "'. $_POST['address'] .'",
                note        = "'. $_POST['note'] .'"
            WHERE id = '. $pinId;
        
        $pins = $db->query($sql);
        
        ?>
        <script>
            alert('Pin Edited Successfully');

                setTimeout(function()
                {
                    window.location.assign('<?php echo HOST ?>admin/pins');
                }, 10);
        </script>
        <?php
    }


    $currentPin = $db->query('SELECT *, pins.id as id FROM pins left join categories on categories.id = pins.category_id WHERE pins.id = :id ', $_GET);
    $currentPin = $currentPin[0];
//    pr($currentPin);
?>
  <style>
       #map {
        height: 300px;
        width: 100%;
       }
    </style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Edit Pin</h2>
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
                    <h5>Edit Pin</h5>
                </div>
                <div class="ibox-content">
                    
                    
                    <div id="map"></div>
                    <div class="clearfix"><br></div>
                    <form class="form-horizontal" action="" method="post">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Select Category</label>
                            <div class="col-sm-10">
                                <select name="category_id" class="form-control" id="category_id">
                                <?php
                                    $pins = $db->query('SELECT DISTINCT(id), name FROM categories order by id');

                                    foreach($pins as $pin)
                                    {
                                        $selected = '';
                                        if($pin['id'] == $currentPin['category_id'])
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                    ?>
                                        <option <?php echo $selected;?> value='<?php echo $pin['id'];?>'><?php echo $pin['name'];?></option>
                                    <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" value="<?php echo $currentPin['title'];?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-10">
                                <input type="text" name="address" value="<?php echo $currentPin['address'];?>"  class="form-control"  required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Notes</label>
                            <div class="col-sm-10">
                                <textarea name="note" class="form-control" required="required"><?php echo $currentPin['note'];?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a href="<?php echo HOST ?>admin/categories/" class="btn btn-white">Cancel</a>
                                <input type="hidden" id="lat" name="lat" value="<?php echo $currentPin['lat'];?>">
                                <input type="hidden" id="long" name="long" value="<?php echo $currentPin['lon'];?>">
                                <input type="hidden" id="user_id" name="user_id" value="1">
                                <input type="hidden" id="pin_id" name="pin_id" value="<?php echo $currentPin['id'];?>" >
                                <button class="btn btn-primary" type="submit">Update Pin</button>
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
                
                document.getElementById("lat").value = event.latLng.lat();
                document.getElementById("long").value = event.latLng.lng();
                //infoWindow.open(map, marker);
            });
          }
     </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDquec3hXP96UH0iNVWoRNK2QltdJsi1kQ&callback=initMap">
    </script>
<?php require DOC_ROOT . 'admin/footer.php'; ?>