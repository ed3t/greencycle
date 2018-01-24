<?php
require_once('includes/config.php');
session_start();
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
require_once('includes/functions/func.sqlquery.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

// Remove access token from session
unset($_SESSION['facebook_access_token']);
//Unset token and user data from session
unset($_SESSION['token']);

session_unset('user');
session_unset('chatHistory');
session_unset('openChatBoxes');
session_destroy();

if($config['mod_rewrite'] == 0)
{
    echo "<script>window.location='login.php'</script>";
}
else
{
    echo "<script>window.location='login'</script>";
}

?>