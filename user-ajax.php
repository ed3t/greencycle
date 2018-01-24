<?php
require_once('includes/config.php');
session_start();
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.sqlquery.php');
require_once('includes/functions/func.users.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');
$con = db_connect($config);

if (isset($_GET['action'])){
    if ($_GET['action'] == "deleteMyAd") { deleteMyAd($con,$config); }
    if ($_GET['action'] == "deleteResumitAd") { deleteResumitAd($con,$config); }

    if ($_GET['action'] == "openlocatoionPopup") { openlocatoionPopup($con,$config); }
    if ($_GET['action'] == "getlocHomemap") { getlocHomemap($con,$config); }
}

if(isset($_POST['action'])){
    if ($_POST['action'] == "hideItem") { hideItem($con,$config); }
    if ($_POST['action'] == "removeAdImg") { removeAdImg($con,$config); }
    if ($_POST['action'] == "setFavAd") {setFavAd($con,$config);}
    if ($_POST['action'] == "removeFavAd") {removeFavAd($con,$config);}
    if ($_POST['action'] == "getsubcatbyidList") { getsubcatbyidList($con,$config); }
    if ($_POST['action'] == "getsubcatbyid") {getsubcatbyid($con,$config);}

    if ($_POST['action'] == "getStateByCountryID") {getStateByCountryID($con,$config);}
    if ($_POST['action'] == "getCityByStateID") {getCityByStateID($con,$config);}
    if ($_POST['action'] == "ModelGetStateByCountryID") {ModelGetStateByCountryID($con,$config);}
    if ($_POST['action'] == "ModelGetCityByStateID") {ModelGetCityByStateID($con,$config);}
    if ($_POST['action'] == "searchStateCountry") {searchStateCountry($con,$config);}
    if ($_POST['action'] == "searchCityStateCountry") {searchCityStateCountry($con,$config);}
    if ($_POST['action'] == "ajaxlogin") {ajaxlogin($config,$lang);}
}

function ajaxlogin($config,$lang){

    $loggedin = userlogin($config,$_POST['username'], $_POST['password']);

    if(!is_array($loggedin))
    {
        echo $lang['USERNOTFOUND'];
    }
    elseif($loggedin['status'] == 2)
    {
        echo $lang['ACCOUNTBAN'];
    }
    else
    {
        $_SESSION['user']['username'] = $loggedin['username'];
        $_SESSION['user']['id'] = $loggedin['id'];
        $_SESSION['user']['email'] = $loggedin['email'];

        update_lastactive($config);

        echo "success";
    }


}
function getStateByCountryID($con,$config)
{
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $selectid = isset($_POST['selectid']) ? $_POST['selectid'] : "";

    $query = "SELECT * FROM `".$config['db']['pre']."states` WHERE country_id = " . $id;
    if ($result = $con->query($query)) {

        $list = '<option value="">Select State</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $state_id = $row['id'];
            if($selectid == $state_id){
                $selected_text = "selected";
            }
            else{
                $selected_text = "";
            }
            $list .= '<option value="'.$state_id.'" '.$selected_text.'>'.$name.'</option>';
        }

        echo $list;
    }
}

function getCityByStateID($con,$config)
{
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $selectid = isset($_POST['selectid']) ? $_POST['selectid'] : "";

    $query = "SELECT * FROM `".$config['db']['pre']."cities` WHERE state_id = " . $id;
    if ($result = $con->query($query)) {

        $list = '<option value="">Select City</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $state_id = $row['id'];
            if($selectid == $state_id){
                $selected_text = "selected";
            }
            else{
                $selected_text = "";
            }
            $list .= '<option value="'.$state_id.'" '.$selected_text.'>'.$name.'</option>';
        }
        echo $list;
    }
}

function ModelGetStateByCountryID($con,$config)
{
    $country_id = isset($_POST['id']) ? $_POST['id'] : 0;
    $countryName = get_countryName_by_id($config,$country_id);

    $query = "SELECT * FROM `".$config['db']['pre']."states` WHERE country_id = " . $country_id;
    $result = mysqli_query($con,$query);
    $total = mysqli_num_rows($result);
    $divide = intval($total/4)+1;
    $col = "";
    $list = "";
    $count = 1;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $id = $row['id'];

            if($count == 1 or $count == $col){
                $list .= '<ul class="column col-md-3 col-sm-6 cities">';
                if($count == 1)
                {
                    $list .=  '<li class="selected"><a class="selectme" data-id="'.$country_id.'" data-name="All '.$countryName.'" data-type="country"><strong>All '.$countryName.'</strong></a></li>';
                }


                $checkEnd = $count+$divide-1;
                $col = $count+$divide;
            }
            $list .= '<li class=""><a id="region'.$id.'" class="statedata" data-id="'.$id.'" data-name="'.$name.'"><span>'.$name.' <i class="fa fa-angle-right"></i></span></a></li>';


            if($count == $checkEnd or $count == $total){
                $list .= '</ul>';
            }
            $count++;
        }

        echo $list;
    }
}

