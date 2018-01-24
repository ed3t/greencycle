<?php
if(!isset($_GET['i']))
{
    error($lang['INVALID-PAYMENT_PROCESS'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
}

$_GET['i'] = str_replace('.','',$_GET['i']);
$_GET['i'] = str_replace('/','',$_GET['i']);
$_GET['i'] = strip_tags($_GET['i']);

if(preg_match('[^A-Za-z0-9_]',$_GET['i']))
{
    error($lang['INVALID-PAYMENT_PROCESS'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
}

if(trim($_GET['i']) == '')
{
    error($lang['INVALID-PAYMENT_PROCESS'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
}

if(isset($_GET['i']))
{
    if(!isset($_POST['custom']))
    {
        error($lang['ERROR'], __LINE__, __FILE__, 1,$lang,$config,$link);
        exit();
    }
    // assign posted variables to local variables
    $transaction_id = $_POST['custom'];
    $amount = $_POST['amount'];
    $bank_information = $_POST['company_bank_info'];
    $item_name = $_POST['item_name'];

    $page = new HtmlTemplate ("includes/payments/wire_transfer/deposit.html");
    $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['PAYMENT'],$link));
    $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
    $page->SetParameter ('TRANSACTION_ID', $transaction_id);
    $page->SetParameter ('BANK_INFO', $bank_information);
    $page->SetParameter ('ORDER_TITLE', $item_name);
    $page->SetParameter ('AMOUNT', $amount);
    $page->SetParameter ('USERNAME', $_SESSION['user']['username']);
    $page->SetParameter('SITE_TITLE', $config['site_title']);
    $page->CreatePageEcho($lang,$config,$link);
}
?>