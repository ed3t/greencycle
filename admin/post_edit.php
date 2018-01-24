<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();


if(isset($_POST['Submit']))
{
    if(!check_allow()){
        ?>
        <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#sa-title').trigger('click');
            });
        </script>
    <?php

    }
    else {
        mysqli_query($mysqli,"UPDATE `".$config['db']['pre']."product` SET
        `product_name` = '" . addslashes($_POST['title']) . "',
        `status` = '" . addslashes($_POST['status']) . "',
        `featured` = '" . addslashes($_POST['featured']) . "',
        `urgent` = '" . addslashes($_POST['urgent']) . "',
        `highlight` = '" . addslashes($_POST['highlight']) . "',
        `city` = '" . addslashes($_POST['city']) . "',
        `state` = '" . addslashes($_POST['state']) . "',
        `country` = '" . addslashes($_POST['country']) . "',
        `description` = '" . addslashes($_POST['content']) . "',
         contact_phone = '" . addslashes($_POST['contact_phone']) . "',
         contact_email = '" . addslashes($_POST['contact_email']) . "',
         contact_chat = '" . addslashes($_POST['contact_chat']) . "'
            WHERE `id` = '".$_GET['id']."' LIMIT 1 ;");

        transfer($config,'post_detail.php?id='.$_GET['id'],'Ad Edited');
        exit;
    }
}


include("header.php");
?>
<!-- page CSS -->
<link href="plugins/bower_components/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Pages</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <?php
            $q = "SELECT * FROM `".$config['db']['pre']."product` WHERE `id` = '".$_GET['id']."' LIMIT 1";
            $page_query = mysqli_query($mysqli,$q);
            $info = mysqli_fetch_array($page_query);

            $item_id = $info['id'];
            $status = $info['status'];
            $item_title = $info['product_name'];
            $item_description = $info['description'];
            $item_catid = $info['category'];
            $item_featured = $info['featured'];
            $item_urgent = $info['urgent'];
            $item_highlight = $info['highlight'];
            $item_city = $info['city'];
            $item_state = $info['state'];
            $item_country = $info['country'];
            $item_contact_phone = $info['contact_phone'];
            $item_contact_email = $info['contact_email'];
            $item_contact_chat = $info['contact_chat'];

            ?>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Edit Ad <?php echo $item_title; ?></h3>
                        <form name="form2"  class="form form-horizontal" method="post" action="#" id="send2">
                            <div class="form-body">
                                <hr>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ad ID:</label>
                                    <div class="col-sm-6">
                                        <input name="id" type="text" class="form-control" value="<?php echo $item_id ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ad Title:</label>
                                    <div class="col-sm-6">
                                        <input name="title" type="text" class="form-control" value="<?php echo $item_title ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ad Status</label>
                                    <div class="col-sm-6">
                                        <select name="status" class="form-control">
                                            <option value="active" <?php if($status == 'active') echo "selected"; ?>>Active</option>
                                            <option value="pending" <?php if($status == 'pending') echo "selected"; ?>>Pending</option>
                                            <option value="rejected" <?php if($status == 'rejected') echo "selected"; ?>>Rejected</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Premium:</label>
                                    <div class="col-sm-6 checkbox" style="margin-left: 10px">
                                        <input type="checkbox" name="featured" value="1" id="Featured"<?php if($item_featured == '1') echo "checked"; ?>>
                                        <label for="Featured">Featured</label><br>
                                        <input type="checkbox" name="urgent" value="1" id="Urgent"<?php if($item_urgent == '1') echo "checked"; ?>>
                                        <label for="Urgent">Urgent</label><br>
                                        <input type="checkbox" name="highlight" value="1" id="highlight"<?php if($item_highlight == '1') echo "checked"; ?>>
                                        <label for="highlight">Highlight</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Contact Option:</label>
                                    <div class="col-sm-6 checkbox" style="margin-left: 10px">
                                        <input type="checkbox" name="contact_phone" value="1" id="contact_phone"<?php if($item_contact_phone == '1') echo "checked"; ?>>
                                        <label for="contact_phone">By Phone</label><br>
                                        <input type="checkbox" name="contact_email" value="1" id="contact_email"<?php if($item_contact_email == '1') echo "checked"; ?>>
                                        <label for="contact_email">By Email</label><br>
                                        <input type="checkbox" name="contact_chat" value="1" id="contact_chat"<?php if($item_contact_chat == '1') echo "checked"; ?>>
                                        <label for="contact_chat">Instant Chat</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Page Content:</label>
                                    <div class="col-sm-6">
                                        <textarea name="content" rows="6" type="text" class="form-control"><?php echo $item_description ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Country</label>
                                    <div class="col-sm-6">
                                        <select name="country" class="form-control select2" id="country" data-ajax-action="getStateByCountryID">
                                            <?php $country = get_country_list($config,$item_country);
                                            foreach ($country as $value){
                                                echo '<option value="'.$value['id'].'" '.$value['selected'].'>'.$value['name'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">State</label>
                                    <div class="col-sm-6">
                                        <select name="state" id="state" class="form-control" data-ajax-action="getCityByStateID">
                                            <option value="">Select State...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">City</label>
                                    <div class="col-sm-6">
                                        <select name="city" id="city" class="form-control">
                                            <option value="">Select City...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-6">
                                        <input type="submit" name="Submit" class="btn btn-success" value="Submit"  />
                                        <a href="page_view.php" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->


            <?php include("footer.php"); ?>

            <script src="plugins/bower_components/custom-select/custom-select.min.js" type="text/javascript"></script>

            <script src="js/admin-ajax.js"></script>

            <script>
                // For select 2
                jQuery(function($) {
                    getStateSelected("<?php echo $item_country; ?>","getStateByCountryID","<?php echo $item_state; ?>");
                    getCitySelected("<?php echo $item_state; ?>","getCityByStateID","<?php echo $item_city; ?>");

                });
                $(".select2").select2();
            </script>