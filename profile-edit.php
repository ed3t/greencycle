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



if(checkloggedin())
{
    $ses_userdata = get_user_data($config,$_SESSION['user']['username']);

    $author_username = ucfirst($ses_userdata['username']);
    $author_name = ucfirst($ses_userdata['name']);
    $author_image = $ses_userdata['image'];
    $author_country = $ses_userdata['country'];
    $author_desc = $ses_userdata['description'];
    $author_tagline = $ses_userdata['tagline'];
    $updated_at = date('Y-m-d', strtotime(str_replace('-','/', $ses_userdata['updated_at'])));

    if(!isset($_POST['submit']))
    {
        // Output to template
        $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/profile-edit.html');
        $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['EDIT-PROFILE'],""));
        $page->SetLoop('ERRORS', "");
        $page->SetParameter ('AUTHORUNAME', $author_username);
        $page->SetParameter ('AUTHORNAME', $author_name);
        $page->SetParameter ('AUTHORIMG', $author_image);
        $page->SetParameter ('AUTHORTAGLINE', $author_tagline);
        $page->SetParameter ('AUTHORABOUT', $author_desc);
        $page->SetParameter ('UPDATED', $updated_at);
        $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
        $page->CreatePageEcho($lang,$config,$link);
    }
    else{
        $errors = array();

        if(!empty($_FILES['avatar']['tmp_name'])) {
            $file_avatar = $_FILES["avatar"];
            $path_avatar = "storage/profile/";
            $first_title = $_SESSION['user']['username'];

            if ($author_image != "default_user.png"){
                $unlink = $author_image;
                $getAvatar = fileUpload($path_avatar, $file_avatar, "image", $first_title, 250, 250, $unlink,true);
            }
            else{
                $getAvatar = fileUpload($path_avatar, $file_avatar, "image", $first_title, null, null, "",true);
            }

            if ($getAvatar != "") {
                $avatarName = $getAvatar;
            } else {
                $errors[]['message'] = "Avatar error: Required JPEG 150x150px image.";
            }
        }
        else{
            $avatarName = $author_image;
        }

        if(count($errors) > 0)
        {
            $page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/profile-edit.html');
            $page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['EDIT-PROFILE']));
            $page->SetLoop('ERRORS', $errors);
            $page->SetParameter ('AUTHORUNAME', $_SESSION['user']['username']);
            $page->SetParameter ('AUTHORNAME', $_POST['name']);
            $page->SetParameter ('AUTHORIMG', $author_image);
            $page->SetParameter ('AUTHORTAGLINE', $_POST['heading']);
            $page->SetParameter ('AUTHORABOUT', $_POST['content']);
            $page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang,$link));
            $page->CreatePageEcho($lang,$config,$link);
            exit();
        }
        else{

            $sql2 = "UPDATE ".$config['db']['pre']."user set
            name = '".$_POST['name']."',
            image = '".$avatarName."',
            tagline = '".$_POST['heading']."',
            description = '".$_POST['content']."',
            updated_at = '".time()."' where id='".$_SESSION['user']['id']."'
            ";
            mysqli_query(db_connect($config), $sql2);

            transfer($config,$link['EDIT-PROFILE'],$lang['PROFILE_UPDATED'],$lang['PROFILE_UPDATED']);
            exit;

            //echo "Success";
        }
    }
}
else{
    error($lang['PAGENOTEXIST'], __LINE__, __FILE__, 1,$lang,$config,$link);
}

?>