function ModelGetCityByStateID($con,$config)
{
    $state_id = isset($_POST['id']) ? $_POST['id'] : 0;
    $stateName = get_stateName_by_id($config,$state_id);

    $query = "SELECT * FROM `".$config['db']['pre']."cities` WHERE state_id = " . $state_id;
    $result = mysqli_query($con,$query);
    $total = mysqli_num_rows($result);
    $divide = intval($total/4)+1;
    $col = "";
    $list = "";
    $count = 1;
    if ($total > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $id = $row['id'];

            if($count == 1 or $count == $col){
                $list .= '<ul class="column col-md-3 col-sm-6 cities">';
                if($count == 1)
                {
                    $list .=  '<li class="selected"><a id="changeState"><strong><i class="fa fa-arrow-left"></i> Change State</strong></a></li>';
                    $list .=  '<li class="selected"><a class="selectme" data-id="'.$state_id.'" data-name="'.$stateName.', State" data-type="state"><strong>Whole '.$stateName.'</strong></a></li>';
                }


                $checkEnd = $count+$divide-1;
                $col = $count+$divide;
            }
            $list .= '<li class=""><a id="region'.$id.'" class="selectme" data-id="'.$id.'" data-name="'.$name.', City" data-type="city"><span>'.$name.' <i class="fa fa-angle-right"></i></span></a></li>';


            if($count == $checkEnd or $count == $total){
                $list .= '</ul>';
            }
            $count++;
        }

        echo $list;
    }
    else{
        echo '<ul class="column col-md-3 col-sm-6 cities"><li class="selected"><a id="changeState"><strong><i class="fa fa-arrow-left"></i> Change State</strong></a></li><li><a> No city available</a></li></ul>';
    }
}

function searchStateCountry($con,$config)
{
    $dataString = isset($_POST['dataString']) ? $_POST['dataString'] : "";
    $sortname = check_user_country($config);
    $query = "SELECT c.id, c.name, c.state_id, s.name AS statename
FROM `".$config['db']['pre']."cities` AS c
INNER JOIN `".$config['db']['pre']."states` AS s ON s.id = c.state_id
INNER JOIN `".$config['db']['pre']."countries` AS a ON a.id = s.country_id
 WHERE c.name like '%$dataString%' and a.sortname = '$sortname' LIMIT 20";

    $result = mysqli_query($con,$query);
    $total = mysqli_num_rows($result);
    $list = '<ul class="searchResgeo">';
    if ($total > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cityid = $row['id'];
            $cityname = $row['name'];
            $stateid = $row['state_id'];
            $statename = $row['statename'];

            $list .= '<li><a href="#" class="title selectme" data-id="'.$cityid.'" data-name="'.$cityname.'" data-type="city">'.$cityname.', <span class="color-9">'.$statename.'</span></a></li>';
        }
        $list .= '</ul>';
        echo $list;
    }
    else{
        echo '<ul class="searchResgeo"><li><span class="noresult">No results found</span></li>';
    }
}

function searchCityStateCountry($con,$config)
{
    $dataString = isset($_POST['dataString']) ? $_POST['dataString'] : "";
    $sortname = check_user_country($config);
    $query = "SELECT c.id, c.name, c.state_id, s.country_id, s.name AS statename
FROM `".$config['db']['pre']."cities` AS c
INNER JOIN `".$config['db']['pre']."states` AS s ON s.id = c.state_id
INNER JOIN `".$config['db']['pre']."countries` AS a ON a.id = s.country_id
 WHERE c.name like '%$dataString%' and a.sortname = '$sortname' LIMIT 20";

    $result = mysqli_query($con,$query);
    $total = mysqli_num_rows($result);
    $list = '<ul class="searchResgeo">';
    if ($total > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cityid = $row['id'];
            $cityname = $row['name'];
            $stateid = $row['state_id'];
            $countryid = $row['country_id'];
            $statename = $row['statename'];

            $list .= '<li><a href="#" class="title selectme" data-cityid="'.$cityid.'" data-stateid="'.$stateid.'"data-countryid="'.$countryid.'" data-name="'.$cityname.', '.$statename.'">'.$cityname.', <span class="color-9">'.$statename.'</span></a></li>';
        }
        $list .= '</ul>';
        echo $list;
    }
    else{
        echo '<ul class="searchResgeo"><li><span class="noresult">No results found</span></li>';
    }
}

