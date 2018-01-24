<?php
error_reporting(1);

$timezone = $config['timezone'];
date_default_timezone_set($timezone);
$date = new DateTime("now", new DateTimeZone($timezone));
$timenow = date('Y-m-d H:i:s');

function db_connect($config)
{
    // Create connection in MYsqli
    $con = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name']);
    // Check connection in MYsqli
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    return $con;
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

function checkloggedadmin()
{
    if(isset($_SESSION['admin']['id']))
    {
        return TRUE;
    }
    else
    {
        echo '<script>window.location="login.php"</script>';
    }
}

function validStrLen($str, $min, $max, $con, $config){
    $len = strlen($str);
    if($len < $min){
        return "Username is too short, minimum is $min characters ($max max)";
    }
    elseif($len > $max){
        return "Username is too long, maximum is $max characters ($min min).";
    }
    elseif(!preg_match("/^[a-zA-Z0-9]+$/", $str))
    {
        return "Only use numbers and letters please";
    }
    else{
        //get the username
        $username = mysqli_real_escape_string($con, $_POST['username']);

        //mysql query to select field username if it's equal to the username that we check '
        $result = mysqli_query($con, "select username from `".$config['db']['pre']."userdata` where username = '".$username."'");

        //if number of rows fields is bigger them 0 that means it's NOT available '
        if(mysqli_num_rows($result)>0){
            //and we send 0 to the ajax request
            return "Error: Username not available";
        }
    }
    return TRUE;
}

function strlimiter($str,$limit){

    if (strlen($str) > $limit)
        $string = substr($str, 0, $limit) . '...';
    else
        $string = $str;

    return $string;
}

function transfer($config,$url,$msg,$page_title='')
{
    if(!$config['transfer_filter'])
    {
        echo '<script>window.location="'.$url.'"</script>';
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
    echo "<table width='95%' height='85%' style='padding-top:50px'>\n";
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

function validate_input($input,$dbcon=true,$content='all',$maxchars=0)
{
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

function update_admin_itemseen($config,$product_id)
{
    mysqli_query(db_connect($config), "UPDATE `".$config['db']['pre']."product` SET `admin_seen` = '1' WHERE `id` = '".$product_id."' LIMIT 1 ;");

}

function update_admin_resubmit_itemseen($config,$product_id)
{
    mysqli_query(db_connect($config), "UPDATE `".$config['db']['pre']."product_resubmit` SET `admin_seen` = '1' WHERE `product_id` = '".$product_id."' LIMIT 1 ;");

}

function get_currency_list($config,$selected="",$selected_text='selected')
{
    $currencies = array();
    $count = 0;

    $query = "SELECT * FROM ".$config['db']['pre']."currencies ORDER BY id";
    $query_result = mysqli_query(db_connect($config),$query);
    while ($info = mysqli_fetch_array($query_result))
    {
        $currencies[$count]['id'] = $info['id'];
        $currencies[$count]['country'] = $info['country'];
        $currencies[$count]['currency'] = $info['currency'];
        $currencies[$count]['code'] = $info['code'];
        $currencies[$count]['symbol'] = $info['symbol'];
        if($selected!="")
        {
            if($selected==$info['id'] or $selected==$info['code'])
            {
                $currencies[$count]['selected'] = $selected_text;
            }
            else
            {
                $currencies[$count]['selected'] = "";
            }
        }
        $count++;
    }

    return $currencies;
}

function get_currency_by_id($config,$id){
    $query = "SELECT * FROM ".$config['db']['pre']."currencies WHERE id='" . $id . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $info = mysqli_fetch_assoc($query_result);
    return $info;
}


?>