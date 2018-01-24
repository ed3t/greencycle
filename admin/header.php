<?php
$query1 = "SELECT * FROM `".$config['db']['pre']."admins` where id = '".$_SESSION['admin']['id']."'";
$result1 = $mysqli->query($query1);
$row1 = mysqli_fetch_assoc($result1);
$string = $row1['username'];
$sesuserpic = $row1['image'];

if($sesuserpic == "")
    $sesuserpic = "default_user.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" content="text/html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title><?php echo $config['site_title'] ?> - Admin Panel</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Menu CSS -->
    <link href="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!--alerts CSS -->
    <link href="plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <!-- morris CSS -->
    <link href="plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!--multi-select-->
    <link href="plugins/bower_components/multiselect/multi-sel.css" rel="stylesheet" type="text/css" />
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <?php

    if(get_option( $config, "admin_menu_style") == 'vertical'){
        echo '<link href="css/style-menu-vertical.css" rel="stylesheet">';
    }else{
        echo '<link href="css/style-menu-horizontal.css" rel="stylesheet">';
    }
    ?>
    <!-- color CSS -->
    <link href="css/screen.css" rel="stylesheet">

    <link href="assets/css/colors/<?php echo $config['admin_tpl_color'] ?>.css" id="theme"  rel="stylesheet">

    <!-- Data Table CSS -->
    <link href="plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/bower_components/datatables/responsive.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/bower_components/datatables/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap On/Off switch CSS -->
    <link href="plugins/bower_components/bootstrap-switch/bootstrap-switch.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        var ajaxurl = '<?php echo $config['site_url'].'admin-ajax.php'; ?>';
    </script>
