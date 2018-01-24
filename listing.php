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

if(!isset($_GET['sort']))
    $sort = "id";
elseif($_GET['sort'] == "title")
    $sort = "product_name";
elseif($_GET['sort'] == "price")
    $sort = "price";
elseif($_GET['sort'] == "date")
    $sort = "created_at";
else
    $sort = "id";

$limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
$filter = isset($_GET['filter']) ? $_GET['filter'] : "";
$sorting = isset($_GET['sort']) ? $_GET['sort'] : "Newest";
$budget = isset($_GET['budget']) ? $_GET['budget'] : "";
$keywords = isset($_GET['keywords']) ? str_replace("-"," ",$_GET['keywords']) : "";


if(isset($_GET['cat']) && !empty($_GET['cat'])){
    $category = $_GET['cat'];
}else{
    $category = "";
}
if(isset($_GET['subcat']) && !empty($_GET['subcat'])){
    $subcat = $_GET['subcat'];
}else{
    $subcat = "";
}

if(isset($_GET['city']) && !empty($_GET['city'])){
    $city = $_GET['city'];
}else{
    $city = "";
}


$total = 0;

    $where = '';

    if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
        $where.= "AND (product_name LIKE '%$keywords%' or tag LIKE '%$keywords%') ";
    }

    if(isset($category) && !empty($category)){
        $where.= "AND (category = '$category') ";
    }

    if(isset($_GET['subcat']) && !empty($_GET['subcat'])){
        $where.= "AND (sub_category = '$subcat') ";
    }


    if (isset($_GET['range1']) && $_GET['range1'] != '') {
        $range1 = str_replace('.', '', $_GET['range1']);
        $range2 = str_replace('.', '', $_GET['range2']);
        $where.= ' AND (price BETWEEN '.$range1.' AND '.$range2.')';
    } else {
        $range1 = "";
        $range2 = "";
    }

    if(isset($_GET['city']) && !empty($_GET['city']))
    {
        $where.= "AND (city = '".$_GET['city']."') ";
    }
    elseif(isset($_GET['location']) && !empty($_GET['location']))
    {
        $placetype = $_GET['placetype'];
        $placeid = $_GET['placeid'];

        if($placetype == "country"){
            $where.= "AND (country = '$placeid') ";
        }elseif($placetype == "state"){
            $where.= "AND (state = '$placeid') ";
        }else{
            $where.= "AND (city = '$placeid') ";
        }
    }
    else{
        $sortname = check_user_country($config);
        $country_id = get_countryID_by_sortname($config,$sortname);
        $where.= "AND (country = '$country_id') ";
    }

    $totalWithoutFilter = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."product where status = 'active' $where"));

    if(isset($_GET['filter'])){
        if($_GET['filter'] == 'free')
        {
            $where.= "AND (urgent='0' AND featured='0' AND highlight='0') ";
        }
        elseif($_GET['filter'] == 'featured')
        {
            $where.= "AND (featured='1') ";
        }
        elseif($_GET['filter'] == 'urgent')
        {
            $where.= "AND (urgent='1') ";
        }
        elseif($_GET['filter'] == 'highlight')
        {
            $where.= "AND (highlight='1') ";
        }
    }

    $count = 0;

    $total = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."product where status = 'active' $where"));

    $query = "SELECT * FROM ".$config['db']['pre']."product where status = 'active' $where ORDER BY $sort DESC LIMIT ".($_GET['page']-1)*$limit.",$limit";

    $featuredAds = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."product where status = 'active' and featured='1' $where"));
    $urgentAds = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."product where status = 'active' and urgent='1' $where"));

