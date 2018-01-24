<?php
/*
Copyright (c) 2015 Devendra Katariya (bylancer.com)
*/
require_once('includes/config.php');
session_start();
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.sqlquery.php');
require_once('includes/functions/func.users.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');
if($config['mod_rewrite'] == 0)
    require_once('includes/simple-url.php');
else
    require_once('includes/seo-url.php');
$con = db_connect($config);
error_reporting(0);
//Admin Ajax Function
if(isset($_GET['action'])){
    if ($_GET['action'] == "installPayment") { installPayment($con,$config); }
    if ($_GET['action'] == "uninstallPayment") { uninstallPayment($con,$config); }
    if ($_GET['action'] == "installCountry") { installCountry($con,$config); }
    if ($_GET['action'] == "uninstallCountry") { uninstallCountry($con,$config); }
    if ($_GET['action'] == "deleteCity") { deleteCity($con,$config); }
    if ($_GET['action'] == "deleteState") { deleteState($con,$config); }
    if ($_GET['action'] == "deleteCountry") { deleteCountry($con,$config); }
    if ($_GET['action'] == "addCity") { addCity($con,$config); }
    if ($_GET['action'] == "addState") { addState($con,$config); }
    if ($_GET['action'] == "addCountry") { addCountry($con,$config); }
    if ($_GET['action'] == "editCity") { editCity($con,$config); }
    if ($_GET['action'] == "editState") { editState($con,$config); }
    if ($_GET['action'] == "editCountry") { editCountry($con,$config); }
    if ($_GET['action'] == "deleteStaticPage") { deleteStaticPage($con,$config); }
    if ($_GET['action'] == "deletefaq") { deletefaq($con,$config); }
    if ($_GET['action'] == "delcoustomfield") { delcoustomfield($con,$config); }
    if ($_GET['action'] == "approveitem") { approveitem($con,$config,$lang,$link); }
    if ($_GET['action'] == "approveResubmitItem") { approveResubmitItem($con,$config,$lang,$link); }
    if ($_GET['action'] == "activeuser") { activeuser($con,$config); }
    if ($_GET['action'] == "banuser") { banuser($con,$config); }
    if ($_GET['action'] == "deleteusers") { deleteusers($con,$config); }
    if ($_GET['action'] == "deleteadmin") { deleteadmin($con,$config); }
    if ($_GET['action'] == "deleteMessage") { deleteMessage($con,$config); }
    if ($_GET['action'] == "deleteads") { deleteads($con,$config); }
    if ($_GET['action'] == "deleteResubmitItem") { deleteResubmitItem($con,$config); }
    if ($_GET['action'] == "deleteTransaction") { deleteTransaction($con,$config); }

    if ($_GET['action'] == "addNewCat") { addNewCat($con,$config); }
    if ($_GET['action'] == "editCat") { editCat($con,$config); }
    if ($_GET['action'] == "deleteCat") { deleteCat($con,$config); }
    if ($_GET['action'] == "addSubCat") { addSubCat($con,$config); }
    if ($_GET['action'] == "editSubCat") { editSubCat($con,$config); }
    if ($_GET['action'] == "delSubCat") { delSubCat($con,$config); }
    if ($_GET['action'] == "getSubCat") { getSubCat($con,$config); }

    if ($_GET['action'] == "openlocatoionPopup") { openlocatoionPopup($con,$config); }
    if ($_GET['action'] == "getlocHomemap") { getlocHomemap($con,$config); }
}


if(isset($_POST['action'])){
    if ($_POST['action'] == "getsubcatbyid") {getsubcatbyid($con,$config);}
    if ($_POST['action'] == "getStateByCountryID") {getStateByCountryID($con,$config);}
    if ($_POST['action'] == "getCityByStateID") {getCityByStateID($con,$config);}
}


function check_allow()
{
    if($_SESSION['admin']['id'] == 1)
    {
        return TRUE;
    }
    else
    {
        return TRUE;
    }
}

