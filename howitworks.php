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

$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/howitworks.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['HOW-IT-WORK'],$link));

$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$mysqli));
$page->CreatePageEcho($lang,$config,$link);

?>