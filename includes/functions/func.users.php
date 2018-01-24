<?php
$config['lang'] = check_user_lang($config);
$config['tpl_name'] = check_user_theme($config);
function get_random_id()
{
    $random = '';

    for ($i = 1; $i <= 8; $i++)
    {
        $random.= mt_rand(0, 9);
    }

    return $random;
}

function check_user_country($config)
{
    if(isset($_SESSION['user']['country']))
    {
        $country = $_SESSION['user']['country'];
    }
    else{
        $_SESSION['user']['country'] = $config['specific_country'];
        $country = $_SESSION['user']['country'];
    }
    return $country;
}

function check_user_lang($config)
{
    if($config['userlangsel'])
    {
        $cookie_name = "Quick_lang";
        if(isset($_COOKIE[$cookie_name])) {
            $config['lang'] = $_COOKIE[$cookie_name];
        }
    }

    return $config['lang'];
}

function check_user_theme($config)
{
    if($config['userthemesel'])
    {
        $cookie_name = "Quick_theme";
        if(isset($_COOKIE[$cookie_name])) {
            $config['tpl_name'] = $_COOKIE[$cookie_name];
        }
    }

    return $config['tpl_name'];
}

function check_account_exists($config,$email)
{
    $query = "SELECT id FROM ".$config['db']['pre']."user WHERE email='" . $email . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $num_rows = mysqli_num_rows($query_result);

    return $num_rows;
}

function check_username_exists($config,$username)
{
    $query = "SELECT id FROM ".$config['db']['pre']."user WHERE username='" . $username . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    $num_rows = mysqli_num_rows($query_result);

    return $num_rows;
}

function createusernameslug($config,$title)
{
    $slug = $title;

    $query = "SELECT COUNT(*) AS NumHits FROM ".$config['db']['pre']."user WHERE username  LIKE '$slug%'";
    $result = mysqli_query(db_connect($config),$query);
    $row = mysqli_fetch_assoc($result);
    $numHits = $row['NumHits'];

    return ($numHits > 0) ? ($slug.$numHits) : $slug;
}

function checkSocialUser($config,$userData = array(),$picname){
    if(!empty($userData)){

        $fullname = $userData['first_name'].' '.$userData['last_name'];
        $fbfirstname = $userData['first_name'];

        // Check whether user data already exists in database
        $prevQuery = "SELECT * FROM ".$config['db']['pre']."user WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
        $prevResult = mysqli_query(db_connect($config), $prevQuery);
        if(mysqli_num_rows($prevResult)>0){
            // Update user data if already exists
            /*$query = "UPDATE ".$config['db']['pre']."user SET
            name = '$fullname',
            email = '".$userData['email']."',
            gender = '".$userData['gender']."',
            image = '".$picname."',
            oauth_link = '".$userData['link']."',
            updated_at = '".date("Y-m-d H:i:s")."'
            WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";*/
            //$update = mysqli_query(db_connect($config), $query);
        }else{

            //mysql query to select field username if it's equal to the username that we check '
            $sql = "select username from ".$config['db']['pre']."user where username = '".$fbfirstname."'";
            $result = mysqli_query(db_connect($config),$sql);

            //if number of rows fields is bigger them 0 that means it's NOT available '
            if(mysqli_num_rows($result)>0){
                $username = createusernameslug($config,$fbfirstname);
            }
            else{
                $username = $fbfirstname;
            }

            // Insert user data
            $query = "INSERT INTO ".$config['db']['pre']."user SET
            oauth_provider = '".$userData['oauth_provider']."',
            oauth_uid = '".$userData['oauth_uid']."',
            status = '1',
            name = '$fullname',
            username = '$username',
            email = '".$userData['email']."',
            sex = '".$userData['gender']."',
            image = '".$picname."',
            oauth_link = '".$userData['link']."',
            created_at = '".date("Y-m-d H:i:s")."',
            updated_at = '".date("Y-m-d H:i:s")."'";
            $insert = mysqli_query(db_connect($config), $query);
        }

        // Get user data from the database
        $result = mysqli_query(db_connect($config), $prevQuery);
        $userData = $result->fetch_assoc();
    }

    // Return user data
    return $userData;
}

function get_user_data($config,$username,$userid=true){
    if($username != null){
        $query = "SELECT * FROM ".$config['db']['pre']."user WHERE username='".$username."' LIMIT 1";
    }
    else{
        $query = "SELECT * FROM ".$config['db']['pre']."user WHERE id='".$userid."' LIMIT 1";
    }

    $query_result = mysqli_query(db_connect($config), $query);
    if (mysqli_num_rows($query_result) > 0)
    {
        $info = mysqli_fetch_assoc($query_result);

        $userinfo['id']         = $info['id'];
        $userinfo['username']   = $info['username'];
        $userinfo['user_type']  = $info['user_type'];
        $userinfo['name']       = $info['name'];
        $userinfo['email']      = $info['email'];
        $userinfo['status']     = $info['status'];
        $userinfo['view']       = $info['view'];
        $userinfo['image']      = $info['image'];
        $userinfo['tagline']    = $info['tagline'];
        $userinfo['description'] = $info['description'];
        $userinfo['sex']        = $info['sex'];
        $userinfo['phone']      = $info['phone'];
        $userinfo['postcode']   = $info['postcode'];
        $userinfo['address']    = $info['address'];
        $userinfo['country']    = $info['country'];
        $userinfo['city']       = $info['city'];
        $userinfo['lastactive'] = $info['lastactive'];
        $userinfo['online']     = $info['online'];
        $userinfo['created_at'] = timeAgo($info['created_at']);
        $userinfo['updated_at'] = $info['updated_at'];

        $userinfo['facebook']   = $info['facebook'];
        $userinfo['twitter']    = $info['twitter'];
        $userinfo['googleplus'] = $info['googleplus'];
        $userinfo['instagram']  = $info['instagram'];
        $userinfo['linkedin']   = $info['linkedin'];
        $userinfo['youtube']    = $info['youtube'];

        return $userinfo;
    }
    else{
       return 0;
    }
}