function hideItem($con,$config)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        $query = "SELECT status FROM ".$config['db']['pre']."product WHERE id='" . $id . "' LIMIT 1";
        $query_result = mysqli_query($con, $query);
        $info = mysqli_fetch_assoc($query_result);
        $status = $info['status'];
        if($status != "hide"){
            $con->query("UPDATE `".$config['db']['pre']."product` set status='hide' WHERE `id` = '".$id."' and `user_id` = '".$_SESSION['user']['id']."' ");
            echo 1;
        }else{
            $con->query("UPDATE `".$config['db']['pre']."product` set status='active' WHERE `id` = '".$id."' and `user_id` = '".$_SESSION['user']['id']."' ");
            echo 2;
        }

        die();
    } else {
        echo 0;
        die();
    }

}

function removeAdImg($con,$config){
    $id = $_POST['id'];
    $img = $_POST['img'];


    $sql = "SELECT screen_shot FROM `".$config['db']['pre']."product` WHERE `id` = '" . $id . "' LIMIT 1";
    if ($result = $con->query($sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $screen = "";
            $uploaddir =  "storage/products/screenshot/";
            $screen_sm = explode(',',$row['screen_shot']);
            $count = 0;
            foreach ($screen_sm as $value)
            {
                $value = trim($value);

                if($value == $img){
                    //Delete Image From Storage ----
                    $filename1 = $uploaddir.$value;
                    if(file_exists($filename1)){
                        $filename1 = $uploaddir.$value;
                        $filename2 = $uploaddir."small_".$value;
                        unlink($filename1);
                        unlink($filename2);
                    }
                }
                else{
                    if($count == 0){
                        $screen .= $value;
                    }else{
                        $screen .= ",".$value;
                    }
                    $count++;
                }
            }
        }
        $sql2 = "UPDATE `".$config['db']['pre']."product` set screen_shot='".$screen."' WHERE `id` = '" . $id . "' LIMIT 1";
        mysqli_query($con,$sql2);

        echo 1;
        die();
    }
    else{
        echo 0;
        die();
    }





}

function setFavAd($con,$config)
{
    $dupesql = "SELECT 1 FROM `".$config['db']['pre']."favads` where (user_id = '".$_POST['userId']."' and product_id = '".$_POST['id']."') limit 1";

    $duperaw = $con->query($dupesql);

    if (mysqli_num_rows($duperaw) == 0) {
        $sql = "INSERT INTO `".$config['db']['pre']."favads` set user_id = '".$_POST['userId']."', product_id = '".$_POST['id']."'";
        $result = $con->query($sql);
        if ($result)
            echo 1;
        else
            echo 0;
    }
    else{
        $sql = "DELETE FROM `".$config['db']['pre']."favads` WHERE `user_id` = '" . $_POST['userId'] . "' AND `product_id` ='" . $_POST['id'] . "'";
        $result = $con->query($sql);
        if ($result)
            echo 2;
        else
            echo 0;
    }
    die();
}

function removeFavAd($con,$config)
{
    $sql = "DELETE FROM `".$config['db']['pre']."favads` WHERE `user_id` = '" . $_POST['userId'] . "' AND `product_id` ='" . $_POST['id'] . "'";
    $result = $con->query($sql);
    if ($result)
        echo 1;
    else
        echo 0;

    die();
}

function deleteMyAd($con,$config)
{
    if(isset($_POST['id']))
    {
        $sql2 = "SELECT screen_shot FROM `".$config['db']['pre']."product` WHERE `id` = '" . $_POST['id'] . "' AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";

        if ($result = $con->query($sql2)) {
            $row = mysqli_fetch_assoc($result);

            $uploaddir =  "storage/products/screenshot/";
            $screen_sm = explode(',',$row['screen_shot']);
            foreach ($screen_sm as $value)
            {
                $value = trim($value);
                //Delete Image From Storage ----
                $filename1 = $uploaddir.$value;
                if(file_exists($filename1)){
                    $filename1 = $uploaddir.$value;
                    $filename2 = $uploaddir."small_".$value;
                    unlink($filename1);
                    unlink($filename2);
                }
            }

            $sql = "DELETE FROM `".$config['db']['pre']."product` WHERE `id` = '" . $_POST['id'] . "' AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
            mysqli_query($con,$sql);
        }

        echo 1;
        die();
    }else {
        echo 0;
        die();
    }

}

