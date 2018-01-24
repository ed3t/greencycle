<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');
$mysqli = db_connect($config);
session_start();
checkloggedadmin();

if(isset($_POST)) {

    if (count($_POST) > 1) {
        if (!check_allow()) {
            ?>
            <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
            <script>
                $(document).ready(function () {
                    $('#sa-title').trigger('click');
                });
            </script>
        <?php
        } else {

            if($_POST['currency'] == 'GBP')
            {
                $currency_sign = '&pound;';
                $currency_code = 'GBP';
                $currency_pos = 'BEF';
            }
            elseif($_POST['currency'] == 'EUR')
            {
                $currency_sign = 'EUR ';
                $currency_code = 'EUR';
                $currency_pos = 'BEF';
            }
            elseif($_POST['currency'] == 'AUD')
            {
                $currency_sign = 'A$';
                $currency_code = 'AUD';
                $currency_pos = 'BEF';
            }
            elseif($_POST['currency'] == 'NZD')
            {
                $currency_sign = 'NZ$';
                $currency_code = 'NZD';
                $currency_pos = 'BEF';
            }
            elseif($_POST['currency'] == 'JPY')
            {
                $currency_sign = '¥';
                $currency_code = 'JPY';
                $currency_pos = 'BEF';
            }
            elseif($_POST['currency'] == 'CAD')
            {
                $currency_sign = 'CDN$';
                $currency_code = 'CAD';
                $currency_pos = 'BEF';
            }
            elseif($_POST['currency'] == 'ZAR')
            {
                $currency_sign = 'R ';
                $currency_code = 'ZAR';
                $currency_pos = 'BEF';
            }
            elseif($_POST['currency'] == 'PLN')
            {
                $currency_sign = 'zł';
                $currency_code = 'PLN';
                $currency_pos = 'AFT';
            }
            elseif($_POST['currency'] == 'INR')
            {
                $currency_sign = '₹';
                $currency_code = 'INR';
                $currency_pos = 'AFT';
            }
            elseif($_POST['currency'] == 'NGN')
            {
                $currency_sign = '₦';
                $currency_code = 'NGN';
                $currency_pos = 'AFT';
            }
            elseif($_POST['currency'] == 'LKR')
            {
                $currency_sign = 'Rs';
                $currency_code = 'LKR';
                $currency_pos = 'AFT';
            }
            elseif($_POST['currency'] == 'PKR')
            {
                $currency_sign = 'Rs';
                $currency_code = 'PKR';
                $currency_pos = 'AFT';
            }
            elseif($_POST['currency'] == 'BDT')
            {
                $currency_sign = '৳';
                $currency_code = 'BDT';
                $currency_pos = 'AFT';
            }
            elseif($_POST['currency'] == 'BRL')
            {
                $currency_sign = 'R$';
                $currency_code = 'BRL';
                $currency_pos = 'AFT';
            }
            else
            {
                $currency_sign = '$';
                $currency_code = 'USD';
                $currency_pos = 'BEF';
            }


            // Content that will be written to the config file
            $content = "<?php\n";
            $content .= "\$config['db']['host'] = '" . addslashes($config['db']['host']) . "';\n";
            $content .= "\$config['db']['name'] = '" . addslashes($config['db']['name']) . "';\n";
            $content .= "\$config['db']['user'] = '" . addslashes($config['db']['user']) . "';\n";
            $content .= "\$config['db']['pass'] = '" . addslashes($config['db']['pass']) . "';\n";
            $content .= "\$config['db']['pre'] = '" . addslashes($config['db']['pre']) . "';\n";
            $content .= "\n";
            $content .= "\$config['site_title'] = '" . addslashes($_POST['site_title']) . "';\n";
            $content .= "\$config['site_url'] = '" . addslashes($_POST['site_url']) . "';\n";
            $content.= "\$config['admin_email'] = '".addslashes($_POST['admin_email'])."';\n";
            $content.= "\$config['timezone'] = '".addslashes($_POST['timezone'])."';\n";
            $content .= "\n";
            $content.= "\$config['email']['type'] = '".addslashes($_POST['email_type'])."';\n";
            $content.= "\$config['email']['smtp']['host'] = '".addslashes($_POST['smtp_host'])."';\n";
            $content.= "\$config['email']['smtp']['user'] = '".addslashes($_POST['smtp_username'])."';\n";
            $content.= "\$config['email']['smtp']['pass'] = '".addslashes($_POST['smtp_password'])."';\n";
            $content.= "\n";
            $content.= "\$config['currency_sign'] = '".addslashes($currency_sign)."';\n";
            $content.= "\$config['currency_code'] = '".addslashes($currency_code)."';\n";
            $content.= "\$config['currency_pos'] = '".addslashes($currency_pos)."';\n";
            $content.= "\$config['featured_fee'] = '".addslashes(stripslashes($_POST['featured_fee']))."';\n";
            $content.= "\$config['urgent_fee'] = '".addslashes(stripslashes($_POST['urgent_fee']))."';\n";
            $content.= "\$config['highlight_fee'] = '".addslashes(stripslashes($_POST['highlight_fee']))."';\n";
            $content.= "\n";
            $content.= "\$config['specific_country'] = '".addslashes(stripslashes($_POST['specific_country']))."';\n";
            $content.= "\$config['home_page'] = '".addslashes(stripslashes($_POST['home_page']))."';\n";
            $content.= "\$config['gmap_api_key'] = '".addslashes(stripslashes($_POST['gmap_api_key']))."';\n";
            $content.= "\n";
            $content .= "\$config['admin_tpl_name'] = '" . addslashes(stripslashes($_POST['admin_tpl_name'])) . "';\n";
            $content .= "\$config['admin_tpl_color'] = '" . addslashes(stripslashes($_POST['admin_tpl_color'])) . "';\n";
            $content.= "\$config['tpl_name'] = '".addslashes(stripslashes($config['tpl_name']))."';\n";
            $content .= "\n";
            $content .= "\$config['lang'] = '" . addslashes($_POST['lang']) . "';\n";
            $content .= "\$config['userlangsel'] = '" . addslashes($_POST['userlangsel']) . "';\n";
            $content .= "\$config['userthemesel'] = '" . addslashes($_POST['userthemesel']) . "';\n";
            $content .= "\$config['color_switcher'] = '" . addslashes($_POST['color_switcher']) . "';\n";
            $content .= "\n";
            $content.= "\$config['cookie_time'] = '".addslashes(stripslashes($config['cookie_time']))."';\n";
            $content.= "\$config['cookie_name'] = '".addslashes(stripslashes($config['cookie_name']))."';\n";
            $content .= "\n";
            $content.= "\$config['mod_rewrite'] = '".addslashes($_POST['mod_rewrite'])."';\n";
            $content .= "\$config['transfer_filter'] = '" . $_POST['transfer_filter'] . "';\n";
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

            transfer($config, 'configuration.php', 'Configuration Saved');
            exit;
        }
    }
}

