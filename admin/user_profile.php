<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

if(!isset($_GET['id']))
{
    echo '<script>window.location="404.php"</script>';
}

$error = array();
$errorNo = 0;
if(isset($_POST['Submit']))
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
    else{
        if($_POST["username"] == ""){
            $error[] = "Username can't be blank.";
            $errorNo = 1;
        }
        if($_POST["email"] == ""){
            $error[] = "Email can't be blank.";
            $errorNo = 2;
        }
        if($errorNo==0) {
            if ($_FILES['file']['name'] != "") {
                $uploaddir = '../storage/profile/';
                $original_filename = $_FILES['file']['name'];

                $extensions = explode(".", $original_filename);
                $extension = $extensions[count($extensions) - 1];
                $uniqueName = $string . "." . $extension;
                $uploadfile = $uploaddir . $uniqueName;

                $file_type = "file";

                if ($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png") {
                    $file_type = "image";

                    $size = filesize($_FILES['file']['tmp_name']);

                    $image = $_FILES["file"]["name"];
                    $uploadedfile = $_FILES['file']['tmp_name'];

                    if ($image) {
                        if ($extension == "jpg" || $extension == "jpeg") {
                            $uploadedfile = $_FILES['file']['tmp_name'];
                            $src = imagecreatefromjpeg($uploadedfile);
                        } else if ($extension == "png") {
                            $uploadedfile = $_FILES['file']['tmp_name'];
                            $src = imagecreatefrompng($uploadedfile);
                        } else {
                            $src = imagecreatefromgif($uploadedfile);
                        }

                        list($width, $height) = getimagesize($uploadedfile);

                        $newwidth = 225;
                        $newheight = ($height / $width) * $newwidth;
                        $tmp = imagecreatetruecolor($newwidth, $newheight);

                        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                        $filename = $uploaddir . "small" . $uniqueName;

                        imagejpeg($tmp, $filename, 100);

                        imagedestroy($src);
                        imagedestroy($tmp);
                    }


                }
                //else if it's not bigger then 0, then it's available '
                //and we send 1 to the ajax request
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                    //$time = date('Y-m-d H:i:s', time());
                    echo $uniqueName;
                    $query = "Update `" . $config['db']['pre'] . "user` set
            username='" . mysqli_real_escape_string($mysqli, $_POST["username"]) . "',
            email='" . mysqli_real_escape_string($mysqli, $_POST["email"]) . "',
            name='" . mysqli_real_escape_string($mysqli, $_POST['name']) . "',
            about='" . mysqli_real_escape_string($mysqli, $_POST['about']) . "',
            sex='" . mysqli_real_escape_string($mysqli, $_POST['sex']) . "',
            dob='" . mysqli_real_escape_string($mysqli, $_POST['dob']) . "',
            country='" . mysqli_real_escape_string($mysqli, $_POST['country']) . "',
            skype='" . mysqli_real_escape_string($mysqli, $_POST['skype']) . "',
            facebook='" . mysqli_real_escape_string($mysqli, $_POST['facebook']) . "',
            twitter='" . mysqli_real_escape_string($mysqli, $_POST['twitter']) . "',
            googleplus='" . mysqli_real_escape_string($mysqli, $_POST['googleplus']) . "',
            instagram='" . mysqli_real_escape_string($mysqli, $_POST['instagram']) . "',
                image='$uniqueName'
                WHERE id = '".$_GET['id']."' LIMIT 1";
                    $query_result = $mysqli->query($query) or mysqli_error($mysqli);

                    $success = "Profile Updated Successfully";
                }
            } else {
                //$time = date('Y-m-d H:i:s', time());
                $query = "Update `" . $config['db']['pre'] . "user` set
            username='" . mysqli_real_escape_string($mysqli, $_POST["username"]) . "',
            email='" . mysqli_real_escape_string($mysqli, $_POST["email"]) . "',
            name='" . mysqli_real_escape_string($mysqli, $_POST['name']) . "',
            about='" . mysqli_real_escape_string($mysqli, $_POST['about']) . "',
            sex='" . mysqli_real_escape_string($mysqli, $_POST['sex']) . "',
            dob='" . mysqli_real_escape_string($mysqli, $_POST['dob']) . "',
            country='" . mysqli_real_escape_string($mysqli, $_POST['country']) . "',
            skype='" . mysqli_real_escape_string($mysqli, $_POST['skype']) . "',
            facebook='" . mysqli_real_escape_string($mysqli, $_POST['facebook']) . "',
            twitter='" . mysqli_real_escape_string($mysqli, $_POST['twitter']) . "',
            googleplus='" . mysqli_real_escape_string($mysqli, $_POST['googleplus']) . "',
            instagram='" . mysqli_real_escape_string($mysqli, $_POST['instagram']) . "'
            WHERE id = '".$_GET['id']."' LIMIT 1";
                $query_result = $mysqli->query($query);

                $success = "Profile Updated Successfully";
            }
        }
    }

}