//Loop for list view
$item = array();
$result = $mysqli->query($query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($info = mysqli_fetch_assoc($result)) {
        $item[$info['id']]['id'] = $info['id'];
        $item[$info['id']]['featured'] = $info['featured'];
        $item[$info['id']]['urgent'] = $info['urgent'];
        $item[$info['id']]['highlight'] = $info['highlight'];
        $item[$info['id']]['product_name'] = $info['product_name'];
        $item[$info['id']]['description'] = $info['description'];
        $item[$info['id']]['category'] = $info['category'];
        $item[$info['id']]['price'] = $info['price'];
        $item[$info['id']]['phone'] = $info['phone'];
        $item[$info['id']]['address'] = strlimiter($info['location'],20);
        $item[$info['id']]['location'] = get_cityName_by_id($config,$info['city']);
        $item[$info['id']]['city'] = get_cityName_by_id($config,$info['city']);
        $item[$info['id']]['state'] = get_stateName_by_id($config,$info['state']);
        $item[$info['id']]['country'] = get_countryName_by_id($config,$info['country']);
        $item[$info['id']]['latlong'] = $info['latlong'];

        $item[$info['id']]['tag'] = $info['tag'];
        $item[$info['id']]['status'] = $info['status'];
        $item[$info['id']]['view'] = $info['view'];
        $item[$info['id']]['created_at'] = timeAgo($info['created_at']);
        $item[$info['id']]['updated_at'] = date('d M Y', $info['updated_at']);

        $item[$info['id']]['cat_id'] = $info['category'];
        $item[$info['id']]['sub_cat_id'] = $info['sub_category'];
        $get_main = get_maincat_by_id($config,$info['category']);
        $get_sub = get_subcat_by_id($config,$info['sub_category']);
        $item[$info['id']]['category'] = $get_main['cat_name'];
        $item[$info['id']]['sub_category'] = $get_sub['sub_cat_name'];

        $item[$info['id']]['favorite'] = check_product_favorite($config,$info['id']);

        $picture     =   explode(',' ,$info['screen_shot']);
        $picture     =   $picture[0];
        $item[$info['id']]['picture'] = $picture;

        $tag = explode(',', $info['tag']);
        $tag2 = array();
        foreach ($tag as $val)
        {
            //REMOVE SPACE FROM $VALUE ----
            $val = preg_replace("/[\s_]/","-", trim($val));
            $tag2[] = '<li><a href="'.$config['site_url'].'listing/keywords/'.$val.'">'.$val.'</a> </li>';
        }
        $item[$info['id']]['tag'] = implode('  ', $tag2);

        $user = "SELECT username FROM ".$config['db']['pre']."user where id='".$info['user_id']."'";
        $userresult = mysqli_query(db_connect($config), $user);
        $userinfo = mysqli_fetch_assoc($userresult);

        $item[$info['id']]['username'] = $userinfo['username'];

        $author_url = preg_replace("/[\s_]/","-", $userinfo['username']);

        $item[$info['id']]['author_link'] = $config['site_url'].'profile/'.$author_url;

        $pro_url = preg_replace("/[\s_]/","-", $info['product_name']);

        $item[$info['id']]['link'] = $config['site_url'].'ad/' . $info['id'] . '/'.$pro_url.'/';

        $cat_url = preg_replace("/[\s_]/","-", $get_main['cat_name']);
        $item[$info['id']]['catlink'] = $config['site_url'].'listing/cat/'.$info['category'].'/'.$cat_url.'/';

        $subcat_url = preg_replace("/[\s_]/","-", $get_sub['sub_cat_name']);
        $item[$info['id']]['subcatlink'] = $config['site_url'].'listing/subcat/'.$info['sub_category'].'/'.$subcat_url.'/';

        $city = preg_replace("/[\s_]/","-", $item[$info['id']]['city']);
        $item[$info['id']]['citylink'] = $config['site_url'].'listing/city/'.$info['city'].'/'.$city.'/';
    }
}
else
{
    //echo "0 results";
}

//Again make loop for grid view
$item2 = array();
$result2 = $mysqli->query($query);
if (mysqli_num_rows($result2) > 0) {
    // output data of each row
    while($info2 = mysqli_fetch_assoc($result2)) {
        $item2[$info2['id']]['id'] = $info2['id'];
        $item2[$info2['id']]['featured'] = $info2['featured'];
        $item2[$info2['id']]['urgent'] = $info2['urgent'];
        $item2[$info2['id']]['highlight'] = $info2['highlight'];
        $item2[$info2['id']]['product_name'] = $info2['product_name'];
        $item2[$info2['id']]['description'] = $info2['description'];
        $item2[$info2['id']]['category'] = $info2['category'];
        $item2[$info2['id']]['price'] = $info2['price'];
        $item2[$info2['id']]['phone'] = $info2['phone'];
        $item2[$info2['id']]['address'] = strlimiter($info2['location'],20);
        $item2[$info2['id']]['location'] = get_cityName_by_id($config,$info2['city']);
        $item2[$info2['id']]['city'] = get_cityName_by_id($config,$info2['city']);
        $item2[$info2['id']]['state'] = get_stateName_by_id($config,$info2['state']);
        $item2[$info2['id']]['country'] = get_countryName_by_id($config,$info2['country']);
        $item2[$info2['id']]['latlong'] = $info2['latlong'];
        $item2[$info2['id']]['tag'] = $info2['tag'];
        $item2[$info2['id']]['status'] = $info2['status'];
        $item2[$info2['id']]['view'] = $info2['view'];
        $item2[$info2['id']]['created_at'] = timeAgo($info2['created_at']);
        $item2[$info2['id']]['updated_at'] = date('d M Y', $info2['updated_at']);

        $item2[$info2['id']]['cat_id'] = $info2['category'];
        $item2[$info2['id']]['sub_cat_id'] = $info2['sub_category'];
        $get_main = get_maincat_by_id($config,$info2['category']);
        $get_sub = get_subcat_by_id($config,$info2['sub_category']);
        $item2[$info2['id']]['category'] = $get_main['cat_name'];
        $item2[$info2['id']]['sub_category'] = $get_sub['sub_cat_name'];

        $item2[$info2['id']]['favorite'] = check_product_favorite($config,$info2['id']);

        $picture2     =   explode(',' ,$info2['screen_shot']);
        $picture2     =   $picture2[0];
        $item2[$info2['id']]['picture'] = $picture2;

        $tag = explode(',', $info2['tag']);
        $tag2 = array();
        foreach ($tag as $val)
        {
            //REMOVE SPACE FROM $VALUE ----
            $val = preg_replace("/[\s_]/","-", trim($val));
            $tag2[] = '<li><a href="'.$config['site_url'].'listing/'.$val.'">'.$val.'</a> </li>';
        }
        $item2[$info2['id']]['tag'] = implode('  ', $tag2);

        $user2 = "SELECT username FROM ".$config['db']['pre']."user where id='".$info2['user_id']."'";
        $userresult2 = mysqli_query(db_connect($config), $user2);
        $userinfo2 = mysqli_fetch_assoc($userresult2);

        $item2[$info2['id']]['username'] = $userinfo2['username'];
        $author_url = preg_replace("/[\s_]/","-", $userinfo2['username']);

        $item2[$info2['id']]['author_link'] = $config['site_url'].'profile/'.$author_url;

        $pro_url = preg_replace("/[\s_]/","-", $info2['product_name']);

        $item2[$info2['id']]['link'] = $config['site_url'].'ad/' . $info2['id'] . '/'.$pro_url.'/';

        $cat_url = preg_replace("/[\s_]/","-", $get_main['cat_name']);
        $item2[$info2['id']]['catlink'] = $config['site_url'].'listing/cat/'.$info2['category'].'/'.$cat_url.'/';

        $subcat_url = preg_replace("/[\s_]/","-", $get_sub['sub_cat_name']);
        $item2[$info2['id']]['subcatlink'] = $config['site_url'].'listing/subcat/'.$info2['sub_category'].'/'.$subcat_url.'/';

        $city = preg_replace("/[\s_]/","-", $item2[$info2['id']]['city']);
        $item2[$info2['id']]['citylink'] = $config['site_url'].'listing/city/'.$info2['city'].'/'.$city.'/';
    }
}
else
{
    //echo "0 results";
}



