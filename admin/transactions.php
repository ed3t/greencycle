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
                    <h4 class="page-title">Transations</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li class="active">Transations</li>
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
                                <div class="pull-left"><h3 class="box-title">All Transations List</h3></div>
                                <div class="pull-right">
                                    <p class="text-muted">
                                        <button data-ajax-response="deletemarked" data-ajax-action="deleteTransaction" class="btn btn-danger waves-effect waves-light m-r-10"><i class="fa fa-trash-o"></i> Delete Marked</button>
                                    </p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <hr>

                            <div class="table-responsive" id="js-table-list">

                                <table id="responsiveTable" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="sortingNone">
                                            <div class="checkbox checkbox-success">
                                                <input type="checkbox" name="selall" value="checkbox" id="selall" onClick="checkBox(this)">
                                                <label for="selall"></label>
                                            </div>
                                        </th>
                                        <th>#ID</th>
                                        <th>Ad Title</th>
                                        <th>Amount</th>
                                        <th>Premium</th>
                                        <th>Status</th>
                                        <th>Pay Method</th>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query = "SELECT * FROM `".$config['db']['pre']."transaction` order by id desc";
                                    $result = $mysqli->query($query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $id = $row['id'];
                                        $ad_id = $row['product_id'];
                                        $ad_title = $row['product_name'];
                                        $amount = $row['amount'];
                                        $payment_method = $row['transaction_gatway'];
                                        $featured = $row['featured'];
                                        $urgent = $row['urgent'];
                                        $highlight = $row['highlight'];
                                        $t_status = $row['status'];
                                        $transaction_time = date('d M Y', $row['transaction_time']);

                                        $premium = '';
                                        if ($featured == "1"){
                                            $premium = $premium.'<span class="label label-warning">Featured</span>';
                                        }

                                        if($urgent == "1")
                                        {
                                            $premium = $premium.'<span class="label label-success">Urgent</span>';
                                        }

                                        if($highlight == "1")
                                        {
                                            $premium = $premium.'<span class="label label-info">Highlight</span>';
                                        }

                                        $status = '';
                                        if ($t_status == "success"){
                                            $status = '<span class="label label-success">Success</span>';
                                        }
                                        elseif($t_status == "pending")
                                        {
                                            $status = '<span class="label label-warning">Pending</span>';
                                        }
                                        elseif($t_status == "failed")
                                        {
                                            $status = '<span class="label label-danger">failed</span>';
                                        }

                                        ?>
                                        <tr class="mail-contnet ajax-item-listing" data-item-id="<?php echo $id ?>">
                                            <td class=" details-control"></td>
                                            <td>
                                                <input type="hidden" name="titles[]" id="titles[]" value="<?php echo $id;?>">
                                                <div class="checkbox checkbox-success">
                                                    <input type="checkbox" name="list[]" id="checkbox<?php echo $id;?>" class="service-checker" value="<?php echo $id;?>">
                                                    <label for="checkbox<?php echo $id;?>"></label>
                                                </div>
                                            </td>
                                            <td><?php echo $row['id'] ?></td>
                                            <td class="ellipsis" width="20%">
                                                <a href="<?php echo $config['site_url'];?>ad-detail.php?id=<?php echo $ad_id;?>" target="_blank"><?php echo $ad_title; ?> </a></td>
                                            <td><?php echo $config['currency_sign'] ?> <?php echo $amount ?></td>
                                            <td><?php echo $premium ?></td>
                                            <td><?php echo $status ?></td>
                                            <td><?php echo $payment_method ?></td>
                                            <td><?php echo $transaction_time ?></td>
                                            <td>
                                                <a href="transaction_edit.php?id=<?php echo $id;?>" data-toggle="tooltip" data-original-title="Edit"> <i class="ti-pencil-alt text-info"></i> </a> &nbsp;
                                                <a href="javacript:void(0)" data-toggle="tooltip" data-original-title="Delete" class="item-js-delete" data-ajax-action="deleteTransaction"><i class="ti-trash text-danger"></i></a>
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