<?php
require_once('includes/config.php');
session_start();
require_once('includes/classes/class.template_engine.php');
require_once("includes/classes/class.phpmailer.php");
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

$mysqli = db_connect($config);



if ($_POST['type'] == "adContact") {
    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_contact_seller.html");
    $page->SetParameter('SITE_TITLE', $config['site_title']);
    $page->SetParameter('SITE_URL', $config['site_url']);
    $page->SetParameter('ADTITLE', $_POST['adTitle']);
    $page->SetParameter('NAME', $_POST['name']);
    $page->SetParameter('EMAIL', $_POST['email']);
    $page->SetParameter('PHONE', $_POST['phone']);
    $page->SetParameter('MESSAGE', $_POST['message']);
    $email_body = $page->CreatePageReturn($lang, $config, $mysqli);

    email($_POST['emailTo'], $config['site_title'] . ' - '.$_POST['name'].' Contact you', $email_body, $config, $mysqli);
    echo $lang['SUCCESS'];
}
elseif(isset($_POST['email'])){
    $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_contact.html");
    $page->SetParameter ('SITE_TITLE', $config['site_title']);
    $page->SetParameter ('NAME', $_POST['name']);
    $page->SetParameter ('EMAIL', $_POST['email']);
    $page->SetParameter('PHONE', $_POST['phone']);
    $page->SetParameter ('MESSAGE', $_POST['message']);
    $email_body = $page->CreatePageReturn($lang,$config,$mysqli);

    email($_POST['email'],$lang['CONTACT_SUBJECT_START'] . $_POST['username'],$email_body,$config,$mysqli);
    email($config['admin_email'],$lang['CONTACT_SUBJECT_START'] . $_POST['username'],$email_body,$config,$mysqli);
    echo $lang['SUCCESS'];
}
else
{
    header("Location: login.php?ref=");
    exit;
}
?>