<?php
/*
Copyright (c) 2015-17 Devendra Katariya (bylancer.com)
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../../includes/config.php');
session_start();

function db_connect($config)
{
    // Create connection in MYsqli
    $db_connection = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name']);
    // Check connection in MYsqli
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    return $db_connection;
}


$con = db_connect($config);

require_once('setting.php');

$timezone = $config['timezone'];
date_default_timezone_set($timezone);
$date = new DateTime("now", new DateTimeZone($timezone));
$timenow = date('Y-m-d H:i:s');


if(isset($_SESSION['user']['id'])){
    $sesUsername    = $_SESSION['user']['username'];
    $sesId          = $_SESSION['user']['id'];
}
else{
    exit();
}

if ($_GET['action'] == "searchUser") { searchUser($con, $config);}
if ($_GET['action'] == "savechat") { savechat($con, $config);}
if ($_GET['action'] == "updateSeenmsg") { updateSeenmsg($con, $config);}
if ($_GET['action'] == "checkMsgSeen") {checkMsgSeen($con, $config);}
if ($_GET['action'] == "lastseen") {lastseen($con, $config);}
if ($_GET['action'] == "userProfile") {userProfile($con, $config);}
if ($_GET['action'] == "chatfrindList") {chatfrindList($con, $config);}

if ($_GET['action'] == "get_all_msg") {get_all_msg($con, $config);}
if ($_GET['action'] == "chatheartbeat") {chatHeartbeat($con,$config);}
if ($_GET['action'] == "sendchat") {sendChat($con, $config);}
if ($_GET['action'] == "closechat") {closeChat();}
if ($_GET['action'] == "startchatsession") {startChatSession();}
if ($_GET['action'] == "typingstatus") {typingStatus();}


if (!isset($_SESSION['chatHistory'])) { $_SESSION['chatHistory'] = array();}

if (!isset($_SESSION['openChatBoxes'])) {$_SESSION['openChatBoxes'] = array();}

if (!isset($_SESSION['chatpage'])) {$_SESSION['chatpage'] = 1;}

function get_userdata($con,$config,$username){

    $query1 = "SELECT * FROM `".$config['db']['pre']."user` WHERE username='" .mysqli_real_escape_string($con,$username). "' LIMIT 1";
    $query_result = mysqli_query ($con, $query1);
    $info = mysqli_fetch_array($query_result);

    return $info;
}

function getlastActiveTime($username){
    $json3 = file_get_contents(dirname(__FILE__).'/json/online-status.json');
    $obj3 = json_decode($json3,true);
    $lastActiveTime = $obj3['lastActive'];

    $lastseen = "";
    for ($i = 0; $i < count($lastActiveTime); $i++) {
        if ($lastActiveTime[$i]['username'] == $username) {
            $last_active = $lastActiveTime[$i]['last_active_timestamp'];

            $timeFirst  = strtotime($last_active);
            $timeSecond = strtotime($GLOBALS['timenow']);
            $differenceInSeconds = $timeSecond - $timeFirst;

            if($differenceInSeconds >= "0" and $differenceInSeconds <= "5")
                $lastseen = "Online";
            else
                $lastseen = "last seen ".timeAgo($last_active);

            break;
        }
        else{
            $lastseen = "Offline";
        }

    }
    return $lastseen;
}

function updatelastActiveTime(){
    $a = 0;
    $key = "";
    //Updating in json
    $json3 = file_get_contents(dirname(__FILE__).'/json/online-status.json');
    $obj3 = json_decode($json3,true);
    $lastActiveTime = $obj3['lastActive'];

    for($i = 0; $i < count($lastActiveTime); $i++){
        $username = $lastActiveTime[$i]['username'];
        if ($username == $GLOBALS['sesUsername']) {
            $a = 1;
            $key = $i;
            break;
        }
    }

    if($a == 1){
        $lastActiveTime[$key]['last_active_timestamp'] = $GLOBALS['timenow'];
    }else{
        $len = count($lastActiveTime);
        $lastActiveTime[$len]["username"]=$GLOBALS['sesUsername'];
        $lastActiveTime[$len]["last_active_timestamp"] = $GLOBALS['timenow'];
    }

    $json = '{"lastActive" : '.json_encode($lastActiveTime, JSON_UNESCAPED_SLASHES).'}';
    file_put_contents(dirname(__FILE__).'/json/online-status.json', $json);
}

function timeAgo($timestamp){
    //$time_now = mktime(date('h')+0,date('i')+30,date('s'));
    $datetime1=new DateTime("now");
    $datetime2=date_create($timestamp);
    $diff=date_diff($datetime1, $datetime2);
    $timemsg='';
    if($diff->y > 0){
        $timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');

    }
    else if($diff->m > 0){
        $timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
    }
    else if($diff->d > 0){
        $timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
    }
    else if($diff->h > 0){
        $timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
    }
    else if($diff->i > 0){
        $timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
    }
    else if($diff->s > 0){
        $timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
    }
    if($timemsg == "")
        $timemsg = "Just now";
    else
        $timemsg = $timemsg.' ago';

    return $timemsg;
}

function searchUser($con,$config){

    if($_POST)
    {
        $q=$_POST['searchword1'];
        $TNMuser       = $GLOBALS['MySQLi_user_table_name'];
        $TFuserid      = $GLOBALS['MySQLi_userid_field'];
        $TFusername    = $GLOBALS['MySQLi_username_field'];
        $TFemail       = $GLOBALS['MySQLi_email_field'];
        $TFphoto       = $GLOBALS['MySQLi_photo_field'];

        $query1 = "SELECT * FROM `".$config['db']['pre'].$TNMuser."` where $TFuserid = '".$GLOBALS['sesId']."'";
        $result1 = $con->query($query1);
        $row1 = mysqli_fetch_assoc($result1);
        $row1[$TFusername];
        $sesuserpic = $row1[$TFphoto];

        if($sesuserpic == "")
            $sesuserpic = "default_user.png";

        $sql_res=mysqli_query($con,"SELECT * FROM `".$config['db']['pre'].$TNMuser."` where ($TFusername like '%$q%' or $TFemail like '%$q%') and ($TFuserid != '".$GLOBALS['sesId']."')  order by $TFuserid LIMIT 5");
        while($row=mysqli_fetch_array($sql_res))
        {
            $id = $row[$TFuserid];
            $username = $row[$TFusername];
            $email = $row[$TFemail];
            $picname = $row[$TFphoto];
            if($picname == "")
                $picname = "default_user.png";
            else{
                $picname = "small_".$picname;
            }

            $onofst =  getlastActiveTime($username);


            ?>


            <li class="person chatboxhead" id="chatbox1_<?php echo $username ?>" data-chat="person_<?php echo $id ?>" href="javascript:void(0);" onclick="javascript:chatWith('<?php echo $username ?>','<?php echo $id ?>','<?php echo $sesuserpic; ?>','<?php echo $onofst ?>')">
                <a href="javascript:void(0)">
                    <span class="userimage profile-picture min-profile-picture"><img src="storage/profile/<?php echo $picname; ?>" alt="<?php echo $username ?>" class="bg-theme"></span>
            <span>
                <span class="bname personName"><?php echo $username ?></span>
                <span class="personStatus"><span class="time <?php echo $onofst ?>"><i class="fa fa-circle" aria-hidden="true"></i></span></span>
                <small class="preview"><div class="<?php echo $onofst ?> email"><?php echo $email ?></div></small>
            </span>
                </a>
        <span class="hidecontent">
            <input id="to_id" name="to_id" value="<?php echo $id ?>" type="hidden">
            <input id="to_uname" name="to_uname" value="<?php echo $username ?>" type="hidden">
            <input id="from_uname" name="from_uname" value="<?php echo $row1['username']; ?>" type="hidden">
        </span>
            </li>
        <?php
        }

    }

}

function savechat($con,$config){

    $Filecontent = "";
    $Mailcontent = "";
    $sql = "select * from `".$config['db']['pre']."messages` where ((to_uname = '".$GLOBALS['sesUsername']."' AND from_uname = '".$_GET['uname']."' ) OR (to_uname = '".$_GET['uname']."' AND from_uname = '".$GLOBALS['sesUsername']."' )) order by message_id DESC";
    $query = $con->query($sql);
    while ($chat = mysqli_fetch_array($query)) {
        $from = $chat['from_uname'];
        $to = $chat['to_uname'];
        $mesg = $chat['message_content'];
        $time = $chat['message_date'];
        $linebreak = "\r\n";
        $Filecontent = $Filecontent.$time.' - '.$from .' : '. $mesg ." \\r\\n";

        $Mailcontent = $Mailcontent."<tr><td>".$time."</td><td>".$from."</td><td>".$mesg."</td></tr>";
    }

    $uname = $_GET['uname'];

    if($_GET['mail'] == "true"){
        $query1 = "SELECT email FROM `".$config['db']['pre'].$GLOBALS['MySQLi_user_table_name']."` where `".$GLOBALS['MySQLi_username_field']."` = '".$GLOBALS['sesUsername']."' LIMIT 1";
        $result1 = $con->query($query1);
        $row1 = mysqli_fetch_assoc($result1);
        $email = $row1[$GLOBALS['MySQLi_email_field']];

        $to = $email;
        $subject = "Wchat with ".$uname;
        $txt = $Filecontent;

        $message = "
        <html>
        <head>
        <title>".$subject."</title>
        </head>
        <body>
        <p>This email contains chat conversation</p>
        <table cellpadding='10' cellspacing='10'>
        <tr>
        <th>Date-Time</th>
        <th>Sender</th>
        <th>Message</th>
        </tr>
        ".$Mailcontent."
        </table>
        </body>
        </html>
        ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= "From: <".$config['admin_email'].">" . "\r\n";
        //$headers = "From: ".$config['admin_email'];
        mail($to,$subject,$message,$headers);

        echo "Mail Sent to ".$to;
        exit();
    }
    else{
        echo $Filecontent;
        exit();
    }

}                                           //Done

function updateSeenmsg($con, $config)
{
    $chatuser = $_POST['chatuser'];
    $query = "Update `".$config['db']['pre']."messages` set seen='1' where to_id = '".$GLOBALS['sesId']."' AND from_uname = '$chatuser'";
    $query_result = $con->query($query);
}                           //Done

function checkMsgSeen($con, $config)
{
    if($_GET['msgid'] == "last"){
        $query1 = "SELECT seen FROM `".$config['db']['pre']."messages` where to_uname = '".$_GET['uname']."' and from_uname = '".$GLOBALS['sesUsername']."' ORDER BY message_id DESC LIMIT 1";
    }
    else{
        $query1 = "SELECT seen FROM `".$config['db']['pre']."messages` where message_id = '".$_GET['msgid']."' LIMIT 1";
    }

    $result1 = $con->query($query1);
    $row1 = mysqli_fetch_assoc($result1);

    if(isset($row1['seen']))
        echo $seen = $row1['seen'];
    else
        echo $seen = "null";
}                       //Done

function lastseen($con,$config) {

    echo $lastseen =  getlastActiveTime($_GET['uname']);
}                                           //Done

function userProfile($con,$config)
{

    $query1 = "SELECT * FROM `".$config['db']['pre'].$GLOBALS['MySQLi_user_table_name']."` where `".$GLOBALS['MySQLi_username_field']."` = '".$_GET['uname']."' LIMIT 1";
    $result1 = $con->query($query1);
    $row1 = mysqli_fetch_assoc($result1);
    $username   = $row1[$GLOBALS['MySQLi_username_field']];
    $name       = $row1[$GLOBALS['MySQLi_fullname_field']];
    $email      = $row1[$GLOBALS['MySQLi_email_field']];
    $status     = $row1[$GLOBALS['MySQLi_about_field']];
    $sex        = $row1[$GLOBALS['MySQLi_sex_field']];
    $picname    = $row1[$GLOBALS['MySQLi_photo_field']];

    if($picname == "")
        $picname = "default_user.png";


    echo '<div class="">
            <div class="user-bg">
                <div class="overlay-box">
                    <div class="user-content"> <a href="javascript:void(0)">
                            <img class="thumb-lg img-circle" src="storage/profile/'.$picname.'" alt="'.$username.'"></a>
                        <h4 class="text-white">'.$username.'</h4>
                        <h5 class="text-white">'.$email.'</h5>
                    </div>
                </div>
            </div>
            <div class="user-btm-box">
                <div class="row text-center m-t-10">
                    <div class="col-md-6 b-r"><strong>Name</strong><p>'.$name.'</p></div>
                    <div class="col-md-6"><strong>Gender</strong><p>'.$sex.'</p></div>
                </div>
                <hr>
                <div class="row text-center m-t-10">
                    <div class="col-md-12"><strong>Status</strong><p>'.$status.'</p></div>
                </div>
                <hr>
                <div class="col-md-1 col-sm-1 text-center">&nbsp;</div>
            </div>
        </div>';


}
//Done

function chatfrindList($con,$config) {

    $query1 = "SELECT * FROM `".$config['db']['pre'].$GLOBALS['MySQLi_user_table_name']."` where `".$GLOBALS['MySQLi_userid_field']."` = '".$GLOBALS['sesId']."' ";
    $result1 = $con->query($query1);
    $row1 = mysqli_fetch_assoc($result1);
    $row1[$GLOBALS['MySQLi_username_field']];
    $sesuserpic = $row1[$GLOBALS['MySQLi_photo_field']];

    if($sesuserpic == "")
        $sesuserpic = "default_user.png";

    $TFid          = $GLOBALS['MySQLi_userid_field'];
    $TFusername    = $GLOBALS['MySQLi_username_field'];
    $TFname        = $GLOBALS['MySQLi_fullname_field'];
    $TFPicname     = $GLOBALS['MySQLi_photo_field'];
    //This query shows user contact list by conversation
    $query = "select $TFid,$TFusername,$TFname,$TFPicname,message_date from `".$config['db']['pre'].$GLOBALS['MySQLi_user_table_name']."` as u
            INNER JOIN
            (
                select max(message_id) as message_id,to_id,from_id,message_date from `".$config['db']['pre']."messages` where to_id = '".$GLOBALS['sesId']."' or from_id = '".$GLOBALS['sesId']."' GROUP BY to_id,from_id
            )
            m ON u.$TFid = m.from_id or u.$TFid = m.to_id  where (u.$TFid != '".$GLOBALS['sesId']."') GROUP BY u.$TFid
            ORDER BY message_id DESC";

    //This quesry shows user contact list publicly
    //$query = "select $TFid,$TFusername,$TFname,$TFPicname from `".$config['db']['pre'].$GLOBALS['MySQLi_user_table_name']."` where `".$GLOBALS['MySQLi_userid_field']."` != '".$GLOBALS['sesId']."' ORDER BY id DESC";



    $result = $con->query($query);
    while ($row = mysqli_fetch_array($result)) {
        $id = $row[$TFid];
        $username = $row[$TFusername];
        $fullname = $row[$TFname];
        $picname = $row[$TFPicname];
        if($picname == "")
            $picname = "default_user.png";
        else{
            $picname = "small_".$picname;
        }

        $sql = "SELECT 1 FROM `".$config['db']['pre']."messages` where to_uname = '".$GLOBALS['sesUsername']."' AND from_uname = '$username' and seen = '0'";
        $countrecd = mysqli_num_rows(mysqli_query($con,$sql));

        $onofst =  getlastActiveTime($username);

        ?>
        <li class="person chatboxhead" id="chatbox1_<?php echo $username ?>" data-chat="person_<?php echo $id ?>" href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $username ?>','<?php echo $id ?>','<?php echo $sesuserpic; ?>','<?php echo $onofst ?>')">
            <a href="javascript:void(0)">
                <span class="userimage profile-picture min-profile-picture"><img src="<?php echo $config['site_url']; ?>storage/profile/<?php echo $picname; ?>" alt="<?php echo $username ?>" class="avatar-image is-loaded bg-theme" width="100%"></span>
                <span>
                    <span class="bname personName"><?php echo $fullname; ?></span>
                    <span class="personStatus"><span class="time <?php echo $onofst ?>"><i class="fa fa-circle" aria-hidden="true"></i></span></span>
                    <span class="count"><?php if($countrecd >0){ ?> <span class="icon-meta unread-count"><?php echo $countrecd; ?></span> <?php }?></span><br>
                    <small class="preview"><span class="<?php echo $onofst ?>"><?php echo $onofst ?></span></small>
                </span>
            </a>
            <span class="hidecontent">
                <input id="to_id" name="to_id" value="<?php echo $id ?>" type="hidden">
                <input id="to_uname" name="to_uname" value="<?php echo $username ?>" type="hidden">
                <input id="from_uname" name="from_uname" value="<?php echo $row1[$GLOBALS['MySQLi_username_field']]; ?>" type="hidden">
            </span>
        </li>
    <?php


    }

}                                     //Done

function get_all_msg($con,$config) {

    $perPage = 10;

    $sql = "select * from `".$config['db']['pre']."messages` where ((to_uname = '".$GLOBALS['sesUsername']."' AND from_uname = '".$_GET['client']."') OR (to_uname = '".$_GET['client']."' AND from_uname = '".$GLOBALS['sesUsername']."' ))order by message_id DESC ";

    $page = 1;
    if(!empty($_GET["page"])) {
        $_SESSION['chatpage'] = $page = $_GET["page"];
    }

    $start = ($page-1)*$perPage;
    if($start < 0) $start = 0;

    $query =  $sql . " limit " . $start . "," . $perPage;

    $query = $con->query($query);

    if(empty($_GET["rowcount"])) {
        $_GET["rowcount"] = $rowcount = mysqli_num_rows(mysqli_query($con, $sql));
    }

    $pages  = ceil($_GET["rowcount"]/$perPage);

    $chatBoxes = array();
    $items = '';
    if(!empty($query)) {

    }

    while ($chat = mysqli_fetch_array($query)) {

        $picname = "";
        $picname2 = "";
        $TFPicname     = $GLOBALS['MySQLi_photo_field'];

        $query1 = "SELECT $TFPicname FROM `".$config['db']['pre'].$GLOBALS['MySQLi_user_table_name']."` where `".$GLOBALS['MySQLi_username_field']."` ='" .mysqli_real_escape_string($con,$chat['from_uname']). "' LIMIT 1";
        $query_result = mysqli_query ($con, $query1) OR error(mysqli_error($con));
        while ($info = mysqli_fetch_array($query_result))
        {
            $picname = "small_".$info[$TFPicname];
            //$status = $info['online'];
        }

        $query4 = "SELECT $TFPicname FROM `".$config['db']['pre'].$GLOBALS['MySQLi_user_table_name']."` where `".$GLOBALS['MySQLi_username_field']."` ='" .mysqli_real_escape_string($con,$chat['to_uname']). "' LIMIT 1";
        $query_result4 = mysqli_query ($con, $query4) OR error(mysqli_error($con));
        while ($info4 = mysqli_fetch_array($query_result4))
        {
            $picname2 = "small_".$info4[$TFPicname];
        }


        if($picname == "small")
            $picname = "default_user.png";

        if($picname2 == "small")
            $picname2 = "default_user.png";

        $status = "0";
        if($status == "0")
            $status = "Offline";
        else
            $status = "Online";


        $chat['message_content'] = sanitize($chat['message_content']);

        if($chat['from_uname'] == $GLOBALS['sesUsername'])
        {
            $u = 1;
            $sespic = $picname;
        }
        else{
            $u = 2;
            $sespic = $picname2;
        }

        if (strpos($chat['message_content'], sanitize('file_name')) !== false) {

        }
        else{
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

            // Check if there is a url in the text
            if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                // make the urls hyper links
                $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

            } else {
                // The Regular Expression filter
                $reg_exUrl = "/(www)\.[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

                // Check if there is a url in the text
                if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                    // make the urls hyper links
                    $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

                }
            }
        }

        $timeago = timeAgo($chat['message_date']);
        $chatContent = stripslashes($chat['message_content']);
        $items .= <<<EOD
					   {
			"s": "0",
			"sender": "{$chat['from_uname']}",
			"f": "{$_GET['client']}",
			"x": "{$chat['from_id']}",
			"p": "{$picname}",
			"p2": "{$picname2}",
			"st": "{$status}",
			"page": "{$_SESSION['chatpage']}",
			"pages": "{$pages}",
			"u": "{$u}",
			"mtype": "{$chat['message_type']}",
			"m": "{$chatContent}",
			"time": "{$timeago}",
			"seen": "{$chat['seen']}"
	   },
EOD;


    }

    $sql = "update `".$config['db']['pre']."messages` set recd = 1 where to_uname = '".mysqli_real_escape_string($con,$GLOBALS['sesUsername'])."' and recd = 0";
    $query = $con->query($sql);

    if ($items != '') {
        $items = substr($items, 0, -1);
    }

    header('Content-type: application/json');
    ?>
    {
    "items": [
    <?php echo $items;?>
    ]
    }

    <?php
    exit(0);
}                                       //Done

function chatHeartbeat($con, $config)
{
    updatelastActiveTime();


    $items = '';
    $chatBoxes = array();
    $msgContent = array();
    $to_uname = "";
    $recd = "";
    $from_uname = "";
    $from_id = "";
    $message_type = "";
    $content = "";
    $message_date = "";
    $picname = "";
    $picname2 = "";
    $status = "";
    $sesUsername = $GLOBALS['sesUsername'];

    $TNMuser       = $GLOBALS['MySQLi_user_table_name'];
    $TFusername    = $GLOBALS['MySQLi_username_field'];
    $TFPicname     = $GLOBALS['MySQLi_photo_field'];

    $sql = "select * from `".$config['db']['pre']."messages` where (to_uname = '".mysqli_real_escape_string($con,$GLOBALS['sesUsername'])."' AND recd = 0) order by message_id ASC";
    $query = $con->query($sql);

    $items = '';

    while ($chat = mysqli_fetch_array($query)) {

        $picname = "";
        $picname2 = "";

        $query1 = "SELECT $TFPicname FROM `".$config['db']['pre'].$TNMuser."` WHERE $TFusername='" .mysqli_real_escape_string($con,$chat['from_uname']). "' LIMIT 1";
        $query_result = mysqli_query ($con, $query1) OR error(mysqli_error($con));
        while ($info = mysqli_fetch_array($query_result))
        {
            $picname = "small_".$info[$TFPicname];
        }

        $query4 = "SELECT $TFPicname FROM `".$config['db']['pre'].$TNMuser."` WHERE $TFusername='" .mysqli_real_escape_string($con,$GLOBALS['sesUsername']). "' LIMIT 1";
        $query_result4 = mysqli_query ($con, $query4) OR error(mysqli_error($con));
        while ($info4 = mysqli_fetch_array($query_result4))
        {
            $picname2 = "small_".$info4[$TFPicname];
        }

        if($picname == "small")
            $picname = "default_user.png";

        if($picname2 == "small")
            $picname2 = "default_user.png";

        $status = "0";
        if($status == "0")
            $status = "Offline";
        else
            $status = "Online";



        $chat['message_content'] = sanitize($chat['message_content']);

        if($chat['from_uname'] == $GLOBALS['sesUsername'])
        {
            $u = 1;
            $sespic = $picname;
        }
        else{
            $u = 2;
            $sespic = $picname2;
        }

        if (strpos($chat['message_content'], sanitize('file_name')) !== false) {

        }
        else{
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

            // Check if there is a url in the text
            if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                // make the urls hyper links
                $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

            } else {
                // The Regular Expression filter
                $reg_exUrl = "/(www)\.[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/";

                // Check if there is a url in the text
                if (preg_match($reg_exUrl, $chat['message_content'], $url)) {

                    // make the urls hyper links
                    $chat['message_content'] = preg_replace($reg_exUrl, "<a href='{$url[0]}'>{$url[0]}</a>", $chat['message_content']);

                }
            }
        }

        $timeago = timeAgo($chat['message_date']);
        $chatContent = stripslashes($chat['message_content']);
        $items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from_uname']}",
			"x": "{$chat['from_id']}",
			"p": "{$picname}",
			"p2": "{$picname2}",
			"spic": "{$sespic}",
			"st": "{$status}",
			"u": "{$u}",
			"mtype": "{$chat['message_type']}",
			"m": "{$chatContent}",
			"time": "{$timeago}"
	   },
EOD;

    }

    /*if (is_array($typing) || is_object($typing)) {
        foreach ($typing as $typ) {
            $to_uname = $typ['to_uname'];
            $from_uname = $typ['from_uname'];
            $isTyping = 0;
            if ($from_uname == $_GET['fromuname'] && $to_uname == $GLOBALS['sesUsername']) {
                $from_uname = $typ['from_uname'];
                $from_id = $typ['from_id'];
                $time = $typ['time'];

                $timeFirst = strtotime($time);
                $timeSecond = strtotime($GLOBALS['timenow']);
                $differenceInSeconds = $timeSecond - $timeFirst;

                if ($differenceInSeconds >= "0" and $differenceInSeconds <= "1")
                {
                    $isTyping = 1;

                    $items .= <<<EOD
                               {

                    "fromUtyp": "{$from_uname}",
                    "fromIDtyp": "{$from_id}",
                    "isTyping": "{$isTyping}"
               },
EOD;
                }
                else
                {
                    $lastseen = getlastActiveTime($from_uname);

                    $isTyping = 0;

                    $items .= <<<EOD
                               {

                    "fromUtyp": "{$from_uname}",
                    "fromIDtyp": "{$from_id}",
                    "isTyping": "{$isTyping}",
                    "lastseen": "{$lastseen}"
               },
EOD;
                }

                break;
            }

        }
    }*/

    /**/

    $sql = "update `".$config['db']['pre']."messages` set recd = 1 where to_uname = '".mysqli_real_escape_string($con,$GLOBALS['sesUsername'])."' and recd = 0";
    $query = $con->query($sql);

    if ($items != '') {
        $items = substr($items, 0, -1);
    }
    header('Content-type: application/json');
    ?>
    {
    "items": [
    <?php echo $items; ?>
    ]
    }

    <?php
    exit(0);
}                                       //Done

