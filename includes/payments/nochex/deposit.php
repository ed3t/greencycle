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

$merchant_id = get_option($config,'nochex_merchant_id');
?>
<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>
<body onload="javascript:document.nochex.submit();">
<form name="nochex" id="nochex" method="POST" action="https://secure.nochex.com/">
<input type="hidden" name="merchant_id" value="<?php echo $merchant_id;?>">
<input type="hidden" name="amount" value="<?php echo $amount; ?>">
<input type="hidden" name="description" value="<?php echo $trans_desc; ?>">
<input type="hidden" name="order_id" value="GRU1625">
<input type="hidden" name="optional_1" value="<?php echo $transaction_id; ?>">
<input type="hidden" name="responder_url" value="<?php echo $config['site_url'] . 'ipn.php?i=nochex'; ?>">
</form>
<div align="center" class="style1">Transfering you to the NoChex Secure payment system, if you are not forwarded <a href="javascript:document.nochex.submit();">click here</a></div>
</body>