include("header.php");
?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Configuration</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Configuration</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- /row -->
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">


                            <div class="col-md-12">
                                <div class="white-box">

                                    <form class="form-horizontal" method="post">
                                        <div class="form-group">
                                            <label for="site_title" class="col-sm-3 control-label">Site Title (<a href="#" onClick="MM_openBrWindow('help.php?id=0','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="site_title" class="form-control" type="Text" id="site_title" value="<?php echo stripslashes($config['site_title']);?>"  style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_url" class="col-sm-3 control-label">Site Url (<a href="#" onClick="MM_openBrWindow('help.php?id=1','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="site_url" type="Text" class="form-control" id="site_url" value="<?php echo stripslashes($config['site_url']);?>"  style="width:60%;">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="admin_email" class="col-sm-3 control-label">Admin Email (<a href="#" onClick="MM_openBrWindow('help.php?id=6','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="admin_email" class="form-control" type="Text" id="admin_email" value="<?php echo stripslashes($config['admin_email']);?>"  style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="paypal_address" class="col-sm-3 control-label">Paypal Email(<a href="#" onClick="MM_openBrWindow('help.php?id=20','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="paypal_address" type="Text" class="form-control" id="paypal_address" value="<?php echo stripslashes($config['paypal_address']);?>"  style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="timezone" class="col-sm-3 control-label">Timezone</label>
                                            <div class="col-sm-9">
                                                <input name="timezone" type="text" class="form-control" value="<?php echo $config['timezone']; ?>"  style="width:60%;">
                                            </div>
                                        </div>

                                        <div class="form-group">&nbsp;</div>
                                        <div class="form-group">
                                            <label for="email_type" class="col-sm-3 control-label">Email Send Type  (<a href="#" onClick="MM_openBrWindow('help.php?id=8','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <select name="email_type" id="email_type" class="form-control" style="width:60%">
                                                    <option <?php if($config['email']['type'] == 'mail'){ echo "selected"; } ?> value="mail">Mail</option>
                                                    <option <?php if($config['email']['type'] == 'sendmail'){ echo "selected"; } ?> value="sendmail">SendMail</option>
                                                    <option <?php if($config['email']['type'] == 'smtp'){ echo "selected"; } ?> value="smtp">SMTP</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="smtp_host" class="col-sm-3 control-label">SMTP Host  (<a href="#" onClick="MM_openBrWindow('help.php?id=9','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="smtp_host" type="Text" class="form-control" id="smtp_host" value="<?php echo stripslashes($config['email']['smtp']['host']);?>"  style="width:60%;">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="smtp_username" class="col-sm-3 control-label">SMTP Username (<a href="#" onClick="MM_openBrWindow('help.php?id=10','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="smtp_username" class="form-control" type="Text" id="smtp_username" value="<?php echo stripslashes($config['email']['smtp']['user']);?>"  style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="smtp_password" class="col-sm-3 control-label">SMTP Password (<a href="#" onClick="MM_openBrWindow('help.php?id=11','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="smtp_password" type="Text" class="form-control" id="smtp_password" value="<?php echo stripslashes($config['email']['smtp']['pass']);?>"  style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group">&nbsp;</div>
                                        <div class="form-group">
                                            <label for="currency" class="col-sm-3 control-label">Currency</label>
                                            <div class="col-sm-9">
                                                <select name="currency" id="currency" class="form-control" style="width:60%">
                                                    <option value="USD" <?php if($config['currency_code'] == 'USD'){ echo 'selected'; } ?>>US Dollars ($)</option>
                                                    <option value="GBP" <?php if($config['currency_code'] == 'GBP'){ echo 'selected'; } ?>>UK Pounds (&pound;)</option>
                                                    <option value="EUR" <?php if($config['currency_code'] == 'EUR'){ echo 'selected'; } ?>>Euros (EUR)</option>
                                                    <option value="INR" <?php if($config['currency_code'] == 'INR'){ echo 'selected'; } ?>>Indian Ruppes (₹)</option>
                                                    <option value="NGN" <?php if($config['currency_code'] == 'NGN'){ echo 'selected'; } ?>>Nigeria (₦)</option>
                                                    <option value="AUD" <?php if($config['currency_code'] == 'AUD'){ echo 'selected'; } ?>>Australian Dollars (A$)</option>
                                                    <option value="NZD" <?php if($config['currency_code'] == 'NZD'){ echo 'selected'; } ?>>New Zealand Dollars (NZ$)</option>
                                                    <option value="JPY" <?php if($config['currency_code'] == 'JPY'){ echo 'selected'; } ?>>Japanese Yen (¥)</option>
                                                    <option value="CAD" <?php if($config['currency_code'] == 'CAD'){ echo 'selected'; } ?>>Canadian Dollar (CDN$)</option>
                                                    <option value="ZAR" <?php if($config['currency_code'] == 'ZAR'){ echo 'selected'; } ?>>South African Rands (R)</option>
                                                    <option value="PLN" <?php if($config['currency_code'] == 'PLN'){ echo 'selected'; } ?>>Polish złoty (zł)</option>
                                                    <option value="LKR" <?php if($config['currency_code'] == 'LKR'){ echo 'selected'; } ?>>Sri Lankan Rupee (Rs)</option>
                                                    <option value="PKR" <?php if($config['currency_code'] == 'PKR'){ echo 'selected'; } ?>>Pakistani Rupee (Rs)</option>
                                                    <option value="BDT" <?php if($config['currency_code'] == 'BDT'){ echo 'selected'; } ?>>Bangladeshi taka (৳)</option>
                                                    <option value="BRL" <?php if($config['currency_code'] == 'BRL'){ echo 'selected'; } ?>>Real (R$)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="featured_fee" class="col-sm-3 control-label">Featured Ad Fee (<a href="#" onClick="MM_openBrWindow('help.php?id=16','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="featured_fee" class="form-control" type="Text" id="featured_fee" value="<?php echo stripslashes($config['featured_fee']);?>"  style="width:60%;">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="urgent_fee" class="col-sm-3 control-label">Urgent Ad Fee (<a href="#" onClick="MM_openBrWindow('help.php?id=17','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="urgent_fee" class="form-control" type="Text" id="urgent_fee" value="<?php echo stripslashes($config['urgent_fee']);?>"  style="width:60%;">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="highlight_fee" class="col-sm-3 control-label">Highlight Ad Fee (<a href="#" onClick="MM_openBrWindow('help.php?id=18','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <input name="highlight_fee" class="form-control" type="Text" id="highlight_fee" value="<?php echo stripslashes($config['highlight_fee']);?>"  style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group">&nbsp;</div>
                                        <div class="form-group">
                                            <label for="home_page" class="col-sm-3 control-label">Home Page</label>
                                            <div class="col-sm-9">
                                                <select name="home_page" id="home_page" class="form-control" style="width:60%">
                                                    <option value="home-image" <?php if($config['home_page'] == 'home-image'){ echo 'selected'; } ?>>Home with Image</option>
                                                    <option value="home-map" <?php if($config['home_page'] == 'home-map'){ echo 'selected'; } ?>>Home with Map</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="gmap_api_key" class="col-sm-3 control-label">Google Map API Key</label>
                                            <div class="col-sm-9">
                                                <input name="gmap_api_key" class="form-control" type="Text" id="gmap_api_key" value="<?php echo stripslashes($config['gmap_api_key']);?>"  style="width:60%;">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="specific_country" class="col-sm-3 control-label">Default Country</label>
                                            <div class="col-sm-9">
                                                <?php
                                                $config['specific_country'];
                                                ?>
                                                <select data-placeholder="Default Country" class="chosen-select" style="width:51%" multiple tabindex="6" name="specific_country" id="specific_country">
                                                    <?php

                                                    $country = get_country_list($config,$config['specific_country']);
                                                    foreach ($country as $value){
                                                        echo '<option value="'.$value['sortname'].'" '.$value['selected'].'>'.$value['name'].'</option>';
                                                    }
                                                    s
                                                    ?>
                                                </select>
                                                <span class="help-block">Select your default country.</span>
                                            </div>

                                        </div>


                                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
                                        <script src="plugins/bower_components/multiselect/chosen.jquery.js" type="text/javascript"></script>
                                        <script type="text/javascript">
                                            var config = {
                                                '.chosen-select'           : {max_selected_options: 1},
                                                '.chosen-select-deselect'  : {allow_single_deselect:true},
                                                '.chosen-select-no-single' : {disable_search_threshold:10},
                                                '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                                                '.chosen-select-width'     : {width:"420px"}
                                            }
                                            for (var selector in config) {
                                                $(selector).chosen(config[selector]);
                                            }
                                        </script>

                                        <div class="form-group">&nbsp;</div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-3 control-label">Language (?)</label>
                                            <div class="col-sm-9">
                                                <select name="lang" id="lang" class="form-control" style="width:60%">
                                                    <?php
                                                    $langs = array();

                                                    if ($handle = opendir('../includes/lang/'))
                                                    {
                                                        while (false !== ($file = readdir($handle)))
                                                        {
                                                            if ($file != "." && $file != "..")
                                                            {
                                                                $lang2 = str_replace('.php','',$file);
                                                                $lang2 = str_replace('lang_','',$lang2);

                                                                $langs[] = $lang2;
                                                            }
                                                        }
                                                        closedir($handle);
                                                    }

                                                    sort($langs);

                                                    foreach ($langs as $key => $lang2)
                                                    {
                                                        if($config['lang'] == $lang2)
                                                        {
                                                            echo '<option value="'.$lang2.'" selected>'.ucwords($lang2).'</option>';
                                                        }
                                                        else
                                                        {
                                                            echo '<option value="'.$lang2.'">'.ucwords($lang2).'</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputPassword4" class="col-sm-3 control-label">Allow User Language Selection</label>
                                            <div class="col-sm-9">
                                                <select name="userlangsel" class="form-control" id="userlangsel" style="width:60%;">
                                                    <option value="1" <?php if($config['userlangsel'] == 1){ echo "selected"; } ?>>Yes</option>
                                                    <option value="0" <?php if($config['userlangsel'] == 0){ echo "selected"; } ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputPassword4" class="col-sm-3 control-label">Allow User Theme Selection</label>
                                            <div class="col-sm-9">
                                                <select name="userthemesel" class="form-control" id="userthemesel" style="width:60%;">
                                                    <option value="1" <?php if($config['userthemesel'] == 1){ echo "selected"; } ?>>Yes</option>
                                                    <option value="0" <?php if($config['userthemesel'] == 0){ echo "selected"; } ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputPassword4" class="col-sm-3 control-label">Theme/Color switcher</label>
                                            <div class="col-sm-9">
                                                <select name="color_switcher" class="form-control" id="color_switcher" style="width:60%;">
                                                    <option value="1" <?php if($config['color_switcher'] == 1){ echo "selected"; } ?>>On</option>
                                                    <option value="0" <?php if($config['color_switcher'] == 0){ echo "selected"; } ?>>Off</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">&nbsp;</div>
                                        <div class="form-group">
                                            <label for="inputPassword4" class="col-sm-3 control-label">Admin Theme Version</label>
                                            <div class="col-sm-9">
                                                <select name="admin_tpl_name" class="form-control" id="admin_tpl_name" style="width:60%;">
                                                    <option value="style-light" <?php if($config['admin_tpl_name'] == "style-light"){ echo "selected"; } ?>>Light Theme</option>
                                                    <option value="style-dark" <?php if($config['admin_tpl_name'] == "style-dark"){ echo "selected"; } ?>>Dark Theme</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword4" class="col-sm-3 control-label">Admin Theme Color</label>
                                            <div class="col-sm-9">
                                                <select name="admin_tpl_color" id="admin_tpl_color" class="form-control" style="width:60%">
                                                    <?php
                                                    $langs = array();

                                                    if ($handle = opendir('assets/css/colors/'))
                                                    {
                                                        while (false !== ($file = readdir($handle)))
                                                        {
                                                            if ($file != "." && $file != "..")
                                                            {
                                                                $lang2 = str_replace('.css','',$file);
                                                                //$lang2 = str_replace('lang_','',$lang2);

                                                                $langs[] = $lang2;
                                                            }
                                                        }
                                                        closedir($handle);
                                                    }

                                                    sort($langs);

                                                    foreach ($langs as $key => $lang2)
                                                    {
                                                        if($config['admin_tpl_color'] == $lang2)
                                                        {
                                                            echo '<option value="'.$lang2.'" selected>'.ucwords($lang2).'</option>';
                                                        }
                                                        else
                                                        {
                                                            echo '<option value="'.$lang2.'">'.ucwords($lang2).'</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputPassword3" class="col-sm-3 control-label">Transfer Filter  (<a href="#" onClick="MM_openBrWindow('help.php?id=19','help','width=500,height=200')">?</a>)</label>
                                            <div class="col-sm-9">
                                                <select name="transfer_filter" class="form-control" id="transfer_filter" style="width:60%;">
                                                    <option value="1" <?php if($config['transfer_filter'] == 1){ echo "selected"; } ?>>Yes</option>
                                                    <option value="0" <?php if($config['transfer_filter'] == 0){ echo "selected"; } ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="mod_rewrite" class="col-sm-3 control-label">Enable SEO URL</label>
                                            <div class="col-sm-9">
                                                <select name="mod_rewrite" id="mod_rewrite" class="form-control" style="width:60%;">
                                                    <option value="1" <?php if($config['mod_rewrite'] == 1){ echo "selected"; } ?>>Yes</option>
                                                    <option value="0" <?php if($config['mod_rewrite'] == 0){ echo "selected"; } ?>>No</option>
                                                </select></td>
                                            </div>
                                        </div>


                                        <div class="form-group m-b-0">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button name="Submit" type="submit" class="btn btn-info waves-effect waves-light m-t-10">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script language="JavaScript" type="text/JavaScript">
            <!--
            function MM_openBrWindow(theURL,winName,features) { //v2.0
                window.open(theURL,winName,features);
            }
            //-->
        </script>

        <?php include("footer.php"); ?>