function deleteResumitAd($con,$config)
{
    if(isset($_POST['id']))
    {
        $sql = "SELECT screen_shot FROM `".$config['db']['pre']."product` WHERE `id` = '" . $_POST['id'] . "' AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";

        $sql2 = "SELECT screen_shot FROM `".$config['db']['pre']."product_resubmit` WHERE `id` = '" . $_POST['id'] . "' AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";


        if ($result = $con->query($sql)) {
            $row = mysqli_fetch_assoc($result);

            $result2 = $con->query($sql2);
            $row2 = mysqli_fetch_assoc($result2);

            $uploaddir =  "storage/products/screenshot/";
            $screen_sm = explode(',',$row['screen_shot']);
            $re_screen = explode(',',$row2['screen_shot']);

            $arr = array_diff($re_screen,$screen_sm);

            foreach ($arr as $value)
            {
                $value = trim($value);

                //Delete Image From Storage ----
                $filename1 = $uploaddir.$value;
                if(file_exists($filename1)){
                    $filename1 = $uploaddir.$value;
                    $filename2 = $uploaddir."small_".$value;
                    unlink($filename1);
                    unlink($filename2);
                }
            }

            $sql = "DELETE FROM `".$config['db']['pre']."product_resubmit` WHERE `product_id` = '" . $_POST['id'] . "' AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1";
            mysqli_query($con,$sql);
        }

        echo 1;
        die();
    }else {
        echo 0;
        die();
    }

}

function getsubcatbyid($con,$config)
{
    $id = isset($_POST['catid']) ? $_POST['catid'] : 0;
    $selectid = isset($_POST['selectid']) ? $_POST['selectid'] : "";

    $query = "SELECT * FROM `" . $config['db']['pre'] . "catagory_sub` WHERE main_cat_id = " . $id;
    if ($result = $con->query($query)) {

        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['sub_cat_name'];
            $sub_id = $row['sub_cat_id'];
            if($selectid == $sub_id){
                $selected_text = "selected";
            }
            else{
                $selected_text = "";
            }
            echo '<option value="'.$sub_id.'" '.$selected_text.'>'.$name.'</option>';
        }


    }
}

function getsubcatbyidList($con,$config)
{
    $id = isset($_POST['catid']) ? $_POST['catid'] : 0;
    $selectid = isset($_POST['selectid']) ? $_POST['selectid'] : "";

    $query = "SELECT * FROM `" . $config['db']['pre'] . "catagory_sub` WHERE main_cat_id = " . $id;
    if ($result = $con->query($query)) {

        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['sub_cat_name'];
            $sub_id = $row['sub_cat_id'];
            if($selectid == $sub_id){
                $selected_text = "link-active";
            }
            else{
                $selected_text = "";
            }
            echo '<li data-ajax-subcatid="'.$sub_id.'" class="'.$selected_text.'"><a href="javascript:void(0)">'.$name.'</a></li>';
        }


    }
}


