<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

include("header.php");

?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Payment Settings</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Payment Settings</li>
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
                    <form method="post" name="f1" id="f1">
                        <div>
                            <div class="pull-left"><h3 class="box-title">Payment Setting List</h3></div>
                            <div class="clear"></div>
                        </div>
                        <hr>

                        <div class="table-responsive" id="js-table-list">

                            <table id="myTable" class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="sortingNone hidden">
                                        <div class="checkbox checkbox-success">
                                            <input type="checkbox" name="selall" value="checkbox" id="selall" onClick="checkBox(this)">
                                            <label for="selall"></label>
                                        </div>
                                    </th>
                                    <th class="sortingNone">&nbsp;</th>
                                    <th class="sortingNone">Logo</th>
                                    <th>Title</th>
                                    <th>Installed</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "SELECT payment_id,payment_title,payment_folder,payment_install FROM ".$config['db']['pre']."payments order by payment_id";
                                $query_result = mysqli_query($mysqli,$query);
                                while ($info = @mysqli_fetch_array($query_result))
                                {
                                    $id = $info['payment_id'];
                                    $name = $info['payment_title'];
                                    $folder = $info['payment_folder'];
                                    if($info['payment_install'] == 1)
                                        $install = '<span class="label label-info">Installed</span>';
                                    else
                                        $install = '<span class="label label-warning">Uninstalled</span>';

                                    ?>
                                    <tr class="mail-contnet ajax-item-listing" data-item-id="<?php echo $id ?>">
                                        <td class="hidden">
                                            <input type="hidden" name="titles[]" id="titles[]" value="<?php echo $name;?>">
                                            <div class="checkbox checkbox-success">
                                                <input type="checkbox" name="list[]" id="checkbox<?php echo $id;?>" class="service-checker" value="<?php echo $id;?>">
                                                <label for="checkbox<?php echo $id;?>"></label>
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <img src="<?php echo $config['site_url'] ?>includes/payments/<?php echo $folder ?>/logo/logo.png" height="40px"/>
                                        </td>
                                        <td>
                                            <?php echo $name ?>
                                        </td>
                                        <td><?php echo $install ?></td>
                                        <td>
                                            <a href="payment_edit.php?id=<?php echo $id;?>" data-toggle="tooltip" data-original-title="Edit" class=""> <i class="ti-pencil-alt text-info"></i> </a> &nbsp;
                                            <?php if($info['payment_install'] == 1) { ?>
                                                <a href="javacript:void(0)" class="uninstall-payment" data-ajax-action="uninstallPayment"> <i class="fa fa-close text-warning"></i> Uninstall</a>
                                            <?php
                                            }
                                            else
                                            { ?>
                                                <a href="javacript:void(0)" class="install-payment"  data-ajax-action="installPayment"> <i class="fa fa-download text-info"></i> Install</a>
                                            <?php } ?>


                                        </td>
                                    </tr>
                                <?php }?>

                                </tbody>
                            </table>

                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- /.row -->


        <?php include("footer.php"); ?>

        <script src="js/admin-ajax.js"></script>
        <script src="js/alert.js"></script>
