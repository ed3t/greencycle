<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

$success = "";
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
    else{
        mysqli_query($mysqli,"UPDATE `".$config['db']['pre']."faq_entries` SET `faq_title` = '" . addslashes($_POST['title']) . "',`faq_content` = '" . addslashes($_POST['content']) . "' WHERE `faq_id` = '".$_GET['id']."' LIMIT 1 ;");

        transfer($config,'faq_entries.php','Content Pages Edited');
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
                    <h4 class="page-title">FAQ </h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <?php
            $query_quickads=mysqli_query($mysqli,"select * from ".$config['db']['pre']."faq_entries  WHERE faq_id = '".$_GET['id']."'");
            $quick_fetch=mysqli_fetch_array($query_quickads);
            ?>

            <!-- /.row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Edit FAQ Entry</h3>
                        <form name="form2"  class="form form-horizontal" method="post" action="#" id="send2">
                            <div class="form-body">
                                <hr>


                                <div class="form-group">
                                    <label class="col-sm-4 control-label">FAQ Title:</label>
                                    <div class="col-sm-6">
                                        <input name="title" type="text" class="form-control" value="<?php echo $quick_fetch['faq_title']?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">FAQ Content:</label>
                                    <div class="col-sm-6">
                                        <textarea name="content" rows="6" type="text" class="form-control"><?php echo $quick_fetch['faq_content']?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-6">
                                        <input type="submit" name="Submit" class="btn btn-success" value="Submit"  />
                                        <a href="faq_entries.php" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->

<?php include("footer.php"); ?>