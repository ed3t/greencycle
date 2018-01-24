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

// Check if this is an Name availability check from signup page using ajax

if(isset($_POST["submit"])) {
    $errors = 0;
    $name_error = '';
    $username_error = '';
    $email_error = '';
    $password_error = '';

    if(empty($_POST["name"])) {
        $errors++;
        $name_error = $lang['ENTER_FULL_NAME'];
        $name_error = "<span class='status-not-available'> ".$name_error."</span>";
    }
    elseif(preg_match('/[^A-Za-z\s]/',$_POST['name']))
    {
        $errors++;
        $name_error = $lang['ONLY_LETTER_SPACE'];
        $name_error = "<span class='status-not-available'> ".$name_error." [A-Z,a-z,0-9]</span>";
    }
    elseif( (strlen($_POST['name']) < 4) OR (strlen($_POST['name']) > 21) )
    {
        $errors++;
        $name_error = $lang['NAMELEN'];
        $name_error = "<span class='status-not-available'> ".$name_error.".</span>";
    }



    // Check if this is an Username availability check from signup page using ajax


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
        else {
            $username_error = $lang['USERUAV'];
            $username_error = "<span class='status-available'>".$username_error."</span>";
        }
    }


    // Check if this is an Email availability check from signup page using ajax

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

    if(empty($_POST["email"]))
    {
        $errors++;
        $email_error = $lang['ENTEREMAIL'];
        $email_error = "<span class='status-not-available'> ".$email_error."</span>";
    }
    elseif(!preg_match($regex, $_POST['email']))
    {
        $errors++;
        $email_error = $lang['EMAILINV'];
        $email_error = "<span class='status-not-available'> ".$email_error.".</span>";
    }
    else{
        $user_count = check_account_exists($config,$_POST["email"]);
        if($user_count>0) {
            $errors++;
            $email_error = $lang['ACCAEXIST'];
            $email_error = "<span class='status-not-available'>".$email_error."</span>";
        }
    }


    // Check if this is an Password availability check from signup page using ajax


    if(empty($_POST["password"]))
    {
        $errors++;
        $password_error = $lang['ENTERPASS'];
        $password_error = "<span class='status-not-available'> ".$password_error."</span>";
    }
    elseif( (strlen($_POST['password']) < 4) OR (strlen($_POST['password']) > 21) )
    {
        $errors++;
        $password_error = $lang['PASSLENG'];
        $password_error = "<span class='status-not-available'> ".$password_error.".</span>";
    }

    if($errors == 0) {

        $confirm_id = get_random_id();
        $location = getLocationInfoByIp();
        $password = $_POST["password"];
        $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

        $query = "INSERT into `".$config['db']['pre']."user` set
        name='" . $_POST["name"] . "',
        username='" . $_POST["username"] . "',
        password_hash='" . $pass_hash . "',
        email='" . $_POST["email"] . "',
        created_at= NOW() ,
        updated_at= NOW() ,
        country = '".$location['country']."',
        city = '".$location['city']."'";

        $mysqli->query($query);

        $user_id = $mysqli->insert_id;

        /*$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_signup_confirm.html");
        $page->SetParameter ('ID', $confirm_id);
        $page->SetParameter ('USER_ID', $user_id);
        $page->SetParameter ('USER_TYPE', "New User");
        $page->SetParameter ('SITE_URL', $config['site_url']);
        $page->SetParameter ('EMAIL', $_POST['email']);
        $page->SetParameter ('SITE_TITLE', $config['site_title']);
        $email_body = $page->CreatePageReturn($lang,$config,$link);

        email($_POST['email'],$config['site_title'].' - '.$lang['EMAILCONFIRM'],$email_body,$config);*/

        $loggedin = userlogin($config,$_POST['username'], $_POST['password']);

        $_SESSION['user']['username'] = $loggedin['username'];
        $_SESSION['user']['id'] = $loggedin['id'];
        $_SESSION['user']['email'] = $loggedin['email'];

        message($lang['WELCOME'],$lang['WELCOMETOSITE'], $config,$lang,$link,'dashboard.php',false);
        //header("Location: dashboard.php");
        exit;
    }
}



// Output to template



$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/signup.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['CREATE-AN-ACCOUNT'],$link));

if(isset($_POST['submit']))
{
    $page->SetParameter ('NAME_FIELD', $_POST['name']);
    $page->SetParameter ('USERNAME_FIELD', $_POST['username']);
    $page->SetParameter ('EMAIL_FIELD', $_POST['email']);

    $page->SetParameter ('NAME_ERROR', $name_error);
    $page->SetParameter ('USERNAME_ERROR', $username_error);
    $page->SetParameter ('EMAIL_ERROR', $email_error);
    $page->SetParameter ('PASSWORD_ERROR', $password_error);
}
else
{
    $page->SetParameter ('NAME_FIELD', '');
    $page->SetParameter ('USERNAME_FIELD', '');
    $page->SetParameter ('EMAIL_FIELD', '');

    $page->SetParameter ('NAME_ERROR', '');
    $page->SetParameter ('USERNAME_ERROR', '');
    $page->SetParameter ('EMAIL_ERROR', '');
    $page->SetParameter ('PASSWORD_ERROR', '');
}
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
$page->CreatePageEcho($lang,$config,$link);
?>