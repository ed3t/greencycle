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
                <h4 class="page-title">Advertisement</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Manage Adsense</li>s
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="table-responsive">

                        <table id="myTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Slug</th>
                                <th>Provider Name</th>
                                <th>Status</th>
                                <th class="sortingNone">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = "SELECT * FROM `".$config['db']['pre']."adsense`";
                            $result = $mysqli->query($query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id = $row['id'];
                                $slug = $row['slug'];
                                $provider_name = $row['provider_name'];
                                $large_track_code = $row['large_track_code'];
                                $tablet_track_code = $row['tablet_track_code'];
                                $phone_track_code = $row['phone_track_code'];
                                $status = $row['status'];
                                if ($status == "0"){
                                    $status = '<span class="label label-warning">Not Active</span>';
                                }
                                else{
                                    $status = '<span class="label label-info">ACTIVE</span>';
                                }


                                ?>
                                <tr>
                                    <td><?php echo $row['id'] ?></td>
                                    <td><?php echo $slug ?></td>
                                    <td><?php echo $provider_name ?></td>
                                    <td><?php echo $status ?></td>

                                    <td class="text-nowrap">
                                        <a href="advertise_edit.php?id=<?php echo $id;?>" data-toggle="tooltip" data-original-title="Edit" class="action"> <i class="ti-pencil-alt text-info"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php }?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->


        <?php include("footer.php"); ?>

        <script src="js/admin-ajax.js"></script>
        <script src="js/alert.js"></script>