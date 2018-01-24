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
            <h4 class="page-title">Users</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li>
                <li class="active">Users DATA EXPORT</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <!-- /row -->
    <div class="row">

        <!-- /.row -->
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0">Data Export</h3>
                <p class="text-muted m-b-30">Export data to Copy, CSV, Excel, PDF & Print</p>
                <div class="table-responsive">
                    <table id="example23" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Sex</th>
                            <th>Country</th>
                            <th>Status</th>
                            <th>Joined</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = "SELECT * FROM `".$config['db']['pre']."user`";
                        $result = $mysqli->query($query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['id'];
                            $username = $row['username'];
                            $image = $row['image'];
                            $status = $row['status'];
                            if ($image == "")
                                $image = "default_user.png";
                            else {
                                //$image = "small" . $image;
                                $image = $image;
                            }

                            if ($status == "0"){
                                $status = '<span class="label label-info">ACTIVE</span>';
                            }
                            elseif($status == "1")
                            {
                                $status = '<span class="label label-success">CONFIRM</span>';
                            }
                            else{
                                $status = '<span class="label label-danger">BANNED</span>';
                            }

                            ?>
                            <tr>
                                <td><?php echo $row['id'] ?></td>
                                <td><img src="../storage/profile/<?php echo $image; ?>" alt="<?php echo $username ?>" class="img-circle bg-theme" width="40"></td>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo $username ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo $row['sex'] ?></td>
                                <td><?php echo $row['country'] ?></td>
                                <td><?php echo $status ?></td>
                                <td><?php echo date('M dS', strtotime($row['joined'])); ?></td>
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

        <!-- start - This is for export functionality only -->
        <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
        <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
        <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
        <!-- end - This is for export functionality only -->
        <script>
            $('#example23').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        </script>