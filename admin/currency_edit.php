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
        mysqli_query($mysqli,"UPDATE `".$config['db']['pre']."html` SET
        `country` = '" . addslashes($_POST['country']) . "',
        `currency` = '" . addslashes($_POST['currency']) . "',
        `code` = '" . addslashes($_POST['code']) . "',
        `symbol` = '" . addslashes($_POST['symbol']) . "',
        `html_code` = '" . addslashes($_POST['html_code']) . "'
        WHERE `id` = '".$_GET['id']."' LIMIT 1 ;");

        transfer($config,'currency.php','Content Currency Edited');
        exit;
    }
}
include("header.php");
?>
    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Currency Edit</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <?php
            $query = "SELECT * FROM ".$config['db']['pre']."currencies WHERE `id` = '".$_GET['id']."' LIMIT 1";
            $query_result = mysqli_query(db_connect($config),$query);
            $info = mysqli_fetch_array($query_result);
            $id = $info['id'];
            $country = $info['country'];
            $currency = $info['currency'];
            $code = $info['code'];
            $symbol = $info['symbol'];
            $html_code   = $info['html_code'];
            ?>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Edit Currency <?php echo $fetch['html_title']; ?></h3>
                        <form name="form2"  class="form form-horizontal" method="post" action="#" id="send2">
                            <div class="form-body">
                                <hr>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Currency ID:</label>
                                    <div class="col-sm-6">
                                        <input name="id" type="text" class="form-control" value="<?php echo $id?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Country:</label>
                                    <div class="col-sm-6">
                                        <input name="country" type="text" class="form-control" value="<?php echo $country?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Currency:</label>
                                    <div class="col-sm-6">
                                        <input name="currency" type="text" class="form-control" value="<?php echo $currency?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Code:</label>
                                    <div class="col-sm-6">
                                        <input name="code" type="text" class="form-control" value="<?php echo $code?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Symbol:</label>
                                    <div class="col-sm-6">
                                        <input name="symbol" type="text" class="form-control" value="<?php echo $symbol?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Html Code:</label>
                                    <div class="col-sm-6">
                                        <input name="html_code" type="text" class="form-control" value="<?php echo $html_code?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-6">
                                        <input type="submit" name="Submit" class="btn btn-success" value="Submit"  />
                                        <a href="currency.php" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->

<?php include("footer.php"); ?>