function installPayment($con,$config)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        if(check_allow())
            $con->query("UPDATE `".$config['db']['pre']."payments` set payment_install='1' WHERE `payment_id` = '" . $id . "'");
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function uninstallPayment($con,$config)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        if(check_allow())
            $con->query("UPDATE `".$config['db']['pre']."payments` set payment_install='0' WHERE `payment_id` = '" . $id . "'");
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function installCountry($con,$config)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        if(check_allow())
            $con->query("UPDATE `".$config['db']['pre']."countries` set install='1' WHERE `id` = '" . $id . "'");
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function uninstallCountry($con,$config)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        if(check_allow())
            $con->query("UPDATE `".$config['db']['pre']."countries` set install='0' WHERE `id` = '" . $id . "'");
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function delete_ad_by_id($con,$config,$product_id){
    if(check_allow()){
        $qry1 = "DELETE FROM `".$config['db']['pre']."product` WHERE id = '$product_id' LIMIT 1";
        $qry2 = "SELECT screen_shot FROM `".$config['db']['pre']."product` WHERE id = '$product_id' LIMIT 1";

        if ($res = $con->query($qry2)) {
            while ($fetch = mysqli_fetch_assoc($res)) {

                $uploaddir =  "storage/products/screenshot/";
                $screen_sm = explode(',',$fetch['screen_shot']);
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
            }
        }
        mysqli_query($con,$qry1);
        return true;
    }
    else{
        return false;
    }
}

function delete_resubmitad_by_id($con,$config,$product_id){
    if(check_allow()){
        $reqry1 = "DELETE FROM `".$config['db']['pre']."product_resubmit` WHERE product_id = '$product_id' LIMIT 1";
        $reqry2 = "SELECT screen_shot FROM `".$config['db']['pre']."product_resubmit` WHERE product_id = '$product_id' LIMIT 1";

        if ($res = $con->query($reqry2)) {
            while ($fetch = mysqli_fetch_assoc($res)) {

                $uploaddir =  "storage/products/screenshot/";
                $screen_sm = explode(',',$fetch['screen_shot']);
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
            }
        }

        mysqli_query($con,$reqry1);
        return true;
    }
    else{
        return false;
    }
}

function deleteCity($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."cities` ";
        $sql2 = "SELECT id from `".$config['db']['pre']."product` ";
        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `id` = '" . $value . "'";
                $sql2.= "WHERE `city` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `id` = '" . $value . "'";
                $sql2.= " OR `city` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow()){
            $result = mysqli_query($con,$sql2);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){
                    $product_id = $row['id'];
                    delete_ad_by_id($con,$config,$product_id);
                    delete_resubmitad_by_id($con,$config,$product_id);
                }
            }
            mysqli_query($con,$sql);
        }
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deleteState($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."states` ";
        $sql2 = "SELECT id from `".$config['db']['pre']."product` ";
        $sql3 = "DELETE FROM `".$config['db']['pre']."cities` ";
        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `id` = '" . $value . "'";
                $sql2.= "WHERE `state` = '" . $value . "'";
                $sql3.= "WHERE `state_id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `id` = '" . $value . "'";
                $sql2.= " OR `state` = '" . $value . "'";
                $sql3.= " OR `state_id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow()){
            $result = mysqli_query($con,$sql2);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){
                    $product_id = $row['id'];
                    delete_ad_by_id($con,$config,$product_id);
                    delete_resubmitad_by_id($con,$config,$product_id);
                }
            }
            mysqli_query($con,$sql);
            mysqli_query($con,$sql3);
        }
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deleteCountry($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."countries` ";
        $sql2 = "SELECT id from `".$config['db']['pre']."product` ";
        $sql3 = "DELETE FROM `".$config['db']['pre']."states` ";
        $sql4 = "SELECT id FROM `".$config['db']['pre']."states` ";
        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `id` = '" . $value . "'";
                $sql2.= "WHERE `country` = '" . $value . "'";
                $sql3.= "WHERE `country_id` = '" . $value . "'";
                $sql4.= "WHERE `country_id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `id` = '" . $value . "'";
                $sql2.= " OR `country` = '" . $value . "'";
                $sql3.= " OR `country_id` = '" . $value . "'";
                $sql4.= " OR `country_id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow()){
            $r = mysqli_query($con,$sql4);
            if(mysqli_num_rows($r) > 0) {
                while ($fch = mysqli_fetch_array($r)) {
                    $id =  $fch['id'];
                    mysqli_query($con,"DELETE FROM `".$config['db']['pre']."cities` WHERE state_id = '$id'");
                }
            }

            $result = mysqli_query($con,$sql2);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){
                    $product_id = $row['id'];
                    delete_ad_by_id($con,$config,$product_id);
                    delete_resubmitad_by_id($con,$config,$product_id);
                }
            }
            mysqli_query($con,$sql);
            mysqli_query($con,$sql3);
            mysqli_query($con,$sql4);
        }
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function addCity($con,$config)
{
    $name = $_POST['name'];
    $popular = $_POST['popular'];
    $state_id = isset($_POST['state_id'])? $_POST['state_id']: 0;

    if (trim($name) != '' && is_string($name)) {

        $query = "Insert into `".$config['db']['pre']."cities` set name='" . $name . "',state_id='" . $state_id . "',popular='" . $popular . "'";
        if(check_allow()){
            $con->query($query);
            echo "success";
        }
        else {
            echo "Admin User Permission Denied.";
        }
        die();
    } else {
        echo "Error in add State.";
        die();
    }
}

