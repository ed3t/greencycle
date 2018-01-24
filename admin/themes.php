<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');

$mysqli = db_connect($config);
session_start();
checkloggedadmin();

if(isset($_POST['tpl_name']))
{
    if(!check_allow()){
        ?>
        <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#sa-title').trigger('click');
            });
        </script>
    <?php

    }
    else {
        // Content that will be written to the config file
        $content = "<?php\n";
        $content .= "\$config['db']['host'] = '" . addslashes(stripslashes($config['db']['host'])) . "';\n";
        $content .= "\$config['db']['name'] = '" . addslashes(stripslashes($config['db']['name'])) . "';\n";
        $content .= "\$config['db']['user'] = '" . addslashes(stripslashes($config['db']['user'])) . "';\n";
        $content .= "\$config['db']['pass'] = '" . addslashes(stripslashes($config['db']['pass'])) . "';\n";
        $content .= "\$config['db']['pre'] = '" . addslashes(stripslashes($config['db']['pre'])) . "';\n";
        $content .= "\n";
        $content .= "\$config['site_title'] = '" . addslashes(stripslashes($config['site_title'])) . "';\n";
        $content .= "\$config['site_url'] = '" . addslashes(stripslashes($config['site_url'])) . "';\n";
        $content .= "\$config['admin_email'] = '" . addslashes(stripslashes($config['admin_email'])) . "';\n";
        $content.= "\$config['timezone'] = '".addslashes($config['timezone'])."';\n";
        $content .= "\n";
        $content .= "\$config['email']['type'] = '" . addslashes(stripslashes($config['email']['type'])) . "';\n";
        $content .= "\$config['email']['smtp']['host'] = '" . addslashes(stripslashes($config['email']['smtp']['host'])) . "';\n";
        $content .= "\$config['email']['smtp']['user'] = '" . addslashes(stripslashes($config['email']['smtp']['user'])) . "';\n";
        $content .= "\$config['email']['smtp']['pass'] = '" . addslashes(stripslashes($config['email']['smtp']['pass'])) . "';\n";
        $content .= "\n";
        $content .= "\$config['currency_sign'] = '" . addslashes(stripslashes($config['currency_sign'])) . "';\n";
        $content .= "\$config['currency_code'] = '" . addslashes(stripslashes($config['currency_code'])) . "';\n";
        $content .= "\$config['currency_pos'] = '" . addslashes(stripslashes($config['currency_pos'])) . "';\n";
        $content .= "\$config['featured_fee'] = '" . addslashes(stripslashes($config['featured_fee'])) . "';\n";
        $content .= "\$config['urgent_fee'] = '" . addslashes(stripslashes($config['urgent_fee'])) . "';\n";
        $content .= "\$config['highlight_fee'] = '" . addslashes(stripslashes($config['highlight_fee'])) . "';\n";
        $content .= "\n";
        $content .= "\$config['specific_country'] = '" . addslashes(stripslashes($config['specific_country'])) . "';\n";
        $content .= "\$config['home_page'] = '" . addslashes(stripslashes($config['home_page'])) . "';\n";
        $content .= "\$config['gmap_api_key'] = '" . addslashes(stripslashes($config['gmap_api_key'])) . "';\n";
        $content .= "\n";
        $content .= "\$config['admin_tpl_name'] = '" . addslashes(stripslashes($config['admin_tpl_name'])) . "';\n";
        $content .= "\$config['admin_tpl_color'] = '" . addslashes(stripslashes($config['admin_tpl_color'])) . "';\n";
        $content .= "\$config['tpl_name'] = '" . addslashes($_POST['tpl_name']) . "';\n";
        $content .= "\n";
        $content .= "\$config['lang'] = '" . addslashes(stripslashes($config['lang'])) . "';\n";
        $content .= "\$config['userlangsel'] = '" . addslashes(stripslashes($config['userlangsel'])) . "';\n";
        $content .= "\$config['userthemesel'] = '".addslashes(stripslashes($config['userthemesel']))."';\n";
        $content .= "\$config['color_switcher'] = '".addslashes(stripslashes($config['color_switcher']))."';\n";
        $content.= "\n";
        $content.= "\$config['cookie_time'] = '".addslashes(stripslashes($config['cookie_time']))."';\n";
        $content.= "\$config['cookie_name'] = '".addslashes(stripslashes($config['cookie_name']))."';\n";
        $content .= "\n";
        $content .= "\$config['mod_rewrite'] = '" . addslashes(stripslashes($config['mod_rewrite'])) . "';\n";
        $content .= "\$config['transfer_filter'] = '" . addslashes(stripslashes($config['transfer_filter'])) . "';\n";
        $content .= "\$config['purchase_key'] = '" . $config['purchase_key'] . "';\n";
        $content .= "\$config['version'] = '" . $config['version'] . "';\n";
        $content .= "\$config['installed'] = '" . $config['installed'] . "';\n";
        $content .= "?>";

        // Open the includes/config.php for writting
        $handle = fopen('../includes/config.php', 'w');
        // Write the config file
        fwrite($handle, $content);
        // Close the file
        fclose($handle);

        transfer($config, $con, 'themes.php', 'Theme Changed');
        exit;
    }
}

include('header.php');
?>
<script language="JavaScript">
    <?php
        echo "\n";
        echo '  var img=new Array();';
        echo "\n";
        if ($handle = opendir('../templates/'))
        {
           while (false !== ($file = readdir($handle)))
           {
               if ($file != "." && $file != "..")
               {
                    echo 'img["' . $file . '"]="../templates/' . $file . '/screenshot.png";';
                    echo "\n";
               }
           }
           closedir($handle);
        }
    ?>

    function swap(type){
        document.getElementById("imgMain").src=img[type];
        var sel=document.shoeFrm.shoeSel;
        for(i=0;i<sel.length;i++){if(sel.options[i].text==type)
        {
            sel.selectedIndex=i;}}
    }
</script>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Themes</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Theme Change</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /row -->
        <div class="row">
            <?php
            if ($handle = opendir('../templates/'))
            {
                while (false !== ($file = readdir($handle)))
                {
                    if ($file != '.' && $file != '..')
                    {
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-4">
                            <div class="white-box pro-box p-0">
                                <div class="pro-list-img" style="background: url('../templates/<?php echo $file ?>/screenshot.png') center center / cover no-repeat;">
                                </div>
                                <div class="pro-content-3-col">
                                    <div class="pro-list-details">
                                        <h4>
                                            <a class="text-dark" href="javascript:void(0)"><?php echo $file ?></a>
                                        </h4>
                                        <h4 class="text-danger"><small>Author</small> Bylancer</h4>
                                    </div>
                                </div>

                                <hr class="m-0">
                                <div class="pro-agent-col-3">
                                    <div class="agent-name">
                                        <form action="themes.php" method="post" name="f1" id="f1">
                                            <input type="hidden" value="<?php echo $file ?>" name="tpl_name">
                                            <?php
                                            if($file == $config['tpl_name'])
                                            {
                                                echo '<button class="btn btn-default btn-rounded waves-effect waves-light btn-sm" type="button"><span class="btn-label"><i class="ti-check"></i></span>Current Theme</button>';
                                            }
                                            else{
                                                echo '<button class="btn btn-success btn-rounded waves-effect waves-light btn-sm" type="submit"><span class="btn-label"><i class="ti-check"></i></span>Activate Me</button>';
                                            }
                                            ?>
                                        </form>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    <?php
                    }
                }
                closedir($handle);
            }
            ?>

        </div>




        <?php include('footer.php'); ?>
