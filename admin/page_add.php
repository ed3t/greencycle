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
        mysqli_query($mysqli,"INSERT INTO `".$config['db']['pre']."html` ( `html_id` , `html_type` , `html_title` , `html_content` ) VALUES ('" . addslashes($_POST['id']) . "', '" . addslashes($_POST['type']) . "', '" . addslashes($_POST['title']) . "', '" . addslashes($_POST['content']) . "');");

        header("Location: page_view.php");
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
                    <h4 class="page-title">Pages</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Add page</h3>
                        <form name="form2"  class="form form-horizontal" method="post" action="#" id="send2">
                            <div class="form-body">
                                <hr>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Page ID:</label>
                                    <div class="col-sm-6">
                                        <input name="id" type="text" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Page Title:</label>
                                    <div class="col-sm-6">
                                        <input name="title" type="text" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Page Type</label>
                                    <div class="col-sm-6">
                                        <select name="type" id="type" class="form-control">
                                            <option value="0" selected>Standard</option>
                                            <option value="1">Logged In Only</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Page Content:</label>
                                    <div class="col-sm-6">
                                        <textarea name="content" rows="6" type="text" class="form-control"></textarea>
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