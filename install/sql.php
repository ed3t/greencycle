<?php
/*Database: `QuickAdClassified`*/

/*Table structure for table `admins`*/

$table_admins = "CREATE TABLE `".addslashes($_POST['DBPre'])."admins` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(40) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT 'default_user.png',
  `permission` enum('0','1') COLLATE utf8_general_ci NOT NULL DEFAULT '0', PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `adsense`*/

$table_adsense = "CREATE TABLE `".addslashes($_POST['DBPre'])."adsense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` text NOT NULL,
  `provider_name` varchar(255) NOT NULL,
  `large_track_code` text NOT NULL,
  `tablet_track_code` text NOT NULL,
  `phone_track_code` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0', PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `balance`*/

$table_balance = "CREATE TABLE `".addslashes($_POST['DBPre'])."balance` (
`id` int(10) NOT NULL AUTO_INCREMENT,
  `current_balance` double(9,2) NOT NULL,
  `total_earning` double(9,2) NOT NULL,
  `total_withdrawal` double(9,2) NOT NULL, PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


/*Table structure for table `catagory_main`*/

$table_catagory_main = "CREATE TABLE `".addslashes($_POST['DBPre'])."catagory_main` (
`cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(300) NOT NULL,
  `icon` varchar(20) NOT NULL DEFAULT 'fa-usd', PRIMARY KEY (`cat_id`), UNIQUE KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


/*Table structure for table `catagory_sub`*/

$table_catagory_sub = "CREATE TABLE `".addslashes($_POST['DBPre'])."catagory_sub` (
`sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `main_cat_id` int(11) NOT NULL,
  `sub_cat_name` varchar(255) NOT NULL,
  `parent_id` mediumint(8) NOT NULL,
  `cat_order` mediumint(8) NOT NULL, PRIMARY KEY (`sub_cat_id`), UNIQUE KEY `sub_cat_id` (`sub_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `custom_fields`*/

$table_custom_fields = "CREATE TABLE `".addslashes($_POST['DBPre'])."custom_fields` (
`custom_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `custom_page` varchar(60) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `custom_catid` int(11) NOT NULL,
  `custom_subcatid` int(11) NOT NULL,
  `custom_name` varchar(40) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `custom_title` varchar(100) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `custom_type` varchar(40) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `custom_content` varchar(20) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `custom_min` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `custom_max` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `custom_required` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `custom_options` longtext COLLATE utf8_general_ci NOT NULL,
  `custom_default` varchar(200) COLLATE utf8_general_ci NOT NULL DEFAULT '', PRIMARY KEY (`custom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `faq_entries`*/

$table_faq_entries = "CREATE TABLE `".addslashes($_POST['DBPre'])."faq_entries` (
`faq_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `faq_pid` smallint(4) NOT NULL DEFAULT '0',
  `faq_weight` mediumint(6) NOT NULL DEFAULT '0',
  `faq_title` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `faq_content` mediumtext COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `favads`*/

$table_favads = "CREATE TABLE `".addslashes($_POST['DBPre'])."favads` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL, PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `html`*/

$table_html = "CREATE TABLE `".addslashes($_POST['DBPre'])."html` (
`html_id` varchar(8) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `html_type` smallint(2) UNSIGNED NOT NULL DEFAULT '0',
  `html_title` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `html_content` mediumtext COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


/*Table structure for table `options`*/

$table_options = "CREATE TABLE `".addslashes($_POST['DBPre'])."options` (
`option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`option_id`), UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `payments`*/

$table_payments = "CREATE TABLE `".addslashes($_POST['DBPre'])."payments` (
`payment_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_install` enum('0','1') COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `payment_title` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `payment_folder` varchar(30) COLLATE utf8_general_ci NOT NULL DEFAULT '', PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

/*Table structure for table `product`*/

$table_product = "CREATE TABLE `".addslashes($_POST['DBPre'])."product` (
`id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0',
  `urgent` enum('0','1') NOT NULL DEFAULT '0',
  `highlight` enum('0','1') NOT NULL DEFAULT '0',
  `product_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` int(11) NOT NULL,
  `sub_category` int(11) NOT NULL,
  `price` int(10) NOT NULL,
  `negotiable` enum('0','1') NOT NULL DEFAULT '0',
  `phone` varchar(50) NOT NULL,
  `hide_phone` enum('0','1') NOT NULL,
  `location` text NOT NULL,
  `city` char(50) NOT NULL,
  `state` char(50) NOT NULL,
  `country` char(50) NOT NULL,
  `latlong` varchar(255) NOT NULL,
  `screen_shot` text NOT NULL,
  `tag` varchar(225) NOT NULL,
  `status` enum('pending','active','rejected','softreject','hide') NOT NULL DEFAULT 'pending',
  `view` int(11) NOT NULL DEFAULT '1',
  `custom_fields` longtext NOT NULL,
  `custom_types` longtext NOT NULL,
  `custom_values` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` int(11) NOT NULL,
  `contact_phone` ENUM('0','1') NOT NULL DEFAULT '0',
  `contact_email` ENUM('0','1') NOT NULL DEFAULT '0',
  `contact_chat` ENUM('0','1') NOT NULL DEFAULT '0',
  `admin_seen` enum('0','1') NOT NULL DEFAULT '0', PRIMARY KEY (`id`), KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `product_resubmit`*/

$table_product_resubmit = "CREATE TABLE `".addslashes($_POST['DBPre'])."product_resubmit` (
`id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(20) NOT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0',
  `urgent` enum('0','1') NOT NULL DEFAULT '0',
  `highlight` enum('0','1') NOT NULL DEFAULT '0',
  `product_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` int(11) NOT NULL,
  `sub_category` int(11) NOT NULL,
  `price` int(10) NOT NULL,
  `negotiable` enum('0','1') NOT NULL DEFAULT '0',
  `phone` varchar(50) NOT NULL,
  `hide_phone` enum('0','1') NOT NULL,
  `location` text NOT NULL,
  `city` char(50) NOT NULL,
  `state` char(50) NOT NULL,
  `country` char(50) NOT NULL,
  `latlong` varchar(255) NOT NULL,
  `screen_shot` text NOT NULL,
  `tag` varchar(225) NOT NULL,
  `status` enum('pending','active','rejected','softreject') NOT NULL DEFAULT 'pending',
  `view` int(11) NOT NULL DEFAULT '1',
  `custom_fields` longtext NOT NULL,
  `custom_types` longtext NOT NULL,
  `custom_values` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` int(11) NOT NULL,
  `contact_phone` ENUM('0','1') NOT NULL DEFAULT '0',
  `contact_email` ENUM('0','1') NOT NULL DEFAULT '0',
  `contact_chat` ENUM('0','1') NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `admin_seen` enum('0','1') NOT NULL DEFAULT '0', PRIMARY KEY (`id`), UNIQUE KEY `product_id` (`product_id`), KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `setting`*/

$table_setting = "CREATE TABLE `".addslashes($_POST['DBPre'])."setting` (
`id` int(10) NOT NULL AUTO_INCREMENT,
  `facebook_app_id` varchar(255) NOT NULL,
  `facebook_app_secret` varchar(255) NOT NULL,
  `google_app_id` varchar(255) NOT NULL,
  `google_app_secret` varchar(255) NOT NULL,
  `facebook` varchar(100) NOT NULL,
  `twitter` varchar(100) NOT NULL,
  `googleplus` varchar(100) NOT NULL,
  `youtube` varchar(100) NOT NULL, PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `transaction`*/

$table_transaction = "CREATE TABLE `".addslashes($_POST['DBPre'])."transaction` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(225) NOT NULL,
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `amount` double(9,2) NOT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0',
  `urgent` enum('0','1') NOT NULL DEFAULT '0',
  `highlight` enum('0','1') NOT NULL DEFAULT '0',
  `transaction_time` int(11) NOT NULL,
  `status` enum('pending','success','failed') NOT NULL,
  `transaction_gatway` varchar(255) NOT NULL,
  `transaction_ip` varchar(15) NOT NULL,
  `transaction_description` varchar(255) NOT NULL,
  `transaction_method` varchar(20) NOT NULL, PRIMARY KEY (`id`), KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

/*Table structure for table `user`*/

$table_user = "CREATE TABLE `".addslashes($_POST['DBPre'])."user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `user_type` enum('user','seller') COLLATE utf8_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `forgot` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `status` enum('0','1','2') COLLATE utf8_general_ci NOT NULL,
  `view` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(225) COLLATE utf8_general_ci NOT NULL,
  `tagline` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `description` text COLLATE utf8_general_ci NOT NULL,
  `sex` enum('Male','Female','Other') COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `postcode` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `city` varchar(225) COLLATE utf8_general_ci NOT NULL,
  `image` varchar(225) COLLATE utf8_general_ci NOT NULL DEFAULT 'default_user.png',
  `lastactive` datetime NOT NULL,
  `online` ENUM('0','1') NOT NULL DEFAULT '0',
  `facebook` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `twitter` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `googleplus` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `instagram` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `linkedin` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `youtube` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `oauth_provider` enum('','facebook','google','twitter') COLLATE utf8_general_ci NOT NULL,
  `oauth_uid` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `oauth_link` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

$table_city = "CREATE TABLE `".addslashes($_POST['DBPre'])."cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL,
  `popular` enum('0','1') NOT NULL DEFAULT '0', PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$table_states = "CREATE TABLE `".addslashes($_POST['DBPre'])."states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$table_country = "CREATE TABLE `".addslashes($_POST['DBPre'])."countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phonecode` int(11) NOT NULL,
  `install` enum('0','1') NOT NULL DEFAULT '1', PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$table_messages = "CREATE TABLE `".addslashes($_POST['DBPre'])."messages` (
  `message_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_id` varchar(40) NOT NULL DEFAULT '',
  `to_id` varchar(50) NOT NULL DEFAULT '',
  `from_uname` varchar(225) NOT NULL DEFAULT '',
  `to_uname` varchar(255) NOT NULL DEFAULT '',
  `message_content` longtext NOT NULL,
  `message_date` datetime NOT NULL,
  `recd` tinyint(1) NOT NULL DEFAULT '0',
  `seen` enum('0','1') NOT NULL DEFAULT '0',
  `message_type` varchar(255) NOT NULL DEFAULT '', PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";



$insert_admins = "INSERT INTO `".addslashes($_POST['DBPre'])."admins` (`id`, `username`, `password`, `name`, `email`, `image`, `permission`) VALUES
(1, '".addslashes($_POST['adminuser'])."', '".md5($_POST['adminpass'])."', 'Admin', '".addslashes($_POST['admin_email'])."', '', '0');";

$insert_adsense = "INSERT INTO `".addslashes($_POST['DBPre'])."adsense` (`id`, `slug`, `provider_name`, `large_track_code`, `tablet_track_code`, `phone_track_code`, `status`) VALUES
(1, 'top', 'Google AdSense', '', '', '', '0'),
(2, 'bottom', 'Google AdSense', '', '', '', '0'),
(3, 'left_sidebar', 'Google AdSense', '', '', '', '0'),
(4, 'right_sidebar', 'Google AdSense', '', '', '', '0')";

/*Dumping data for table `balance`*/
$insert_balance = "INSERT INTO `".addslashes($_POST['DBPre'])."balance` (`id`, `current_balance`, `total_earning`, `total_withdrawal`) VALUES
(1, 0.00, 0.00, 0.00)";

/*Dumping data for table `catagory_main`*/

$insert_catagory_main = "INSERT INTO `".addslashes($_POST['DBPre'])."catagory_main` (`cat_id`, `cat_name`, `icon`) VALUES
(1, 'Cars & Bikes', 'pe-7s-car'),
(2, 'Mobiles & Tablets', 'pe-7s-phone'),
(3, 'Electronics & Appliances', 'pe-7s-monitor'),
(4, 'Real Estate', 'pe-7s-home'),
(5, 'Home & Lifestyle', 'pe-7s-drawer'),
(6, 'Jobs', 'pe-7s-portfolio'),
(7, 'Services', 'pe-7s-tools'),
(11, 'Entertainment', 'pe-7s-film');";

/*Dumping data for table `catagory_sub`*/

$insert_catagory_sub = "INSERT INTO `".addslashes($_POST['DBPre'])."catagory_sub` (`sub_cat_id`, `main_cat_id`, `sub_cat_name`, `parent_id`, `cat_order`) VALUES
(4, 1, 'Bikes & Scooters', 0, 0),
(5, 1, 'Cars', 0, 0),
(7, 1, 'Commercial Vehicles', 0, 0),
(8, 1, 'Spare Parts - Accessories', 0, 0),
(9, 2, 'Mobile Phones', 0, 0),
(10, 2, 'Accessories', 0, 0),
(11, 2, 'Tablets', 0, 0),
(12, 2, 'Wearables', 0, 0),
(15, 3, 'Camera Accessories', 0, 0),
(16, 3, 'Computer Peripherals', 0, 0),
(17, 3, 'Home - Kitchen Appliances', 0, 0),
(18, 3, 'Laptops - Computers', 0, 0),
(19, 4, 'Commercial Property for Rent', 0, 0),
(20, 4, 'Commercial Property for Sale', 0, 0),
(21, 4, 'Houses - Apartments for Rent', 0, 0),
(22, 4, 'Houses - Apartments for Sale', 0, 0),
(23, 5, 'Furniture & Decor', 0, 0),
(24, 5, 'Sports, Books & Hobbies', 0, 0),
(25, 5, 'Kids & Toys', 0, 0),
(26, 5, 'Fashion', 0, 0),
(27, 5, 'Health - Beauty Products', 0, 0),
(30, 6, 'Full Time Jobs', 0, 0),
(31, 6, 'Internships', 0, 0),
(32, 6, 'Part Time Jobs', 0, 0),
(33, 6, 'Work Abroad', 0, 0),
(34, 7, 'Advertising - Design', 0, 0),
(35, 1, 'Other Vehicles', 0, 0),
(36, 3, 'Music Systems - Home Theatre', 0, 0),
(37, 3, 'Video Games - Consoles', 0, 0),
(38, 4, 'Land - Plot For Sale', 0, 0),
(39, 4, 'Paying Guest - Hostel', 0, 0),
(40, 4, 'Service Apartments', 0, 0),
(41, 6, 'Work From Home', 0, 0),
(42, 7, 'Catering -Tiffin Services', 0, 0),
(43, 7, 'Computer Repair and Service', 0, 0),
(44, 7, 'Dance Classes', 0, 0),
(45, 7, 'Electronics - Appliances Repair', 0, 0),
(46, 7, 'Legal Services', 0, 0),
(47, 7, 'Mobile & Tablet repair', 0, 0),
(48, 7, 'Movers & Packers', 0, 0),
(49, 11, 'Acting - Modeling Roles', 0, 0),
(50, 11, 'Fashion Designers - Stylists', 0, 0),
(51, 11, 'Make Up - Hair - Films & TV', 0, 0),
(52, 11, 'Modeling Agencies', 0, 0),
(53, 11, 'Photographers - Cameraman', 0, 0),
(54, 11, 'Studios - Locations for hire', 0, 0)";

/*Dumping data for table `custom_fields`*/

$insert_custom_fields = "INSERT INTO `".addslashes($_POST['DBPre'])."custom_fields` (`custom_id`, `custom_page`, `custom_catid`, `custom_subcatid`, `custom_name`, `custom_title`, `custom_type`, `custom_content`, `custom_min`, `custom_max`, `custom_required`, `custom_options`, `custom_default`) VALUES
(1, 'post_ad', 1, 4, '', 'Brand', 'text', 'all', 0, 50, 1, '', ''),
(2, 'post_ad', 1, 4, '', 'Model', 'textarea', 'all', 0, 50, 1, '', ''),
(3, 'post_ad', 1, 4, '', 'Year of registration', 'select', 'all', 0, 50, 1, '2017,2016,2015,2014,2013,2012,2011,2010,2009,2008,2007,2006,2005,2004,2003,2002,2001,2000', ''),
(4, 'post_ad', 1, 4, '', 'Kms Driven', 'text', 'all', 0, 50, 1, '', ''),
(5, 'post_ad', 1, 5, '', 'Brand', 'text', 'all', 0, 50, 1, '', ''),
(6, 'post_ad', 1, 5, '', 'Model', 'text', 'all', 0, 50, 1, '', ''),
(7, 'post_ad', 1, 5, '', 'Year of registration', 'select', 'all', 0, 50, 1, '2017,2016,2015,2014,2013,2012,2011,2010,2009,2008,2007,2006,2005,2004,2003,2002,2001,2000', ''),
(8, 'post_ad', 1, 5, '', 'Kms Driven', 'text', 'all', 0, 50, 1, '', ''),
(10, 'post_ad', 1, 5, '', 'Features', 'checkbox', 'all', 0, 50, 1, 'Air conditionar,Music System,GPS,Security System,Parking Sensor,parking camera,Stepney,Jack,Auto gear,Fog lamp,ABS,Airbags', ''),
(9, 'post_ad', 1, 5, '', 'Fuel Type', 'radio', 'all', 0, 50, 1, 'Petrol,Diesel,LPG,CNG,Electric', '')";

/*Dumping data for table `payments`*/

$insert_payments = "INSERT INTO `".addslashes($_POST['DBPre'])."payments` (`payment_id`, `payment_install`, `payment_title`, `payment_folder`) VALUES
(1, '1', 'Paypal', 'paypal'),
(4, '1', 'Wire Transfer', 'wire_transfer'),
(5, '1', 'Cheque', 'cheque'),
(3, '1', 'NoChex', 'nochex'),
(2, '1', 'Skrill(MoneyBookers)', 'moneybookers'),
(6, '1', 'Paytm', 'paytm'),
(8, '1', 'Paystack', 'paystack')";

/*Dumping data for table `setting`*/

$insert_setting = "INSERT INTO `".addslashes($_POST['DBPre'])."setting` (`id`, `facebook_app_id`, `facebook_app_secret`, `google_app_id`, `google_app_secret`, `facebook`, `twitter`, `googleplus`, `youtube`) VALUES
(1, '', '', '', '', 'https://www.facebook.com/bylancer.in', 'https://www.twitter.com/thebylancer', 'https://plus.google.com/+bylancer', 'https://www.youtybe.com/bylancer')";


$createTablemsg = array();
require_once('location-sql.php');

if ($con->query($table_admins) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."admins created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table ".$_POST['DBPre']."admins: " . $con->error ." </span></br>";

if ($con->query($table_adsense) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."adsense created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table ".$_POST['DBPre']."adsense: " . $con->error ." </span></br>";

if ($con->query($table_balance) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."balance created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table ".$_POST['DBPre']."balance: " . $con->error ." </span></br>";

if ($con->query($table_catagory_main) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."catagory_main created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table ".$_POST['DBPre']."catagory_main: " . $con->error ." </span></br>";

if ($con->query($table_catagory_sub) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."catagory_sub created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table catagory_sub: " . $con->error ." </span></br>";

if ($con->query($table_custom_fields) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."custom_fields created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table custom_fields: " . $con->error ." </span></br>";

if ($con->query($table_faq_entries) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."faq_entries created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table faq_entries: " . $con->error ." </span></br>";

if ($con->query($table_favads) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."favads created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table favads: " . $con->error ." </span></br>";

if ($con->query($table_html) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."html created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table html: " . $con->error ." </span></br>";

if ($con->query($table_options) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."options created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table options: " . $con->error ." </span></br>";

if ($con->query($table_payments) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."payments created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table payments: " . $con->error ." </span></br>";

if ($con->query($table_product) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."product created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table product: " . $con->error ." </span></br>";

if ($con->query($table_product_resubmit) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."product_resubmit created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table product_resubmit: " . $con->error ." </span></br>";

if ($con->query($table_setting) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."setting created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table setting: " . $con->error ." </span></br>";

if ($con->query($table_transaction) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."transaction created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table transaction: " . $con->error ." </span></br>";

if ($con->query($table_user) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."user created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table user: " . $con->error ." </span></br>";

if ($con->query($table_city) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."cities created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table cities: " . $con->error ." </span></br>";

if ($con->query($table_states) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."states created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table states: " . $con->error ." </span></br>";

if ($con->query($table_country) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."countries created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table countries: " . $con->error ." </span></br>";

if ($con->query($table_messages) === TRUE)
    $createTablemsg[] = "<span style='color: #46ad46;'>Table ".$_POST['DBPre']."messages created successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table messages: " . $con->error ." </span></br>";



/*Insert Data in table*/
if ($con->query($insert_admins) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."admins inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table admins: " . $con->error ." </span></br>";

if ($con->query($insert_adsense) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."adsense inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table adsense: " . $con->error ." </span></br>";


if ($con->query($insert_balance) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."balance inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table balance: " . $con->error ." </span></br>";

if ($con->query($insert_catagory_main) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."catagory_main inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table catagory_main: " . $con->error ." </span></br>";

if ($con->query($insert_catagory_sub) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."catagory_sub inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table catagory_sub: " . $con->error ." </span></br>";

if ($con->query($insert_custom_fields) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."custom_fields inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table custom_fields: " . $con->error ." </span></br>";

if ($con->query($insert_payments) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."payments inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table payments: " . $con->error ." </span></br>";

if ($con->query($insert_setting) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."setting inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table setting: " . $con->error ." </span></br>";



if ($con->query($insert_city1) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-1 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table cities: " . $con->error ." </span></br>";

if ($con->query($insert_city2) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-2 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table cities: " . $con->error ." </span></br>";

if ($con->query($insert_city3) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-3 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table cities: " . $con->error ." </span></br>";

if ($con->query($insert_city4) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-4 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city5) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-5 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city6) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-6 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city7) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-7 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city8) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-8 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city9) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-9 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city10) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-10 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city11) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-11 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city12) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-12 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city13) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-13 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city14) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-14 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city15) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-15 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city16) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-16 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city17) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-17 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city18) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-18 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city19) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-19 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city20) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-20 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city21) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-21 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city22) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-22 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city23) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-23 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city24) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-24 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city25) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-25 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city26) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-26 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city27) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-27 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city28) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-28 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city29) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-29 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table: " . $con->error ." </span></br>";

if ($con->query($insert_city30) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."cities inserted data-30 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table cities: " . $con->error ." </span></br>";







if ($con->query($insert_states) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."states inserted data1 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table states: " . $con->error ." </span></br>";

if ($con->query($insert_states2) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."states inserted data2 successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table states: " . $con->error ." </span></br>";

if ($con->query($insert_country) === TRUE)
    $createTablemsg[] = "<span style='color: #5c43dc;'>Table ".$_POST['DBPre']."countries inserted data successfully </span></br>";
else
    $createTablemsg[] = "<span style='color: #dc4366;'>Error insert into table countries: " . $con->error ." </span></br>";

$con->close();
?>