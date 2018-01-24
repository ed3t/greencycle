<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.sqlquery.php');
require_once('filewrite.php');
$mysqli = db_connect($config);
session_start();
checkloggedadmin();

//include("header.php");

function change_config_file_settings($filePath, $newSettings)
{

    // Get a list of the variables in the scope before including the file
    $new = get_defined_vars();
    // Include the config file and get it's values
    include($filePath);

    // Get a list of the variables in the scope after including the file
    $old = get_defined_vars();

    // Find the difference - after this, $fileSettings contains only the variables
    // declared in the file
    $fileSettings = array_diff($lang, $new);
    print_r($fileSettings);
    echo "<br>";
    // Update $fileSettings with any new values
    $fileSettings = array_merge($fileSettings, $newSettings);
    print_r($fileSettings);
    // Build the new file as a string
    $newFileStr = "<?php\n\n";
    foreach ($fileSettings as $name => $val) {
        echo $name;
        // Using var_export() allows you to set complex values such as arrays and also
        // ensures types will be correct
        //$newFileStr .= "\${$name} = " . var_export($val, true) . ";\n";
        $newFileStr .= "\$lang['$name'] = " . var_export($val, true) . ";\n";
    }
    // Closing tag intentionally omitted, you can add one if you want

    // Write it back to the file
    file_put_contents($filePath, $newFileStr);

}

// Example usage:
// This will update $dbuser and $dbpass but leave everything else untouched

$newSettings = array(
    'dbuser' => 'devsasendra',
    'dbpass' => '123sascas456',
);
change_config_file_settings('filewrite.php', $newSettings);

?>