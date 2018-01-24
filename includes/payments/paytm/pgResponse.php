<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {

	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";

	if ($_POST["STATUS"] == "TXN_SUCCESS") {

		echo "<b>Transaction status is success</b>" . "<br/>";
        echo "<b>Order ID</b>" .$paramList["ORDER_ID"]. "<br/>";
        echo "<b>Amount</b>" .$paramList["TXN_AMOUNT"]. "<br/>";

		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.

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
	else {
		echo "<b>Transaction status is failure</b>" . "<br/>";
        $mysqli->query("UPDATE ". $config['db']['pre'] . "transaction set status = 'failed' where id='".$custom."' LIMIT 1");
        mail($config['admin_email'],'Paytm error in '.$config['site_title'],'Paytm error in '.$config['site_title'].', Transaction status is failure from paytm');
        message($lang['ERROR'],$lang['TRANS_FAIL'], $config,$lang,$link);
        exit;
	}

	if (isset($_POST) && count($_POST)>0 )
	{
		foreach($_POST as $paramName => $paramValue) {
				echo "<br/>" . $paramName . " = " . $paramValue;
		}
	}


}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
    exit;
}

?>