<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

require_once('../includes/functions/func.users.php');
require_once('../includes/functions/func.sqlquery.php');

include("header.php");

?>

    <!-- Page Content -->
    <div id="page-wrapper">
    <div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Manage Ads</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li>
                <li class="active">Ads List</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div id="quickad-tbs" class="wrap">
                    <div id="quickad-alert" class="quickad-alert"></div>
                </div>
                <form method="post" name="f1" id="f1">
                    <div>
                        <div class="pull-left"><h3 class="box-title">All Ads List</h3></div>
                        <div class="pull-right">
                            <p class="text-muted">
                                <button data-ajax-response="deletemarked" data-ajax-action="deleteads" class="btn btn-danger waves-effect waves-light m-r-10"><i class="fa fa-trash-o"></i> Delete Marked</button>
                            </p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <hr>

                    <div class="table-responsive" id="js-table-list">
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="sortingNone">
                                        <div class="checkbox checkbox-success">
                                            <input type="checkbox" name="selall" value="checkbox" id="selall" onClick="checkBox(this)">
                                            <label for="selall"></label>
                                        </div>
                                    </th>
                                    <th>PIC</th>
                                    <th>TITLE</th>
                                    <th>AUTHOR</th>
                                    <th>CATEGORY</th>
                                    <th>Location</th>
                                    <th>DATE</th>
                                    <th>STATUS</th>

                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $getItem = get_items($config);
                            foreach ($getItem as $ads) {
                                $ad_id          = $ads['id'];
                                $ad_title       = $ads['product_name'];
                                $featured       = $ads['featured'];
                                $urgent         = $ads['urgent'];
                                $highlight      = $ads['highlight'];
                                $ad_category    = $ads['category'];
                                $ad_price       = $ads['price'];
                                $ad_location    = $ads['location'];
                                $ad_status    = $ads['status'];
                                $ad_created_at  = date('d M Y', strtotime($ads['created_at']));
                                $ad_pic         = $ads['picture'];
                                $ad_link        = $ads['link'];
                                $ad_author      = $ads['username'];
                                $ad_author_id   = $ads['author_id'];
                                $ad_author_link = $ads['author_link'];

                                $premium = '';
                                if ($featured == "1"){
                                    $premium = $premium.'<li class="promotion-featured" data-promotion="featured">featured</li>';
                                }

                                if($urgent == "1")
                                {
                                    $premium = $premium.'<li class="promotion-urgent" data-promotion="urgent">urgent</li>';
                                }

                                if($highlight == "1")
                                {
                                    $premium = $premium.'<li class="promotion-highlight" data-promotion="highlight">highlight</li>';
                                }

                                $status = '';
                                if ($ad_status == "active"){
                                    $status = '<span class="label label-success">Approved</span>';
                                }
                                elseif($ad_status == "pending")
                                {
                                    $status = '<span class="label label-warning">Pending</span>';
                                }
                                elseif($ad_status == "hide")
                                {
                                    $status = '<span class="label label-info">Hidden</span>';
                                }
                                ?>

                                <tr class="mail-contnet ajax-item-listing" data-item-id="<?php echo $ad_id ?>">
                                    <td>
                                        <input type="hidden" name="titles[]" id="titles[]" value="<?php echo $ad_title;?>">

                                        <div class="checkbox checkbox-success">
                                            <input type="checkbox" name="list[]" id="checkbox<?php echo $ad_id;?>" class="service-checker" value="<?php echo $ad_id;?>" style="display: block">
                                            <label for="checkbox<?php echo $ad_id;?>"></label>
                                        </div>
                                    </td>
                                    <td class="txt-oflo">
                                        <?php
                                        if ($premium != ""){ ?>
                                            <ul class="promotion-flags promotions promotion-flags--small inverted">
                                                <?php echo $premium; ?>
                                            </ul>
                                        <?php } ?>

                                        <div class="user-img"> <img src="../storage/products/screenshot/small_<?php echo $ad_pic; ?>" alt="<?php echo $ad_author ?>" style="width:auto; max-height:100px;max-width: 100px;"></div>
                                    </td>
                                    <td>
                                        <a href="post_detail.php?id=<?php echo $ad_id;?>" target="_blank"><?php echo $ad_title; ?> </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $config['site_url'];?>/admin/user_profile.php?id=<?php echo $ad_author_id;?>" target="_blank" data-toggle="tooltip" data-original-title="View <?php echo $ad_author ?> Profile">
                                            <?php
                                            if(strlen($ad_author) >= 15)
                                                echo substr($ad_author,0,14)."...";
                                            else
                                                echo $ad_author;
                                            ?>
                                        </a>
                                    </td>
                                    <td><?php echo $ad_category; ?></td>
                                    <td>
                                        <span  data-toggle="tooltip" data-original-title="<?php echo $ad_location ?>">
                                        <?php
                                        if(strlen($ad_location) >= 15)
                                            echo substr($ad_location,0,14)."...";
                                        else
                                            echo $ad_location;
                                        ?>
                                        </span>
                                    </td>
                                    <td class="txt-oflo"><?php echo $ad_created_at; ?></td>
                                    <td class="text-nowrap text-center">
                                        <?php echo $status; ?>
                                        <a href="post_detail.php?id=<?php echo $ad_id;?>"  data-toggle="tooltip" data-original-title="View"  class="action" target="_blank"><i class="ti-eye"></i></a>
                                        <a href="post_edit.php?id=<?php echo $ad_id;?>"  data-toggle="tooltip" data-original-title="Edit"  class="action" target="_blank"><i class="ti-pencil-alt text-warning"></i></a>
                                        <?php if($ad_status == "pending"){
                                            ?>
                                            <a href="javacript:void(0)"  data-toggle="tooltip" data-original-title="Approve <?php echo $ad_title ?>"  class="action item-approve" data-ajax-action="approveitem"><i class="ti-check text-success"></i></a>
                                        <?php } ?>

                                        <a href="javacript:void(0)" data-toggle="tooltip" data-original-title="Delete" class="action item-js-delete" data-ajax-action="deleteads"><i class="ti-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- /.row -->


<?php include("footer.php"); ?>
        <script src="js/admin-ajax.js"></script>
        <script src="js/alert.js"></script>
