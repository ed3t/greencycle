<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

include("header.php");

?>
<?php

?>
<!-- /.modal -->
<div id="addEntry" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addAjaxForm" action="addCountry">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add New Country</h4>
                </div>
                <div class="modal-body">
                    <div id="login-status" class="info-notice" style="display: none;margin-bottom: 20px">
                        <div class="content-wrapper">
                            <div id="login-detail">
                                <div id="login-status-icon-container"><span class="login-status-icon"></span></div>
                                <div id="login-status-message">Processing...</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cname" class="control-label">Country name:</label>
                        <input type="text" class="form-control" id="cname" name="countryname">
                    </div>
                    <div class="form-group">
                        <label for="sname" class="control-label">Short Name:</label>
                        <input type="text" class="form-control" id="sname" name="sortname">
                    </div>
                    <div class="form-group">
                        <label for="pcode" class="control-label">Phonecode:</label>
                        <input type="text" class="form-control" id="pcode" name="phonecode">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="ajaxSaveMe">Save</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /.modal -->
<div id="editEntry" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editAjaxForm" action="editCountry">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Edit Country</h4>
                </div>
                <div class="modal-body">
                    <div id="login-status" class="info-notice" style="display: none;margin-bottom: 20px">
                        <div class="content-wrapper">
                            <div id="login-detail">
                                <div id="login-status-icon-container"><span class="login-status-icon"></span></div>
                                <div id="login-status-message">Processing...</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="countryname" class="control-label">Country name:</label>
                        <input type="text" class="form-control" id="countryname" name="countryname" value="">
                    </div>
                    <div class="form-group">
                        <label for="sortname" class="control-label">Short Name:</label>
                        <input type="text" class="form-control" id="sortname" name="sortname" value="">
                    </div>
                    <div class="form-group">
                        <label for="phonecode" class="control-label">Phonecode:</label>
                        <input type="text" class="form-control" id="phonecode" name="phonecode" value="">
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" id="countryid" name="id" value="">
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="ajaxEdit">Save</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Countries </h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Countries</li>
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
                            <div class="pull-left"><h3 class="box-title">Countries Data</h3></div>
                            <div class="pull-right">
                                <p class="text-muted">
                                    <a href="#" data-toggle="modal" data-target="#addEntry" class="btn btn-success waves-effect waves-light m-r-10">Add Country</a>
                                    <button data-ajax-response="deletemarked" data-ajax-action="deleteCountry" class="btn btn-danger waves-effect waves-light m-r-10"><i class="fa fa-trash-o"></i> Delete Marked</button>
                                </p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <code>Note: If you delete any country then all city,region,ad and ads images will be deleted which related to deleted country.</code>
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
                                    <th>Country Code</th>
                                    <th>Country Name</th>
                                    <th>Status</th>
                                    <th class="sortingNone">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "SELECT * FROM `".$config['db']['pre']."countries` ORDER BY id";
                                $result = $mysqli->query($query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['id'];
                                    $name = $row['name'];
                                    $sortname = $row['sortname'];
                                    $phonecode = $row['phonecode'];
                                    $status = $row['install'];


                                    if ($status == "1"){
                                        $status = '<span class="label label-success">Activated</span>';
                                    }
                                    elseif($status == "0")
                                    {
                                        $status = '<span class="label label-warning">Not Active</span>';
                                    }
                                    ?>
                                    <tr class="mail-contnet ajax-item-listing" data-item-id="<?php echo $id ?>" data-item-name="<?php echo $name ?>" data-item-sortname="<?php echo $sortname ?>" data-item-phonecode="<?php echo $phonecode;?>">
                                        <td>
                                            <input type="hidden" name="titles[]" id="titles[]" value="<?php echo $id;?>">
                                            <div class="checkbox checkbox-success">
                                                <input type="checkbox" name="list[]" id="checkbox<?php echo $id;?>" class="service-checker" value="<?php echo $id;?>">
                                                <label for="checkbox<?php echo $id;?>"></label>
                                            </div>
                                        </td>
                                        <td><?php echo $id ?></td>
                                        <td><?php echo $sortname ?></td>
                                        <td><?php echo $name ?>
                                            <span style="float:right;padding-right: 40px">
                                                <a class="btn btn-xs btn-info" href="region.php?country=<?php echo $id;?>"><i class="fa fa-folder"></i> Regions</a>
                                            </span>
                                        </td>
                                        <td><?php echo $status ?></td>

                                        <td class="text-nowrap">
                                            <a href="#" data-toggle="tooltip" data-original-title="Edit" class="editAjaxCountry"> <i class="ti-pencil-alt text-success"></i> </a>
                                            <a href="javacript:void(0)" data-toggle="tooltip" data-original-title="Delete" class="action item-js-delete" data-ajax-action="deleteCountry"><i class="ti-close text-danger"></i></a>
                                            <?php if($row['install'] == 1) { ?>
                                                <a href="javacript:void(0)" data-toggle="tooltip" data-original-title="Uninstall" class="action uninstall-country" data-ajax-action="uninstallCountry"> <i class="fa fa-close text-warning"></i> </a>
                                            <?php
                                            }else
                                            { ?>
                                                <a href="javacript:void(0)" data-toggle="tooltip" data-original-title="Install" class="action install-country"  data-ajax-action="installCountry"> <i class="fa fa-download text-info"></i> </a>
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



