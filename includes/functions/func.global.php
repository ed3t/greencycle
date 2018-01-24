<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$timezone = $config['timezone'];
date_default_timezone_set($timezone);
$date = new DateTime("now", new DateTimeZone($timezone));
$timenow = date('Y-m-d H:i:s');

function create_header($config,$lang,$page_title='',$link)
{
    $sortname = check_user_country($config);
    $countryName = get_countryName_by_sortname($config,$sortname);
    $countryId = get_countryID_by_sortname($config,$sortname);

    $popular = array();
    $count = 1;

    $query = "SELECT c.id, c.name, c.state_id, s.name AS statename
FROM `".$config['db']['pre']."cities` AS c
INNER JOIN `".$config['db']['pre']."states` AS s ON s.id = c.state_id
INNER JOIN `".$config['db']['pre']."countries` AS a ON a.id = s.country_id
 WHERE a.id = '$countryId' and c.popular = '1' ORDER BY c.name";
    $query_result = mysqli_query(db_connect($config),$query);
    $total = mysqli_num_rows($query_result);
    $divide = intval($total/6)+1;
    $col = "";
    while ($info = mysqli_fetch_array($query_result))
    {
        $id = $info['id'];
        $name = $info['name'];
        $popular[$count]['tpl'] = "";
        if($count == 1 or $count == $col){
            $popular[$count]['tpl'] .= '<ul class="col-lg-2 col-md-3 col-sm-4 col-xs-6">';
            $checkEnd = $count+$divide-1;
            $col = $count+$divide;
        }
        $popular[$count]['tpl'] .=  '<li><a class="selectme" data-id="'.$id.'" data-name="'.$name.'" data-type="city"><span>'.$name.'</span></a></li>';


        if($count == $checkEnd or $count == $total){
            $popular[$count]['tpl'] .= '</ul>';
        }
        $count++;
    }

    $states = array();
    $count = 1;

    $query = "SELECT * FROM ".$config['db']['pre']."states where country_id = '$countryId' ORDER BY name";
    $query_result = mysqli_query(db_connect($config),$query);
    $total = mysqli_num_rows($query_result);
    $divide = intval($total/4)+1;
    $col = "";
    while ($info = mysqli_fetch_array($query_result))
    {
        $states[$count]['tpl'] = "";
        $id = $info['id'];
        $name = $info['name'];
        if($count == 1 or $count == $col){
            $states[$count]['tpl'] .= '<ul class="column col-md-3 col-sm-6 cities">';
            if($count == 1)
            {
                $states[$count]['tpl'] .=  '<li class="selected"><a class="selectme" data-id="'.$countryId.'" data-name="All '.$countryName.'" data-type="country"><strong>All '.$countryName.'</strong></a></li>';
            }


            $checkEnd = $count+$divide-1;
            $col = $count+$divide;
        }
        $states[$count]['tpl'] .= '<li class=""><a id="region'.$id.'" class="statedata" data-id="'.$id.'" data-name="'.$name.'"><span>'.$name.' <i class="fa fa-angle-right"></i></span></a></li>';


        if($count == $checkEnd or $count == $total){
            $states[$count]['tpl'] .= '</ul>';
        }
        $count++;
    }

    checkinstall($config);
    $items = get_items($config,"","active",false,1,2,"id");
    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/overall_header.html");
    $page->SetLoop ('POPULARCITY',$popular);
    $page->SetLoop ('STATELIST',$states);
    $page->SetLoop ('ITEM', $items);
    $page->SetParameter('THEME_COLOR', get_option($config,"theme_color"));
    $page->SetParameter('MAP_COLOR', get_option($config,"map_color"));
    $page->SetParameter('SITE_LOGO', get_option($config,"site_logo"));
    $page->SetParameter('COUNTRY_TYPE', get_option($config,"country_type"));
    $page->SetParameter('WCHAT', get_option($config,"wchat_on_off"));
    $page->SetParameter('META_KEYWORDS', get_option($config,"meta_keywords"));
    $page->SetParameter('META_DESCRIPTION', get_option($config,"meta_description"));
    $page->SetParameter('SITE_TITLE', $config['site_title']);
    $page->SetParameter('PAGE_TITLE', $page_title);
    $page->SetParameter('TPL_NAME', $config['tpl_name']);
    $page->SetParameter('SITE_URL', $config['site_url']);
    $page->SetParameter('GMAP_KEY', $config['gmap_api_key']);
    if(isset($_SESSION['user']['id']))
    {
        $get_userdata = get_user_data($config,$_SESSION['user']['username']);
        $page->SetParameter('LOGGED_IN', 1);
        $page->SetParameter('USER_ID',$_SESSION['user']['id']);
        $page->SetParameter ('USERNAME', $_SESSION['user']['username']);
        $page->SetParameter ('USEREMAIL', $_SESSION['user']['email']);
        $page->SetParameter ('USERPIC', $get_userdata['image']);

    }
    else
    {
        $page->SetParameter('LOGGED_IN', 0);
        $page->SetParameter('USER_ID','');
        $page->SetParameter ('USERNAME', '');
        $page->SetParameter ('USEREMAIL', '');
    }

    $page->SetParameter ('LANG_SEL', $config['userlangsel']);
    $page->SetLoop ('LANGS', get_lang_list($config));
    $page->SetParameter('USER_COUNTRY', strtolower($sortname));
    $page->SetParameter('DEFAULT_COUNTRY', $countryName);
    $page->SetParameter('DEFAULT_COUNTRY_ID', $countryId);
    return $page->CreatePageReturn($lang,$config,$link);
}

