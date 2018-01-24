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

$merchant_id = get_option($config,'payment_merchant_email_id');
?>
<style type="text/css">
    .style1 {  font-size: 14px;  font-family: Verdana, Arial, Helvetica, sans-serif;  }
</style>
<body onLoad="javascript:document.paypal.submit();">
<form name="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $merchant_id;?>">
    <input type="hidden" name="item_name" value="<?php echo $trans_desc; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="currency_code" value="<?php echo $config['currency_code']; ?>">
    <input type="hidden" name="custom" value="<?php echo $transaction_id; ?>">
    <input type="hidden" name="return" value="<?php echo $config['site_url'].'dashboard.php'; ?>">
    <input type="hidden" name="notify_url" value="<?php echo $config['site_url'] . 'ipn.php?i=paypal'; ?>">
</form>
<div align="center" class="style1">Transfering you to the Paypal.com Secure payment system, if you are not forwarded <a href="javascript:document.paypal.submit();">click here</a></div>
</body>