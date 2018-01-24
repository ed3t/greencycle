<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

require_once('../includes/functions/func.users.php');
require_once('../includes/functions/func.sqlquery.php');

$total_active_item = mysqli_num_rows(mysqli_query($mysqli,"select 1 from `".$config['db']['pre']."product` where status = 'active'"));
$total_pending_item = mysqli_num_rows(mysqli_query($mysqli,"select 1 from `".$config['db']['pre']."product` where status = 'pending'"));
$banned_user = mysqli_num_rows(mysqli_query($mysqli,"select 1 from `".$config['db']['pre']."user` where status = '2'"));
$total_user = mysqli_num_rows(mysqli_query($mysqli,"select 1 from `".$config['db']['pre']."user`"));

include('header.php');
?>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Dashboard</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- .row -->
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <div class="white-box">
                                <h3 class="box-title">Active Ads</h3>
                                <ul class="list-inline two-part">
                                    <li><i class="icon-check text-info"></i></li>
                                    <li class="text-right"><span class="counter"><?php echo $total_active_item; ?></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <div class="white-box">
                                <h3 class="box-title">New Unapproved Ads</h3>
                                <ul class="list-inline two-part">
                                    <li><i class="fa fa-info-circle text-purple"></i></li>
                                    <li class="text-right"><span class="counter"><?php echo $total_pending_item; ?></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <div class="white-box">
                                <h3 class="box-title">BANNED USERS</h3>
                                <ul class="list-inline two-part">
                                    <li><i class="fa fa-user-times text-danger"></i></li>
                                    <li class="text-right"><span class=""><?php echo $banned_user; ?></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <div class="white-box">
                                <h3 class="box-title">TOTAL USERS</h3>
                                <ul class="list-inline two-part">
                                    <li><i class="fa fa-users text-success"></i></li>
                                    <li class="text-right"><span class=""><?php echo $total_user; ?></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- .row -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="white-box">
                        <h4 class="box-title">Recent 5 Ads</h4>
                        <?php
                        $getItem = get_items($config,'','',false,1,5);
                        foreach ($getItem as $ads) {
                            $ad_id          = $ads['id'];
                            $ad_title       = $ads['product_name'];
                            $featured       = $ads['featured'];
                            $urgent         = $ads['urgent'];
                            $highlight      = $ads['highlight'];
                            $ad_category    = $ads['category'];
                            $ad_price       = $ads['price'];
                            $ad_location    = $ads['location'];
                            $ad_created_at  = $ads['created_at'];
                            $ad_pic         = $ads['picture'];
                            $ad_link        = $ads['link'];
                            $ad_author      = $ads['username'];
                            $ad_author_link = $ads['author_link'];

                            ?>
                            <div class="pro-list">
                                <div class="pro-img p-r-10">
                                    <a href="javascript:void(0)">
                                        <img src="../storage/products/screenshot/small_<?php echo $ad_pic; ?>" alt="<?php echo $ad_title ?>" style="width: 100px; height: 66px;">
                                    </a>
                                </div>
                                <div class="pro-detail">
                                    <h5 class="m-t-0 m-b-5">
                                        <a href="post_detail.php?id=<?php echo $ad_id;?>"><?php echo $ad_title; ?></a>
                                    </h5>
                                    <p class="text-muted font-12"><?php echo $ad_created_at; ?> | <?php echo $ad_author; ?></p>
                                </div>
                            </div>

                        <?php } ?>

                        <div class="text-right">
                            <a href="posts.php" class="btn btn-sm btn-rounded btn-info m-t-10">View All</a>
                        </div>
                    </div>
                </div>



                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title">Recent Registered</h3>
                        <div class="row sales-report">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <h2>Today</h2>
                                <p>CREATE ACCOUNT REPORT</p>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 ">
                                <h1 class="text-right text-success m-t-20"><?php echo $day_user; ?></h1>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>DATE</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "SELECT * FROM `".$config['db']['pre']."user` order by id DESC LIMIT 5";
                                $result = $mysqli->query($query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td class="txt-oflo"><?php echo $row['name']; ?></td>
                                        <td><span class="label label-megna label-rounded"><?php echo $row['email']; ?></span> </td>
                                        <td class="txt-oflo"><?php echo timeAgo($row['created_at']); ?></td>
                                    </tr>
                                <?php } ?>


                                </tbody>
                            </table>
                            <a href="users.php">Check all Users</a> </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->


<?php include("footer.php"); ?>