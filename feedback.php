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
if(!isset($_POST['Submit']))
{
// Output to template
    $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/feedback.html');
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['FEEDBACK'],$link));
    $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
    $page->CreatePageEcho($lang,$config,$link);
}
else
{
    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_feedback.html");
    $page->SetParameter ('SITE_TITLE', $config['site_title']);
    $page->SetParameter ('NAME', $_POST['name']);
    $page->SetParameter ('EMAIL', $_POST['email']);
    $page->SetParameter ('PHONE', $_POST['phone']);
    $page->SetParameter ('SUBJECT', $_POST['subject']);
    $page->SetParameter ('MESSAGE', $_POST['message']);
    $email_body = $page->CreatePageReturn($lang,$config,$link);

    email($_POST['email'],$lang['CONTACT_SUBJECT_START'] . $_POST['subject'],$email_body,$config);
    email($config['admin_email'],$lang['CONTACT_SUBJECT_START'] . $_POST['subject'],$email_body,$config);

    message($lang['THANKS'],$lang['FEEDBACKTHANKS'],$config,$lang,$link);
}


?>