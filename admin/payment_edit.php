<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
        mysqli_query($mysqli,"UPDATE `".$config['db']['pre']."payments` SET `payment_title` = '" . addslashes($_POST['title']) . "',`payment_install` = '" . addslashes($_POST['install']) . "' WHERE `payment_id` = '".$_GET['id']."' LIMIT 1 ;");

        if(isset($_POST['payment_merchant_email_id'])){
            update_option($config,"payment_merchant_email_id",$_POST['payment_merchant_email_id']);
        }

        if(isset($_POST['skrill_merchant_id'])){
            update_option($config,"skrill_merchant_id",$_POST['skrill_merchant_id']);
        }

        if(isset($_POST['nochex_merchant_id'])){
            update_option($config,"nochex_merchant_id",$_POST['nochex_merchant_id']);
        }

        if(isset($_POST['company_bank_info'])){
            update_option($config,"company_bank_info",$_POST['company_bank_info']);
        }

        if(isset($_POST['company_cheque_info'])){
            update_option($config,"company_cheque_info",$_POST['company_cheque_info']);
            update_option($config,"cheque_payable_to",$_POST['cheque_payable_to']);
        }

        if(isset($_POST['paystack_public_key'])){
            update_option($config,"paystack_public_key",$_POST['paystack_public_key']);
            update_option($config,"paystack_secret_key",$_POST['paystack_secret_key']);
        }

        if(isset($_POST['paystack_public_key'])){
            update_option($config,"paystack_public_key",$_POST['paystack_public_key']);
            update_option($config,"paystack_secret_key",$_POST['paystack_secret_key']);
        }

        if(isset($_POST['PAYTM_ENVIRONMENT'])){
            update_option($config,"PAYTM_ENVIRONMENT",$_POST['PAYTM_ENVIRONMENT']);
            update_option($config,"PAYTM_MERCHANT_KEY",$_POST['PAYTM_MERCHANT_KEY']);
            update_option($config,"PAYTM_MERCHANT_MID",$_POST['PAYTM_MERCHANT_MID']);
            update_option($config,"PAYTM_MERCHANT_WEBSITE",$_POST['PAYTM_MERCHANT_WEBSITE']);
        }

        transfer($config,'payment_settings.php','Payment Settings Edited');
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
                    <h4 class="page-title">Payment Edit</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <?php
            $q = "SELECT * FROM `".$config['db']['pre']."payments` WHERE `payment_id` = '".$_GET['id']."'";
            $page_query = mysqli_query($mysqli,$q);
            $fetch = mysqli_fetch_array($page_query);
            $status = $fetch['payment_install'];
            $folder = $fetch['payment_folder'];
            ?>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Edit <?php echo $fetch['payment_title']; ?></h3>
                        <form name="form2"  class="form form-horizontal" method="post" action="#" id="send2">
                            <div class="form-body">
                                <hr>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Title:</label>
                                    <div class="col-sm-6">
                                        <input name="title" type="text" class="form-control" value="<?php echo $fetch['payment_title']?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Turn On/Off</label>
                                    <div class="col-sm-6">
                                        <select name="install" id="install" class="form-control">
                                            <option value="1" <?php if($status == '1') echo "selected"; ?>>Enable</option>
                                            <option value="0" <?php if($status == '0') echo "selected"; ?>>Disable</option>
                                        </select>
                                    </div>
                                </div>

                                <?php
                                if($folder == "paypal"){
                                ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Paypal Merchant Email Id:</label>
                                        <div class="col-sm-6">
                                            <input name="payment_merchant_email_id" type="text" class="form-control" placeholder="Enter your Paypal merchant email id" value="<?php echo get_option($config,'payment_merchant_email_id')?>">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                                <?php
                                if($folder == "paytm"){
                                    ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Paytm ENVIRONMENT:</label>
                                        <div class="col-sm-6">
                                            <input name="PAYTM_ENVIRONMENT" type="text" class="form-control" placeholder="Environment for TEST or PRODUCTION mode" value="<?php echo get_option($config,'PAYTM_ENVIRONMENT')?>">
                                            <code class="help-block">Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.</code>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Paytm Merchant key:</label>
                                        <div class="col-sm-6">
                                            <input name="PAYTM_MERCHANT_KEY" type="text" class="form-control" placeholder="Enter your Merchant key" value="<?php echo get_option($config,'PAYTM_MERCHANT_KEY')?>">
                                            <code class="help-block">Change this constant's value with Merchant key downloaded from portal</code>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Paytm Merchant ID:</label>
                                        <div class="col-sm-6">
                                            <input name="PAYTM_MERCHANT_MID" type="text" class="form-control" placeholder="Enter your MID (Merchant ID)" value="<?php echo get_option($config,'PAYTM_MERCHANT_MID')?>">
                                            <code class="help-block">Change this constant's value with MID (Merchant ID) received from Paytm</code>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Paytm Website name:</label>
                                        <div class="col-sm-6">
                                            <input name="PAYTM_MERCHANT_WEBSITE" type="text" class="form-control" placeholder="Enter your Website name" value="<?php echo get_option($config,'PAYTM_MERCHANT_WEBSITE')?>">
                                            <code class="help-block">Change this constant's value with Website name received from Paytm</code>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                                <?php
                                if($folder == "paystack"){
                                    ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Paystack Secret Key:</label>
                                        <div class="col-sm-6">
                                            <input name="paystack_secret_key" type="password" class="form-control" placeholder="Enter your Paystack Secret Key" value="<?php echo get_option($config,'paystack_secret_key')?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Paystack Public Key:</label>
                                        <div class="col-sm-6">
                                            <input name="paystack_public_key" type="text" class="form-control" placeholder="Enter your Paystack Public Key" value="<?php echo get_option($config,'paystack_public_key')?>">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <?php
                                if($folder == "moneybookers"){
                                    ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Skrill Merchant Id:</label>
                                        <div class="col-sm-6">
                                            <input name="skrill_merchant_id" type="text" class="form-control" placeholder="Enter your skrill(moneybookers) merchant id" value="<?php echo get_option($config,'skrill_merchant_id')?>">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                                <?php
                                if($folder == "nochex"){
                                    ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">NoChex Merchant Id:</label>
                                        <div class="col-sm-6">
                                            <input name="nochex_merchant_id" type="text" class="form-control" placeholder="Enter your NoChex Merchant Id" value="<?php echo get_option($config,'nochex_merchant_id')?>">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                                <?php
                                if($folder == "wire_transfer"){
                                    ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bank Information :</label>
                                        <div class="col-sm-6">
                                            <textarea name="company_bank_info" rows="6" type="text" placeholder="Write Information about Bank transfer" class="form-control"><?php echo get_option($config,'company_bank_info')?></textarea>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <?php
                                if($folder == "cheque"){
                                    ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cheque Information:</label>
                                        <div class="col-sm-6">
                                            <textarea name="company_cheque_info" rows="6" type="text" placeholder="Write Cheque Information" class="form-control"><?php echo get_option($config,'company_cheque_info')?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cheque Payable To:</label>
                                        <div class="col-sm-6">
                                            <input name="cheque_payable_to" type="text" class="form-control" placeholder="Payable To" value="<?php echo get_option($config,'cheque_payable_to')?>">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>




                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-6">
                                        <input type="submit" name="Submit" class="btn btn-success" value="Submit"  />
                                        <a href="payment_settings.php" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->

<?php include("footer.php"); ?>