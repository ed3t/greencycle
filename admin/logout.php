<?php 

session_start();

require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');



session_unset($_SESSION['admin']['id']);
session_unset($_SESSION['admin']['username']);

echo '<script>window.location="login.php"</script>';



?>

