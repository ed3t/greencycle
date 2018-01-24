<?php
if(!isset($_POST['pay_from_email']))
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

$payment_status = $_POST['status'];
$payment_amount = $_POST['mb_amount'];
$payment_currency = $_POST['mb_currency'];
$txn_id = $_POST['mb_transaction_id'];
$receiver_email = $_POST['pay_to_email'];
$payer_email = $_POST['pay_from_email'];
$custom = $_POST['transaction_id'];



if($receiver_email != $settings)
{
	mail($config['admin_email'],'Skrill error in '.$config['site_title'],'Skrill error in '.$config['site_title'].', address that the money was sent to does not match the settings');
    error($lang['ERROR_WITH_EMAIL'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
}

if ($payment_status == 2) 
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
    }else{
        error($lang['INVALID-PAYMENT_PROCESS'], __LINE__, __FILE__, 1,$lang,$config,$link);
        exit();
    }
}
else 
{
    $mysqli->query("UPDATE ". $config['db']['pre'] . "transaction set status = 'failed' where id='".$custom."' LIMIT 1");
 	mail($config['admin_email'],'Skrill error in '.$config['site_title'],'Skrill error in '.$config['site_title'].', status from Skrill');
    error($lang['INVALID-TRANSACTION'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
}
?>