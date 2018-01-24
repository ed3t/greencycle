<?php
require_once('includes/config.php');
session_start();
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
require_once('includes/functions/func.sqlquery.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');
if($config['mod_rewrite'] == 0)
    require_once('includes/simple-url.php');
else
    require_once('includes/seo-url.php');

$mysqli = db_connect($config);


if(checkloggedin()) {

    $query = "SELECT * FROM `".$config['db']['pre']."payments` WHERE payment_install='1' ORDER BY  payment_id";
    $query_result = @mysqli_query ($mysqli,$query) OR error(mysqli_error($mysqli));
    while ($info = @mysqli_fetch_array($query_result))
    {
        $payment_types[$info['payment_id']]['id'] = $info['payment_id'];
        $payment_types[$info['payment_id']]['title'] = $info['payment_title'];
        $payment_types[$info['payment_id']]['folder'] = $info['payment_folder'];
    }

    $urgent_project_fee = $config['urgent_fee'];
    $featured_project_fee = $config['featured_fee'];
    $highlight_project_fee = $config['highlight_fee'];

    if(isset($_POST["submit"])) {


        $urgent = isset($_POST['urgent']) ? 1 : 0;
        $featured = isset($_POST['featured']) ? 1 : 0;
        $highlight = isset($_POST['highlight']) ? 1 : 0;

        $errors = array();
        $inputstring = $_POST['title'];
        $first_title = strtok($inputstring, " ");

        $payment_req = "";
        if(isset($_POST['urgent'])){
            if(!isset($_POST['payment_id'])){
                $payment_req = $lang['PAYMENT_METHOD_REQ'];
            }
        }
        if(isset($_POST['featured'])){
            if(!isset($_POST['payment_id'])){
                $payment_req = $lang['PAYMENT_METHOD_REQ'];
            }
        }
        if(isset($_POST['highlight'])){
            if(!isset($_POST['payment_id'])){
                $payment_req = $lang['PAYMENT_METHOD_REQ'];
            }
        }

        if(!empty($payment_req))
            $errors[]['message'] = $payment_req;

        if(empty($_POST['title'])) {
            $errors[]['message'] = $lang['ADTITLE_REQ'];
        }
        if(empty($_POST['subcatid']) or empty($_POST['catid'])) {
            $errors[]['message'] = $lang['CAT_REQ'];
        }
        if(empty($_POST['content'])) {
            $errors[]['message'] = $lang['DESC_REQ'];
        }
        if(empty($_POST['tags'])) {
            $errors[]['message'] = $lang['TAG_REQ'];
        }
        if(empty($_POST['country'])) {
            $errors[]['message'] = "Please select your country.";
        }
        if(empty($_POST['city'])) {
            $errors[]['message'] = $lang['CITY_REQ'] ;
        }
        if(empty($_POST['state'])) {
            $errors[]['message'] = $lang['STATE_REQ'] ;
        }
        if(!empty($_POST['price'])) {
            if (!is_numeric($_POST['price'])) {
                $errors[]['message'] = $lang['PRICE_MUST_NO'];
            }
        }

        if(isset($_POST['subcatid'])){
            $custom_fields = get_customFields_by_catid($config,$mysqli,$_POST['catid'],$_POST['subcatid']);

            foreach($custom_fields as $key=>$value)
            {
                if($value['userent'])
                {
                    $custom_db_fields[$value['id']] = $value['title'];
                    $custom_db_data[$value['id']] = str_replace(',','&#44;',$value['default']);
                }
            }

            $showCustomField = (count($custom_fields) > 0) ? 1 : 0;
        }

        $location = $_POST['location'];
        if(!empty($location)){
            $mapLat     =   $_POST['latitude'];
            $mapLong    =   $_POST['longitude'];
            $latlong = $mapLat.",".$mapLong;
        }
        else{
            $errors[]['message'] = $lang['LOC_REQ'];
        }



        if(empty($_POST['agree'])) {
            $errors[]['message'] = $lang['AGREE_COPYRIGHT'];
        }

        if(isset($_FILES['item_screen']) && count($_FILES['item_screen']['error']) == 1 && $_FILES['item_screen']['error'][0] > 0){
            $errors[]['message'] = $lang['PIC_REQ'];
        }

        if(count($errors) > 0)
        {
            $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/ad-post.html');
            $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['POST-FREE-AD'],""));
            $page->SetLoop('ERRORS', $errors);
            $page->SetLoop ('PAYMENT_TYPES', $payment_types);

            if(isset($_POST["featured"])) {
                if($_POST['featured']=='1')
                    $page->SetParameter ('FEATURED', 'checked');
                else
                    $page->SetParameter ('FEATURED', '');
            }
            if(isset($_POST["urgent"])) {
                if($_POST['urgent']=='1')
                    $page->SetParameter ('URGENT', 'checked');
                else
                    $page->SetParameter ('URGENT', '');
            }
            if(isset($_POST["highlight"])) {
                if($_POST['highlight']=='1')
                    $page->SetParameter ('HIGHLIGHT', 'checked');
                else
                    $page->SetParameter ('HIGHLIGHT', '');
            }

            $contact_phone = isset($_POST['contact_phone']) ? "checked" : "";
            $contact_email = isset($_POST['contact_email']) ? "checked" : "";
            $contact_chat = isset($_POST['contact_chat']) ? "checked" : "";

            $page->SetParameter ('CONTACT_PHONE', $contact_phone);
            $page->SetParameter ('CONTACT_EMAIL', $contact_email);
            $page->SetParameter ('CONTACT_CHAT', $contact_chat);

            $maincat = get_maincat_by_id($config,$_POST['catid']);
            $maincatName = $maincat['cat_name'];
            $maincatIcon = $maincat['icon'];
            $subcat = get_subcat_by_id($config,$_POST['subcatid']);
            $subcatName = $subcat['sub_cat_name'];

            $country = isset($_POST['country']) ? $_POST['country'] : "";
            $page->SetParameter ('DEFAULT_COUNTRY', check_user_country($config));
            $page->SetParameter ('CATID', $_POST['catid']);
            $page->SetParameter ('SUBCATID', $_POST['subcatid']);
            $page->SetParameter ('CATEGORY', $maincatName);
            $page->SetParameter ('CATICON', $maincatIcon);
            $page->SetParameter ('SUBCATEGORY', $subcatName);
            $page->SetParameter ('USERNAME', $_SESSION['user']['username']);
            $page->SetParameter ('TITLE',$_POST['title']);
            $page->SetParameter ('PRICE', $_POST['price']);
            $page->SetParameter ('PHONE', $_POST['phone']);
            $page->SetParameter ('LOCATION', $_POST['location']);
            $page->SetParameter ('LATITUDE', $_POST['latitude']);
            $page->SetParameter ('LONGITUDE', $_POST['longitude']);
            $page->SetParameter ('DESCRIPTION', $_POST['content']);
            $page->SetParameter ('TAGS', $_POST['tags']);
            $page->SetParameter ('FEATURED_FEE', $featured_project_fee);
            $page->SetParameter ('HIGHLIGHT_FEE', $highlight_project_fee);
            $page->SetParameter ('URGENT_FEE', $urgent_project_fee);
            $page->SetParameter ('FEATURED', $featured);
            $page->SetParameter ('HIGHLIGHT', $highlight);
            $page->SetParameter ('URGENT', $urgent);
            $page->SetLoop ('CUSTOMFIELDS',$custom_fields);
            $page->SetParameter ('SHOWCUSTOMFIELD', $showCustomField);
            $page->SetParameter ('CITY', isset($_POST['city']) ? $_POST['city'] : "");
            $page->SetParameter ('STATE', isset($_POST['state']) ? $_POST['state'] : "");
            $page->SetParameter ('COUNTRY', $country);
            $page->SetParameter ('CITYFIELD', $_POST['cityfield']);
            $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
            $page->CreatePageEcho($lang,$config,$link);
            exit();
        }
        else{

            $uploaddir = "storage/products/screenshot/"; //Screenshot upload directory

            $valid_formats = array("jpg"); // Valid image formats

            $countScreen = 0;
            foreach ($_FILES['item_screen']['name'] as $name => $value) {
                $filename = stripslashes($_FILES['item_screen']['name'][$name]);

                $size = filesize($_FILES['item_screen']['tmp_name'][$name]);
                //Convert extension into a lower case format
                $ext = getExtension($filename);
                $ext = strtolower($ext);
                //File extension check
                if (in_array($ext, $valid_formats)) {

                    if($ext=="jpg" || $ext=="jpeg" )
                    {
                        $uploadedfile = $_FILES['item_screen']['tmp_name'][$name];
                        $src = @imagecreatefromjpeg($uploadedfile);
                    }
                    else if($ext=="png")
                    {
                        $uploadedfile = $_FILES['item_screen']['tmp_name'];
                        $src = @imagecreatefrompng($uploadedfile);
                    }
                    else
                    {
                        $src = @imagecreatefromgif($uploadedfile);
                    }


                    list($width,$height)=getimagesize($uploadedfile);

                    $newwidth=800;
                    $newheight=($height/$width)*$newwidth;
                    $tmp=imagecreatetruecolor($newwidth,$newheight);

                    $newwidth1=222;
                    $newheight1=($height/$width)*$newwidth1;
                    $tmp1=imagecreatetruecolor($newwidth1,$newheight1);

                    @imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
                    @imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);

                    $random1 = rand(9999, 100000);
                    $random2 = rand(9999, 200000);
                    $image_name =  $first_title . '_' . $random1 . $random2 . '.' . $ext;
                    $image_name1 = 'small_' .$first_title . '_' . $random1 . $random2 . '.' . $ext;

                    //$newname = $uploaddir . $image_name;

                    $filename = $uploaddir . $image_name;
                    $filename1 = $uploaddir . $image_name1;
                    $pic_name = $image_name1;

                    @imagejpeg($tmp,$filename,100);
                    @imagejpeg($tmp1,$filename1,100);

                    @imagedestroy($src);
                    @imagedestroy($tmp);
                    @imagedestroy($tmp1);

                    //Moving file to uploads folder
                    if (!move_uploaded_file($_FILES['item_screen']['tmp_name'][$name], $filename)) {
                        $errors[]['message'] = $lang['ERROR_UPLOAD_IMG'];
                    }
                    if ($countScreen == 0)
                        $image_name2 = $image_name;
                    elseif ($countScreen >= 1)
                        $image_name2 = $image_name2 . "," . $image_name;

                } else {
                    $errors[]['message'] = $lang['ONLY_JPG_ALLOW'];
                }
                $countScreen++;
            }

            $custom_db_fields = array();
            $custom_db_fields2 = '';
            $custom_db_types = array();
            $custom_db_types2 = '';
            $custom_db_data = array();
            $custom_db_data2 = '';

            foreach($custom_fields as $key=>$value)
            {
                if($value['userent'])
                {
                    $custom_db_fields[$value['id']] = $value['title'];
                    $custom_db_types[$value['id']] = $value['type'];
                    $custom_db_data[$value['id']] = str_replace(',','&#44;',$value['default']);
                }
            }

            $custom_db_fields2 = implode(',',$custom_db_fields);
            $custom_db_types2 = implode(',',$custom_db_types);
            $custom_db_data2 = implode(',',$custom_db_data);

            $description = sanitize($_POST['content']);

            $timenow = date('Y-m-d H:i:s');

            $price  = isset($_POST['price'])? $_POST['price'] : 0;
            $negotiable  = isset($_POST['negotiable'])? $_POST['negotiable'] : 0;
            $phone  = isset($_POST['phone'])? $_POST['phone'] : "";
            $hide_phone  = isset($_POST['hide_phone'])? $_POST['hide_phone'] : 0;

            $contact_phone = isset($_POST['contact_phone']) ? 1 : 0;
            $contact_email = isset($_POST['contact_email']) ? 1 : 0;
            $contact_chat = isset($_POST['contact_chat']) ? 1 : 0;

            $sql = "INSERT INTO ".$config['db']['pre']."product set
            user_id = '".$_SESSION['user']['id']."',
            product_name = '".$_POST['title']."',
            category = '".$_POST['catid']."',
            sub_category = '".$_POST['subcatid']."',
            description = '".$description."',
            price = '".$price."',
            negotiable = '".$negotiable."',
            phone = '".$phone."',
            hide_phone = '".$hide_phone."',
            location = '".$_POST['location']."',
            city = '".$_POST['city']."',
            state = '".$_POST['state']."',
            country = '".$_POST['country']."',
            latlong = '$latlong',
            screen_shot = '".$image_name2."',
            tag = '".$_POST['tags']."',
            custom_fields = '$custom_db_fields2',
            custom_types = '$custom_db_types2',
            custom_values = '".$custom_db_data2."',
            created_at = '$timenow',
            contact_phone = '$contact_phone',
            contact_email = '$contact_email',
            contact_chat = '$contact_chat'
            ";

            $mysqli->query($sql);

            $product_id = $mysqli->insert_id;

            $amount = 0;
            $trans_desc = "Make Ad ";
            if($featured == 1)
            {
                $amount = $featured_project_fee;
                $trans_desc = $trans_desc." Featured ";
            }
            if($urgent == 1)
            {
                $amount = $amount+$urgent_project_fee;
                $trans_desc = $trans_desc." Urgent ";
            }
            if($highlight == 1)
            {
                $amount = $amount+$highlight_project_fee;
                $trans_desc = $trans_desc." Highlight ";
            }

            if($amount>0){
                if(isset($_POST['payment_id'])){
                    $query1 = "SELECT * FROM `".$config['db']['pre']."payments` WHERE payment_id='" . $_POST['payment_id'] . "' AND payment_install='1' LIMIT 1";
                    $query_result1 = @mysqli_query ($mysqli,$query1) OR error(mysqli_error($mysqli));
                    while ($info1 = @mysqli_fetch_array($query_result1))
                    {
                        $title = $info1['payment_title'];
                        $folder = $info1['payment_folder'];

                    }
                    require_once('includes/payments/' . $folder . '/deposit.php');
                }
            }
            else{
                unset($_POST);
                transfer($config,$link['PENDINGADS'],$lang['AD_UPLOADED_SUCCESS'],$lang['AD_UPLOADED_SUCCESS']);
                //message($lang['SUCCESS'],$lang['ADSUCCESS'],$config,$lang,$link,'',false);
                exit;
            }



        }
    }
    else{
        /*$getlatlong = getLocationInfoByIp();
        $mapLat     =  $getlatlong['latitude'];
        $mapLong    =  $getlatlong['longitude'];*/
        $mapLat     =   "40.7127837";
        $mapLong    =   "-74.00594130000002";


        $sql2 = "SELECT * FROM ".$config['db']['pre']."user where username='".$_SESSION['user']['username']."'";
        $result2 = mysqli_query(db_connect($config), $sql2);
        $info2 = mysqli_fetch_assoc($result2);

        $user_image = $info2['image'];
        $created = date('d M Y', strtotime($info2['created_at']));
        $lastactive = date('d M Y', strtotime($info2['lastactive']));

        if(isset($_POST['catid']) && isset($_POST['subcatid'])){

            $custom_fields = get_customFields_by_catid($config,$mysqli,$_POST['catid'],$_POST['subcatid']);
            $showCustomField = (count($custom_fields) > 0) ? 1 : 0;
            $maincat = get_maincat_by_id($config,$_POST['catid']);
            $maincatName = $maincat['cat_name'];
            $maincatIcon = $maincat['icon'];
            $subcat = get_subcat_by_id($config,$_POST['subcatid']);
            $subcatName = $subcat['sub_cat_name'];
                // Output to template
            $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/ad-post.html');
            $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['POST-FREE-AD'],$link));

            $page->SetParameter ('CATID', $_POST['catid']);
            $page->SetParameter ('SUBCATID', $_POST['subcatid']);
            $page->SetParameter ('CATEGORY', $maincatName);
            $page->SetParameter ('CATICON', $maincatIcon);
            $page->SetParameter ('SUBCATEGORY', $subcatName);
            $page->SetLoop ('CUSTOMFIELDS',$custom_fields);
            $page->SetParameter ('SHOWCUSTOMFIELD', $showCustomField);
        }
        else{
            $cat =  get_maincategory($config);
            $subcat =  get_categories($config,$mysqli);

            $catid = isset($_GET['catid']) ? $_GET['catid'] : "";
            $subcatid = isset($_GET['subcatid']) ? $_GET['subcatid'] : "";
            // Output to template
            $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/choose-category.html');
            $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['POST-FREE-AD'],$link));
            $page->SetLoop ('CATEGORY',$cat);
            $page->SetLoop ('SUBCAT',$subcat);
            $page->SetParameter ('CATEGORY', "");
            $page->SetParameter ('SUBCATEGORY', "");
            $page->SetParameter ('CATID', $catid);
            $page->SetParameter ('SUBCATID', $subcatid);
            $page->SetParameter ('CATICON', "");
            /*Advertisement Fetching*/
            $page->SetParameter('TOP_ADSCODE', get_advertise($config,"top"));
            /*Advertisement Fetching*/
        }


        $page->SetLoop('ERRORS', "");
        $page->SetLoop ('PAYMENT_TYPES', $payment_types);
        $page->SetParameter ('CITY', "");
        $page->SetParameter ('STATE', "");
        $page->SetParameter ('COUNTRY', "");
        $page->SetParameter ('CITYFIELD', "");
        $page->SetParameter ('DEFAULT_COUNTRY', check_user_country($config));
        $page->SetParameter ('USERIMAGE', $user_image);
        $page->SetParameter ('TITLE',"");
        $page->SetParameter ('PRICE', "");
        $page->SetParameter ('PHONE', "");
        $page->SetParameter ('LOCATION', "");
        $page->SetParameter ('LATITUDE', $mapLat);
        $page->SetParameter ('LONGITUDE', $mapLong);
        $page->SetParameter ('DESCRIPTION', "");
        $page->SetParameter ('TAGS', "");
        $page->SetParameter ('FEATURED', '');
        $page->SetParameter ('HIGHLIGHT', '');
        $page->SetParameter ('URGENT', '');
        $page->SetParameter ('CONTACT_PHONE', 'checked');
        $page->SetParameter ('CONTACT_EMAIL', 'checked');
        $page->SetParameter ('CONTACT_CHAT', 'checked');
        $page->SetParameter ('FEATURED_FEE', $featured_project_fee);
        $page->SetParameter ('HIGHLIGHT_FEE', $highlight_project_fee);
        $page->SetParameter ('URGENT_FEE', $urgent_project_fee);
        $page->SetParameter ('FEATURED', '');
        $page->SetParameter ('HIGHLIGHT', '');
        $page->SetParameter ('URGENT', '');
        $page->SetParameter ('USERNAME', $_SESSION['user']['username']);
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
    }

}
else{
    header("Location: login.php?ref=post-item.php");
    exit();
}
?>
