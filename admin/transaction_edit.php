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
        mysqli_query($mysqli,"UPDATE `".$config['db']['pre']."transaction` SET
        `status` = '" . addslashes($_POST['status']) . "'
            WHERE `id` = '".$_GET['id']."' LIMIT 1 ;");

        transfer($config,'transactions.php?id='.$_GET['id'],'Transaction Edited');
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
        $q = "SELECT * FROM `".$config['db']['pre']."transaction` WHERE `id` = '".$_GET['id']."'";
        $page_query = mysqli_query($mysqli,$q);
        $info = mysqli_fetch_array($page_query);

        $item_id = $info['id'];
        $status = $info['status'];

        ?>
        <!-- /.row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Edit Transaction Status</h3>
                    <form name="form2"  class="form form-horizontal" method="post" action="#" id="send2">
                        <div class="form-body">
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Transaction ID:</label>
                                <div class="col-sm-6">
                                    <input type="text" disabled class="form-control" value="<?php echo $item_id ?>">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Transaction Status</label>
                                <div class="col-sm-6">
                                    <select name="status" class="form-control">
                                        <option value="success" <?php if($status == 'success') echo "selected"; ?>>Success</option>
                                        <option value="pending" <?php if($status == 'pending') echo "selected"; ?>>Pending</option>
                                        <option value="failed" <?php if($status == 'failed') echo "selected"; ?>>Failed</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label"></label>
                                <div class="col-sm-6">
                                    <input type="submit" name="Submit" class="btn btn-success" value="Submit"  />
                                    <a href="transactions.php" class="btn btn-default">Cancel</a>
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