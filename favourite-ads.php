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

$limit = 4;
$page = $_GET['page'];

if(checkloggedin()) {
    $item = array();

    $pagelimit = "";
    if($page != null && $limit != null){
        $pagelimit = "LIMIT  ".($page-1)*$limit.",".$limit;
    }

    $total_item = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."favads where `user_id` = '".$_SESSION['user']['id']."'"));

    $queryFav = "SELECT product_id FROM ".$config['db']['pre']."favads WHERE `user_id` = '".$_SESSION['user']['id']."' $pagelimit";
    $query_result = mysqli_query($mysqli,$queryFav);
    if (mysqli_num_rows($query_result) > 0) {
        while ($fav = mysqli_fetch_assoc($query_result)) {

            $query = "SELECT * FROM `".$config['db']['pre']."product` where id = '".$fav['product_id']."'  ORDER BY id DESC LIMIT $limit";
            $result = $mysqli->query($query);
            if (mysqli_num_rows($result) > 0) {
                while ($info = mysqli_fetch_assoc($result)) {
                    $item[$info['id']]['id'] = $info['id'];
                    $item[$info['id']]['product_name'] = $info['product_name'];
                    $item[$info['id']]['featured'] = $info['featured'];
                    $item[$info['id']]['urgent'] = $info['urgent'];
                    $item[$info['id']]['highlight'] = $info['highlight'];
                    $item[$info['id']]['price'] = $info['price'];
                    $item[$info['id']]['address'] = strlimiter($info['location'],20);
                    $item[$info['id']]['location'] = get_cityName_by_id($config,$info['city']);
                    $item[$info['id']]['city'] = get_cityName_by_id($config,$info['city']);
                    $item[$info['id']]['state'] = get_stateName_by_id($config,$info['state']);
                    $item[$info['id']]['country'] = get_countryName_by_id($config,$info['country']);
                    $item[$info['id']]['status'] = $info['status'];
                    $item[$info['id']]['created_at'] = timeago($info['created_at']);

                    $item[$info['id']]['cat_id'] = $info['category'];
                    $item[$info['id']]['sub_cat_id'] = $info['sub_category'];

                    $get_main = get_maincat_by_id($config,$info['category']);
                    $get_sub = get_subcat_by_id($config,$info['sub_category']);
                    $item[$info['id']]['category'] = $get_main['cat_name'];
                    $item[$info['id']]['sub_category'] = $get_sub['sub_cat_name'];

                    $item[$info['id']]['favorite'] = check_product_favorite($config,$info['id']);

                    $tag = explode(',', $info['tag']);
                    $tag2 = array();
                    foreach ($tag as $val)
                    {
                        //REMOVE SPACE FROM $VALUE ----
                        $val = trim($val);
                        $tag2[] = '<li><a href="listing.php?keywords='.$val.'">'.$val.'</a> </li>';
                    }
                    $item[$info['id']]['tag'] = implode('  ', $tag2);

                    $picture     =   explode(',' ,$info['screen_shot']);
                    $picture     =   $picture[0];
                    $item[$info['id']]['picture'] = $picture;

                    $userinfo = get_user_data($config,null,$info['user_id']);

                    $item[$info['id']]['username'] = $userinfo['username'];
                    $author_url = preg_replace("/[\s_]/","-", $userinfo['username']);

                    if($config['mod_rewrite'] == 0)
                        $item[$info['id']]['author_link'] = $config['site_url'].'profile.php?username='.$author_url;
                    else
                        $item[$info['id']]['author_link'] = $config['site_url'].'profile/'.$author_url;

                    $pro_url = preg_replace("/[\s_]/","-", $info['product_name']);

                    if($config['mod_rewrite'] == 0)
                        $item[$info['id']]['link'] = $config['site_url'].'ad-detail.php?id=' . $info['id'] . '/'.$pro_url.'/';
                    else
                        $item[$info['id']]['link'] = $config['site_url'].'ad/' . $info['id'] . '/'.$pro_url.'/';

                    if($config['mod_rewrite'] == 0)
                        $item[$info['id']]['catlink'] = $config['site_url'].'listing.php?cat='.$info['category'];
                    else
                        $item[$info['id']]['catlink'] = $config['site_url'].'listing/'.$info['category'].'/'.$get_main['cat_name'].'/';

                    if($config['mod_rewrite'] == 0)
                        $item[$info['id']]['subcatlink'] = $config['site_url'].'listing.php?subcat='.$info['category'];
                    else
                        $item[$info['id']]['subcatlink'] = $config['site_url'].'listing/'.$info['sub_category'].'/'.$get_sub['sub_cat_name'].'/';
                }
            }


        }
    }


    $pagging = pagenav($total_item,$page,$limit,$config['site_url'].'favourite-ads.php');
    // Output to template
    $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/ad-favourite.html');
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['FAVOURITE-ADS'],$link));
    $page->SetParameter ('RESUBMITADS', resubmited_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('HIDDENADS', hidden_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('PENDINGADS', pending_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('FAVORITEADS', favorite_ads_count($config,$_SESSION['user']['id']));
    $page->SetParameter ('MYADS', myads_count($config,$_SESSION['user']['id']));
    $page->SetLoop ('ITEM', $item);
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