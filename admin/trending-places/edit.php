<?php
    require '../../config.php';

    $msg = array();

    if (isset($_POST) && count($_POST) > 0) {
        global $db;
        if ($_POST['name'] == '')
            $msg['error'] = 'Trending Place name can not be blank!';
        else {
            $_POST['image'] = $_POST['old_image'];
            unset($_POST['old_image']);
            if (isset($_FILES) && isset($_FILES['image']) && count($_FILES['image']) > 0 && $_FILES['image']['name'] != '') {
                $file = pathinfo($_FILES['image']['name']);
                $name = $file['filename'];
                $ext = $file['extension'];
                $rand = time();
                $image = $name . '-' . $rand . '.' . $ext;
                $upload_to = PUBLIC_PATH . 'categories/' . $image;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_to);
                $_POST['image'] = PUBLIC_URL . 'categories/' . $image;
            }
            $db->query("UPDATE trending_places SET name = :name, user_id = :user_id, image = :image WHERE id = :id ", $_POST);
            header('Location: ' . HOST . 'admin/trending-places/index.php?updated=true');
        }
    }

    if (!isset($_GET) || !isset($_GET['id']) || $_GET['id'] == '')
        header('Location: ' . HOST . 'admin/trending-places/');

    $cat = $db->query('SELECT * FROM trending_places WHERE id = :id ', $_GET);
    if (count($cat) > 0)
        $cat = $cat[0];
    else {
        header('Location: ' . HOST . 'admin/trending-places');
        die();
    }
    $users = $db->query('SELECT id, user_name FROM users');
    require DOC_ROOT . 'admin/header.php';
?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Edit Trending Place</h2>
            <ol class="breadcrumb">
                <li><a href="<?php echo HOST ?>admin/dashboard.php">Dashboard</a></li>
                <li><a href="<?php echo HOST ?>admin/trending-places/">Places</a></li>
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
                    <h5>Edit Trending Place: <?php echo $cat['name']; ?></h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group <?php echo isset($msg['error']) ? 'has-error' : ''; ?>">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="name" type="text" value="<?php echo $cat['name']; ?>">
                            <?php 
                                if (isset($msg['error']))
                                    echo '<span class="help-block m-b-none">'.$msg['error'].'</span>';
                            ?>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>" />
                            <input type="hidden" name="user_id" value="1" />
                        </div>
                 
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-7">
                                <input type="file" name="image" class="control-label" style="text-align: left;" />
                                <input type="hidden" name="old_image" value="<?php echo $cat['image'] ?>" />
                                <div class="col-sm-12">&nbsp;</div>
                                <?php if ($cat['image'] != '') { ?>
                                    <img src="<?php echo $cat['image'] ?>" width="300px" />
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a href="<?php echo HOST ?>admin/trending-places/" class="btn btn-white">Cancel</a>
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php require DOC_ROOT . 'admin/footer.php'; ?>