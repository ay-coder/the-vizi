<?php
	require '../config.php';
	require DOC_ROOT . 'admin/header.php';
?>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-8">
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <?php $total_users = $db->single('SELECT count(id) FROM users WHERE role LIKE "user" ' ); ?>
                        <div class="ibox-title">
                            <h5>Users</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><?php echo $total_users; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <?php $total_cats = $db->single('SELECT count(id) FROM categories '); ?>
                        <div class="ibox-title">
                            <h5>Categories</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><?php echo $total_cats; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require DOC_ROOT . 'admin/footer.php'; ?>