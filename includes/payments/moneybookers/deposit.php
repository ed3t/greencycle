<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

if(!isset($product_id)) {
    header("Location: ../../../login.php");
    exit();
}


$query = "INSERT INTO ".$config['db']['pre']."transaction set
product_name = '".$_POST['title']."',
product_id = '".$product_id."',
seller_id = '".$_SESSION['user']['id']."',
amount = '$amount',
featured = '$featured',
urgent = '$urgent',
highlight = '$highlight',
transaction_time = '".time()."',
status = 'pending',
transaction_gatway = '$folder',
transaction_ip = '".encode_ip($_SERVER,$_ENV)."',
transaction_description = '$trans_desc',
transaction_method = 'Premium Ad'
";
$mysqli->query($query);

$transaction_id = $mysqli->insert_id;

$merchant_id = get_option($config,'skrill_merchant_id');
?>

<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>

<body onload="javascript:document.moneybookers.submit();">
<form name="moneybookers" action="https://www.moneybookers.com/app/payment.pl" method="post">
    <input type="hidden" name="pay_to_email" value="<?php echo $merchant_id;?>">
    <input type="hidden" name="detail1_description" value="Description:<?php echo $trans_desc; ?>">
    <input type="hidden" name="detail1_text" value="Deposit into <?php echo $config['site_title']; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="currency" value="<?php echo $config['currency_code']; ?>">
    <input type="hidden" name="transaction_id" value="<?php echo $transaction_id ?>">
    <input type="hidden" name="status_url" value="<?php echo $config['site_url'].'ipn.php?i=moneybookers'; ?>">
    <input type="hidden" name="return_url" value="<?php echo $config['site_url'].'dashboard.php'; ?>">
</form>

    
<div align="center" class="style1">Transfering you to the moneybookers.com Secure payment system, if you are not forwarded <a href="javascript:document.moneybookers.submit();">click here</a></div>
</body>