</head>
<body>
<!-- Preloader -->
<!--<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>-->
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top m-b-0">
        <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>

            <div class="top-left-part">
                <a class="logo" href="index.php">
                    <b>
                        <img src="plugins/images/admin-logo.png" alt="home" class="dark-logo">
                    </b>
                    <span class="hidden-xs" style="display: inline;">
                        <img src="plugins/images/admin-text.png" alt="home" class="dark-logo">
                    </span>
                </a>
            </div>


            <ul class="nav navbar-top-links navbar-left hidden-xs">
                <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>

            </ul>
            <ul class="nav navbar-top-links navbar-right pull-right">
                <!-- /.dropdown -->
                <li class="dropdown"> <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="../storage/profile/<?php echo $sesuserpic;?>" alt="<?php echo $row1['name'];?>" width="36" class="img-circle"><b class="hidden-xs"><?php echo $row1['username'];?></b><i class="icon-options-vertical"></i> </a>
                    <ul class="dropdown-menu dropdown-user animated flipInY">
                        <li><a href="../index.php" target="_blank"><i class="ti-comments-smiley"></i> Frontend</a></li>
                        <li><a href="configuration.php"><i class="ti-settings"></i> Quickad Setting</a></li>
                        <li><a href="../../documentation.html" target="_blank" class="waves-effect"><i class="fa fa-circle-o text-danger"></i> <span class="hide-menu">Documentation</span></a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>

                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.Megamenu -->
                <li class="right-side-toggle"> <a class="waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                <!-- /.dropdown -->
            </ul>
            <ul class="nav navbar-top-links navbar-right pull-right">

                <li class="dropdown">
                    <?php
                    $a = "SELECT id FROM ".$config['db']['pre']."product_resubmit";
                    $ar = mysqli_query(db_connect($config),$a);
                    $arn = mysqli_num_rows($ar);
                    ?>
                    <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#">
                        <i class="fa fa-briefcase"></i>
                        <?php
                        if($arn > 0){
                            echo '<div class="notify"><span class="heartbit"></span><span class="point"></span></div>';
                        } ?>

                    </a>
                    <ul class="dropdown-menu mailbox animated bounceInDown">
                        <li>

                            <div class="drop-title">You have <?php echo $arn ?> Re-submitted ads</div>
                        </li>
                        <li>
                            <div class="message-center">

                                <?php

                                $reads_sql = "SELECT * FROM `".$config['db']['pre']."product_resubmit` ORDER BY id desc LIMIT 5";
                                $reads_result = db_connect($config)->query($reads_sql);
                                if (mysqli_num_rows($reads_result) > 0) {
                                    while ($ads = mysqli_fetch_assoc($reads_result)) {
                                        $ad_id = $ads['product_id'];
                                        $ad_title = $ads['product_name'];
                                        $ad_created_at = date('d M Y', strtotime($ads['created_at']));

                                        $s = "SELECT * FROM ".$config['db']['pre']."catagory_main  WHERE `cat_id` = '".$ads['category']."' LIMIT 1";
                                        $sr = mysqli_query(db_connect($config),$s);
                                        $val = mysqli_fetch_assoc($sr);
                                        $ad_category = $val['cat_name'];

                                        $picture     =   explode(',' ,$ads['screen_shot']);
                                        $picture     =   $picture[0];
                                        $ad_pic = $picture;
                                        ?>

                                        <a href="post_detail.php?id=<?php echo $ad_id; ?>&resubmit=1">
                                            <div class="user-img">
                                                <img src="../storage/products/screenshot/<?php echo $ad_pic; ?>"
                                                     class="img-circle">
                                                <span class="profile-status online pull-right"></span>
                                            </div>
                                            <div class="mail-contnet">
                                                <h5><?php echo $ad_title; ?></h5>
                                                <span class="mail-desc"><?php echo $ad_category; ?></span>
                                                <span class="time"><?php echo $ad_created_at; ?></span>
                                            </div>
                                        </a>
                                    <?php }
                                }?>
                            </div>
                        </li>
                        <li>
                            <a class="text-center" href="post_resubmit.php"> <strong>See all re-submit ads</strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>

                <li class="dropdown">
                    <?php
                    $b = "SELECT id FROM ".$config['db']['pre']."product WHERE status = 'pending'";
                    $br = mysqli_query(db_connect($config),$b);
                    $brn = mysqli_num_rows($br);
                    ?>
                    <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#">
                        <i class="icon-envelope"></i>
                        <?php
                        if($brn > 0){
                            echo '<div class="notify"><span class="heartbit"></span><span class="point"></span></div>';
                        } ?>

                    </a>
                    <ul class="dropdown-menu mailbox animated bounceInDown">
                        <li>

                            <div class="drop-title">You have <?php echo $brn ?> new ads</div>
                        </li>
                        <li>
                            <div class="message-center">

                                <?php
                                $newAds_sql = "SELECT * FROM `".$config['db']['pre']."product` WHERE status = 'pending' ORDER BY id desc LIMIT 5";
                                $newAds_result = db_connect($config)->query($newAds_sql);
                                if (mysqli_num_rows($newAds_result) > 0) {
                                    while ($ads = mysqli_fetch_assoc($newAds_result)) {
                                        $ad_id = $ads['id'];
                                        $ad_title = $ads['product_name'];
                                        $ad_created_at = date('d M Y', strtotime($ads['created_at']));

                                        $s = "SELECT * FROM ".$config['db']['pre']."catagory_main  WHERE `cat_id` = '".$ads['category']."' LIMIT 1";
                                        $sr = mysqli_query(db_connect($config),$s);
                                        $val = mysqli_fetch_assoc($sr);
                                        $ad_category = $val['cat_name'];

                                        $picture     =   explode(',' ,$ads['screen_shot']);
                                        $picture     =   $picture[0];
                                        $ad_pic = $picture;
                                        ?>

                                        <a href="post_detail.php?id=<?php echo $ad_id; ?>">
                                            <div class="user-img">
                                                <img src="../storage/products/screenshot/<?php echo $ad_pic; ?>"
                                                     class="img-circle">
                                                <span class="profile-status online pull-right"></span>
                                            </div>
                                            <div class="mail-contnet">
                                                <h5><?php echo $ad_title; ?></h5>
                                                <span class="mail-desc"><?php echo $ad_category; ?></span>
                                                <span class="time"><?php echo $ad_created_at; ?></span>
                                            </div>
                                        </a>
                                    <?php }
                                }?>
                            </div>
                        </li>
                        <li>
                            <a class="text-center" href="post_pending.php"> <strong>See all new ads</strong> <i class="fa fa-angle-right"></i> </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->

                <!-- /.dropdown -->
                <li class="dropdown"> <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" aria-expanded="true"><i class="ti-money"></i></a>

                    <?php
                    $query_quickpay=mysqli_query($mysqli,"select * from ".$config['db']['pre']."balance");
                    $quick_fetch=mysqli_fetch_array($query_quickpay);
                    $curbalance = $quick_fetch['current_balance'];
                    $totalearning = $quick_fetch['total_earning'];
                    ?>
                    <ul class="dropdown-menu dropdown-tasks animated slideInUp">
                        <li> <a href="#">
                                <div>
                                    <p> <strong>Current Balance</strong> <span class="pull-right text-muted"><?php echo $config['currency_sign'] ?> <?php echo $curbalance ?></span> </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:50%"> <span class="sr-only"><?php echo $config['currency_sign'] ?> <?php echo $curbalance ?></span> </div>
                                    </div>
                                </div>
                            </a> </li>
                        <li class="divider"></li>
                        <li> <a href="#">
                                <div>
                                    <p> <strong>Total Earning</strong> <span class="pull-right text-muted"><?php echo $config['currency_sign'] ?> <?php echo $totalearning; ?></span> </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 50%"> <span class="sr-only"><?php echo $config['currency_sign'] ?> <?php echo $totalearning; ?></span> </div>
                                    </div>
                                </div>
                            </a> </li>

                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </div>
        <!-- /.navbar-header -->
        <!-- /.navbar-top-links -->
        <!-- /.navbar-static-side -->
    </nav>
    <!-- Left navbar-header -->
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse collapse">
            <ul class="nav" id="side-menu">
                <li><a href="index.php" class="waves-effect"><i class="fa fa-home fa-fw"></i> <span class="hide-menu">Dashboard</span></a></li>
                <li> <a href="#" class="waves-effect"><i data-icon="/" class="fa fa-plus-circle fa-fw"></i> <span class="hide-menu">Custom Fields<span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="custom_add.php">Add Fields</a></li>
                        <li><a href="custom_view.php">View Fields</a></li>
                    </ul>
                </li>
                <li> <a href="#" class="waves-effect"><i data-icon="/" class="ti-image  fa-fw"></i> <span class="hide-menu">Ads Manage<span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="post_active.php">Active Ads</a></li>
                        <li><a href="post_pending.php">Pending Ads</a></li>
                        <li><a href="post_hidden.php">Hidden by user</a></li>
                        <li><a href="post_resubmit.php">Resubmited Ads</a></li>
                        <li><a href="posts.php">All Ads List</a></li>
                    </ul>
                </li>
                <li><a href="category.php" class="waves-effect"><i class="ti-menu-alt fa-fw"></i> <span class="hide-menu">Category</span></a></li>
                <li> <a href="#" class="waves-effect"><i data-icon="/" class="icon-user  fa-fw"></i> <span class="hide-menu">Users<span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="users_add.php">Add Users</a></li>
                        <li><a href="users.php">All Users</a></li>
                        <li><a href="users-export.php">Export Users Data</a></li>
                    </ul>
                </li>
                <li> <a href="#" class="waves-effect"><i data-icon="/" class="icon-people fa-fw"></i> <span class="hide-menu"> Admin<span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="admin_add.php">Add Admin</a></li>
                        <li><a href="admin_view.php">All Admin</a></li>
                    </ul>
                </li>
                <li><a href="countries.php" class="waves-effect"><i class="fa fa-globe fa-fw"></i> <span class="hide-menu">Location</span></a></li>
                <li class="hidden"><a href="#" class="waves-effect"><i class="fa fa-money fa-fw"></i> <span class="hide-menu">Currency</span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="currency_add.php">Add Currency</a></li>
                        <li><a href="currency.php">Currency List</a></li>

                    </ul>
                </li>
                <li><a href="#" class="waves-effect"><i class="fa fa-comment-o fa-fw"></i> <span class="hide-menu">Chat</span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="chating.php">Chat Messages</a></li>
                        <li><a href="chat_setting.php">Chat Setting</a></li>
                    </ul>
                </li>
                <li> <a href="#" class="waves-effect"><i data-icon="/" class="ti-file fa-fw"></i> <span class="hide-menu"> Content<span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="javascript:void(0)">FAQ</a>
                            <ul class="nav nav-third-level collapse">
                                <li> <a href="faq_entry_add.php">Add FAQ Entry</a></li>
                                <li> <a href="faq_entries.php">View Entries List</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0)">Pages</a>
                            <ul class="nav nav-third-level collapse">
                                <li> <a href="page_add.php">Add Page</a></li>
                                <li> <a href="page_view.php">View All Pages</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="hidden"><a href="currency.php" class="waves-effect"><i class="fa fa-money fa-fw"></i> <span class="hide-menu">Currency</span></a></li>
                <li> <a href="#" class="waves-effect"><i data-icon="/" class="fa fa-usd fa-fw"></i> <span class="hide-menu"> Payment<span class="fa arrow"></span> </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="payment_settings.php">Payment Setting</a></li>
                        <li><a href="transactions.php">Transactions</a></li>
                    </ul>
                </li>
                <li><a href="advertising.php" class="waves-effect"><i class="fa fa-life-ring fa-fw"></i> <span class="hide-menu">Adsense</span></a></li>
                <li><a href="xml_manage.php" class="waves-effect"><i class="fa fa-file-excel-o fa-fw"></i> <span class="hide-menu">XML</span></a></li>
                <li><a href="themes.php" class="waves-effect"><i class="fa fa-hdd-o fa-fw"></i> <span class="hide-menu">Themes</span></a></li>
                <li><a href="#" class="waves-effect"><i class="fa fa-cogs fa-fw"></i> <span class="hide-menu">Setting</span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="configuration.php">Configuration</a></li>
                        <li><a href="setting.php">Site Setting</a></li>
                        <li><a href="template_settings.php">Theme Setting</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- Left navbar-header end -->