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
    header("Location: dashboard.php");
    exit;
}



if(isset($_POST['forgot']))
{
    $_GET['forgot'] = $_POST['forgot'];
}
if(isset($_POST['r']))
{
    $_GET['r'] = $_POST['r'];
}
if(isset($_POST['e']))
{
    $_GET['e'] = $_POST['e'];
}
if(isset($_POST['t']))
{
    $_GET['t'] = $_POST['t'];
}
if(isset($_POST['type']))
{
    $_GET['type'] = $_POST['type'];
}

if(isset($_GET['ref']))
{
    $_GET['ref'] = htmlentities($_GET['ref']);
}
if(isset($_POST['ref']))
{
    $_POST['ref'] = htmlentities($_POST['ref']);
}

if(isset($_GET['r']))
{
    $_GET['r'] = htmlentities($_GET['r']);
}
if(isset($_POST['r']))
{
    $_POST['r'] = htmlentities($_POST['r']);
}

if(isset($_GET['t']))
{
    $_GET['t'] = htmlentities($_GET['t']);
}
if(isset($_POST['r']))
{
    $_POST['t'] = htmlentities($_POST['t']);
}

if(isset($_GET['e']))
{
    $_GET['e'] = htmlentities($_GET['e']);
}
if(isset($_POST['r']))
{
    $_POST['e'] = htmlentities($_POST['e']);
}

// Check if they are using a forgot password link
if(isset($_GET['forgot']))
{
    if(!isset($_GET['start']))
    {

        $check_forgot1 = mysqli_fetch_row(mysqli_query($mysqli,"SELECT id,forgot,username FROM ".$config['db']['pre']."user WHERE email='".$_GET['e']."' LIMIT 1"));


        if($_GET['forgot'] == $check_forgot1[1])
        {
            if($_GET['forgot'] == md5($_GET['t'].'_:_'.$_GET['r'].'_:_'.$_GET['e']))
            {
                // Check that the link hasn't timed out (30 minutes old)
                if($_GET['t'] > (time()-108000))
                {
                    $forgot_error = '';

                    if(isset($_POST['password']))
                    {
                        if( (strlen($_POST['password']) < 4) OR (strlen($_POST['password']) > 16) )
                        {
                            $forgot_error = $lang['PASSCHAR'];
                        }
                        else
                        {
                            if($_POST['password'] == $_POST['password2'])
                            {

                                mysqli_query($mysqli,"UPDATE `".$config['db']['pre']."user` SET `forgot` = '' WHERE `id` =".$check_forgot1[0]." LIMIT 1 ;");
                                $password = $_POST["password"];
                                $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

                                mysqli_query($mysqli,"UPDATE `".$config['db']['pre']."user` SET password_hash='" . $pass_hash . "' WHERE `id` =".$check_forgot1[0]." LIMIT 1 ;");


                                $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message.html");
                                $page->SetParameter ('HEADING',$lang['SUCCESS']);
                                $page->SetParameter ('SUBJECT',$lang['FORGOTPASS']);
                                $page->SetParameter ('MESSAGE',$lang['PASSCHANGED']);

                                if(isset($_SESSION['user']['id']))
                                {
                                    $page->SetParameter ('LOGGEDIN', 1);
                                }
                                else
                                {
                                    $page->SetParameter ('LOGGEDIN', 0);
                                }
                                $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
                                $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
                                $page->CreatePageEcho($lang,$config,$link);

                                exit;
                            }
                            else
                            {
                                $forgot_error = $lang['PASSNOMATCH'];
                            }
                        }
                    }

                    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/forgot.html");
                    $page->SetParameter ('FIELD_FORGOT',$_GET['forgot']);
                    $page->SetParameter ('FIELD_R',$_GET['r']);
                    $page->SetParameter ('FIELD_E',$_GET['e']);
                    $page->SetParameter ('FIELD_T',$_GET['t']);
                    $page->SetParameter ('USERNAME',$check_forgot1[2]);
                    $page->SetParameter ('FORGOT_ERROR',$forgot_error);
                    if(isset($_SESSION['user']['id']))
                    {
                        $page->SetParameter ('LOGGEDIN', 1);
                    }
                    else
                    {
                        $page->SetParameter ('LOGGEDIN', 0);
                    }
                    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
                    $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
                    $page->CreatePageEcho($lang,$config,$link);
                    exit;
                }
                else
                {
                    $login_error = $lang['FORGOTEXP'];
                }
            }
            else
            {
                $login_error = $lang['FORGOTINV'];
            }
        }
        else
        {
            $login_error = $lang['FORGOTINV'];
        }
    }

    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
    $page->SetParameter ('ERROR',$login_error);
    if(isset($_SESSION['user']['id']))
    {
        $page->SetParameter ('LOGGEDIN', 1);
    }
    else
    {
        $page->SetParameter ('LOGGEDIN', 0);
    }
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
    $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
    $page->CreatePageEcho($lang,$config,$link);
    exit;
}