function get_user_id($config,$username){
    $query = "SELECT id FROM ".$config['db']['pre']."user WHERE username='" . $username . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    if (mysqli_num_rows($query_result) > 0)
    {
        $info = mysqli_fetch_assoc($query_result);
        $user_id = $info['id'];
        return $user_id;
    }
    else{
        return FALSE;
    }
}

function get_user_id_by_email($config,$email){
    $query = "SELECT id FROM ".$config['db']['pre']."user WHERE email='" . $email . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    if (mysqli_num_rows($query_result) > 0)
    {
        $info = mysqli_fetch_assoc($query_result);
        $user_id = $info['id'];
        return $user_id;
    }
    else{
        return FALSE;
    }
}

function is_seller($config,$username)
{
    $query = "SELECT user_type FROM ".$config['db']['pre']."user WHERE username='" . $username . "' LIMIT 1";
    $query_result = mysqli_query(db_connect($config), $query);
    if (mysqli_num_rows($query_result) > 0)
    {
        $info = mysqli_fetch_assoc($query_result);
        $user_type = $info['user_type'];
        if($user_type == "seller")
            return TRUE;
        else
            return FALSE;
    }
    else
    {
        return FALSE;
    }
}

function userlogin($config,$username,$password)
{
	$username = stripslashes($username);
	$password = stripslashes($password);
	$userinfo = array();
	
	if(strlen($username) > 50)
	{
		return 0;
	}
	if(strlen($password) > 50)
	{
		return 0;
	}

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

	if(!preg_match("/^[[:alnum:]]+$/", $username))
	{
        if(!preg_match($regex,$username))
        {
            return 0;
        }
        else{
            //checking in email
            $query1 = "SELECT * FROM ".$config['db']['pre']."user WHERE email='".$username."' LIMIT 1";
            $query_result = mysqli_query(db_connect($config), $query1);
            $num_rows = mysqli_num_rows($query_result);
        }
	}
    else{
        $query = "SELECT * FROM ".$config['db']['pre']."user WHERE username='" . $username . "' LIMIT 1";
        $query_result = mysqli_query(db_connect($config), $query);
        $num_rows = mysqli_num_rows($query_result);
    }

		if($num_rows == 1)
		{
			$info = @mysqli_fetch_assoc($query_result);

				$userinfo['username'] = $info['username'];
				$userinfo['id'] = $info['id'];
				$userinfo['name'] = $info['name'];
				$userinfo['email'] = $info['email'];
                $userinfo['status'] = $info['status'];

            $hash = $info['password_hash'];

            $validate = password_verify($password, $hash);
            if(isset($validate))
                return $userinfo;
            else
                return 0;
		}
		else
		{
			return 0;
		}
	
}

function checkloggedin()
{
	if(isset($_SESSION['user']['id']))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function update_lastactive($config)
{
    if(isset($_SESSION['user']['id']))
    {
        mysqli_query(db_connect($config), "UPDATE `".$config['db']['pre']."user` SET `lastactive` = NOW() WHERE `id` = '".addslashes($_SESSION['user']['id'])."' LIMIT 1 ;");

    }
}

function send_forgot_email($email,$id,$config,$lang=array())
{
	$time = time();
	$rand = getrandnum(10);
	$forgot = md5($time.'_:_'.$rand.'_:_'.$email);
	
	mysqli_query(db_connect($config), "UPDATE `".$config['db']['pre']."user` SET `forgot` = '".$forgot."' WHERE `id` =".$id." LIMIT 1 ;");

	require_once("includes/classes/class.phpmailer.php");
	
	$mail = new PHPMailer();
	
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
	$mail->AddAddress($email);
	
	$mail->Subject = $config['site_title'] . ': '.$lang['FORGOTPASS'];
	$mail->Body = $lang['TORESET'].":\n\n".$config['site_url']."login.php?forgot=".$forgot."&r=".$rand."&e=".$email."&t=".$time;
	$mail->IsHTML(false);
	$mail->Send();
}

function getrandnum($length)
{
    $randstr='';
    srand((double)microtime()*1000000);
    $chars = array ( 'a','b','C','D','e','f','G','h','i','J','k','L','m','N','P','Q','r','s','t','U','V','W','X','y','z','1','2','3','4','5','6','7','8','9');
    for ($rand = 0; $rand <= $length; $rand++)
    {
        $random = rand(0, count($chars) -1);
        $randstr .= $chars[$random];
    }

    return $randstr;
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function update_profileview($user_id,$config)
{
    mysqli_query(db_connect($config), "UPDATE `".$config['db']['pre']."user` SET `view` = view+1 WHERE `id` = '".$user_id."' LIMIT 1 ;");

}
?>