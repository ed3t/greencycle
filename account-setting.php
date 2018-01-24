<?php
require_once('includes/config.php');
session_start();
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/lib/password.php');
require_once('includes/functions/func.users.php');
require_once('includes/functions/func.sqlquery.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');
if($config['mod_rewrite'] == 0)
    require_once('includes/simple-url.php');
else
    require_once('includes/seo-url.php');

$mysqli = db_connect($config);

if(checkloggedin())
{
    $ses_userdata = get_user_data($config,$_SESSION['user']['username']);

    $author_image = $ses_userdata['image'];
    $author_lastactive = $ses_userdata['lastactive'];

    $errors = 0;
    $username_error = '';
    $email_error = '';
    $password_error = '';

    if(isset($_POST['submit']))
    {
        // Check if this is an Username availability check from signup page using ajax
        if($_POST["username"] != $_SESSION['user']['username'])
        {
            if(empty($_POST["username"]))
            {
                $errors++;
                $username_error = $lang['ENTERUNAME'];
                $username_error = "<span class='status-not-available'> ".$username_error."</span>";
            }
            elseif(preg_match('/[^A-Za-z0-9]/',$_POST['username']))
            {
                $errors++;
                $username_error = $lang['USERALPHA'];
                $username_error = "<span class='status-not-available'> ".$username_error." [A-Z,a-z,0-9]</span>";
            }
            elseif( (strlen($_POST['username']) < 4) OR (strlen($_POST['username']) > 16) )
            {
                $errors++;
                $username_error = $lang['USERLEN'];
                $username_error = "<span class='status-not-available'> ".$username_error.".</span>";
            }
            else{
                $user_count = check_username_exists($config,$_POST["username"]);
                if($user_count>0) {
                    $errors++;
                    $username_error = $lang['USERUNAV'];
                    $username_error = "<span class='status-not-available'>".$username_error."</span>";
                }
            }
        }

        // Check if this is an Email availability check from signup page using ajax
        if(is_null($_POST["email"])) {
            $errors++;
            $email_error = $lang['ENTEREMAIL'];
            $email_error = "<span class='status-not-available'> ".$email_error."</span>";
        }
        elseif($_POST["email"] != $_SESSION['user']['email'])
        {
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

            if (!preg_match($regex, $_POST['email'])) {
                $errors++;
                $email_error = $lang['EMAILINV'];
                $email_error = "<span class='status-not-available'> " . $email_error . ".</span>";
            } else {
                $user_count = check_account_exists($config,$_POST["email"]);
                if ($user_count > 0) {
                    $errors++;
                    $email_error = $lang['ACCAEXIST'];
                    $email_error = "<span class='status-not-available'>" . $email_error . "</span>";
                }
            }
        }

        // Check if this is an Password availability check from signup page using ajax
        if(!empty($_POST["password"]))
        {
            if( (strlen($_POST['password']) < 5) OR (strlen($_POST['password']) > 21) )
            {
                $errors++;
                $password_error = $lang['PASSLENG'];
                $password_error = "<span class='status-not-available'> ".$password_error.".</span>";
            }
        }

        if($errors == 0)
        {
            $queryVar = "";
            if(!empty($_POST["password"]))
            {
                $password = $_POST["password"];
                $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

                $queryVar = "username='".$_POST["username"]."',email='".$_POST["email"]."',updated_at= NOW(),password_hash='".$pass_hash."'";
            }
            else{
                $queryVar = "username='".$_POST["username"]."',email='".$_POST["email"]."',updated_at= NOW()";
            }

            $query = "UPDATE user set $queryVar where id = '".$_SESSION['user']['id']."'";
            $mysqli->query($query);

            //Updating Session Values
            $_SESSION['user']['email'] = $_POST["email"];
            $_SESSION['user']['username'] = $_POST["username"];

            transfer($config,$link['ACCOUNT_SETTING'],$lang['SETTING_SAVED_SUCCESS'],$lang['SETTING_SAVED']);
            exit;
        }
    }

    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/account-setting.html");
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['ACCOUNT-SETTING'],$link));
    if(isset($_POST['submit']))
    {
        $page->SetParameter ('EMAIL_FIELD', $_SESSION['user']['email']);
        $page->SetParameter ('USERNAME_FIELD', $_SESSION['user']['username']);

        $page->SetParameter ('USERNAME_ERROR', $username_error);
        $page->SetParameter ('EMAIL_ERROR', $email_error);
        $page->SetParameter ('PASSWORD_ERROR', $password_error);
    }
    else
    {
        $page->SetParameter ('EMAIL_FIELD', $_SESSION['user']['email']);
        $page->SetParameter ('USERNAME_FIELD', $_SESSION['user']['username']);


        $page->SetParameter ('USERNAME_ERROR', '');
        $page->SetParameter ('EMAIL_ERROR', '');
        $page->SetParameter ('PASSWORD_ERROR', '');

    }
    $page->SetParameter ('RESUBMITADS', resubmited_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('HIDDENADS', hidden_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('PENDINGADS', pending_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('FAVORITEADS', favorite_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('MYADS', myads_count($config,$_SESSION['user']['id']));
    $page->SetParameter('USER_ID',$_SESSION['user']['id']);
    $page->SetParameter ('USERNAME', $_SESSION['user']['username']);
    $page->SetParameter ('AUTHORIMG', $author_image);
    $page->SetParameter ('LASTACTIVE', $author_lastactive);
    $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
    $page->CreatePageEcho($lang,$config,$link);
}
else{
    error($lang['PAGENOTEXIST'], __LINE__, __FILE__, 1,$lang,$config,$link);
}
?>