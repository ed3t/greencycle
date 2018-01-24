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
        function getExtension($str)
        {
            $i = strrpos($str, ".");
            if (!$i) {
                return "";
            }
            $l = strlen($str) - $i;
            $ext = substr($str, $i + 1, $l);
            return $ext;
        }


        update_option($config,"home_heading",$_POST['home_heading']);
        update_option($config,"home_sub_heading",$_POST['home_sub_heading']);
        update_option($config,"home_map_latitude",$_POST['home_map_latitude']);
        update_option($config,"home_map_longitude",$_POST['home_map_longitude']);
        update_option($config,"home_map_zoom",$_POST['home_map_zoom']);
        update_option($config,"theme_color",$_POST['theme_color']);
        update_option($config,"map_color",$_POST['map_color']);
        update_option($config,"country_type",$_POST['country_type']);

        update_option($config,"meta_keywords",$_POST['meta_keywords']);
        update_option($config,"meta_description",$_POST['meta_description']);

        update_option($config,"contact_address",$_POST['contact_address']);
        update_option($config,"contact_phone",$_POST['contact_phone']);
        update_option($config,"contact_email",$_POST['contact_email']);
        update_option($config,"contact_latitude",$_POST['contact_latitude']);
        update_option($config,"contact_longitude",$_POST['contact_longitude']);
        update_option($config,"footer_text",$_POST['footer_text']);
        update_option($config,"copyright_text",$_POST['copyright_text']);
        update_option($config,"admin_menu_style",$_POST['admin_menu_style']);

        $message = '<span style="color:green;">( Theme Setting Saved )</span>';

        if ($_FILES['banner']['tmp_name'] != "") {
            $uploaddir = "../storage/banner/"; //Image upload directory
            $bannername = stripslashes($_FILES['banner']['name']);
            $size = filesize($_FILES['banner']['tmp_name']);
            //Convert extension into a lower case format

            $ext = getExtension($bannername);
            $ext = strtolower($ext);
            $banner_name = "bg" . '.' . $ext;
            $newBgname = $uploaddir . $banner_name;
            //Moving file to uploads folder
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $newBgname)) {

                update_option($config,"home_banner",$banner_name);
                $message = '<span style="color:green;">( Banner Update Successfully )</span>';

            } else {
                $message = '<span style="color:#FF0000;">( Error in uploading Banner )</span>';
            }
        }

        if ($_FILES['file']['tmp_name'] != "") {
            $uploaddir = "../storage/logo/"; //Image upload directory
            $filename = stripslashes($_FILES['file']['name']);
            $size = filesize($_FILES['file']['tmp_name']);
            //Convert extension into a lower case format

            $ext = getExtension($filename);
            $ext = strtolower($ext);
            $image_name = $config['tpl_name']."_logo" . '.' . $ext;
            $newLogo = $uploaddir . $image_name;

            //Moving file to uploads folder
            if (move_uploaded_file($_FILES['file']['tmp_name'], $newLogo)) {

                update_option($config,"site_logo",$image_name);

                $message = '<span style="color:green;">( Update Successfully )</span>';
            } else {
                $message = '<span style="color:#FF0000;">( Error in uploading logo )</span>';
            }
        }
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
                        <form class="form form-horizontal" action="template_settings.php" method="post" enctype="multipart/form-data">
                            <div>
                                <div class="text-left"><h3 class="box-title">Theme Setting <?php echo $message; ?></h3></div>
                            </div>

                            <!-- xfile upload-->
                            <div class="form-group" style="padding-top: 20px">
                                <label class="col-sm-4 control-label">Logo:</label>
                                <div class="col-sm-6">
                                    <div class="screenshot"><img class="redux-option-image" id="image_logo_uploader" src="../storage/logo/<?php echo get_option( $config, "site_logo"); ?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000"></div>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Select file</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="file">
                                            </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>

                                    <div><span class="help-block" style="color:#2196f3;">Ideal Size 168x57 PX</span></div>
                                </div>

                            </div>
                            <!-- xfile upload-->


                            <!-- xfile upload-->
                            <div class="form-group" style="padding-top: 20px">
                                <label class="col-sm-4 control-label">Home Banner:</label>
                                <div class="col-sm-6">
                                    <div class="screenshot"><img class="redux-option-image" id="image_logo_uploader" src="../storage/banner/<?php echo get_option( $config, "home_banner"); ?>" alt="" target="_blank" rel="external" width="400px"></div>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Select file</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="banner">
                                            </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>

                            </div>
                            <!-- xfile upload-->

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Home Page Heading:</label>
                                <div class="col-sm-6">
                                    <input name="home_heading" type="text" class="form-control" value="<?php echo get_option( $config, "home_heading"); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Home Page Sub-Heading:</label>
                                <div class="col-sm-6">
                                    <input name="home_sub_heading" type="text" class="form-control" value="<?php echo get_option( $config, "home_sub_heading"); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Home Map Latitude:</label>
                                <div class="col-sm-6">
                                    <input name="home_map_latitude" type="text" class="form-control" value="<?php echo get_option( $config, "home_map_latitude"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Home Map Longitude:</label>
                                <div class="col-sm-6">
                                    <input name="home_map_longitude" type="text" class="form-control" value="<?php echo get_option( $config, "home_map_longitude"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Home Map Zoom:</label>
                                <div class="col-sm-6">
                                    <input name="home_map_zoom" type="text" class="form-control" value="<?php echo get_option( $config, "home_map_zoom"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Theme Color:</label>
                                <div class="col-sm-6">
                                    <input name="theme_color" type="color" class="form-control" value="<?php echo get_option( $config, "theme_color"); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Map Color:</label>
                                <div class="col-sm-6">
                                    <input name="map_color" type="color" class="form-control" value="<?php echo get_option( $config, "map_color"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Meta Keywords:</label>
                                <div class="col-sm-6">
                                    <input name="meta_keywords" type="text" class="form-control" value="<?php echo get_option( $config, "meta_keywords"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Meta Description:</label>
                                <div class="col-sm-6">
                                    <input name="meta_description" type="text" class="form-control" value="<?php echo get_option( $config, "meta_description"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Site Country Type:</label>
                                <div class="col-sm-6">
                                    <select name="country_type" class="form-control">
                                        <option <?php if(get_option( $config, "country_type") == 'single'){ echo "selected"; } ?> value="single">Single Country</option>
                                        <option <?php if(get_option( $config, "country_type") == 'multi'){ echo "selected"; } ?> value="multi">Multi Countries</option>
                                    </select>
                                </div>
                            </div>

                            <!--Default Horizontal Form-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contact Address:</label>
                                <div class="col-sm-6">
                                    <input name="contact_address" type="text" class="form-control" value="<?php echo get_option( $config, "contact_address"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contact Map Latitude:</label>
                                <div class="col-sm-6">
                                    <input name="contact_latitude" type="text" class="form-control" value="<?php echo get_option( $config, "contact_latitude"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contact Map Longitude:</label>
                                <div class="col-sm-6">
                                    <input name="contact_longitude" type="text" class="form-control" value="<?php echo get_option( $config, "contact_longitude"); ?>">
                                </div>
                            </div>
                            <!--Default Horizontal Form-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contact Email:</label>
                                <div class="col-sm-6">
                                    <input name="contact_email" type="text" class="form-control" value="<?php echo get_option( $config, "contact_email"); ?>">
                                </div>
                            </div>
                            <!--Default Horizontal Form-->
                            <!--Default Horizontal Form-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contact Phone:</label>
                                <div class="col-sm-6">
                                    <input name="contact_phone" type="text" class="form-control" value="<?php echo get_option( $config, "contact_phone"); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Copyright Text:</label>
                                <div class="col-sm-6">
                                    <input name="copyright_text" type="text" class="form-control" value="<?php echo get_option( $config, "copyright_text"); ?>">
                                </div>
                            </div>
                            <!--Default Horizontal Form-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Footer Text:</label>
                                <div class="col-sm-6">
                                    <textarea name="footer_text" class="form-control"><?php echo get_option( $config, "footer_text"); ?></textarea>
                                </div>
                            </div>
                            <!--Default Horizontal Form-->

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Admin Menu Style:</label>
                                <div class="col-sm-6">
                                    <select name="admin_menu_style" class="form-control">
                                        <option <?php if(get_option( $config, "admin_menu_style") == 'vertical'){ echo "selected"; } ?> value="vertical">Vertical Menu</option>
                                        <option <?php if(get_option( $config, "admin_menu_style") == 'horizontal'){ echo "selected"; } ?> value="horizontal">Horizontal Menu</option>
                                    </select>
                                </div>
                            </div>


                            <!--Default Horizontal Form-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label"></label>
                                <div class="col-sm-6">
                                    <input name="update" type="submit" class="btn btn-primary btn-radius" value="Update">
                                </div>
                            </div>
                            <!--Default Horizontal Form-->

                        </form>
                    </div>
                </div>

            </div>
            <!-- /.row -->




<?php include("footer.php"); ?>