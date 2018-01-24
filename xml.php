<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.sqlquery.php');
require_once('includes/functions/func.users.php');
session_start();
require_once('includes/lang/lang_'.$config['lang'].'.php');
require_once('includes/seo-url.php');

$con = db_connect($config);

header('Content-type: text/xml');

switch ($_GET['t']) 
{
	case 'latestads':
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">';
		echo '<channel>';
		echo '<title>' . stripslashes($config['site_title']) . '</title>';
		echo '<link>' . $config['site_url'] . '</link>';
		echo '<description>' . stripslashes($config['site_title']) . '</description>';
		echo '<language>en</language>';
		echo '<atom:link href="'.$config['site_url'].'xml.php?t='.$_GET['t'].'" rel="self" type="application/rss+xml" />';
		
		$query = "SELECT * FROM `".$config['db']['pre']."product` WHERE status='active' ORDER BY id DESC";
		$query_result = @mysqli_query ($con,$query) OR error(mysqli_error($con));
		while ($info = @mysqli_fetch_array($query_result))
		{
			//$info['description'] = strip_tags($info['description']);
			$info['product_name'] = str_replace("&","&amp;",stripslashes($info['product_name']));
			$info['product_name'] = str_replace('<','&lt;',$info['product_name']);
			$info['product_name'] = str_replace('>','&gt;',$info['product_name']);
			$info['description'] = str_replace("&","&amp;",stripslashes($info['description']));
			$info['description'] = str_replace('<','&lt;',$info['description']);
			$info['description'] = str_replace('>','&gt;',$info['description']);
			$info['description'] = str_replace('&lt;br /&gt;','<br />',$info['description']);
			$info['description'] = str_replace('&lt;br&gt;','<br>',$info['description']);
            
            $pro_url = preg_replace("/[\s_]/","-", $info['product_name']);
            $item_link = $config['site_url'].'ad/' . $info['id'] . '/'.$pro_url.'/';

            $item_created_at = timeAgo($info['created_at']);
            $get_main = get_maincat_by_id($config,$info['category']);
            $get_sub = get_subcat_by_id($config,$info['sub_category']);
            $item_category = $get_main['cat_name'];
            $item_sub_category = $get_sub['sub_cat_name'];

            $item_category = str_replace("&","&amp;",stripslashes($item_category));
            $item_category = str_replace('<','&lt;',$item_category);
            $item_category = str_replace('>','&gt;',$item_category);

            $item_sub_category = str_replace("&","&amp;",stripslashes($item_sub_category));
            $item_sub_category = str_replace('<','&lt;',$item_sub_category);
            $item_sub_category = str_replace('>','&gt;',$item_sub_category);

			echo '<item>';
			echo '<title><![CDATA[' . $info['product_name'] . ']]></title>';
			echo '<link>' . $item_link . '</link>';
			echo '<guid>' . $item_link . '</guid>';
			echo '<pubDate>'.$item_created_at.'</pubDate>';
            echo '<category>' . $item_category . '</category>';
            echo '<sub-category>' . $item_sub_category . '</sub-category>';
			echo '<description><![CDATA[' . $info['description'] . ']]></description>';
			echo '</item>';
		}
		
		echo '</channel>';
		echo '</rss>';
		break;
	case 'premiumads':
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">';
		echo '<channel>';
		echo '<title>' . stripslashes($config['site_title']) . '</title>';
		echo '<link>' . $config['site_url'] . '</link>';
		echo '<description>' . stripslashes($config['site_title']) . '</description>';
		echo '<language>en</language>';
		
		$query = "SELECT * FROM `".$config['db']['pre']."product` WHERE (featured = '1' or urgent = '1' or highlight = '1') AND  status='active' ORDER BY id DESC";
		$query_result = @mysqli_query ($con,$query) OR error(mysqli_error($con));
		while ($info = @mysqli_fetch_array($query_result))
		{
			//$info['description'] = strip_tags($info['description']);
			$info['product_name'] = str_replace("&","&amp;",stripslashes($info['product_name']));
			$info['product_name'] = str_replace('<','&lt;',$info['product_name']);
			$info['product_name'] = str_replace('>','&gt;',$info['product_name']);
			$info['description'] = str_replace("&","&amp;",stripslashes($info['description']));
			$info['description'] = str_replace('<','&lt;',$info['description']);
			$info['description'] = str_replace('>','&gt;',$info['description']);
			$info['description'] = str_replace('&lt;br /&gt;','<br />',$info['description']);
			$info['description'] = str_replace('&lt;br&gt;','<br>',$info['description']);

            $premium = '';
            if ($info['featured'] == "1"){
                $premium = $premium.'Featured ';
            }

            if($info['urgent'] == "1")
            {
                $premium = $premium.'Urgent ';
            }

            if($info['highlight'] == "1")
            {
                $premium = $premium.'Highlight ';
            }

            $pro_url = preg_replace("/[\s_]/","-", $info['product_name']);
            $item_link = $config['site_url'].'ad/' . $info['id'] . '/'.$pro_url.'/';
            $item_created_at = timeAgo($info['created_at']);
            $get_main = get_maincat_by_id($config,$info['category']);
            $get_sub = get_subcat_by_id($config,$info['sub_category']);
            $item_category = $get_main['cat_name'];
            $item_sub_category = $get_sub['sub_cat_name'];

            $item_category = str_replace("&","&amp;",stripslashes($item_category));
            $item_category = str_replace('<','&lt;',$item_category);
            $item_category = str_replace('>','&gt;',$item_category);

            $item_sub_category = str_replace("&","&amp;",stripslashes($item_sub_category));
            $item_sub_category = str_replace('<','&lt;',$item_sub_category);
            $item_sub_category = str_replace('>','&gt;',$item_sub_category);

			echo '<item>';
			echo '<title><![CDATA[' . $info['product_name'] . ']]></title>';
			echo '<link>' . $item_link . '</link>';
			echo '<guid>' . $item_link . '</guid>';
			echo '<pubDate>'.$item_created_at.'</pubDate>';
            echo '<featured>' . $premium . '</featured>';
            echo '<category>' . $item_category . '</category>';
            echo '<sub-category>' . $item_sub_category . '</sub-category>';
			echo '<description><![CDATA[' . $info['description'] . ']]></description>';
			echo '</item>';
		}
		
		echo '</channel>';
		echo '</rss>';
		break;
}
?>