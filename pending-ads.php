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

if(!isset($_GET['page']))
    $_GET['page'] = 1;

$limit = 6;

if(checkloggedin()) {

    $items = get_items($config,$_SESSION['user']['id'],"pending",false,$_GET['page'],$limit);
    $total_item = get_items_count($config,$_SESSION['user']['id'],"pending");
    $pagging = pagenav($total_item,$_GET['page'],$limit,$config['site_url'].'pending-ads.php');

    // Output to template
    $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/ad-pending-approval.html');
    $page->SetParameter ('RESUBMITADS', resubmited_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('HIDDENADS', hidden_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['PENDING-ADS'],$link));
    $page->SetParameter ('PENDINGADS', pending_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('FAVORITEADS', favorite_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('MYADS', myads_count($config,$_SESSION['user']['id']));
    $page->SetLoop ('ITEM', $items);
    $page->SetLoop ('PAGES', $pagging);
    $page->SetParameter ('TOTALITEM', $total_item);
    $page->SetParameter('USER_ID',$_SESSION['user']['id']);
    $page->SetParameter ('USERNAME', $_SESSION['user']['username']);
    $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$mysqli));
    $page->CreatePageEcho($lang,$config,$link);
}
else{
    error($lang['PAGENOTEXIST'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
}
?>