function chatBoxSession($chatbox) {

    $items = '';

    if (isset($_SESSION['chatHistory'][$chatbox])) {
        $items = $_SESSION['chatHistory'][$chatbox];
    }

    return $items;
}                                       //DOne

function startChatSession() {
    $items = '';

    header('Content-type: application/json');
    ?>
    {
    "username": "<?php echo $GLOBALS['sesUsername'];?>",
    "items": [
    <?php echo $items;?>
    ]
    }

    <?php

    exit(0);
}                                               //Done

function sendChat($con,$config) {
    if(isset($GLOBALS['sesUsername'])){
        $from = $GLOBALS['sesUsername'];
        $from_id = $GLOBALS['sesId'];
        $message = $_POST['message'];
        $to = $_POST['to'];
        $messagesan = sanitize($message);

        $from_userdata = get_userdata($con,$config,$to);
        if(count($from_userdata) > 0){
            $to_id = $from_userdata['id'];
            $picname = $from_userdata['image'];
            $status = $from_userdata['online'];
            $picname = ($picname == "")? "default_user.png" : $picname;
            $status  = ($status == "0")? "Offline" : "Online";

            $to_userdata = get_userdata($con,$config,$GLOBALS['sesUsername']);
            $picname2 = $to_userdata['image'];

            $picname2 = ($picname2 == "")? "default_user.png" : $picname2;


            $sql = "insert into `".$config['db']['pre']."messages` (from_uname,to_uname,from_id,to_id,message_content,message_type,message_date) values ('".mysqli_real_escape_string($con,$from)."', '".mysqli_real_escape_string($con,$to)."','".mysqli_real_escape_string($con,$from_id)."','".mysqli_real_escape_string($con,$to_id)."','".mysqli_real_escape_string($con,$message)."','text','".$GLOBALS['timenow']."')";

            $query = $con->query($sql);

            //$msg_id = $con->insert_id;

            echo "1";
        }
        else{
            echo "0";
        }
        exit(0);

    }
    else{
        echo "0";
    }
    exit(0);
}

                            //Done

