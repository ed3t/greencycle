<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
session_start();
require_once('includes/lang/lang_'.$config['lang'].'.php');
require_once('includes/seo-url.php');

$mysqli = db_connect($config);


error($lang['PAGENOTEXIST'], __LINE__, __FILE__, 1,$lang,$config,$link);

?>