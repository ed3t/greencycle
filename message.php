<?php
require_once('includes/config.php');
session_start();
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
require_once('includes/functions/func.sqlquery.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');
if($config['mod_rewrite'] == 0)
    require_once('includes/simple-url.php');
else
    require_once('includes/seo-url.php');

$mysqli = db_connect($config);

if(checkloggedin()) {
    if(!get_option($config,"wchat_purchase_code"))
        error($lang['PAGENOTEXIST'], __LINE__, __FILE__, 1,$lang,$config,$link);
    if(get_option( $config, "wchat_on_off") == 'on') {
        $ses_userdata = get_user_data($config, $_SESSION['user']['username']);
        $author_image = $ses_userdata['image'];
        $setting = get_setting($config);
        $page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/wchat.html");
        $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,"Chat",$link));
        $page->SetParameter ('USERIMG', $author_image);
        $page->SetParameter('USER_ID',$_SESSION['user']['id']);
        $page->SetParameter ('USERNAME', $_SESSION['user']['username']);
        $page->SetParameter('COPYRIGHT_TEXT', get_option($config,"copyright_text"));
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
    }else
        error($lang['PAGENOTEXIST'], __LINE__, __FILE__, 1,$lang,$config,$link);
}else
    error($lang['PAGENOTEXIST'], __LINE__, __FILE__, 1,$lang,$config,$link);
?>