function create_footer($config,$lang,$link)
{
    $htmlPages = get_html_pages($config);
    $setting = get_setting($config);
    $items = get_items($config,"","active",false,1,2,"id");
    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/overall_footer.html");
    $page->SetLoop ('ITEM', $items);
    $page->SetLoop ('HTMLPAGE', $htmlPages);
    $page->SetParameter('SITE_TITLE', $config['site_title']);
    $page->SetParameter('ZECHAT', get_option($config,"zechat_on_off"));
    $page->SetParameter('SITE_LOGO', get_option($config,"site_logo"));
    $page->SetParameter('PHONE', get_option($config,"contact_phone"));
    $page->SetParameter('ADDRESS', get_option($config,"contact_address"));
    $page->SetParameter('EMAIL', get_option($config,"contact_email"));
    $page->SetParameter('FOOTER_TEXT', get_option($config,"footer_text"));
    $page->SetParameter('COPYRIGHT_TEXT', get_option($config,"copyright_text"));
    $page->SetParameter('COUNTRY_TYPE', get_option($config,"country_type"));

    $page->SetParameter('FACEBOOK', $setting['facebook']);
    $page->SetParameter('TWITTER', $setting['twitter']);
    $page->SetParameter('GPLUS', $setting['googleplus']);
    $page->SetParameter('YOUTUBE', $setting['youtube']);
    $page->SetParameter ('SWITCHER', $config['color_switcher']);

    if(isset($_SESSION['user']['id']))
    {
        $get_userdata = get_user_data($config,$_SESSION['user']['username']);
        $page->SetParameter('LOGGED_IN', 1);
        $page->SetParameter('USER_ID',$_SESSION['user']['id']);
        $page->SetParameter ('USERNAME', $_SESSION['user']['username']);
        $page->SetParameter ('USEREMAIL', $_SESSION['user']['email']);
        $page->SetParameter ('USERPIC', $get_userdata['image']);

        $userinfo = array();
        $query = "SELECT * FROM ".$config['db']['pre']."user WHERE id != '".$_SESSION['user']['id']."' ";
        $query_result = mysqli_query(db_connect($config), $query);
        if (mysqli_num_rows($query_result) > 0)
        {
            while($info = mysqli_fetch_assoc($query_result)){
                $userinfo[$info['id']]['id']        = $info['id'];
                $userinfo[$info['id']]['username']   = $info['username'];
                $userinfo[$info['id']]['user_type']  = $info['user_type'];
                $userinfo[$info['id']]['name']       = $info['name'];
                $userinfo[$info['id']]['email']      = $info['email'];
                $userinfo[$info['id']]['status']     = $info['status'];
                $userinfo[$info['id']]['image']      = $info['image'];
            }
        }
        $page->SetLoop ('ZECHATCONTACT', $userinfo);

    }
    else
    {
        $page->SetParameter('LOGGED_IN', 0);
        $page->SetParameter('USER_ID','');
        $page->SetParameter ('USERNAME', '');
        $page->SetParameter ('USEREMAIL', '');
        $page->SetLoop ('ZECHATCONTACT', "");
    }

    return $page->CreatePageReturn($lang,$config,$link);
}

