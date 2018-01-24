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

$con = db_connect($config);


if(isset($_POST['email']))
{
	$_POST['email'] = htmlentities($_POST['email']);
}

if(isset($_POST['name']))
{
	$_POST['name'] = htmlentities($_POST['name']);
}

if(isset($_POST['type']))
{
	$_POST['type'] = htmlentities($_POST['type']);
}

if(isset($_POST['username']))
{
	$_POST['username'] = htmlentities($_POST['username']);
}

if(isset($_POST['username2']))
{
	$_POST['username2'] = htmlentities($_POST['username2']);
}

if(isset($_POST['details']))
{
	$_POST['details'] = htmlentities($_POST['details']);
}

if(isset($_POST['url']))
{
	$_POST['url'] = htmlentities($_POST['url']);
}

if(isset($_POST['violation']))
{
	$_POST['violation'] = htmlentities($_POST['violation']);
}



$errors = 0;
$name_error = '';
$email_error = '';
$viol_error = '';

if(!isset($_POST['Submit']))
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/report.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['REPORTVIO'],$link));
	$page->SetParameter ('USERNAME2', '');
	$page->SetParameter ('DETAILS', '');
	$page->SetParameter ('EMAIL_ERROR', '');
	$page->SetParameter ('NAME_ERROR', '');
	$page->SetParameter ('VIOL_ERROR', '');
	
	if(isset($_SESSION['user']['name']))
	{
		$page->SetParameter ('USERNAME', $_SESSION['user']['username']);
		$page->SetParameter ('NAME', $_SESSION['user']['name']);
		$page->SetParameter ('EMAIL', $_SESSION['user']['email']);	
	}
	else
	{
		$page->SetParameter ('USERNAME', '');
		$page->SetParameter ('NAME', '');
		$page->SetParameter ('EMAIL', '');	
	}
    if(isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
        if ((strpos($referer, $link['AD-DETAIL']) !== false)){
            $page->SetParameter ('REDIRECT_URL', $_SERVER['HTTP_REFERER']);
        }
        else
        {
            $page->SetParameter ('REDIRECT_URL', '');
        }
    }
    else
    {
        $page->SetParameter ('REDIRECT_URL', '');
    }

	
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
	$page->CreatePageEcho($lang,$config,$link);
}
else
{
	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
	
	if(trim($_POST['email']) == '')
	{
		$errors++;
		$email_error = $lang['ENTEREMAIL'];
	}
	elseif(!preg_match($regex, $_POST['email'])) 
	{
		$errors++;
		$email_error = $lang['EMAILINV'];
	}
	
	if(trim($_POST['name']) == '')
	{
		$errors++;
		$name_error = $lang['ENTERNAME'];
	}
	
	if(trim($_POST['details']) == '')
	{
		$errors++;
		$viol_error = $lang['ENTERVIOL'];
	}
	
	if($errors)
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/report.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['REPORTVIO'],$link));

		$page->SetParameter ('USERNAME', $_POST['username']);
		$page->SetParameter ('USERNAME2', $_POST['username2']);
		$page->SetParameter ('NAME', $_POST['name']);
		$page->SetParameter ('EMAIL', $_POST['email']);
		$page->SetParameter ('DETAILS', $_POST['details']);
		
		$page->SetParameter ('EMAIL_ERROR', $email_error);
		$page->SetParameter ('NAME_ERROR', $name_error);
		$page->SetParameter ('VIOL_ERROR', $viol_error);

        if(isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            if ((strpos($referer, $link['AD-DETAIL']) !== false)){
                $page->SetParameter ('REDIRECT_URL', $_SERVER['HTTP_REFERER']);
            }
            else
            {
                $page->SetParameter ('REDIRECT_URL', '');
            }
        }
        else
        {
            $page->SetParameter ('REDIRECT_URL', '');
        }
		
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
		$page->CreatePageEcho($lang,$config,$link);
	}
	else
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_report.html");
		$page->SetParameter ('SITE_TITLE', $config['site_title']);
		$page->SetParameter ('EMAIL', $_POST['email']);
		$page->SetParameter ('NAME', $_POST['name']);
		$page->SetParameter ('USERNAME', $_POST['username']);
		$page->SetParameter ('USERNAME2', $_POST['username2']);
		$page->SetParameter ('VIOLATION', $_POST['violation']);
		$page->SetParameter ('URL', $_POST['url']);
		$page->SetParameter ('DETAILS', $_POST['details']);
		$email_body = $page->CreatePageReturn($lang,$config,$link);
			
		email($config['admin_email'],$lang['REPORTVIO'],$email_body,$config);
	
		message($lang['THANKS'],$lang['REPORT_THANKS'],$config,$lang,$link);
	}
}
?>