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
            <h4 class="page-title">Categories</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li>
                <li class="active">Categories</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">


                <div>


                    <link href="css/category.css" rel="stylesheet" />
                    <div id="quickad-tbs" class="wrap">
                        <div class="quickad-tbs-body">
                            <div class="page-header text-right clearfix">
                                <div class="quickad-page-title">Manage Categories</div>
                            </div>
                            <div class="row">
                                <div id="quickad-sidebar" class="col-sm-4">
                                    <div id="quickad-categories-list" class="quickad-nav">
                                        <div class="quickad-nav-item active quickad-category-item quickad-js-all-services">
                                            <div class="quickad-padding-vertical-xs">All Categories</div>
                                        </div>
                                        <ul id="quickad-category-item-list" class="ui-sortable">
                                            <?php
                                            $query = "SELECT * FROM `".$config['db']['pre']."catagory_main`";
                                            $result = $mysqli->query($query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $catid = $row['cat_id'];
                                                $catname = $row['cat_name'];
                                                $caticon = $row['icon'];
                                            ?>
                                                <li class="quickad-nav-item quickad-category-item" data-category-id="<?php echo $catid; ?>">
                                                    <div class="quickad-flexbox">
                                                        <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%">
                                                            <i id="quickad-cat-icon" class="quickad-margin-right-sm <?php echo $caticon; ?>"
                                                               title="<?php echo $catname; ?>"></i>
                                                        </div>
                                                        <div class="quickad-flex-cell quickad-vertical-middle">
                                                            <span class="displayed-value" style="display: inline;">
                                                                <?php echo $catname; ?>
                                                            </span>
                                                            <form method="post" id="edit-category-form" style="display: none">
                                                                <div class="form-field form-required">
                                                                    <label for="quickad-category-name" style="color:#000;">Title</label>
                                                                    <input class="form-control input-lg" id="cat-name" type="text" name="name"
                                                                           value="<?php echo $catname; ?>">
                                                                </div>
                                                                <div class="form-field form-required">
                                                                    <label for="quickad-category-name" style="color:#000;">Category icon</label>
                                                                    <input class="form-control input-lg" id="cat-icon" type="text" name="icon" placeholder="fa fa-usd"
                                                                           value="<?php echo $caticon; ?>">
                                                                </div>
                                                                <input class="form-control input-lg" id="cat-id" type="hidden" name="id"
                                                                       value="<?php echo $catid; ?>" >
                                                                <div class="text-right">
                                                                    <button type="submit" class="btn btn-success">Save</button>
                                                                    <button type="button" id="cancel-button" class="btn btn-default">Cancel</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%">
                                                            <a href="#" class="glyphicon glyphicon-edit quickad-margin-horizontal-xs quickad-js-edit" title="Edit"></a>
                                                        </div>

                                                        <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%">
                                                            <a href="#" class="glyphicon glyphicon-trash text-danger quickad-js-delete"
                                                               title="Delete"></a>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php  } ?>
                                        </ul>
                                    </div>

                                    <div class="form-group">
                                        <button id="quickad-new-category" type="button" class="btn btn-lg btn-block btn-success-outline"
                                                data-original-title="" title=""><i class="dashicons dashicons-plus-alt"></i>New Category
                                        </button>
                                    </div>
                                    <form method="post" id="new-category-form" style="display: none">
                                        <div class="form-group quickad-margin-bottom-md">
                                            <div class="form-field form-required">
                                                <label for="quickad-category-name">Title</label>
                                                <input class="form-control" id="quickad-category-name" type="text" name="name" required=""/>
                                            </div>
                                            <div class="form-field form-required">
                                                <label for="quickad-category-name">FontAwesome icon for Category</label>
                                                <input class="form-control" id="quickad-category-icon" type="text" name="icon" placeholder="fa-usd" required=""/>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success">Save</button>
                                            <button type="button" class="btn btn-default">Cancel</button>
                                        </div>
                                    </form>


                                </div>

                                <div id="quickad-services-wrapper" class="col-sm-8">
                                    <div class="panel panel-default quickad-main">
                                        <div class="panel-body">
                                            <h4 class="quickad-block-head">
                                                <span class="quickad-category-title">All Categories</span>
                                                <button type="button" class="new-subcategory  ladda-button pull-right btn btn-success"
                                                        data-spinner-size="40" data-style="zoom-in">
                                                    <span class="ladda-label"><i class="glyphicon glyphicon-plus"></i>Add Sub-Category</span>
                                                </button>
                                            </h4>
                                            <form method="post" id="new-subcategory-form" style="display: none">
                                                <div class="form-group quickad-margin-bottom-md">
                                                    <div class="form-field form-required">
                                                        <label for="new-subcategory-name">Title</label>
                                                        <input class="form-control" id="new-subcategory-name" type="text" name="name" required=""/>
                                                        <input type="hidden" id="cat-id" name="cat_id" value="0">
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                    <button type="button" class="btn btn-default">Cancel</button>
                                                </div>
                                            </form>

                                            <p class="quickad-margin-top-xlg no-result" style="display: none;">No services found. Please add services</p>

                                            <div class="quickad-margin-top-xlg" id="ab-services-list">
                                                <div class="panel-group ui-sortable" id="services_list" role="tablist"
                                                     aria-multiselectable="true">

                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button type="button" id="quickad-delete" class="btn btn-danger ladda-button"
                                                        data-spinner-size="40" data-style="zoom-in"><span class="ladda-label"><i
                                                            class="glyphicon glyphicon-trash"></i> Delete</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="quickad-alert" class="quickad-alert"></div>
                    </div>
                    <script>

                        function editSubCat(id){
                            var data = $('#'+id).serialize();
                            $.post(ajaxurl+'?action=editSubCat&'+data, function (response) {
                                if (response != 0) {
                                    quickadAlert({success: ['Successfully edited']});
                                } else {
                                    quickadAlert({error: ['Problem in saving, Please try again.']});
                                }
                            });
                        }
                    </script>






                </div>
            </div>
        </div>

    </div>
    <!-- /.row -->


<?php include("footer.php"); ?>

<script src="js/category.js"></script>
<script src="js/alert.js"></script>