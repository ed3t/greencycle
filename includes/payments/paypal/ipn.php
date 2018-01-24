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
	//require_once('includes/payments/paypal/ipn.php');

    if(!isset($_POST['receiver_email']))
    {
        error($lang['INVALID-PAYMENT_PROCESS'], __LINE__, __FILE__, 1,$lang,$config,$link);
        exit();
    }

// read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';

    foreach ($_POST as $key => $value)
    {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
    }

// post back to PayPal system to validate
    $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

    // assign posted variables to local variables
     $item_name = $_POST['item_name'];
     $item_number = $_POST['item_number'];
     $payment_status = $_POST['payment_status'];
     $payment_amount = $_POST['mc_gross'];
     $payment_currency = $_POST['mc_currency'];
     $txn_id = $_POST['txn_id'];
     $receiver_email = $_POST['receiver_email'];
     $payer_email = $_POST['payer_email'];
     $custom = $_POST['custom'];


    if($receiver_email != $settings)
    {
        mail($config['admin_email'],'Paypal error in '.$config['site_title'],'Paypal error in '.$config['site_title'].', address that the money was sent to does not match the settings');
        error($lang['ERROR_WITH_EMAIL'], __LINE__, __FILE__, 1,$lang,$config,$link);
        exit();
    }

    if (!$fp)
    {
        // HTTP ERROR
    }
    else
    {
        fputs ($fp, $header . $req);
        while (!feof($fp))
        {
            $res = fgets ($fp, 1024);
            if (strcmp ($res, "VERIFIED") == 0)
            {
                $result = $mysqli->query("SELECT * FROM `".$config['db']['pre']."transaction` WHERE `id` = '" . addslashes($custom) . "' LIMIT 1");
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    $info = mysqli_fetch_assoc($result);

                    $item_pro_id = $info['product_id'];
                    $item_amount = $info['amount'];
                    $item_featured = $info['featured'];
                    $item_urgent = $info['urgent'];
                    $item_highlight = $info['highlight'];

                    $mysqli->query("UPDATE ". $config['db']['pre'] . "transaction set status = 'success' where id='".$custom."' LIMIT 1");

                    $mysqli->query("UPDATE ". $config['db']['pre'] . "product set featured = '$item_featured',urgent = '$item_urgent',highlight = '$item_highlight' where id='".$item_pro_id."' LIMIT 1");

                    if(check_valid_resubmission($config,$item_pro_id)){
                        $mysqli->query("UPDATE ". $config['db']['pre'] . "product_resubmit set featured = '$item_featured',urgent = '$item_urgent',highlight = '$item_highlight' where product_id='".$item_pro_id."' LIMIT 1");
                    }

                    $result2 = $mysqli->query("SELECT * FROM `".$config['db']['pre']."balance` WHERE id = '1' LIMIT 1");
                    if (mysqli_num_rows($result2) > 0) {
                        $info2 = mysqli_fetch_assoc($result2);
                        $current_amount=$info2['current_balance'];
                        $total_earning=$info2['total_earning'];

                        $updated_amount=($item_amount+$current_amount);
                        $total_earning=($item_amount+$total_earning);

                        $mysqli->query("UPDATE ". $config['db']['pre'] . "balance set current_balance = '" . $updated_amount . "', total_earning = '" . $total_earning . "' where id='1' LIMIT 1");
                    }

                    message($lang['SUCCESS'],$lang['PAYMENTSUCCESS'], $config,$lang,$link);
                    exit;
                }
            }
            else if (strcmp ($res, "INVALID") == 0)
            {
                $mysqli->query("UPDATE ". $config['db']['pre'] . "transaction set status = 'failed' where id='".$custom."' LIMIT 1");
                mail($config['admin_email'],'Paypal error in '.$config['site_title'],'Paypal error in '.$config['site_title'].', invalid response from paypal');
                error($lang['INVALID-TRANSACTION'], __LINE__, __FILE__, 1,$lang,$config,$link);
                exit();
            }
        }
        fclose ($fp);
    }
}
?>