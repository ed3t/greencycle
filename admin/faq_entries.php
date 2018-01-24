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
                <h4 class="page-title">FAQ</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">FAQ Entries</li>
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
                            <div class="pull-left"><h3 class="box-title">FAQ Entries List</h3></div>
                            <div class="pull-right">
                                <p class="text-muted">
                                    <a href="faq_entry_add.php" class="btn btn-success waves-effect waves-light m-r-10">Add Entry</a>
                                    <button data-ajax-response="deletemarked" data-ajax-action="deletefaq" class="btn btn-danger waves-effect waves-light m-r-10"><i class="fa fa-trash-o"></i> Delete Marked</button>
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
                                    <th>#ID</th>
                                    <th>Title</th>
                                    <th class="sortingNone">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "SELECT * FROM `".$config['db']['pre']."faq_entries`";
                                $result = $mysqli->query($query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['faq_id'];
                                    $title = $row['faq_title'];

                                    ?>
                                    <tr class="ajax-item-listing" data-item-id="<?php echo $id ?>">
                                        <td>
                                            <input type="hidden" name="titles[]" id="titles[]" value="<?php echo $title;?>">

                                            <div class="checkbox checkbox-success">
                                                <input type="checkbox" name="list[]" id="checkbox<?php echo $id;?>" class="service-checker" value="<?php echo $id;?>" style="display: block">
                                                <label for="checkbox<?php echo $id;?>"></label>
                                            </div>
                                        </td>
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo $title; ?></td>

                                        <td class="text-nowrap">
                                            <a href="faq_entry_edit.php?id=<?php echo $id;?>" data-toggle="tooltip" data-original-title="Edit" class="action"> <i class="ti-pencil-alt text-info"></i> </a> &nbsp;
                                            <a href="javacript:void(0)" data-toggle="tooltip" data-original-title="Delete" class="action item-js-delete" data-ajax-action="deletefaq"><i class="ti-close text-danger"></i></a>
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