function addState($con,$config)
{
    $name = $_POST['name'];
    $country_id = isset($_POST['country_id'])? $_POST['country_id']: 0;

    if (trim($name) != '' && is_string($name)) {

        $query = "Insert into `".$config['db']['pre']."states` set name='" . $name . "',country_id='" . $country_id . "'";
        if(check_allow()){
            $con->query($query);
            echo "success";
        }
        else {
            echo "Admin User Permission Denied.";
        }
        die();
    } else {
        echo "Error in add State.";
        die();
    }
}

function addCountry($con,$config)
{
    $countryname = $_POST['countryname'];
    $sortname = $_POST['sortname'];
    $phonecode = $_POST['phonecode'];

    if (trim($countryname) != '' && is_string($countryname)) {

        $query = "Insert into `".$config['db']['pre']."countries` set name='" . $countryname . "',sortname='" . $sortname . "',phonecode='" . $phonecode . "'";
        if(check_allow()){
            $con->query($query);
            echo "success";
        }
        else {
            echo "Admin User Permission Denied.";
        }
        die();
    } else {
        echo "Error in add country.";
        die();
    }
}

function editCity($con,$config)
{
    $name = $_POST['name'];
    $popular = $_POST['popular'];
    $id = $_POST['id'];

    if (trim($name) != '' && is_string($name)) {

        $query = "UPDATE `".$config['db']['pre']."cities` set name='" . $name . "',popular='" . $popular . "' WHERE id = '$id'";
        if(check_allow()){
            $con->query($query);
            echo "success";
        }
        else {
            echo "Admin User Permission Denied.";
        }
        die();
    } else {
        echo "Error in add State.";
        die();
    }
}

function editState($con,$config)
{
    $name = $_POST['name'];
    $id = $_POST['id'];

    if (trim($name) != '' && is_string($name)) {

        $query = "UPDATE `".$config['db']['pre']."states` set name='" . $name . "' WHERE id = '$id'";
        if(check_allow()){
            $con->query($query);
            echo "success";
        }
        else {
            echo "Admin User Permission Denied.";
        }
        die();
    } else {
        echo "Error in add State.";
        die();
    }
}

function editCountry($con,$config)
{
    $id = $_POST['id'];
    $countryname = $_POST['countryname'];
    $sortname = $_POST['sortname'];
    $phonecode = $_POST['phonecode'];

    if (isset($_POST['id'])) {

        $query = "UPDATE `".$config['db']['pre']."countries` set name='" . $countryname . "',sortname='" . $sortname . "',phonecode='" . $phonecode . "' WHERE id = '$id'";
        if(check_allow()){
            $con->query($query);
            echo "success";
        }
        else {
            echo "Admin User Permission Denied.";
        }
        die();
    } else {
        echo "Error in add country.";
        die();
    }
}

