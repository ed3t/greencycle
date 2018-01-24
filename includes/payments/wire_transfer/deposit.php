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



?>
<style type="text/css">
    .style1 {  font-size: 14px;  font-family: Verdana, Arial, Helvetica, sans-serif;  }
</style>
<body onLoad="javascript:document.wiretransfer.submit();">
<form name="wiretransfer" action="<?php echo $config['site_url'].'ipn.php?i=wire_transfer'; ?>" method="post">
    <input type="hidden" name="username" value="<?php echo $_SESSION['user']['username'];?>">
    <input type="hidden" name="item_name" value="<?php echo $trans_desc; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="custom" value="<?php echo $transaction_id; ?>">
    <input type="hidden" name="company_bank_info" value="<?php echo nl2br(get_option($config,'company_bank_info')); ?>">
</form>
<div align="center" class="style1">Transfering you to the Secure Wire transfer payment system, if you are not forwarded <a href="javascript:document.wiretransfer.submit();">click here</a></div>
</body>