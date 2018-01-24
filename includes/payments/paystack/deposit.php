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

$paystack_public_key = get_option($config,'paystack_public_key');

$email = "customer@email.com";
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    function payWithPaystack(){
        var handler = PaystackPop.setup({
            key: '<?php echo $paystack_public_key; ?>',
            email: '<?php echo $email; ?>',
            amount: <?php echo $amount; ?>,
            ref: '<?php echo $transaction_id; ?>', // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
            metadata: {
                custom_fields: [
                    {
                        display_name: "Blank",
                        product_id: "Blank",
                        value: "Blank"
                    }
                ]
            },
            callback: function(response){
                var transaction_id = response.reference;
                var payment_status = "success";
                $('#transaction_id').val(transaction_id);
                $('#payment_status').val(payment_status);
                $("#paystack").submit();
            },
            onClose: function(){
                $('#transaction_id').val("Null");
                $('#payment_status').val("canceled");
                $("#paystack").submit();
            }
        });
        handler.openIframe();
    }
</script>

<body onLoad="javascript:payWithPaystack();">
<form name="paystack" id="paystack" action="<?php echo $config['site_url'].'ipn.php?i=paystack'; ?>" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="custom" id="transaction_id" value="">
    <input type="hidden" name="status" id="payment_status" value="">
</form>
</body>