function deleteStaticPage($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."html` ";

        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `html_id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `html_id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow())
            mysqli_query($con,$sql);

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deletefaq($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."faq_entries` ";

        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `faq_id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `faq_id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow())
            mysqli_query($con,$sql);

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function delcoustomfield($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."custom_fields` ";

        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `custom_id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `custom_id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);
        if(check_allow()) {
            mysqli_query($con, $sql);
        }

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function approveResubmitItem($con,$config,$lang,$link)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        if(check_allow()) {
            $sql = "SELECT * FROM `" . $config['db']['pre'] . "product_resubmit` WHERE `product_id` = '" . $_POST['id'] . "' LIMIT 1";
            $sql2 = "SELECT screen_shot FROM `" . $config['db']['pre'] . "product` WHERE `id` = '" . $_POST['id'] . "' LIMIT 1";
            $result = $con->query($sql);
            $info = mysqli_fetch_assoc($result);

            $result2 = $con->query($sql2);
            $info2 = mysqli_fetch_assoc($result2);

            $a1 = explode(',', $info2['screen_shot']);
            $a2 = explode(',', $info['screen_shot']);
            $arr = array_diff($a1, $a2);
            $uploaddir =  "storage/products/screenshot/";
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

            $sql3 = "UPDATE " . $config['db']['pre'] . "product set
                    user_id         = '" . $info['user_id'] . "',
                    product_name    = '" . $info['product_name'] . "',
                    category        = '" . $info['category'] . "',
                    sub_category    = '" . $info['sub_category'] . "',
                    description     = '" . $info['description'] . "',
                    price           = '" . $info['price'] . "',
                    negotiable      = '" . $info['negotiable'] . "',
                    phone           = '" . $info['phone'] . "',
                    hide_phone      = '" . $info['hide_phone'] . "',
                    location        = '" . $info['location'] . "',
                    city            = '" . $info['city'] . "',
                    state           = '" . $info['state'] . "',
                    country         = '" . $info['country'] . "',
                    latlong         = '" . $info['latlong'] . "',
                    screen_shot     = '" . $info['screen_shot'] . "',
                    tag             = '" . $info['tag'] . "',
                    custom_fields   = '" . $info['custom_fields'] . "',
                    custom_types    = '" . $info['custom_types'] . "',
                    custom_values   = '" . $info['custom_values'] . "',
                    created_at      = '" . $info['created_at'] . "',
                    contact_phone = '" . $info['contact_phone'] . "',
                    contact_email = '" . $info['contact_email'] . "',
                    contact_chat = '" . $info['contact_chat'] . "'
                    WHERE id = '" . $info['product_id'] . "'
                    ";


            $con->query($sql3);

            $con->query("DELETE FROM `" . $config['db']['pre'] . "product_resubmit` WHERE `product_id` = '" . $_POST['id'] . "' LIMIT 1");

            //Resubmission approve Email to seller

            $item_title = $info['product_name'];

            $item_author_id = $info['user_id'];

            $info2 = get_user_data($config,null,$item_author_id);
            $item_author_email = $info2['email'];
            if(!empty($item_author_email)){
                $ad_link = $config['site_url']."ad/".$id;
                $page = new HtmlTemplate ($config['site_url']."templates/" . $config['tpl_name'] . "/email_resubmission_approve.html");
                $page->SetParameter ('SITE_TITLE', $config['site_title']);
                $page->SetParameter ('ADTITLE', $item_title);
                $page->SetParameter ('ADLINK', $ad_link);
                $email_body = $page->CreatePageReturn($lang,$config,$link);

                email($item_author_email,"Your ad Re-Submission has been approved",$email_body,$config);
            }
        }
        echo 1;
        die();

    }
    else {
        echo 0;
        die();
    }

}

function approveitem($con,$config,$lang,$link)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        if(check_allow()){
            $con->query("UPDATE `".$config['db']['pre']."product` set status='active' WHERE `id` = '".$id."'");

            $query = "SELECT product_name,user_id from `".$config['db']['pre']."product` WHERE `id` = '".$id."' LIMIT 1";
            $result = mysqli_query($con, $query);
            if (mysqli_num_rows($result) > 0) {
                $info = mysqli_fetch_assoc($result);
                $item_title = $info['product_name'];

                $item_author_id = $info['user_id'];

                $info2 = get_user_data($config,null,$item_author_id);
                $item_author_email = $info2['email'];
                if(!empty($item_author_email)){
                    $ad_link = $config['site_url']."ad/".$id;
                    $page = new HtmlTemplate ($config['site_url']."templates/" . $config['tpl_name'] . "/email_ads_approve.html");
                    $page->SetParameter ('SITE_TITLE', $config['site_title']);
                    $page->SetParameter ('ADTITLE', $item_title);
                    $page->SetParameter ('ADLINK', $ad_link);
                    $email_body = $page->CreatePageReturn($lang,$config,$link);

                    email($item_author_email,"Your ad has been approved",$email_body,$config);
                }
            }
        }

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }



}

