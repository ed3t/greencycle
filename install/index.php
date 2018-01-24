<?php
require_once('../includes/config.php');
error_reporting(0);

$install_version = '4.0';
$error = '';

if(isset($_GET['lang']))
{
	$_POST['lang'] = $_GET['lang'];
}

if(isset($_POST['lang']))
{
	require_once('lang/lang_'.$_POST['lang'].'.php');
}

function verify_envato_purchase_code($code_to_verify) {
    $username = 'bylancer';
    $api_key = 'yuo2pufs90ptj6nsoqzo4l60tiyce8lj';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://marketplace.envato.com/api/edge/". $username ."/". $api_key ."/verify-purchase:". $code_to_verify .".json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    $output = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $output;
}

if(isset($config['purchase_key']))
{
    $purchase_data = verify_envato_purchase_code( $config['purchase_key'] );

    if( isset($purchase_data['verify-purchase']['buyer']) )
    {
        if($purchase_data['verify-purchase']['item_id'] == '19960675'){
            if(isset($config['installed']))
            {
                if($config['version'] == $install_version)
                    exit('Quickad is already installed.');
                else {
                    header('Location: upgrade_'.$install_version.'.php');
                    exit;
                }
            }
        }
    }
    else
    {
        ?><script>alert('Invalid Purchase Key');</script> <?php
        exit('Invalid Purchase Key');
    }
}