$selected = "";
if(isset($_GET['cat']) && !empty($_GET['cat'])){
    $selected = $_GET['cat'];
}
// Check Settings For quotes
$GetCategory = get_maincategory($config,$selected);

$cat_dropdown = get_categories_dropdown($config);


$subCategory = isset($subcat) ? get_subcat_by_id($config,$subcat) : "";
$mainCategory = isset($category) ? get_maincat_by_id($config,$category) : "";

$Pagetitle = "";
if(isset($category) && !empty($category)){
    $Pagetitle = $mainCategory['cat_name'];
}
elseif(isset($subcat) && !empty($subcat)){
    $Pagetitle = $subCategory['sub_cat_name'];
}
elseif(!empty($keywords)){
    $Pagetitle = ucfirst($keywords);
}
else{
    $Pagetitle = $lang['ADS-LISTINGS'];
}

if(!empty($_GET['location'])){
    $locTitle        =   explode(',' ,$_GET['location']);
    $locTitle     =   $locTitle[0];
    $Pagetitle .= " in ".$locTitle;
}
else{
    $sortname = check_user_country($config);
    $countryName = get_countryName_by_sortname($config,$sortname);
    $Pagetitle .= " in ".$countryName;
}

if(isset($_GET['city']) && !empty($_GET['city']))
{
    $cityName = get_cityName_by_id($config,$_GET['city']);
    $Pagetitle = $lang['ADS-LISTINGS']." in ".$cityName;
}

// Output to template
$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/ad-listing.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$Pagetitle,$link));
$page->SetParameter ('PAGETITLE', $Pagetitle);
$page->SetLoop ('ITEM', $item);
$page->SetLoop ('ITEM2', $item2);
$page->SetLoop ('CATEGORY',$GetCategory);
$page->SetParameter ('CAT_DROPDOWN',$cat_dropdown);
$page->SetParameter ('SERKEY', $keywords);
$page->SetParameter ('MAINCAT', $category);
$page->SetParameter ('SUBCAT', $subcat);
$page->SetParameter ('MAINCATEGORY', $mainCategory['cat_name']);
$page->SetParameter ('SUBCATEGORY', $subCategory['sub_cat_name']);
$page->SetParameter ('BUDGET', $budget);
$page->SetParameter ('KEYWORDS', $keywords);
$page->SetParameter ('RANGE1', $range1);
$page->SetParameter ('RANGE2', $range2);
$page->SetParameter ('ADSFOUND', $total);
$page->SetParameter ('TOTALADSFOUND', $totalWithoutFilter);
$page->SetParameter ('FEATUREDFOUND', $featuredAds);
$page->SetParameter ('URGENTFOUND', $urgentAds);
$page->SetParameter ('LIMIT', $limit);
$page->SetParameter ('FILTER', $filter);
$page->SetParameter ('SORT', $sorting);
if(isset($_SESSION['user']['id']))
{
    $page->SetParameter('USER_ID',$_SESSION['user']['id']);
    $page->SetParameter('LOGGED_IN', 1);
}
else
{
    $page->SetParameter('USER_ID','');
    $page->SetParameter('LOGGED_IN', 0);
}
$page->SetLoop ('PAGES', pagenav($total,$_GET['page'],$limit,$config['site_url'].'listing.php'));
$page->SetParameter ('CATEGORY', "Ads Listing");
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
$page->CreatePageEcho($lang,$config,$link);
?>