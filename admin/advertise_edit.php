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
        $query = "Update `".$config['db']['pre']."adsense` set
            provider_name='" . addslashes($_POST['provider_name']) . "',
            status='" . $_POST['status'] . "',
            large_track_code='" . $_POST['large_track_code'] . "',
            tablet_track_code='" . $_POST['tablet_track_code'] . "',
            phone_track_code='" . $_POST['phone_track_code'] . "'
            WHERE id = '".$_GET['id']."'";
        $query_result = $mysqli->query($query);

        $success = '<span style="color:green;">( Update Successfully )</span>';;
    }


}


include("header.php");
?>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Advertisement </h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <?php
            $query_quickads=mysqli_query($mysqli,"select * from ".$config['db']['pre']."adsense  WHERE id = '".$_GET['id']."'");
            $quick_fetch=mysqli_fetch_array($query_quickads);
            $status = $quick_fetch['status'];
            ?>

            <!-- /.row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Edit Advertise <?php echo $quick_fetch['slug']; ?> <?php echo $success; ?></h3>
                        <form name="form2"  class="form form-horizontal" method="post" action="#" id="send2">
                            <div class="form-body">
                                <hr>


                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Provider name:</label>
                                    <div class="col-sm-6">
                                        <input name="provider_name" type="text" class="form-control" value="<?php echo $quick_fetch['provider_name']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Status</label>
                                    <div class="col-sm-6">
                                        <select name="status">
                                            <option value="1" <?php if($status == '1') echo "selected"; ?>>Turn On</option>
                                            <option value="0" <?php if($status == '0') echo "selected"; ?>>Turn Off</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Tracking Code (Large Format):</label>
                                    <div class="col-sm-6">
                                        <textarea name="large_track_code" rows="6" type="text" class="form-control"><?php echo $quick_fetch['large_track_code']; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Tracking Code (Tablet  Format):</label>
                                    <div class="col-sm-6">
                                        <textarea name="tablet_track_code" rows="6" type="text" class="form-control"><?php echo $quick_fetch['tablet_track_code']; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Tracking Code (Phone Format):</label>
                                    <div class="col-sm-6">
                                        <textarea name="phone_track_code" rows="6" type="text" class="form-control"><?php echo $quick_fetch['phone_track_code']; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-6">
                                        <input type="submit" name="Submit" class="btn btn-success" value="Submit"  />
                                        <a href="advertising.php" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->

<?php include("footer.php"); ?>