function getLocationInfoByIp(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = array('country'=>'', 'city'=>'');
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    if($ip != "::1"){
        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
        if($ip_data && $ip_data->geoplugin_countryName != null){
            $result['countryCode'] = $ip_data->geoplugin_countryCode;
            $result['country'] = $ip_data->geoplugin_countryName;
            $result['city'] = $ip_data->geoplugin_city;
            $result['latitude'] = $ip_data->geoplugin_latitude;
            $result['longitude'] = $ip_data->geoplugin_longitude;
        }
    }
    else{
        $result['countryCode'] = "IN";
        $result['country'] = "India";
        $result['city'] = "Jodhpur";
        $result['latitude'] = "26.23894689999999";
        $result['longitude'] = "73.02430939999999";
    }

    return $result;
}

function checkinstall($config)
{
    if(!isset($config['installed']))
    {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $site_url = $protocol . $_SERVER['HTTP_HOST'] . str_replace ("index.php", "", $_SERVER['PHP_SELF']);
        header("Location: ".$site_url."install/");
        exit;
    }
    //checkpurchase($config);
}

function checkpurchase($config)
{
    if(!isset($config['purchase_key']))
    {
        header("Location: ".$config['site_url']."install/");
        exit;
    }
    else{
        $purchase_data = verify_envato_purchase_code($config['purchase_key']);

        if( isset($purchase_data['verify-purchase']['item_id']) )
        {
            if($purchase_data['verify-purchase']['item_id'] == '19960675'){
                return true;
            }
        }
        else
        {
            $url = $config['site_url'];
            echo 'Invalid Purchase code Or Check Internet connection.';
            //echo '<script type="text/javascript"> window.location = "'.$url.'install/" </script>';
            exit;
        }
    }
}

function db_connect($config)
{
    checkinstall($config);
    // Create connection in MYsqli
    $db_connection = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name']);
    // Check connection in MYsqli
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    return $db_connection;
}

function get_lang_list($config)
{
    $langs = array();

    if ($handle = opendir('includes/lang/'))
    {
        while (false !== ($file = readdir($handle)))
        {
            if ($file != '.' && $file != '..')
            {
                $langv = str_replace('.php','',$file);
                $langv = str_replace('lang_','',$langv);

                $langs[]['value'] = $langv;
            }
        }
        closedir($handle);
    }

    sort($langs);

    foreach ($langs as $key => $value)
    {
        if($config['lang'] == $value['value'])
        {
            $langs[$key]['name'] = ucwords($value['value']);
            $langs[$key]['selected'] = 'selected';
        }
        else
        {
            $langs[$key]['name'] = ucwords($value['value']);
            $langs[$key]['selected'] = '';
        }
    }

    return $langs;
}

function getExtension($str)
{
    $i = strrpos($str, ".");
    if (!$i) {
        return "";
    }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}



