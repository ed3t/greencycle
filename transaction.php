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

if(checkloggedin()) {
    $transactions = array();
    $count = 0;
    $query = "SELECT * FROM `".$config['db']['pre']."transaction` WHERE seller_id='".$_SESSION['user']['id']."' order by id desc";
    $result = $mysqli->query($query);
    $total_item = mysqli_num_rows($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[$count]['id'] = $row['id'];
        $transactions[$count]['product_id'] = $row['product_id'];
        $transactions[$count]['product_name'] = $row['product_name'];
        $transactions[$count]['amount'] = $row['amount'];
        $transactions[$count]['payment_by'] = $row['transaction_gatway'];
        $transactions[$count]['time'] = date('d M Y h:i A', $row['transaction_time']);

        $pro_url = preg_replace("/[\s_]/","-", $row['product_name']);
        $product_link = $config['site_url'].'ad/' . $row['id'] . '/'.$pro_url.'/';
        $transactions[$count]['product_link'] = $product_link;

        $featured = $row['featured'];
        $urgent = $row['urgent'];
        $highlight = $row['highlight'];

        $premium = '';
        if ($featured == "1") {
            $premium = $premium . '<span class="label label-warning">Featured</span>';
        }

        if ($urgent == "1") {
            $premium = $premium . '<span class="label label-success">Urgent</span>';
        }

        if ($highlight == "1") {
            $premium = $premium . '<span class="label label-info">Highlight</span>';
        }

        $t_status = $row['status'];
        $status = '';
        if ($t_status == "success") {
            $status = '<span class="label label-success">Success</span>';
        } elseif ($t_status == "pending") {
            $status = '<span class="label label-warning">Pending</span>';
        } elseif ($t_status == "failed") {
            $status = '<span class="label label-danger">failed</span>';
        }

        $transactions[$count]['premium'] = $premium;
        $transactions[$count]['status'] = $status;

        $count++;
    }
    // Output to template
    $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/transaction.html');
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MY-ADS'],$link));
    $page->SetLoop ('TRANSACTIONS', $transactions);
    $page->SetLoop ('PAGES', pagenav($total_item,$_GET['page'],20,$config['site_url'].'transactions.php',0));
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