$user = "SELECT * FROM `".$config['db']['pre']."user` where id = '".$_GET['id']."'";
$userresult = $mysqli->query($user);
$fetchuser = mysqli_fetch_assoc($userresult);
$fetchusername  = $fetchuser['username'];
$fetchuserpic     = $fetchuser['image'];

if($fetchuserpic == "")
    $fetchuserpic = "default_user.png";

include("header.php");
?>

    <!-- Page Content -->
    <div id="page-wrapper">
    <div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><?php echo $fetchuser['username'];?> Profile</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li>
                <li class="active"><?php echo $fetchuser['username'];?> Profile</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
        <span style="color:#df6c6e;">
                    <?php
                    if($errorNo!=0){
                        foreach($error as $value){
                            echo '<div class="byMsg byMsgError">! '.$value.'</div>';
                        }
                    }

                    ?>
                </span>
            <span style="color:#31df0c;">
                    <?php
                    if(!empty($success)){
                        echo '<div class="byMsg byMsgSuccess">! '.$success.'</div>';
                    }
                    ?>
                </span>
    <!-- .row -->
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="white-box">
                <div class="user-bg">
                    <img width="100%" alt="user" src="plugins/images/large/img1.jpg">
                    <div class="overlay-box">
                        <div class="user-content"> <a href="javascript:void(0)">
                                <img class="thumb-lg img-circle" src="../storage/profile/<?php echo $fetchuserpic;?>" alt="<?php echo $fetchuser['name'];?>"></a>
                            <h4 class="text-white"><?php echo $fetchuser['username'];?></h4>
                            <h5 class="text-white"><?php echo $fetchuser['email'];?></h5>
                        </div>
                    </div>
                </div>

                <div class="user-btm-box">
                    <!-- .row -->
                    <div class="row text-center m-t-10">
                        <div class="col-md-6 b-r"><strong>Name</strong><p><?php echo $fetchuser['name'];?></p></div>
                        <div class="col-md-6"><strong>Gender</strong><p><?php echo $fetchuser['sex'];?></p></div>
                    </div>
                    <!-- /.row -->
                    <hr>
                    <!-- .row -->
                    <div class="row text-center m-t-10">
                        <div class="col-md-6 b-r"><strong>Email ID</strong><p style="word-wrap: break-word;"><?php echo $fetchuser['email'];?></p></div>
                        <div class="col-md-6"><strong>Joined</strong><p><?php echo date('dS M g:iA', strtotime($fetchuser['joined'])); ?></p></div>
                    </div>
                    <!-- /.row -->
                    <hr>
                    <!-- .row -->
                    <div class="row text-center m-t-10">
                        <div class="col-md-12"><strong>Country</strong><p><?php echo $fetchuser['country'];?></p></div>

                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="white-box">
                <!-- .tabs -->
                <ul class="nav nav-tabs tabs customtab">
                    <li class="active tab"><a href="#profile" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">About <?php echo $fetchuser['username'];?></span> </a> </li>
                    <!--<li class="tab"><a href="#settings" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Edit Detail</span> </a> </li>-->
                </ul>
                <!-- /.tabs -->
                <div class="tab-content">
                    <!-- .tabs2 -->
                    <div class="tab-pane active" id="profile">
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong> <br>
                                <p class="text-muted"><?php echo $fetchuser['name'];?></p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Joined</strong> <br>
                                <p class="text-muted"><?php echo date('dS M g:iA', strtotime($fetchuser['created_at'])); ?></p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Email</strong> <br>
                                <p class="text-muted" style="word-wrap: break-word;"><?php echo $fetchuser['email'];?></p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Location</strong> <br>
                                <p class="text-muted"><?php echo $fetchuser['country'];?></p>
                            </div>
                        </div>
                        <hr>
                        <p class="m-t-30"><?php echo $fetchuser['description'];?></p>

                    </div>
                    <!-- /.tabs2 -->
                    <!-- .tabs3 -->
                    <!--<div class="tab-pane" id="settings">
                        <form class="form-horizontal form-material">
                            <div class="form-group">
                                <label class="col-md-12">Full Name</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="Johnathan Doe" class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="example-email" class="col-md-12">Email</label>
                                <div class="col-md-12">
                                    <input type="email" placeholder="johnathan@admin.com" class="form-control form-control-line" name="example-email" id="example-email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Password</label>
                                <div class="col-md-12">
                                    <input type="password" value="password" class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Phone No</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="123 456 7890" class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Message</label>
                                <div class="col-md-12">
                                    <textarea rows="5" class="form-control form-control-line"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12">Select Country</label>
                                <div class="col-sm-12">
                                    <select class="form-control form-control-line">
                                        <option>London</option>
                                        <option>India</option>
                                        <option>Usa</option>
                                        <option>Canada</option>
                                        <option>Thailand</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>-->
                    <!-- /.tabs3 -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->


<?php include("footer.php"); ?>