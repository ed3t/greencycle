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

$query = "SELECT * FROM ".$config['db']['pre']."catagory_main order by cat_id ASC";
$query_result = mysqli_query($mysqli,$query);
while ($info = mysqli_fetch_array($query_result))
{
    $cat[$info['cat_id']]['icon'] = $info['icon'];
    $cat[$info['cat_id']]['main_title'] = $info['cat_name'];
    $cat[$info['cat_id']]['main_id'] = $info['cat_id'];
    $cat_url = preg_replace("/[\s_]/","-", $info['cat_name']);
    $cat[$info['cat_id']]['catlink'] = $config['site_url'].'listing/cat/'.$info['cat_id'].'/'.$cat_url.'/';

    $totalAdsMaincat = get_items_count($config,false,"active",false,null,$info['cat_id'],true);
    $cat[$info['cat_id']]['main_ads_count'] = $totalAdsMaincat;
    $count = 1;
    $query1 = "SELECT * FROM ".$config['db']['pre']."catagory_sub WHERE `main_cat_id` = '".$info['cat_id']."'";
    $query_result1 = mysqli_query($mysqli,$query1);
    while ($info1 = mysqli_fetch_array($query_result1))
    {
        $totalads = get_items_count($config,false,"active",false,$info1['sub_cat_id'],null,true);
        $subcat_url = preg_replace("/[\s_]/","-", $info1['sub_cat_name']);
        $subcat_tpl = '<li><a href="listing/subcat/'.$info1['sub_cat_id'].'/'.$subcat_url.'">'.$info1['sub_cat_name'].' ('.$totalads.')</a></li>';

        if($count == 1)
            $cat[$info['cat_id']]['sub_title'] = $subcat_tpl;
        else
            $cat[$info['cat_id']]['sub_title'] .= $subcat_tpl;


        $count++;
    }
}

$page = new HtmlTemplate ('templates/'.$config['tpl_name'].'/sitemap.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,"Product Categories",$link));
$page->SetLoop ('CAT',$cat);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
$page->CreatePageEcho($lang,$config,$link);
?>