function fileUpload($path,$files,$type_file,$title,$reqwid,$reqhei,$Anysize=false,$unlink=null){

    $target_dir = $path;
    $target_file = $target_dir . basename($files["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    $random1 = rand(9999,100000);
    $random2 = rand(9999,200000);
    $image_title=$title.'_'.$random1.$random2.'.'.$imageFileType;

    $newname = $target_dir.$image_title;

    $error = "";
    if($type_file == "image"){
        list($width, $height) = getimagesize($files["tmp_name"]);
        if($Anysize){
            $uploadedfile = $files["tmp_name"];

            if($imageFileType=="jpg" || $imageFileType=="jpeg" )
            {
                $src = imagecreatefromjpeg($uploadedfile);
            }
            else if($imageFileType=="png")
            {
                $src = imagecreatefrompng($uploadedfile);
            }
            else
            {
                $src = imagecreatefromgif($uploadedfile);
            }

            $thumb_width = $reqwid;
            $thumb_height = $reqhei;

            $width = imagesx($src);
            $height = imagesy($src);

            $original_aspect = $width / $height;
            $thumb_aspect = $thumb_width / $thumb_height;

            if ( $original_aspect >= $thumb_aspect )
            {
                // If image is wider than thumbnail (in aspect ratio sense)
                $new_height = $thumb_height;
                $new_width = $width / ($height / $thumb_height);
            }
            else
            {
                // If the thumbnail is wider than the image
                $new_width = $thumb_width;
                $new_height = $height / ($width / $thumb_width);
            }

            $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

            // Resize and crop
            imagecopyresampled($thumb,
                $src,
                0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                0, 0,
                $new_width, $new_height,
                $width, $height);

            $image_name =  "small_".$image_title;

            $filename = $target_dir . $image_name;

            imagejpeg($thumb, $filename, 80);

            imagedestroy($src);
            imagedestroy($thumb);

            //Moving file to uploads folder
            if ($filename) {
                if($unlink != null){
                    $filename = $target_dir.$unlink;
                    unlink($filename);
                }
                move_uploaded_file($files["tmp_name"], $newname);
                $success = "The file ". basename( $files["name"]). " has been uploaded.";
                return $image_title;
            } else {
                $error = "Sorry, there was an error uploading your file.";
                return "";
            }

        }
        else{
            //Check width height
            if($reqwid != $width && $reqhei != $height){
                $error = "Sorry, only dimension".$width."x".$height."files are allowed.";
                $uploadOk = 0;
            }
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $error = "Sorry, only JPG, JPEG & PNG files are allowed.";
            $uploadOk = 0;
        }
    }
    elseif($type_file == "zip"){
        // Allow certain file formats
        if($imageFileType != "zip") {
            $error = "Sorry, only Zip file are allowed.";
            $uploadOk = 0;
        }
    }
    else{
    //Any type accepted
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error = "Sorry, your file was not uploaded.";
        return 0;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($files["tmp_name"], $newname)) {
            if($unlink != null){
                $filename = $target_dir.$unlink;
                unlink($filename);
            }
            $success = "The file ". basename( $files["name"]). " has been uploaded.";
            return $image_title;
        } else {
            $error = "Sorry, there was an error uploading your file.";
            return "";
        }
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function validate_input($input,$dbcon=true,$content='all',$maxchars=0)
{
    //include('../../includes/config.php');
    //$mysqli = db_connect($config);

    if(get_magic_quotes_gpc())
    {
        if(ini_get('magic_quotes_sybase'))
        {
            $input = str_replace("''", "'", $input);
        }
        else
        {
            $input = stripslashes($input);
        }
    }

    if($content == 'alnum')
    {
        $input = ereg_replace("[^a-zA-Z0-9]", '', $input);
    }
    elseif($content == 'num')
    {
        $input = ereg_replace("[^0-9]", '', $input);
    }
    elseif($content == 'alpha')
    {
        $input = ereg_replace("[^a-zA-Z]", '', $input);
    }

    if($maxchars)
    {
        $input = substr($input,0,$maxchars);
    }

    if($dbcon)
    {
        $input = @mysqli_real_escape_string($dbcon,$input);
    }
    else
    {
        $input = mysqli_escape_string($dbcon,$input);
    }

    return $input;
}

function pagenav($total,$page,$perpage,$url,$posts=0) 
{
	$page_arr = array();
	$arr_count = 0;

	if($posts) 
	{
		$symb='&';
	}
	else
	{
		$symb='?';
	}
	$total_pages = ceil($total/$perpage);
	$llimit = 1;
	$rlimit = $total_pages;
	$window = 5;
	$html = '';
	if ($page<1 || !$page) 
	{
		$page=1;
	}
	
	if(($page - floor($window/2)) <= 0)
	{
		$llimit = 1;
		if($window > $total_pages)
		{
			$rlimit = $total_pages;
		}
		else
		{
			$rlimit = $window;
		}
	}
	else
	{
		if(($page + floor($window/2)) > $total_pages) 
		{
			if ($total_pages - $window < 0)
			{
				$llimit = 1;
			}
			else
			{
				$llimit = $total_pages - $window + 1;
			}
			$rlimit = $total_pages;
		}
		else
		{
			$llimit = $page - floor($window/2);
			$rlimit = $page + floor($window/2);
		}
	}
	if ($page>1)
	{
		$page_arr[$arr_count]['title'] = 'Prev';
		$page_arr[$arr_count]['link'] = $url.$symb.'page='.($page-1);
		$page_arr[$arr_count]['current'] = 0;
		
		$arr_count++;
	}

	for ($x=$llimit;$x <= $rlimit;$x++) 
	{
		if ($x <> $page) 
		{
			$page_arr[$arr_count]['title'] = $x;
			$page_arr[$arr_count]['link'] = $url.$symb.'page='.($x);
			$page_arr[$arr_count]['current'] = 0;
		} 
		else 
		{
			$page_arr[$arr_count]['title'] = $x;
			$page_arr[$arr_count]['link'] = $url.$symb.'page='.($x);
			$page_arr[$arr_count]['current'] = 1;
		}
		
		$arr_count++;
	}
	
	if($page < $total_pages)
	{
		$page_arr[$arr_count]['title'] = 'Next';
		$page_arr[$arr_count]['link'] = $url.$symb.'page='.($page+1);
		$page_arr[$arr_count]['current'] = 0;
		
		$arr_count++;
	}
	
	return $page_arr;
}

function error($msg, $line='', $file='', $formatted=0,$lang=array(),$config=array(),$link=array())
{
    if($formatted == 0)
    {
        echo "Low Level Error: " . $msg;
    }
    else
    {
        if(!isset($lang['ERROR']))
        {
            $lang['ERROR'] = '';
        }

        $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/error.html");
        $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['ERROR'],$link));
        $page->SetParameter ('MESSAGE', $msg);
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
    }
    exit;
}

function email($email_to,$email_subject,$email_body,$config,$bcc=array())
{
    require_once("includes/classes/class.phpmailer.php");
    $mail = new PHPMailer();

    $mail->CharSet="utf-8";

    if($config['email']['type'] == 'smtp')
    {
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = $config['email']['smtp']['user'];
        $mail->Password = $config['email']['smtp']['pass'];
        $mail->Host = $config['email']['smtp']['host'];
    }
    elseif ($config['email']['type'] == 'sendmail')
    {
        $mail->IsSendmail();
    }
    else
    {
        $mail->IsMail();
    }

    $mail->FromName = $config['site_title'];
    $mail->From = $config['admin_email'];

    if(count($bcc) > 0)
    {
        $counter = 0;

        foreach ($bcc as $value)
        {
            if($counter == 0)
            {
                $mail->AddAddress($value);
            }
            else
            {
                $mail->AddBCC($value);
            }
            $counter++;
        }
    }
    else
    {
        $mail->AddAddress($email_to);
    }

    $mail->Subject = $email_subject;
    $mail->Body = $email_body;

    $mail->IsHTML(false);

    $mail->Send();
}

function message($heading,$message,$config,$lang,$link,$forward='',$back=true)
{
    if($forward == '')
    {
        if($back)
        {
            $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message.html");
            $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MESSAGE'],$link));
            $page->SetParameter ('HEADING', $heading);
            $page->SetParameter ('MESSAGE', $message);
            $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
            $page->CreatePageEcho($lang,$config,$link);
        }
        else
        {
            $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message_noback.html");
            $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MESSAGE'],$link));
            $page->SetParameter ('HEADING', $heading);
            $page->SetParameter ('MESSAGE', $message);
            $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
            $page->CreatePageEcho($lang,$config,$link);
        }
    }
    else
    {
        $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message_forward.html");
        $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MESSAGE'],$link));
        $page->SetParameter ('HEADING', $heading);
        $page->SetParameter ('MESSAGE', $message);
        $page->SetParameter ('FORWARD', $forward);
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
    }
    exit;
}

function transfer($config,$url,$msg,$page_title='')
{
	if(!$config['transfer_filter'])
	{
		header("Location: ".$url);
		exit;
	}
	ob_start();
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>\n";
	echo $page_title;
	echo "</title>\n";
	echo "<STYLE>\n";
	echo "<!--\n";
	echo "TABLE, TR, TD                { font-family:Verdana, Tahoma, Arial;font-size: 7.5pt; color:#000000}\n";
	echo "a:link, a:visited, a:active  { text-decoration:underline; color:#000000 }\n";
	echo "a:hover                      { color:#465584 }\n";
	echo "#alt1   { font-size: 16px; }\n";
	echo "body {\n";
	echo "	background-color: #e8ebf1\n";
    echo "	z-index: 99999\n";
	echo "}\n";
	echo "-->\n";
	echo "</STYLE>\n";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";
	echo "function changeurl(){\n";
	echo "window.location='" . $url . "';\n";
	echo "}\n";
	echo "</script>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></head>\n";
	echo "<body onload=\"window.setTimeout('changeurl();',2000);\">\n";
	echo "<table width='95%' height='85%'>\n";
	echo "<tr>\n";
	echo "<td valign='middle'>\n";
	echo "<table align='center' border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#fff\">\n";
	echo "<tr>\n";
	echo "<td id='mainbg'>";
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"12\">\n";
	echo "<tr>\n";
	echo "<td width=\"100%\" align=\"center\" id=alt1>\n";
	echo $msg . "<br><br>\n";
	echo "<div><img src=\"" . $config['site_url'] . "loading.gif\"/></div><br><br>\n";
	echo "(<a href='" . $url . "'>Or click here if you do not wish to wait</a>)</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</body></html>\n";
	ob_end_flush();
}

function encode_ip($server,$env)
{
    if( getenv('HTTP_X_FORWARDED_FOR') != '' )
    {
        $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );

        $entries = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
        reset($entries);
        while (list(, $entry) = each($entries))
        {
            $entry = trim($entry);
            if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
            {
                $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/', '/^224\..*/', '/^240\..*/');
                $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

                if ($client_ip != $found_ip)
                {
                    $client_ip = $found_ip;
                    break;
                }
            }
        }
    }
    else
    {
        $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
    }

    return $client_ip;
}