function getlocHomemap($con,$config)
{
    $appr = 'active';

    if(isset($_GET['serachStr'])){
        $serachStr = $_GET['serachStr'];
    }
    else{
        $serachStr = '';
    }
    /*if(isset($_GET['location'])){
        $location = $_GET['location'];
    }
    else{
        $location = '';
    }*/
    if(isset($_GET['country'])){
        $country = $_GET['country'];
    }
    else{
        $country = '';
    }
    if(isset($_GET['state'])){
        $state = $_GET['state'];
    }
    else{
        $state = '';
    }
    if(!empty($_GET['city'])){
        $city = $_GET['city'];
    }
    else{
        if(!empty($_GET['locality'])){
            $city = $_GET['locality'];
        }else{
            $city = '';
        }
    }
    if(isset($_GET['searchBox'])){
        $searchBox = $_GET['searchBox'];
    }
    else{
        $searchBox = '';
    }

    if(isset($_GET['catid'])){
        $catid = $_GET['catid'];
    }
    else{
        $catid = '';
    }


    $where = "";



    if ($city != '') {

        if ($serachStr != '') {
            $where .= "AND p.product_name LIKE '%$serachStr%'";
        }

        if ($searchBox != '') {
            $where .= " AND p.category = '$searchBox' ";
        }

        if ($catid != '') {
            $where .= " AND p.sub_category = '$catid' ";
        }

        $query = "SELECT p.*,c.name AS cityname, s.name AS statename, a.name AS countryname
        FROM `".$config['db']['pre']."countries` AS a
        INNER JOIN `".$config['db']['pre']."states` AS s ON s.country_id = a.id
        INNER JOIN `".$config['db']['pre']."cities` AS c ON c.state_id = s.id
        INNER JOIN `".$config['db']['pre']."product` AS p ON p.city = c.id Where c.name = '$city' and p.status = 'active' $where";
    }
    else{

        if ($serachStr != '') {
            $where .= "AND product_name LIKE '%$serachStr%'";
        }

        if ($searchBox != '') {
            $where .= " AND category = '$searchBox' ";
        }

        if ($catid != '') {
            $where .= " AND sub_category = '$catid' ";
        }

        $query = "SELECT * FROM `".$config['db']['pre']."product`  WHERE `status` = '$appr' $where ";
    }

    $query_result = mysqli_query ($con, $query);

    $data = array();
    $i = 0;
    if ($query_result->num_rows > 0) {

        while ($row = mysqli_fetch_array($query_result))
            $results[] = $row;

        foreach($results as $result){
            $id = $result['id'];
            $featured = $result['featured'];
            $urgent = $result['urgent'];
            $highlight = $result['highlight'];
            $title = $result['product_name'];
            $cat = $result['category'];
            $price = $result['price'];
            $pics = $result['screen_shot'];
            $location = $result['location'];
            $latlong = $result['latlong'];
            $desc = $result['description'];
            $url = $config['site_url'].$id;

            $caticonquery = "SELECT * FROM `".$config['db']['pre']."catagory_main`  WHERE `cat_id` = '$cat' LIMIT 1";
            $caticonres = mysqli_query ($con, $caticonquery);
            $fetch = mysqli_fetch_array($caticonres);
            $catIcon = $fetch['icon'];
            $catname = $fetch['cat_name'];

            $map = explode(',', $latlong);
            $lat = $map[0];
            $long = $map[1];

            $p = explode(',', $pics);
            $pic = $p[0];
            $pic = 'storage/products/screenshot/'.$pic;

            $data[$i]['id'] = $id;
            $data[$i]['latitude'] = $lat;
            $data[$i]['longitude'] = $long;
            $data[$i]['featured'] = $featured;
            $data[$i]['title'] = $title;
            $data[$i]['location'] = $location;
            $data[$i]['category'] = $catname;
            $data[$i]['cat_icon'] = $catIcon;
            $data[$i]['marker_image'] = $pic;
            $data[$i]['url'] = $url;
            $data[$i]['description'] = $desc;


            $i++;
        }
        echo json_encode($data);
    } else {
        echo '0';
    }
    die();
}

function openlocatoionPopup($con,$config)
{
    /*$query = "SELECT a.*, b.name AS cat FROM `".$config['db']['pre']."product` AS a INNER JOIN `".$config['db']['pre']."category` AS b ON a.category = b.id WHERE a.id = '" . $_POST['id'] . "' LIMIT 1";*/
    $query = "SELECT * FROM `".$config['db']['pre']."product` WHERE id = '" . $_POST['id'] . "' LIMIT 1";
    $query_result = mysqli_query ($con, $query);
    $data = array();
    $i = 0;
    if ($query_result->num_rows > 0) {
        while ($result = mysqli_fetch_array($query_result)) {
            $id = $result['id'];
            $featured = $result['featured'];
            $urgent = $result['urgent'];
            $highlight = $result['highlight'];
            $title = $result['product_name'];
            $cat = $result['category'];
            $price = $result['price'];
            $pics = $result['screen_shot'];
            $location = $result['location'];
            $latlong = $result['latlong'];
            $desc = $result['description'];
            $url = $config['site_url']."ad-detail.php?id=".$id;

            $caticonquery = "SELECT * FROM `".$config['db']['pre']."catagory_main`  WHERE `cat_id` = '$cat' LIMIT 1";
            $caticonres = mysqli_query ($con, $caticonquery);
            $fetch = mysqli_fetch_array($caticonres);
            $catIcon = $fetch['icon'];
            $catname = $fetch['cat_name'];

            $map = explode(',', $latlong);
            $lat = $map[0];
            $long = $map[1];

            $p = explode(',', $pics);
            $pic = $p[0];
            $pic = 'storage/products/screenshot/'.$pic;


            echo '<div class="item gmapAdBox" data-id="' . $id . '" style="margin-bottom: 0px;">
                    <a href="' . $url . '" style="display: block;position: relative;">
                     <div class="card small">
                        <div class="card-image waves-effect waves-block waves-light">
                          <img class="activator" src="' . $pic . '">
                        </div>
                        <div class="card-content">
                            <div class="label label-default">' . $catname . '</div>
                          <span class="card-title activator grey-text text-darken-4 mapgmapAdBoxTitle">' . $title . '</span>
                          <p class="mapgmapAdBoxLocation">' . $location . '</p>
                        </div>
                      </div>

                    </a>
                </div>';

        }
    } else {
        echo false;
    }
    die();
}
?>