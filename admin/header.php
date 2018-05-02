<?php redirectIfnotAdmin(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Vizi</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="<?php echo PUBLIC_URL; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo PUBLIC_URL; ?>font-awesome/css/font-awesome.css" rel="stylesheet">

        <!-- Morris -->
        <link href="<?php echo PUBLIC_URL; ?>css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

        <!-- Gritter -->
        <link href="<?php echo PUBLIC_URL; ?>js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

        <link href="<?php echo PUBLIC_URL; ?>css/animate.css" rel="stylesheet">
        <link href="<?php echo PUBLIC_URL; ?>css/style.css" rel="stylesheet">

        <!-- Data Tables -->
        <link href="<?php echo PUBLIC_URL; ?>css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
        <link href="<?php echo PUBLIC_URL; ?>css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
        <link href="<?php echo PUBLIC_URL; ?>css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">


        <!-- Mainly scripts -->
        <script src="<?php echo PUBLIC_URL; ?>js/jquery-2.1.1.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/bootstrap.min.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/metisMenu/jquery.metisMenu.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

        <!-- Flot -->
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/flot/jquery.flot.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/flot/jquery.flot.spline.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/flot/jquery.flot.pie.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/flot/jquery.flot.symbol.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/flot/jquery.flot.time.js"></script>

        <!-- Peity -->
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/peity/jquery.peity.min.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/demo/peity-demo.js"></script>

        <!-- Custom and plugin javascript -->
        <script src="<?php echo PUBLIC_URL; ?>js/inspinia.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/pace/pace.min.js"></script>

        <!-- jQuery UI -->
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/jquery-ui/jquery-ui.min.js"></script>

        <!-- Jvectormap -->
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

        <!-- EayPIE -->
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/easypiechart/jquery.easypiechart.js"></script>

        <!-- Sparkline -->
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/sparkline/jquery.sparkline.min.js"></script>

        <!-- Sparkline demo data  -->
        <script src="<?php echo PUBLIC_URL; ?>js/demo/sparkline-demo.js"></script>

        <!-- Data Tables -->
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/dataTables/dataTables.responsive.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>js/plugins/dataTables/dataTables.tableTools.min.js"></script>

    </head>

    <body>
        <?php
            error_reporting(0);
            $opti = $cats = $dash = $pros =  $levs = $usrs = $sett = $import = $doms = $down = '';
            $all_opti = $all_cats = $all_pros = $all_levs = $all_usrs = $all_adms = $all_dom = $all_pages = '';
            $new_opti = $my_profile = $new_cat = $new_pro = $new_lev = $new_usr = $abs_usr = $new_dom = $new_page = '';

            $trendingMenu = $trendingPlace = $createTrendingPlace = '';

            if (strpos( $_SERVER['REQUEST_URI'], 'dashboard') != false)
                $dash = 'active';
            
            if (strpos( $_SERVER['REQUEST_URI'], 'categories/users') != false)
                $cats = $all_u_cats = 'active';
            else if (strpos( $_SERVER['REQUEST_URI'], 'categories') != false)
                $all_cats = $cats = 'active';

            if (strpos( $_SERVER['REQUEST_URI'], 'categories/new') != false) {
                $new_cat = 'active';
                $all_cats = '';
            }

            if (strpos( $_SERVER['REQUEST_URI'], 'trending-places/new') != false)
            {
                $trendingMenu  = 'active';
                $createTrendingPlace = 'active';
            }


            if (strpos( $_SERVER['REQUEST_URI'], 'trending-places') != false)
            {
                $trendingMenu  = 'active';
                $trendingPlace = $createTrendingPlace == 'active' ? '' :'active';
            }



            if (strpos( $_SERVER['REQUEST_URI'], 'admin/users/admin') != false)
                $all_adms = $usrs = 'active';
            else if (strpos( $_SERVER['REQUEST_URI'], 'admin/users') != false)
                $all_usrs = $usrs = 'active';

            if (strpos( $_SERVER['REQUEST_URI'], 'users/new') != false) {
                $usrs = $new_usr = 'active';
                $all_usrs = '';
            }

            if (strpos( $_SERVER['REQUEST_URI'], 'users/abuse') != false) {
                $usrs = $abs_usr = 'active';
                $all_usrs = '';
            }

            if (strpos( $_SERVER['REQUEST_URI'], 'settings') != false)
                $sett = 'active';

            if (strpos( $_SERVER['REQUEST_URI'], 'profile') != false)
                $my_profile = 'active';
        ?>
        <div id="wrapper">
            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="nav-header text-center">
                            <?php $logo_path = PUBLIC_URL . 'img/logo.png'; ?>
                            <!-- <img src="<?php echo $logo_path ?>" width="80%" /> -->
                            <h1>Vizi</h1>
                            <div class="logo-element">Vz</div>
                        </li>
                        <li class="<?php echo $dash; ?>">
                            <a href="<?php echo HOST ?>admin/dashboard.php"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                        </li>
                        <li class="<?php echo $dash; ?>">
                            <a href="<?php echo HOST ?>admin/pins/new.php">
                                <i class="fa fa-th-large"></i> <span class="nav-label">Create Admin Pins</span>
                            </a>
                        </li>
                        <li class="<?php echo $cats; ?>">
                            <a href="#"><i class="fa fa-sitemap"></i> <span class="nav-label">Categories</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li class="<?php echo $all_cats; ?>"><a href="<?php echo HOST ?>admin/categories">All Admin Categories</a></li>
                                <li class="<?php echo $all_u_cats; ?>"><a href="<?php echo HOST ?>admin/categories/users.php">All User Categories</a></li>
                                <li class="<?php echo $new_cat; ?>"><a href="<?php echo HOST ?>admin/categories/new.php">Add New</a></li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Admin Pins</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li class=""><a href="<?php echo HOST ?>admin/pins">All Pins</a></li>
                                <li class=""><a href="<?php echo HOST ?>admin/pins/new.php">Create Pin</a></li>
                            </ul>
                        </li>

                        <li class="<?php echo $trendingMenu; ?>">
                            <a href="#"><i class="fa fa-sitemap"></i> <span class="nav-label">Trending Places</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li class="<?php echo $trendingPlace; ?>"><a href="<?php echo HOST ?>admin/trending-places">All Trending Places</a></li>
                                <li class="<?php echo $createTrendingPlace; ?>"><a href="<?php echo HOST ?>admin/trending-places/new.php">Add New Trending Place</a></li>
                            </ul>
                        </li>

                         <li class="">
                            <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Trending Pins</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li class=""><a href="<?php echo HOST ?>admin/trending-pins">All Trending Pins</a></li>
                                <li class=""><a href="<?php echo HOST ?>admin/trending-pins/new.php">Create Trending Pin</a></li>
                            </ul>
                        </li>

                        


                        <li class="<?php echo $usrs; ?>">
                            <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Users</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li class="<?php echo $all_usrs; ?>"><a href="<?php echo HOST ?>admin/users">All Users</a></li>
                                <li class="<?php echo $all_adms; ?>"><a href="<?php echo HOST ?>admin/users/admin.php">All Admins</a></li>
                                <li class="<?php echo $new_usr; ?>"><a href="<?php echo HOST ?>admin/users/new.php">Add New</a></li>
                                <li class="<?php echo $abs_usr; ?>"><a href="<?php echo HOST ?>admin/users/abuse.php">Abuse Report</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo $my_profile; ?>">
                            <a href="<?php echo HOST ?>admin/profile.php"><i class="fa fa-user"></i> <span class="nav-label">My Profile</span></a>
                        </li>
                        <li class="<?php echo $sett; ?>">
                            <a href="<?php echo HOST ?>admin/settings.php"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span></a>
                        </li>
                    </ul>
                </div>
            </nav>
              <div id="page-wrapper" class="gray-bg">
                  <div class="row border-bottom">
                      <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                          <div class="navbar-header">
                              <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                          </div>
                          <ul class="nav navbar-top-links navbar-right">
                              <li><a href="<?php echo HOST ?>logout.php"><i class="fa fa-sign-out"></i> Log out</a></li>
                          </ul>
                      </nav>
                  </div>