function activeuser($con,$config)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        if(check_allow())
            $con->query("UPDATE `".$config['db']['pre']."user` set status='0' WHERE `id` = '" . $id . "'");
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function banuser($con,$config)
{
    $id = $_POST['id'];
    if (trim($id) != '') {
        if(check_allow())
            $con->query("UPDATE `".$config['db']['pre']."user` set status='2' WHERE `id` = '" . $id . "'");
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deleteusers($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."user` ";

        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow())
            mysqli_query($con,$sql);

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deleteadmin($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."admins` ";

        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow())
            mysqli_query($con,$sql);

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deleteMessage($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."messages` ";

        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `message_id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `message_id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow())
            mysqli_query($con,$sql);

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deleteads($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."product` ";
        $sql2 = "SELECT screen_shot FROM `".$config['db']['pre']."product` ";
        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `id` = '" . $value . "'";
                $sql2.= "WHERE `id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `id` = '" . $value . "'";
                $sql2.= " OR `id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);
        $sql2.= " LIMIT " . count($_POST['list']);

        if(check_allow()){
            if ($result = $con->query($sql2)) {
                while ($row = mysqli_fetch_assoc($result)) {

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
                }
            }

            mysqli_query($con,$sql);
        }

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deleteResubmitItem($con,$config)
{
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."product_resubmit` ";
        $sql2 = "SELECT screen_shot FROM `".$config['db']['pre']."product_resubmit` ";
        $sql3 = "SELECT screen_shot FROM `".$config['db']['pre']."product` ";
        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `product_id` = '" . $value . "'";
                $sql2.= "WHERE `product_id` = '" . $value . "'";
                $sql3.= "WHERE `id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `product_id` = '" . $value . "'";
                $sql2.= " OR `product_id` = '" . $value . "'";
                $sql3.= " OR `id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);
        $sql2.= " LIMIT " . count($_POST['list']);
        $sql3.= " LIMIT " . count($_POST['list']);

        if(check_allow()){
            if ($result = $con->query($sql2)) {
                while ($row = mysqli_fetch_assoc($result)) {


                    $result3 = $con->query($sql3);
                    $row3 = mysqli_fetch_assoc($result3);

                    $uploaddir =  "storage/products/screenshot/";
                    $screen_sm = explode(',',$row['screen_shot']);
                    $re_screen = explode(',',$row3['screen_shot']);
                    $arr = array_diff($screen_sm,$re_screen);

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
                }
            }

            mysqli_query($con,$sql);
        }

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}

function deleteTransaction($con,$config)
{
    echo $_POST['id'];
    if(isset($_POST['id']))
    {
        $_POST['list'][] = $_POST['id'];
    }

    if (is_array($_POST['list'])) {

        $count = 0;
        $sql = "DELETE FROM `".$config['db']['pre']."transaction` ";

        foreach ($_POST['list'] as $value)
        {
            if($count == 0)
            {
                $sql.= "WHERE `id` = '" . $value . "'";
            }
            else
            {
                $sql.= " OR `id` = '" . $value . "'";
            }

            $count++;
        }
        $sql.= " LIMIT " . count($_POST['list']);

        if(check_allow())
            mysqli_query($con,$sql);

        echo 1;
        die();
    } else {
        echo 0;
        die();
    }

}
/**********************
 * @param $con
 * @param $config
 * Manage Categories  add/edit//delete function
 */

function addNewCat($con,$config)
{
    $name = $_POST['name'];
    $icon = $_POST['icon'];
    if (trim($name) != '' && is_string($name)) {

        $query = "Insert into `".$config['db']['pre']."catagory_main` set cat_name='" . $name . "',icon='" . $icon . "'";
        if(check_allow()){
            $con->query($query);
            $id = $con->insert_id;
        }
        else {
            $id = 1;
        }
        echo $name . ',' . $id . ',' . $icon;
        die();
    } else {
        echo 0;
        die();
    }
}

function editCat($con,$config)
{
    $name = $_POST['name'];
    $icon = $_POST['icon'];
    $id = $_POST['id'];
    if (trim($name) != '' && is_string($name) && trim($id) != '') {
        $query = "UPDATE `".$config['db']['pre']."catagory_main` SET `cat_name` = '" . $name . "',`icon` = '" . $icon . "' WHERE `cat_id` = '" . $id . "'";
        if(check_allow())
            $con->query($query);
        echo $name . ',' . $icon;
        die();
    } else {
        echo 0;
        die();
    }
}

function deleteCat($con,$config)
{
    $id = $_POST['id'];
    if (trim($id) != '') {

        if(check_allow()){
            if ($con->query("DELETE FROM `".$config['db']['pre']."catagory_main` WHERE `cat_id` = '" . $id . "'")) {
                $con->query("DELETE FROM `".$config['db']['pre']."catagory_sub` WHERE `main_cat_id` = '" . $id . "'");
                echo 1;
                die();
            } else {
                echo 0;
                die();
            }
        }
        else{
            echo 1;
        }
    } else {
        echo 0;
        die();
    }
}

function addSubCat($con,$config)
{
    $name = $_POST['name'];
    $cat_id = $_GET['mainid'];
    if (trim($name) != '' && is_string($name) && trim($cat_id) != '') {
        $query = "Insert into `".$config['db']['pre']."catagory_sub` set sub_cat_name='" . $name . "',main_cat_id='" . $cat_id . "'";
        if(check_allow()){
            $con->query($query);
            $id = $con->insert_id;
        }
        else{
            $id =1;
        }

        echo $name . ',' . $id;
        die();
    } else {
        echo 0;
        die();
    }
}

function editSubCat($con,$config)
{
    $name = $_GET['title'];
    $id = $_GET['id'];
    if (trim($name) != '' && is_string($name) && trim($id) != '') {
        $query = "UPDATE `".$config['db']['pre']."catagory_sub` SET `sub_cat_name` = '" . $name . "' WHERE `sub_cat_id` = '" . $id . "'";
        if(check_allow())
            $con->query($query);
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }
}

function delSubCat($con,$config)
{
    $subCatids = $_POST['subCatids'];
    if (is_array($subCatids)) {
        foreach ($subCatids as $subCatid) {
            if(check_allow())
                $con->query("DELETE FROM `".$config['db']['pre']."catagory_sub` WHERE `sub_cat_id` = '" . $subCatid . "'");
        }
        echo 1;
        die();
    } else {
        echo 0;
        die();
    }
}

function getSubCat($con,$config)
{
    $id = isset($_GET['category_id']) ? $_GET['category_id'] : 0;
    if ($id > 0) {
        $query = "SELECT * FROM `".$config['db']['pre']."catagory_sub` WHERE main_cat_id = " . $id;
    } else {
        $query = "SELECT * FROM `".$config['db']['pre']."catagory_sub`";
    }
    $tags = '';

    if ($result = $con->query($query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['sub_cat_name'];
            $sub_id = $row['sub_cat_id'];
            $tags .= ' <div class="panel panel-default quickad-js-collapse" data-service-id="' . $sub_id . '">
                                        <div class="panel-heading" role="tab" id="s_' . $sub_id . '">
                                            <div class="row">
                                                <div class="col-sm-8 col-xs-10">
                                                    <div class="quickad-flexbox">
                                                        <div class="quickad-flex-cell quickad-vertical-middle"
                                                             style="width: 1%">
                                                            <i class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move ui-sortable-handle"
                                                               title="Reorder"></i>
                                                        </div>
                                                        <div class="quickad-flex-cell quickad-vertical-middle">
                                                            <a role="button"
                                                               class="panel-title collapsed quickad-js-service-title"
                                                               data-toggle="collapse" data-parent="#services_list"
                                                               href="#service_' . $sub_id . '" aria-expanded="false"
                                                               aria-controls="service_' . $sub_id . '">
                                                                '.$name.' </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-xs-2">
                                                    <div class="quickad-flexbox">
                                                        <div class="quickad-flex-cell quickad-vertical-middle text-right"
                                                             style="width: 10%">
                                                            <div class="checkbox checkbox-success">
                                                                <input id="checkbox'.$sub_id.'" type="checkbox" class="service-checker" value="'.$sub_id.'">
                                                                <label for="checkbox'.$sub_id.'"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="service_' . $sub_id . '" class="panel-collapse collapse" role="tabpanel"
                                             style="height: 0">
                                            <div class="panel-body">
                                                <form method="post" id="' . $sub_id . '">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="title_' . $sub_id . '">Title</label>
                                                                <input name="title" value="'.$name.'" id="title_' . $sub_id . '"
                                                                       class="form-control" type="text">
                <input name="id" value="' . $sub_id . '" type="hidden">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="panel-footer">
                                                        <button type="button"
                                                                class="btn btn-lg btn-success ladda-button ajax-service-send"
                                                                data-style="zoom-in" data-spinner-size="40" onclick="editSubCat('.$sub_id.');"><span
                                                                class="ladda-label">Save</span></button>
                                                        <button class="btn btn-lg btn-default js-reset" type="reset">Reset
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>';

        }
        echo $tags;
        die();
    } else {
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

/**********************
 * @param $con
 * @param $config
 * Google map location function
 */

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