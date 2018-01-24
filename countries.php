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

$countries = array();
$count = 1;

$query = "SELECT * FROM ".$config['db']['pre']."countries where install = '1' ORDER BY name";
$query_result = mysqli_query(db_connect($config),$query);
$total = mysqli_num_rows($query_result);
$divide = intval($total/4)+1;
$col = "";
while ($info = mysqli_fetch_array($query_result))
{
    $countries[$count]['tpl'] = "";
    if($count == 1 or $count == $col){
        $countries[$count]['tpl'] .= '<ul class="flag-list col-xs-3 ">';
        $checkEnd = $count+$divide-1;
        $col = $count+$divide;
        //echo "Start : ".$divide."<br>";
    }
    $countries[$count]['tpl'] .= '<li><span class="flag flag-'.strtolower($info['sortname']).'"></span><a href="'.$config['site_url'].'index/'.$info['sortname'].'" data-id="'.$info['id'].'" data-name="'.$info['name'].'">'.$info['name'].'</a></li>';


    if($count == $checkEnd or $count == $total){
        $countries[$count]['tpl'] .= '</ul>';
        //echo "end : ".$checkEnd."<br>";
    }
    $count++;
}


$title = "Free Local Classified Ads in the World";
$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/countries.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$title,$link));
$page->SetLoop ('COUNTRYLIST',$countries);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
$page->CreatePageEcho($lang,$config,$link);
?>