function typingStatus() {
    $from = $GLOBALS['sesUsername'];
    $from_id = $GLOBALS['sesId'];
    $to = $_POST['toUname'];
    $to_id = $_POST['toid'];
    $a = 0;
    $key = "";
    //Updating in json

    /*for($i = 0; $i < count($typing); $i++){
        $toID = $typing[$i]['to_id'];
        $fromID = $typing[$i]['from_id'];
        if ($toID == $to_id && $fromID == $from_id) {
            $a = 1;
            $key = $i;
            break;
        }
    }

    if($a == 1){
        $typing[$key]['time'] = $GLOBALS['timenow'];
    }else{
        $len = count($typing);
        $typing[$len]["from_id"]=$from_id;
        $typing[$len]["to_id"]=$to_id;
        $typing[$len]["from_uname"]=$from;
        $typing[$len]["to_uname"]=$to;
        $typing[$len]["time"]=$GLOBALS['timenow'];
    }

    $json = '{"typing" : '.json_encode($typing, JSON_UNESCAPED_SLASHES).'}';
    file_put_contents(dirname(__FILE__).'/json/typing-status.json', $json);*/

    echo "1";
    exit(0);
}                                           //Done

function closeChat() {

    unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);

    echo "1";
    exit(0);
}

function sanitize($text) {
    $text = htmlspecialchars($text, ENT_QUOTES);
    $text = str_replace("\n\r","\n",$text);
    $text = str_replace("\r\n","\n",$text);
    $text = str_replace("\n","<br>",$text);
    return $text;
}

?>