// Check if they are trying to retrieve their email
if(isset($_POST['email']))
{
    // Lookup the email address
    $email_info1 = check_account_exists($config,$_POST['email']);

    // Check if the email address exists
    if($email_info1 != 0)
    {
        $email_userid = get_user_id_by_email($config,$_POST['email']);
        // Send the email
        send_forgot_email($_POST['email'],$email_userid,$config,$lang);

        $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/forgot_form.html");
        $page->SetParameter ('SUCCESS',$lang['CHECKEMAILFORGOT']);
        $page->SetParameter ('LOGIN_ERROR','');
        if(isset($_SESSION['user']['id']))
        {
            $page->SetParameter ('LOGGEDIN', 1);
        }
        else
        {
            $page->SetParameter ('LOGGEDIN', 0);
        }
        $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
        exit;
    }
    else
    {
        $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/forgot_form.html");
        $page->SetParameter ('LOGIN_ERROR',$lang['EMAILNOTEXIST']);
        $page->SetParameter ('SUCCESS',"");
        if(isset($_SESSION['user']['id']))
        {
            $page->SetParameter ('LOGGEDIN', 1);
        }
        else
        {
            $page->SetParameter ('LOGGEDIN', 0);
        }
        $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
        exit;
    }
}

if(isset($_GET['fstart']))
{
    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/forgot_form.html");
    $page->SetParameter ('LOGIN_ERROR','');
    $page->SetParameter ('SUCCESS','');
    if(isset($_SESSION['user']['id']))
    {
        $page->SetParameter ('LOGGEDIN', 1);
    }
    else
    {
        $page->SetParameter ('LOGGEDIN', 0);
    }
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
    $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
    $page->CreatePageEcho($lang,$config,$link);

    exit;
}



if(!isset($_POST['submit']))
{
    if(!isset($_GET['ref']))
    {
        $_GET['ref'] = 'dashboard.php';
    }


    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
    $page->SetParameter ('REF', $_GET['ref']);
    $page->SetParameter ('ERROR', '');
    $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
    $page->CreatePageEcho($lang,$config,$link);
}
else
{
    $loggedin = userlogin($config,$_POST['username'], $_POST['password']);

    if(!is_array($loggedin))
    {
        $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
        $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
        $page->SetParameter ('ERROR', $lang['USERNOTFOUND']);
        $page->SetParameter ('REF', $_POST['ref']);
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
    }
    elseif($loggedin['status'] == 2)
    {
        $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
        $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN'],$link));
        $page->SetParameter ('ERROR', $lang['ACCOUNTBAN']);
        $page->SetParameter ('REF', $_POST['ref']);
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
    }
    else
    {
        $_SESSION['user']['username'] = $loggedin['username'];
        $_SESSION['user']['id'] = $loggedin['id'];
        $_SESSION['user']['email'] = $loggedin['email'];

        update_lastactive($config);

        if(!isset($_GET['ref']))
        {
            $_GET['ref'] = $link['DASHBOARD'];
        }

        header("Location: " . $_GET['ref']);
    }
}
?>