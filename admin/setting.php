<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

if(isset($_POST['update']))
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
        $facebook_app_id = $_POST['facebook_app_id'];
        $facebook_app_secret = $_POST['facebook_app_secret'];
        $google_app_id = $_POST['google_app_id'];
        $google_app_secret = $_POST['google_app_secret'];

        $facebook = $_POST['facebook'];
        $twitter = $_POST['twitter'];
        $googleplus = $_POST['googleplus'];
        $youtube = $_POST['youtube'];

        mysqli_query($mysqli, "update `".$config['db']['pre']."setting` set facebook_app_id='$facebook_app_id',facebook_app_secret='$facebook_app_secret',google_app_id='$google_app_id',google_app_secret='$google_app_secret',facebook='$facebook', twitter='$twitter', googleplus='$googleplus', youtube='$youtube'");

        $error = '<span style="color:green;">( Update Successfully )</span>';
    }
}

include("header.php");
?>


    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Setting</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li class="active">Site Setting</li>
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
                        <form class="form form-horizontal" action="#" method="post" enctype="multipart/form-data">
                            <div>
                                <div class="pull-left"><h3 class="box-title">Site Setting <?php echo $error; ?></h3></div>
                                <div class="pull-right">
                                    <p class="text-muted">
                                        <button type="submit" name="update" class="btn btn-primary waves-effect waves-light m-r-10"><i class="fa fa-trash-o"></i> Save Setting</button>
                                    </p>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <!-- panel -->
                            <div role="tabpanel">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-justified Admin-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#sociallogin" aria-controls="sociallogin" role="tab" data-toggle="tab">Social Login Configuration</a></li>
                                    <li role="presentation"><a href="#socialtab" aria-controls="social" role="tab" data-toggle="tab">Footer Social Link</a></li>
                                </ul>
                                <!-- /Nav tabs -->

                                <!-- Tab panes -->
                                <div class="tab-content Admin-tab-content">
                                    <?php
                                    $query_quickpay=mysqli_query($mysqli,"select * from ".$config['db']['pre']."setting");
                                    $quick_fetch=mysqli_fetch_array($query_quickpay);
                                    ?>
                                    <!-- Social Login tab -->
                                    <div role="tabpanel" class="tab-pane active" id="sociallogin">

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Facebook app id:</label>
                                            <div class="col-sm-6">
                                                <input name="facebook_app_id" type="text" class="form-control" value="<?php echo $quick_fetch['facebook_app_id']; ?>">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Facebook app secret:</label>
                                            <div class="col-sm-6">
                                                <input name="facebook_app_secret" type="text" class="form-control" value="<?php echo $quick_fetch['facebook_app_secret']; ?>">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Facebook callback url:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" disabled value="<?php echo $config['site_url']; ?>includes/social_login/facebook/index.php">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Google+ app id:</label>
                                            <div class="col-sm-6">
                                                <input name="google_app_id" type="text" class="form-control" value="<?php echo $quick_fetch['google_app_id']; ?>">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Google+ app secret:</label>
                                            <div class="col-sm-6">
                                                <input name="google_app_secret" type="text" class="form-control" value="<?php echo $quick_fetch['google_app_secret']; ?>">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->
                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Google+ callback url:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" disabled value="<?php echo $config['site_url']; ?>includes/social_login/google/index.php">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->
                                    </div>
                                    <!-- /Social Login tab -->

                                    <!-- Social tab -->
                                    <div role="tabpanel" class="tab-pane" id="socialtab">

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Facebook:</label>
                                            <div class="col-sm-6">
                                                <input name="facebook" type="text" class="form-control" value="<?php echo $quick_fetch['facebook']; ?>">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Twitter:</label>
                                            <div class="col-sm-6">
                                                <input name="twitter" type="text" class="form-control" value="<?php echo $quick_fetch['twitter']; ?>">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Google+:</label>
                                            <div class="col-sm-6">
                                                <input name="googleplus" type="text" class="form-control" value="<?php echo $quick_fetch['googleplus']; ?>">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->

                                        <!--Default Horizontal Form-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Youtube:</label>
                                            <div class="col-sm-6">
                                                <input name="youtube" type="text" class="form-control" value="<?php echo $quick_fetch['youtube']; ?>">
                                            </div>
                                        </div>
                                        <!--Default Horizontal Form-->
                                    </div>
                                    <!-- /Social tab -->
                                </div>
                                <!-- /Tab panes -->
                            </div>
                            <!-- /panel -->

                        </form>
                    </div>
                </div>

            </div>
            <!-- /.row -->




<?php include("footer.php"); ?>