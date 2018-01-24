<?php
require_once('../includes/config.php');
?>
    <style>
        .install-widget {
            position: absolute;
            top: 30%;
            left: 25%;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            width: 50%;
            background-color: #333333;
            border-radius: 5px;
            color: white;
            display: table;
            font-size: 14px;
            height: 130px;
            padding: 20px 10px 10px;
        }
        .install-widget>p {
            padding: 10px;
            text-align: center;
            vertical-align: middle;
            line-height: 20px;
        }
        .btn {
            background-color: #82b440;
            color: #fff;
            font-size: 14px;
            padding: 5px 20px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            border: none;
            border-radius: 4px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
<?php
function install_error($error)
{
    if(!isset($_GET['ignore_errors']))
    {
        exit($error.'<br><Br><a href="'.$_SERVER['PHP_SELF'].'?ignore_errors=1&install=1">Click here</a> to run the upgrade and ignore errors');
    }
}

$install_version = '4.0';

// Check to see if the script is already installed
if(isset($config['installed']))
{
    if($config['version'] == $install_version)
    {
        // Exit the script
        exit('Quickad is already installed.');
    }
}

if(!isset($_GET['install']))
{
    echo '<div class="install-widget">
      <p>Before you run an upgrade it is recommended that you backup your Quickad database and storage folder.All customization will be lost on upgrade.Are you sure you want to upgrade your Quickad installation from '.$config['version'].' to '.$install_version.'?</p>
    <p><a class="btn" href="upgrade_4.0.php?install=1">Yes do it</a></p>
    </div>';
}
else
{
    ignore_user_abort(1);

    echo '<pre>';

    // Try to connect to the databse
    echo "Connecting to database.... \t";
    $con = @mysqli_connect ($config['db']['host'], $config['db']['user'], $config['db']['pass']);
    $db_select = @mysqli_select_db ($con,$config['db']['name']) OR install_error('ERROR ('.mysqli_error($con).')');
    echo "success<br>";

    echo "Drop Payments Table...  \t\t";
    $q = "DROP TABLE `".$config['db']['pre']."payments`";
    @mysqli_query($con,$q) or install_error('ERROR ('.mysqli_error($con).')');
    echo "success<br>";

    echo "Creating Payments Table...  \t\t";
    $table_payments = "CREATE TABLE `".$config['db']['pre']."payments` (
`payment_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_install` enum('0','1') COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `payment_title` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `payment_folder` varchar(30) COLLATE utf8_general_ci NOT NULL DEFAULT '', PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
    @mysqli_query($con,$table_payments) or install_error('ERROR ('.mysqli_error($con).')');
    echo "success<br>";

    echo "Inserting Data in Payments Table...  \t\t";
    $insert_payments = "INSERT INTO `".$config['db']['pre']."payments` (`payment_id`, `payment_install`, `payment_title`, `payment_folder`) VALUES
(1, '1', 'Paypal', 'paypal'),
(4, '1', 'Wire Transfer', 'wire_transfer'),
(5, '1', 'Cheque', 'cheque'),
(3, '1', 'NoChex', 'nochex'),
(2, '1', 'Skrill(MoneyBookers)', 'moneybookers'),
(6, '1', 'Paytm', 'paytm'),
(8, '1', 'Paystack', 'paystack')";
    @mysqli_query($con,$insert_payments) or install_error('ERROR ('.mysqli_error($con).')');
    echo "success<br>";

    // Check that config file is writtable
    echo "Checking config file.. \t\t";
    if(@is_writable('../includes/config.php'))
    {
        echo "success<br>";
    }
    else
    {
        echo 'ERROR (config.php permisions not set correctly)';
        exit;
    }


    // Start updating the config file with new variables
    echo "Writting config.php updates.. \t";
    $content = "<?php\n";
    $content.= "\$config['db']['host'] = '".$config['db']['host']."';\n";
    $content.= "\$config['db']['name'] = '".$config['db']['name']."';\n";
    $content.= "\$config['db']['user'] = '".$config['db']['user']."';\n";
    $content.= "\$config['db']['pass'] = '".$config['db']['pass']."';\n";
    $content.= "\$config['db']['pre'] = '".$config['db']['pre']."';\n";
    $content.= "\n";
    $content.= "\$config['site_title'] = '".$config['site_title']."';\n";
    $content.= "\$config['site_url'] = '".$config['site_url']."';\n";
    $content.= "\$config['admin_email'] = '".addslashes($config['admin_email'])."';\n";
    $content.= "\$config['timezone'] = 'Asia/Kolkata';\n";
    $content .= "\n";
    $content.= "\$config['email']['type'] = '".$config['email']['type']."';\n";
    $content.= "\$config['email']['smtp']['host'] = '".$config['email']['smtp']['host']."';\n";
    $content.= "\$config['email']['smtp']['user'] = '".$config['email']['smtp']['user']."';\n";
    $content.= "\$config['email']['smtp']['pass'] = '".$config['email']['smtp']['pass']."';\n";
    $content.= "\n";
    $content.= "\$config['currency_sign'] = '".$config['currency_sign']."';\n";
    $content.= "\$config['currency_code'] = '".$config['currency_code']."';\n";
    $content.= "\$config['currency_pos'] = '".$config['currency_pos']."';\n";
    $content.= "\$config['featured_fee'] = '".$config['featured_fee']."';\n";
    $content.= "\$config['urgent_fee'] = '".$config['urgent_fee']."';\n";
    $content.= "\$config['highlight_fee'] = '".$config['highlight_fee']."';\n";
    $content.= "\n";
    $content.= "\$config['specific_country'] = '".$config['specific_country']."';\n";
    $content.= "\$config['home_page'] = '".$config['home_page']."';\n";
    $content.= "\$config['gmap_api_key'] = '".$config['gmap_api_key']."';\n";
    $content.= "\n";
    $content .= "\$config['admin_tpl_name'] = '".$config['admin_tpl_name']."';\n";
    $content .= "\$config['admin_tpl_color'] = '".$config['admin_tpl_color']."';\n";
    $content.= "\$config['tpl_name'] = '".$config['tpl_name']."';\n";

    $content .= "\n";
    $content .= "\$config['lang'] = '".$config['lang']."';\n";
    $content .= "\$config['userlangsel'] = '".$config['userlangsel']."';\n";
    $content .= "\$config['userthemesel'] = '".$config['userthemesel']."';\n";
    $content .= "\$config['color_switcher'] = '".$config['color_switcher']."';\n";
    $content .= "\n";
    $content.= "\$config['cookie_time'] = '".$config['cookie_time']."';\n";
    $content.= "\$config['cookie_name'] = '".$config['cookie_name']."';\n";
    $content .= "\n";
    $content.= "\$config['mod_rewrite'] = '".$config['mod_rewrite']."';\n";
    $content .= "\$config['transfer_filter'] = '".$config['transfer_filter']."';\n";
    $content .= "\$config['purchase_key'] = '".addslashes($config['purchase_key'])."';\n";
    $content .= "\$config['version'] = '4.0';\n";
    $content .= "\$config['installed'] = '1';\n";
    $content.= "?>";

    // Open the includes/config.php for writting
    $handle = fopen('../includes/config.php', 'w');
    // Write the config file
    fwrite($handle, $content);
    // Close the file
    fclose($handle);
    echo "success<br>";

    echo "<br><Br><Br>Thank You! for upgrading Quickad, Please <a href=\"../index.php\">click here</a> to access your site";

    echo '</pre>';
}
?>