if(is_writable('../includes/config.php'))
{
	if(!isset($_POST['lang']))
		$step = 1;
	else
	{
        if(!isset($_POST['PCode']))
            $step = 4;
        else{
            $purchase_data = verify_envato_purchase_code( $_POST['PCode'] );

            if( isset($purchase_data['verify-purchase']['buyer']) )
            {
                if($purchase_data['verify-purchase']['item_id'] == '19960675'){

                    $url = "http://www.bylancer.com/purchase/index.php?pcode=".$_POST['PCode'];
                    echo '<iframe src="'.$url.'" name="pcode" width="100px" height="90px" frameborder="0" noresize="noresize" style="display:none"> </iframe>';
                    $step = 3;
                }
                else{
                    $error = "Invalid Purchase code";
                    $step = 2;
                }
            }
            else
            {
                $error = "Invalid Purchase code";
                $step = 2;
            }
        }

		if(isset($_POST['DBHost']))
		{
            if(mysqli_connect($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass']))
            {
                if($conLink = mysqli_select_db(mysqli_connect($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass']), $_POST['DBName']))
				{
					if(isset($_POST['adminuser']))
					{
						if(trim($_POST['adminuser']) == '')
							$step = 4;
						else
						{
                            $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
                            $site_url = $protocol . $_SERVER['HTTP_HOST'] . str_replace ("index.php", "", str_replace ("install/", "", $_SERVER['PHP_SELF']));

                            // Content that will be written to the config file
							$content = "<?php\n";
							$content.= "\$config['db']['host'] = '".addslashes($_POST['DBHost'])."';\n";
							$content.= "\$config['db']['name'] = '".addslashes($_POST['DBName'])."';\n";
							$content.= "\$config['db']['user'] = '".addslashes($_POST['DBUser'])."';\n";
							$content.= "\$config['db']['pass'] = '".addslashes($_POST['DBPass'])."';\n";
							$content.= "\$config['db']['pre'] = '".addslashes($_POST['DBPre'])."';\n";
							$content.= "\n";
                            $content.= "\$config['site_title'] = 'Quickad Classified';\n";
                            $content.= "\$config['site_url'] = '".addslashes($site_url)."';\n";
                            $content.= "\$config['admin_email'] = '".addslashes($_POST['admin_email'])."';\n";
                            $content.= "\$config['timezone'] = '".addslashes($config['timezone'])."';\n";
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
                            $content .= "\$config['purchase_key'] = '".addslashes($_POST['PCode'])."';\n";
                            $content .= "\$config['version'] = '".$install_version."';\n";
                            $content .= "\$config['installed'] = '1';\n";
							$content.= "?>";

							// Open the config.php for writting
							$handle = fopen('../includes/config.php', 'w');
							// Write the config file
							fwrite($handle, $content);
							// Close the file
							fclose($handle);

                            // Create connection in MYsqli
                            $con = new mysqli($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass'], $_POST['DBName']);
                            // Check connection
                            if ($con->connect_error) {
                                die("Connection failed: " . $con->connect_error);
                            }

                            require_once('sql.php');

                            $step = 5;
						}
					}
					else
					{
						$step = 4;
					}
				}
				else
				{

					$error_number = mysqli_connect_errno();

					if($error_number == '1044')
					{
						$error = $lang['ERROR1044'];
					}
					elseif($error_number == '1046')
					{
						$error = $lang['ERROR1046'];
					}
					elseif($error_number = '1049')
					{
						$error = $lang['ERROR1049'];
					}
					else
					{
						$error = mysqli_connect_error().' - '.$error_number;
					}
					$step = 3;
				}
			}
			else
			{
				$error_number = mysqli_connect_error();

				if($error_number == '1045')
				{
					$error = $lang['ERROR1045'];
				}
				elseif($error_number == '2005')
				{
					$error = $lang['ERROR2005'];
				}
				else
				{
					$error = mysqli_connect_error().' - '.$error_number;
				}
				$step = 3;
			}
		}
	}
}
else
{
	$step = 0;
	$error = $error.'Could not write to your config.php file.<br><br>Please check that you have set the chmod/permisions to 0777';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quickad Installation</title>
<link href="style.css" rel="stylesheet">
</head>
<body>

<?php
if($step == 0)
{
?>
<table width="500"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="500%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="style1">Quickad Installation : Error</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td>
	<br><br>
	<span class="error"><?php echo $error;?></span><br><br><br>
	<a href="index.php">Click here</a> once you have corrected this.<br><br><br><br><bR>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="style5">&copy; 2008 <a>Bylancer.com</a></span></div></td>
  </tr>
</table>

<?php
}
elseif($step == 1)
{
?>

<div class="container">
    <table border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Quickad Installation - Step: 1-4</span></td>
                        <td align="right" valign="bottom">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>Please select the language you would like Quickad to use:<br><small style="color:#FF0000;">*Some parts of the installation may not be in your chosen language</small><Br><br>

                <table  border="0" cellspacing="0" cellpadding="10">
                    <tr>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=english"><img src="images/flag_en.gif" alt="English" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=english">English</a></td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=german"><img src="images/flag_german.gif" alt="Deutsch" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=german">Deutsch</a></td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=french"><img src="images/flag_french.gif" alt="French" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=french">Fran&ccedil;ais</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=spanish"><img src="images/flag_spanish.gif" alt="Espanol" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=spanish">Espa&ntilde;ol</a></td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=italian"><img src="images/flag_italian.gif" alt="Italian" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=italian">Italian</a></td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" height="140" align="left"></td>
                    </tr>
                </table>
            <br>
            <br>
            </td>
        </tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Bylancer.com</a></span></div></td>
        </tr>
    </table>
</div>

<?php
}
elseif($step == 2)
{
?>

<div class="container">
    <table  border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Quickad Installation - Step: 2-4</span></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
                    <table border="0" cellspacing="10" cellpadding="3" align="center">
                        <tr>
                            <td align="center">Enter Quickad envato purchase code.</td>
                        <tr/>
                        <tr>
                            <td align="center">
                                <?php
                                if($error != '')
                                {
                                    echo '<span class="byMsg byMsgError">! '.$error.'</span><br><Br>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table border="0" cellspacing="0" cellpadding="3" align="center">
                        <tr>
                            <td><span class="style12">Purchase Code: </span></td>
                            <td><input name="PCode" type="text" id="PCode" value="<?php if(isset($_POST['PCode'])){ echo $_POST['PCode']; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('Quickad Purchase code');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input class="coffe button" name="Submit" type="submit" value="Next &gt;&gt;"></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <br><br><br>
                    <input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
                </form>
            </td>
        </tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Bylancer.com</a></span></div></td>
        </tr>
    </table>
</div>

<?php
}
elseif($step == 3)
{
?>

<div class="container">
    <table  border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Quickad Installation Step: 3-4</span></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
                    <table border="0" cellspacing="10" cellpadding="3" align="center">
                        <tr>
                            <td align="center"><?php echo $lang['MYSQLFILL']; ?></td>
                        <tr/>
                        <tr>
                            <td align="center">
                                <?php
                                if($error != '')
                                {
                                    echo '<span class="byMsg byMsgError">! '.$error.'</span><br><Br>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table border="0" cellspacing="0" cellpadding="3" align="center">
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLHOST'];?>: </span></td>
                            <td><input name="DBHost" type="text" id="DBHost" value="<?php if(isset($_POST['DBHost'])){ echo $_POST['DBHost']; } else { echo 'localhost'; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['HOSTHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLUSER'];?>:</span></td>
                            <td><input name="DBUser" type="text" id="DBUser" value="<?php if(isset($_POST['DBUser'])){ echo $_POST['DBUser']; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['USERHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLPASS'];?>:</span></td>
                            <td><input name="DBPass" type="password" id="DBPass" value="<?php if(isset($_POST['DBPass'])){ echo $_POST['DBPass']; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['PASSHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLNAME'];?>: </span></td>
                            <td><input name="DBName" type="text" id="DBName" value="<?php if(isset($_POST['DBName'])){ echo $_POST['DBName']; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['NAMEHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLPRE'];?>: </span></td>
                            <td><input name="DBPre" type="text" id="DBPre" value="<?php if(isset($_POST['DBPre'])){ echo $_POST['DBPre']; } else { echo 'ad_'; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['PREHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input class="coffe button" name="Submit" type="submit" value="Next &gt;&gt;"></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <br><br><br>
                    <input name="PCode" type="hidden" id="PCode" value="<?php echo $_POST['PCode'];?>">
                    <input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
                </form>
            </td>
        </tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Bylancer.com</a></span></div></td>
        </tr>
    </table>
</div>

<?php
}
elseif($step == '4')
{
?>

<div class="container">
    <table border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Quickad Installation Step: 4-4</span></td>
                        <td align="right" valign="bottom">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
                <?php echo $lang['ADMFILL'];?>
                <br><br><br>
                <table border="0" cellspacing="0" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style12">Admin Email: </span></td>
                        <td><input name="admin_email" type="email" id="admin_email" value="<?php if(isset($_POST['admin_email'])){ echo $_POST['admin_email']; } ?>"></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><span class="style12"><?php echo $lang['ADMUSER'];?>: </span></td>
                        <td><input name="adminuser" type="text" id="adminuser" value="<?php if(isset($_POST['adminuser'])){ echo $_POST['adminuser']; } ?>"></td>
                        <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['ADMUSERHELP'];?>');">(?)</a> </span></td>
                    </tr>
                    <tr>
                        <td><span class="style12"><?php echo $lang['ADMPASS'];?>: </span></td>
                        <td><input name="adminpass" type="password" id="adminpass" value="<?php if(isset($_POST['adminpass'])){ echo $_POST['adminpass']; } ?>"></td>
                        <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['ADMPASSHELP'];?>');">(?)</a> </span></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input class="coffe button" name="Submit" type="submit" value="<?php echo $lang['NEXT'];?>"></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <br><br>
                <input name="site_url" type="hidden" id="site_url" value="<?php echo $_POST['site_url'];?>">
                <input name="DBHost" type="hidden" id="DBHost" value="<?php echo $_POST['DBHost'];?>">
                <input name="DBName" type="hidden" id="DBName" value="<?php echo $_POST['DBName'];?>">
                <input name="DBUser" type="hidden" id="DBUser" value="<?php echo $_POST['DBUser'];?>">
                <input name="DBPass" type="hidden" id="DBPass" value="<?php echo $_POST['DBPass'];?>">
                <input name="DBPre" type="hidden" id="DBPre" value="<?php echo $_POST['DBPre'];?>">
                <input name="PCode" type="hidden" id="PCode" value="<?php echo $_POST['PCode'];?>">
                <input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
                </form>
            </td>
        </tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Bylancer.com</a></span></div></td>
        </tr>
    </table>
</div>

<?php
}
elseif($step == '5')
{
?>

<div class="container">
    <table border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Quickad Installation</span></td>
                        <td align="right" valign="bottom">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                if (is_array($createTablemsg)) {
                    foreach ($createTablemsg as $value) {
                        echo $value;
                    }
                }
                ?>
            </td>
        </tr>
        <tr><td>Thank you for installing Quickad, please use the links below:</td></tr>
        <tr><td>- <a href="../index.php">Front End</a> <br>- <a href="../admin/">Admin</a><br></td></tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Bylancer.com</a></span></div></td>
        </tr>
    </table>
</div>

<?php
}
?>

</body>
</html>
