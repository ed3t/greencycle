<?php
require_once('../includes/config.php');
require_once('../includes/lang/lang_'.$config['lang'].'.php');

$subjects['0'] = 'What is the Site Title?';
$messages['0'] = 'The site title is what you would like your website to be known as, this will be used in emails and in the title of your webpages.';

$subjects['1'] = 'What is the Site Url?';
$messages['1'] = 'The site url is the url where you installed KubeLance.';

$subjects['2'] = 'What are Meta Keywords?';
$messages['2'] = 'Meta Keywords ar used by Search engines to identify a sites content, these will be automatically inserted into the overall_header template using the {META_KEYWORDS} tag.';

$subjects['3'] = 'What is the Meta Description?';
$messages['3'] = 'The Meta Description is used by search engines to identify a sites content.';

$subjects['4'] = 'What is the Language Field?';
$messages['4'] = 'The language field allows you to change which language the script will use.';

$subjects['5'] = 'What is Enable Quotes?';
$messages['5'] = 'This allows you to switch on or off the Customer Quotes shown on the front page of the script.';

$subjects['6'] = 'What is the Admin Email?';
$messages['6'] = 'This is the email address that the contact and report emails will be sent to, aswell as being the from address in signup and notification emails.';

$subjects['7'] = 'What is the Email Content?';
$messages['7'] = 'The Email Content field allows you to choose between using html and plain-text emails.';

$subjects['8'] = 'What is the Email Send Type?';
$messages['8'] = 'The Send Type is the method you would like KubeLance to use to send all emails.';

$subjects['9'] = 'What is the SMTP Host?';
$messages['9'] = 'This is the host address for your smtp server,  this is only needed if you are using SMTP as the Email Send Type.';

$subjects['10'] = 'What is the SMTP Username?';
$messages['10'] = 'This is the username for your smtp server,  this is only needed if you are using SMTP as the Email Send Type.';

$subjects['11'] = 'What is the SMTP Password?';
$messages['11'] = 'This is the password for your smtp server,  this is only needed if you are using SMTP as the Email Send Type.';

$subjects['12'] = 'What is the Database Host?';
$messages['12'] = 'This is the MySQL Host, generally this field is just localhost.';

$subjects['13'] = 'What is the Database Name?';
$messages['13'] = 'This is the MySQL database name, generally this is in the format username_database.';

$subjects['14'] = 'What is the Database Username?';
$messages['14'] = 'This is the MySQL database username, generally this is in the format username_database';

$subjects['15'] = 'What is the Database Password?';
$messages['15'] = 'This is the MySQL database password.';


$subjects['16'] = 'What is the Featured Ad Amount?';
$messages['16'] = 'This is the amount of money that it will cost user to post a featured ads.';

$subjects['17'] = 'What is the Urgent Ad Amount?';
$messages['17'] = 'This is the amount of money that it will cost user to post a urgent ads.';

$subjects['18'] = 'What is the Highlight Ad Amount?';
$messages['18'] = 'This is the amount of money that it will cost user to post a highlight ads.';

$subjects['19'] = 'What is the Transfer Filter?';
$messages['19'] = 'Whether you should be shown a transfer screen between saving admin pages or not';


$subjects['20'] = 'What is the Paypal Email?';
$messages['20'] = 'This is the Paypal email address For Deposit fund by paypal. Write merchant paypal account email id.';

$subject = $subjects[$_GET['id']];
$msg = $messages[$_GET['id']];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Help: <?php echo $subject; ?></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.style1 {
	color: #FFFFFF;
	font-weight: bold;
	font-family: Tahoma, Verdana;
	font-size: 14px;
}
.style2 {
	font-size: 14px;
	font-family: Tahoma, Verdana;
}
-->
</style></head>

<body>
<table width="500" height="200" border="0" cellpadding="4" cellspacing="0">
  <tr>
    <td height="20" bgcolor="#FF9900"><span class="style1"><?php echo $subject; ?></span></td>
  </tr>
  <tr>
    <td valign="top"><span class="style2"><?php echo $msg; ?></span></td>
  </tr>
  <tr>
    <td height="20" valign="top" class="style2"><div align="right">Need more help?, &nbsp;Just Ask</div></td>
  </tr>
</table>
</body>
</html>