function verify_envato_purchase_code($code_to_verify) {
    // Your Username
    $username = 'bylancer';

    // Set API Key
    $api_key = 'yuo2pufs90ptj6nsoqzo4l60tiyce8lj';

    // Open cURL channel
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, "http://marketplace.envato.com/api/edge/". $username ."/". $api_key ."/verify-purchase:". $code_to_verify .".json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //Set the user agent
    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    // Decode returned JSON
    $output = json_decode(curl_exec($ch), true);

    // Close Channel
    curl_close($ch);

    // Return output
    return $output;
}

function sanitize($text) {
    $text = htmlspecialchars($text, ENT_QUOTES);
    $text = str_replace("\n\r","\n",$text);
    $text = str_replace("\r\n","\n",$text);
    $text = str_replace("\n","<br>",$text);
    return $text;
}

function strlimiter($str,$limit){

    if (strlen($str) > $limit)
        $string = substr($str, 0, $limit) . '...';
    else
        $string = $str;

    return $string;
}

function redirect_parent($url,$close=false)
{
    echo "<script type='text/javascript'>";
    if ($close)
    {
        echo "window.close(); ";
        echo "window.opener.location.href='$url'";
    }
    else
    {
        echo "window.location.href='$url'";
    }
    echo "</script>";

}


?>