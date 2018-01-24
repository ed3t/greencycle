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



$query = "SELECT html_title,html_content,html_type FROM `".$config['db']['pre']."html` WHERE html_id='" . $_GET['id'] . "' LIMIT 1";
$query_result = mysqli_query ($mysqli,$query);
while ($info = mysqli_fetch_array($query_result))
{
	$html = stripslashes($info['html_content']);
	$title = stripslashes($info['html_title']);
	$type = $info['html_type'];
}

if(!isset($title))
{
	message("Error",$lang['PAGENOTEXIST'], $config,$lang,$link);
}

if($type == 1)
{
	if(!isset($_SESSION['user']['id']))
	{
		message("Login to view",$lang['MUSTLOGINVIEWPAGE'],$config,$lang,$link);
	}
}

if(isset($_GET['basic']))
{
	$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/html_content_no.html');
}
else
{
	$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/html_content.html');
}
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$title,$link));
$page->SetParameter ('SITE_TITLE', $config['site_title']);
$page->SetParameter ('TITLE', $title);
$page->SetParameter ('HTML', $html);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
$page->CreatePageEcho($lang,$config,$link);
?>