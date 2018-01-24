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

//Loop for Premium Ads and (featured = 1 or urgent = 1 or highlight = 1)

$item = get_items($config,"","active",true,1,10,"id",true);
$item2 = get_items($config,"","active",false,1,20,"id",true);

$category = get_maincategory($config,$mysqli);
$cat_dropdown = get_categories_dropdown($config);



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
    $query1 = "SELECT * FROM ".$config['db']['pre']."catagory_sub WHERE `main_cat_id` = '".$info['cat_id']."' LIMIT 4";
    $query_result1 = mysqli_query($mysqli,$query1);
    while ($info1 = mysqli_fetch_array($query_result1))
    {
        $totalads = get_items_count($config,false,"active",false,$info1['sub_cat_id'],null,true);



        $subcat_url = preg_replace("/[\s_]/","-", $info1['sub_cat_name']);
        $subcatlink = $config['site_url'].'listing/subcat/'.$info1['sub_cat_id'].'/'.$subcat_url.'/';

        if($count == 1)
            $cat[$info['cat_id']]['sub_title'] = '<li><a href="'.$subcatlink.'" title="'.$info1['sub_cat_name'].'">'.$info1['sub_cat_name'].'</a><figure class="count">'.$totalads.'</figure></li>';
        else
            $cat[$info['cat_id']]['sub_title'] .= '<li><a href="'.$subcatlink.'" title="'.$info1['sub_cat_name'].'">'.$info1['sub_cat_name'].'</a><figure class="count">'.$totalads.'</figure></li>';

        if($count == 4)
            $cat[$info['cat_id']]['sub_title'] .= '<li><a href="sitemap.php" style="color: #6f6f6f;text-decoration: underline;">View More...</a></li>';

        $count++;
    }
}

// Output to template
$page = new HtmlTemplate ('templates/'.$config['tpl_name'].'/index.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['HOME-IMAGE'],$link));
$page->SetLoop ('ITEM', $item);
$page->SetLoop ('ITEM2', $item2);
$page->SetLoop ('CATEGORY',$category);
$page->SetParameter ('CAT_DROPDOWN',$cat_dropdown);
$page->SetLoop ('CAT',$cat);
if(checkloggedin()) {
    $page->SetParameter('USER_ID', $_SESSION['user']['id']);
}else{
    $page->SetParameter('USER_ID', "");
}
/*Advertisement Fetching*/
$page->SetParameter('TOP_ADSCODE', get_advertise($config,"top"));
$page->SetParameter('BOTTOM_ADSCODE', get_advertise($config,"bottom"));
$page->SetParameter('LEFT_ADSCODE', get_advertise($config,"left_sidebar"));
$page->SetParameter('RIGHT_ADSCODE', get_advertise($config,"right_sidebar"));
/*Advertisement Fetching*/
$page->SetParameter('BANNER_IMAGE', get_option($config,"home_banner"));
$page->SetParameter('HEADING', get_option($config,"home_heading"));
$page->SetParameter('SUB_HEADING', get_option($config,"home_sub_heading"));
$page->SetParameter('SPECIFIC_COUNTRY', check_user_country($config));
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
$page->CreatePageEcho($lang,$config,$link);
?>