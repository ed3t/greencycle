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

$count = 0;
$faq = array();

$query = "SELECT faq_id,faq_title,faq_content FROM `".$config['db']['pre']."faq_entries` ORDER BY faq_id";
$query_result = mysqli_query ($mysqli,$query);
while ($info = mysqli_fetch_array($query_result))
{
    $count++;

    $faq[$count]['id'] = $info['faq_id'];
    $faq[$count]['title'] = stripslashes($info['faq_title']);
    $faq[$count]['content'] = stripslashes($info['faq_content']);
}

$advertise_top = get_advertise($config,"top");

$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/faq.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,"FAQ",$link));
$page->SetLoop ('FAQ', $faq);
if(checkloggedin()) {
    $page->SetParameter('USER_ID', $_SESSION['user']['id']);
}else{
    $page->SetParameter('USER_ID', "");
}
/*Advertisement Fetching*/
$page->SetParameter('TOP_ADSCODE', get_advertise($config,"top"));
/*Advertisement Fetching*/
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
$page->CreatePageEcho($lang,$config,$link);
?>