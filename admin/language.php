<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');
require_once('../includes/lang/lang_'.$config['lang'].'.php');
$mysqli = db_connect($config);
session_start();
checkloggedadmin();

include("header.php");

function change_config_file_settings($filePath, $newSettings)
{

    // Get a list of the variables in the scope before including the file
    $new = get_defined_vars();
    // Include the config file and get it's values
    include($filePath);

    // Get a list of the variables in the scope after including the file
    $old = get_defined_vars();

    // Find the difference - after this, $fileSettings contains only the variables
    // declared in the file
    $fileSettings = array_diff($lang, $new);

    // Update $fileSettings with any new values
    $fileSettings = array_merge($fileSettings, $newSettings);
    // Build the new file as a string
    $newFileStr = "<?php\n\n";
    foreach ($fileSettings as $name => $val) {
        // Using var_export() allows you to set complex values such as arrays and also
        // ensures types will be correct
        $newFileStr .= "\$lang['$name'] = " . var_export($val, true) . ";\n";
    }
    // Closing tag intentionally omitted, you can add one if you want

    // Write it back to the file
    file_put_contents($filePath, $newFileStr);

}

// Example usage:
// This will update $dbuser and $dbpass but leave everything else untouched

$newSettings = array(
    'dbuser' => '1',
    'dbpass' => '2',
);
change_config_file_settings('filewrite.php', $newSettings);
?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Language</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Static Pages</li>
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
                            <div class="pull-left"><h3 class="box-title">Static Pages List</h3></div>
                            <div class="pull-right">
                                <p class="text-muted">
                                    <a href="page_add.php" class="btn btn-success waves-effect waves-light m-r-10">Add Page</a>
                                    <button data-ajax-response="deletemarked" data-ajax-action="deleteStaticPage" class="btn btn-danger waves-effect waves-light m-r-10"><i class="fa fa-trash-o"></i> Delete Marked</button>
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
                                    <th>No.</th>
                                    <th>ID</th>
                                    <th>Value</th>
                                    <th>Shortcode</th>
                                    <th class="sortingNone">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 1;
                                foreach ($lang as $key => $value)
                                {
                                    $id = $count;
                                    $title = $key;
                                    $template_name = '{LANG_' . $key . '}';
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
                                        <td><?php echo $value; ?></td>
                                        <td><?php echo $template_name; ?></td>
                                        <td class="text-nowrap">
                                            <a href="page_edit.php?id=<?php echo $id;?>" data-toggle="tooltip" data-original-title="Edit" class="action"> <i class="ti-pencil-alt text-info"></i> </a> &nbsp;
                                            <a href="javacript:void(0)" data-toggle="tooltip" data-original-title="Delete" class="action item-js-delete" data-ajax-action="deleteStaticPage"><i class="ti-close text-danger"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                    $count++;
                                }?>

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