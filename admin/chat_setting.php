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
        $message = "";
        if(isset($_POST['zechat_on_off'])){
            $zechat_purchase = get_option( $config, "zechat_purchase_code");
            if($zechat_purchase == NULL) {
                $message .= '<span style="color:red;">( Enter Your Valid Zechat Purchase Code.)</span>';
            }
            else{
                $purchase_data = verify_envato_purchase_code($zechat_purchase);
                if( isset($purchase_data['verify-purchase']['buyer']))
                {
                    if($purchase_data['verify-purchase']['item_id'] == '16491266'){
                        update_option($config,"zechat_on_off",$_POST['zechat_on_off']);
                    }
                }else{
                    $message .= '<span style="color:red;">( Enter Your Valid Zechat Purchase Code.)</span>';
                }
            }
        }
        else{
            update_option($config,"zechat_on_off","off");
        }

        if(isset($_POST['wchat_on_off'])){
            $wchat_purchase = get_option( $config, "wchat_purchase_code");
            if($wchat_purchase == NULL) {
                $message .= '<span style="color:red;">( Enter Your Valid Wchat Purchase Code.)</span>';
            }
            else{
                $purchase_data = verify_envato_purchase_code($wchat_purchase);
                if( isset($purchase_data['verify-purchase']['buyer']) )
                {
                    if($purchase_data['verify-purchase']['item_id'] == '18047319'){
                        update_option($config,"wchat_on_off",$_POST['wchat_on_off']);
                    }
                }else{
                    $message .= '<span style="color:red;">( Enter Your Valid Wchat Purchase Code.)</span>';
                }
            }
        }
        else{
            update_option($config,"wchat_on_off","off");
        }

        if(isset($_POST['zechat_purchase_code'])){
            if($_POST['zechat_purchase_code'] != "") {
                $purchase_data = verify_envato_purchase_code($_POST['zechat_purchase_code']);
                if(isset($purchase_data['verify-purchase']['buyer']) )
                {
                    if($purchase_data['verify-purchase']['item_id'] == '16491266'){
                        update_option($config,"zechat_purchase_code",$_POST['zechat_purchase_code']);
                    }else{
                        $message .= '<span style="color:red;">( Inalid Zechat Purchase Code.)</span>';
                    }
                }
                else{
                    $message .= '<span style="color:red;">( Enter Your Valid Zechat Purchase Code.)</span>';
                }
            }
        }

        if(isset($_POST['wchat_purchase_code'])){
            if($_POST['wchat_purchase_code'] != "") {
                $purchase_data = verify_envato_purchase_code($_POST['wchat_purchase_code']);
                if(isset($purchase_data['verify-purchase']['buyer']) )
                {
                    if($purchase_data['verify-purchase']['item_id'] == '18047319'){
                        update_option($config,"wchat_purchase_code",$_POST['wchat_purchase_code']);
                    }else{
                        $message .= '<span style="color:red;">( Inalid Wchat Purchase Code.)</span>';
                    }
                }
                else{
                    $message .= '<span style="color:red;">( Enter Your Valid Wchat Purchase Code.)</span>';
                }
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
                    <h4 class="page-title">Chat Setting</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li class="active">Chat Setting</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <form class="form form-horizontal" action="chat_setting.php" method="post">
                            <div>
                                <div class="text-left"><h3 class="box-title">Chat Setting <?php echo $message; ?></h3></div>
                            </div>


                            <div class="form-group bt-switch">
                                <label class="col-sm-4 control-label">Zechat on/off:</label>
                                <div class="col-sm-6">
                                    <input name="zechat_on_off" type="checkbox" <?php if(get_option( $config, "zechat_on_off") == 'on'){ echo "checked"; } ?> data-on-color="success" data-off-color="warning">
                                </div>
                            </div>
                            <div class="form-group bt-switch">
                                <label class="col-sm-4 control-label">Wchat on/off:</label>
                                <div class="col-sm-6">
                                    <input name="wchat_on_off" type="checkbox" <?php if(get_option( $config, "wchat_on_off") == 'on'){ echo "checked"; } ?> data-on-color="success" data-off-color="warning">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Zechat Purchase Code:</label>
                                <div class="col-sm-6">
                                    <input name="zechat_purchase_code" type="password" class="form-control" value="<?php echo get_option( $config, "zechat_purchase_code"); ?>">
                                    <span class="font-14"><code style="color: green">Get Purchase code From Here.</code><a href="https://codecanyon.net/item/facebook-style-php-ajax-chat-zechat/16491266?clickthrough_id=16491266&license=regular&open_purchase_for_item_id=16491266&purchasable=source&redirect_back=true&ref=bylancer&utm_source=item_desc_link" target="_blank">Buy Zechat</a></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Wchat Purchase Code:</label>
                                <div class="col-sm-6">
                                    <input name="wchat_purchase_code" type="password" class="form-control" value="<?php echo get_option( $config, "wchat_purchase_code"); ?>">
                                    <span class="font-14"><code style="color: green">Get Purchase code From Here.</code><a href="https://codecanyon.net/item/wchat-fully-responsive-phpajax-chat/18047319?clickthrough_id=18047319&license=regular&open_purchase_for_item_id=18047319&purchasable=source&redirect_back=true&ref=bylancer&utm_source=item_desc_link" target="_blank">Buy Wchat</a></span>
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
