<?php
if(!isset($_POST['to_email']))
{
    error($lang['INVALID-PAYMENT_PROCESS'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
}

$transaction_id = $_POST['transaction_id']; 
$payment_amount = $_POST['amount']; 
$order_id       = $_POST['order_id']; 
$from_email     = $_POST['from_email']; 
$to_email       = $_POST['to_email']; 
$status         = $_POST['status']; 
$custom			= $_POST['custom'];

if($to_email != $settings)
{
	mail($config['admin_email'],'NoChex error in Bylancer','NoChex error in Bylancer, address that the money was sent to does not match the settings');
    error($lang['INVALID-TRANSACTION'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
}

$req = ''; 
foreach ($_POST as $key => $value) 
{
	$value = urlencode(stripslashes($value)); 
	$req  .= "&$key=$value"; 
} 
$req = ltrim($req,'&'); 

$header  = "POST /nochex.dll/apc/apc HTTP/1.0\r\n"; 
$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n"; 
$fp      = fsockopen('ssl://www.nochex.com', 443, $errno, $errstr, 10); 

if ($fp) 
{
	fputs($fp, $header . $req); 

	while (!feof($fp)) 
	{
    	$apc_status = fgets($fp, 1024); 

		if ($apc_status == 'AUTHORISED') 
		{

			switch ($payment_status)
			{
				case 'Completed':
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
				break;
				case 'Pending':
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
                break;
				case 'Failed':
					mail($config['admin_email'],'NoChex Payment Failed','Someone tried to deposit money into Bylancer but it failed');
                    error($lang['FAILED-TRANSACTION'], __LINE__, __FILE__, 1,$lang,$config,$link);
                    exit();
				break;
			}
		} 
		else if ($apc_status == 'DECLINED') 
		{
			mail($config['admin_email'],'NoChex Payment Declined','Someone tried to deposit money into Bylancer but it was declined');
            error($lang['DECLINED-TRANSACTION'], __LINE__, __FILE__, 1,$lang,$config,$link);
            exit();
		} 
  } 

  fclose($fp); 
}
else 
{
	mail($config['admin_email'],'NoChex Error',$errstr.' ('.$errno.')');
    error($lang['ERROR-TRANSACTION'], __LINE__, __FILE__, 1,$lang,$config,$link);
    exit();
} 
?>