<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

require_once('../includes/functions/func.users.php');
require_once('../includes/functions/func.sqlquery.php');

include("header.php");

?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Currency</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Currency List</li>
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
                            <div class="pull-left"><h3 class="box-title">All Currency List</h3></div>
                            <div class="pull-right">
                                <p class="text-muted">
                                    <a href="currency_add.php" class="btn btn-success waves-effect waves-light m-r-10">Add Currency</a>
                                </p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <hr>

                        <div class="table-responsive" id="js-table-list">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="sortingNone">
                                            <div class="checkbox checkbox-success">
                                                <input type="checkbox" name="selall" value="checkbox" id="selall" onClick="checkBox(this)">
                                                <label for="selall"></label>
                                            </div>
                                        </th>
                                        <th>TITLE</th>
                                        <th>CURRENCY</th>
                                        <th class="text-center">CODE</th>
                                        <th class="text-center">SYMBOL</th>
                                        <th class="text-center">HTML CODE</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $currency = get_currency_list($config);

                                    foreach ($currency as $value)
                                    {
                                        $id          = $value['id'];
                                        $title       = $value['country'];
                                        $currency    = $value['currency'];
                                        $code        = $value['code'];
                                        $symbol      = $value['symbol'];
                                        $html_code   = $value['html_code'];

                                        ?>

                                        <tr class="mail-contnet ajax-item-listing" data-item-id="<?php echo $id ?>">
                                            <td>
                                                <input type="hidden" name="titles[]" id="titles[]" value="<?php echo $id;?>">

                                                <div class="checkbox checkbox-success">
                                                    <input type="checkbox" name="list[]" id="checkbox<?php echo $id;?>" class="service-checker" value="<?php echo $id;?>" style="display: block">
                                                    <label for="checkbox<?php echo $id;?>"></label>
                                                </div>
                                            </td>
                                            <td><?php echo $title;?></td>
                                            <td><?php echo $currency;?></td>
                                            <td class="text-center"><?php echo $code;?></td>
                                            <td class="text-center"><?php echo $symbol;?></td>
                                            <td class="text-center"><?php echo $html_code;?></td>
                                            <td class="text-nowrap text-center">

                                                <a href="currency_edit.php?id=<?php echo $id;?>" data-toggle="tooltip" data-original-title="Edit"> <i class="ti-pencil-alt text-info"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
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
