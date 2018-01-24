<?php
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

?>

<style type="text/css">
    .style1 {  font-size: 14px;  font-family: Verdana, Arial, Helvetica, sans-serif;  }
</style>
<body onLoad="javascript:document.cheque.submit();">
<form name="cheque" action="<?php echo $config['site_url'].'ipn.php?i=cheque'; ?>" method="post">
    <input type="hidden" name="username" value="<?php echo $_SESSION['user']['username'];?>">
    <input type="hidden" name="item_name" value="<?php echo $trans_desc; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="custom" value="<?php echo $transaction_id; ?>">
    <input type="hidden" name="cheque_information" value="<?php echo nl2br(get_option($config,'company_cheque_info')); ?>">
    <input type="hidden" name="payable_to" value="<?php echo get_option($config,'cheque_payable_to'); ?>">
</form>
<div align="center" class="style1">Transfering you to the Secure Cheque payment system, if you are not forwarded <a href="javascript:document.cheque.submit();">click here</a></div>
</body>