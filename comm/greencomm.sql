-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 15, 2018 at 08:24 PM
-- Server version: 5.7.18-1
-- PHP Version: 7.1.6-2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greencomm`
--

-- --------------------------------------------------------

--
-- Table structure for table `gcaccess_tokens`
--

CREATE TABLE `gcaccess_tokens` (
  `id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `last_activity` int(11) NOT NULL,
  `lifetime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcaccess_tokens`
--

INSERT INTO `gcaccess_tokens` (`id`, `user_id`, `last_activity`, `lifetime`) VALUES
('lifPeogtVK4w7OtAGzuXP1iHCVJPuTVi7GlD04tO', 3, 1516044187, 3600);

-- --------------------------------------------------------

--
-- Table structure for table `gcapi_keys`
--

CREATE TABLE `gcapi_keys` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gcauth_tokens`
--

CREATE TABLE `gcauth_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gcdiscussions`
--

CREATE TABLE `gcdiscussions` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `participants_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `number_index` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL,
  `start_user_id` int(10) UNSIGNED DEFAULT NULL,
  `start_post_id` int(10) UNSIGNED DEFAULT NULL,
  `last_time` datetime DEFAULT NULL,
  `last_user_id` int(10) UNSIGNED DEFAULT NULL,
  `last_post_id` int(10) UNSIGNED DEFAULT NULL,
  `last_post_number` int(10) UNSIGNED DEFAULT NULL,
  `hide_time` datetime DEFAULT NULL,
  `hide_user_id` int(10) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '1',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `is_sticky` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcdiscussions`
--

INSERT INTO `gcdiscussions` (`id`, `title`, `comments_count`, `participants_count`, `number_index`, `start_time`, `start_user_id`, `start_post_id`, `last_time`, `last_user_id`, `last_post_id`, `last_post_number`, `hide_time`, `hide_user_id`, `slug`, `is_private`, `is_approved`, `is_locked`, `is_sticky`) VALUES
(1, 'Farms in Nigeria', 2, 1, 3, '2018-01-03 07:55:22', 1, 1, '2018-01-08 12:03:16', 1, 2, 2, NULL, NULL, 'farms-in-nigeria', 0, 1, 0, 0),
(3, 'How do you build a fish farm', 2, 1, 2, '2018-01-08 16:13:44', 1, 4, '2018-01-08 16:14:11', 1, 5, 2, NULL, NULL, 'how-do-you-build-a-fish-farm', 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gcdiscussions_tags`
--

CREATE TABLE `gcdiscussions_tags` (
  `discussion_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcdiscussions_tags`
--

INSERT INTO `gcdiscussions_tags` (`discussion_id`, `tag_id`) VALUES
(1, 1),
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gcemail_tokens`
--

CREATE TABLE `gcemail_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcemail_tokens`
--

INSERT INTO `gcemail_tokens` (`id`, `email`, `user_id`, `created_at`) VALUES
('VzQxehdadLy3Mt8eRMJCNqFOc9lWVRkXNJ5kCZuR', 'farmer@likeme.com', 3, '2018-01-15 18:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `gcflagrow_images`
--

CREATE TABLE `gcflagrow_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upload_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `file_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcflagrow_images`
--

INSERT INTO `gcflagrow_images` (`id`, `user_id`, `file_name`, `upload_method`, `created_at`, `file_url`, `file_size`) VALUES
(1, 1, '1-Qy4DKxKQDL67bY1f.jpeg', 'local', '2018-01-08 11:03:11', 'http://greencycle.forum/assets/images/1-Qy4DKxKQDL67bY1f.jpeg', 66686),
(2, 1, '1-ZdSxyKyf0BIpBO3L.jpeg', 'local', '2018-01-15 18:17:00', 'http://greencycle.forum/assets/images/1-ZdSxyKyf0BIpBO3L.jpeg', 42865);

-- --------------------------------------------------------

--
-- Table structure for table `gcflags`
--

CREATE TABLE `gcflags` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gcgroups`
--

CREATE TABLE `gcgroups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name_singular` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_plural` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcgroups`
--

INSERT INTO `gcgroups` (`id`, `name_singular`, `name_plural`, `color`, `icon`) VALUES
(1, 'Admin', 'Admins', '#B72A2A', 'wrench'),
(2, 'Guest', 'Guests', NULL, NULL),
(3, 'Member', 'Members', NULL, NULL),
(4, 'Mod', 'Mods', '#80349E', 'bolt'),
(5, 'Farmer', 'Farmers', '#39b570', 'lemon-o'),
(6, 'Dealer', 'Dealers', '#7039b5', 'handshake-o');

-- --------------------------------------------------------

--
-- Table structure for table `gclinks`
--

CREATE TABLE `gclinks` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int(11) DEFAULT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT '0',
  `is_newtab` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gclinks`
--

INSERT INTO `gclinks` (`id`, `title`, `url`, `position`, `is_internal`, `is_newtab`) VALUES
(1, 'FAQ', 'http://greencycle.forum/p/1-faq', NULL, 0, 0),
(2, 'Rules', 'http://greencycle.forum/p/2-rules', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gcmentions_posts`
--

CREATE TABLE `gcmentions_posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `mentions_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcmentions_posts`
--

INSERT INTO `gcmentions_posts` (`post_id`, `mentions_id`) VALUES
(5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `gcmentions_users`
--

CREATE TABLE `gcmentions_users` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `mentions_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gcmigrations`
--

CREATE TABLE `gcmigrations` (
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcmigrations`
--

INSERT INTO `gcmigrations` (`migration`, `extension`) VALUES
('2015_02_24_000000_create_access_tokens_table', NULL),
('2015_02_24_000000_create_api_keys_table', NULL),
('2015_02_24_000000_create_config_table', NULL),
('2015_02_24_000000_create_discussions_table', NULL),
('2015_02_24_000000_create_email_tokens_table', NULL),
('2015_02_24_000000_create_groups_table', NULL),
('2015_02_24_000000_create_notifications_table', NULL),
('2015_02_24_000000_create_password_tokens_table', NULL),
('2015_02_24_000000_create_permissions_table', NULL),
('2015_02_24_000000_create_posts_table', NULL),
('2015_02_24_000000_create_users_discussions_table', NULL),
('2015_02_24_000000_create_users_groups_table', NULL),
('2015_02_24_000000_create_users_table', NULL),
('2015_09_15_000000_create_auth_tokens_table', NULL),
('2015_09_20_224327_add_hide_to_discussions', NULL),
('2015_09_22_030432_rename_notification_read_time', NULL),
('2015_10_07_130531_rename_config_to_settings', NULL),
('2015_10_24_194000_add_ip_address_to_posts', NULL),
('2015_12_05_042721_change_access_tokens_columns', NULL),
('2015_12_17_194247_change_settings_value_column_to_text', NULL),
('2016_02_04_095452_add_slug_to_discussions', NULL),
('2017_04_07_114138_add_is_private_to_discussions', NULL),
('2017_04_07_114138_add_is_private_to_posts', NULL),
('2017_04_09_152230_change_posts_content_column_to_mediumtext', NULL),
('2015_09_21_011527_add_is_approved_to_discussions', 'flarum-approval'),
('2015_09_21_011706_add_is_approved_to_posts', 'flarum-approval'),
('2017_07_22_000000_add_default_permissions', 'flarum-approval'),
('2015_09_02_000000_add_flags_read_time_to_users_table', 'flarum-flags'),
('2015_09_02_000000_create_flags_table', 'flarum-flags'),
('2017_07_22_000000_add_default_permissions', 'flarum-flags'),
('2015_05_11_000000_create_posts_likes_table', 'flarum-likes'),
('2015_09_04_000000_add_default_like_permissions', 'flarum-likes'),
('2015_02_24_000000_add_locked_to_discussions', 'flarum-lock'),
('2017_07_22_000000_add_default_permissions', 'flarum-lock'),
('2015_05_11_000000_create_mentions_posts_table', 'flarum-mentions'),
('2015_05_11_000000_create_mentions_users_table', 'flarum-mentions'),
('2015_02_24_000000_add_sticky_to_discussions', 'flarum-sticky'),
('2017_07_22_000000_add_default_permissions', 'flarum-sticky'),
('2015_05_11_000000_add_subscription_to_users_discussions_table', 'flarum-subscriptions'),
('2015_05_11_000000_add_suspended_until_to_users_table', 'flarum-suspend'),
('2015_09_14_000000_rename_suspended_until_column', 'flarum-suspend'),
('2017_07_22_000000_add_default_permissions', 'flarum-suspend'),
('2015_02_24_000000_create_discussions_tags_table', 'flarum-tags'),
('2015_02_24_000000_create_tags_table', 'flarum-tags'),
('2015_02_24_000000_create_users_tags_table', 'flarum-tags'),
('2015_02_24_000000_set_default_settings', 'flarum-tags'),
('2015_10_19_061223_make_slug_unique', 'flarum-tags'),
('2017_07_22_000000_add_default_permissions', 'flarum-tags'),
('2016_04_11_182821__create_pages_table', 'sijad-pages'),
('2016_08_28_180020_add_is_html', 'sijad-pages'),
('2016_02_13_000000_create_links_table', 'sijad-links'),
('2016_04_19_065618_change_links_columns', 'sijad-links'),
('2016_01_14_000000_create_socialbuttons_table', 'davis-socialprofile'),
('2016_10_20_000000_create_socialbuttons_column', 'davis-socialprofile'),
('2016_10_21_000000_migrate_data_to_user_column', 'davis-socialprofile'),
('2016_10_22_000000_drop_socialbuttons_table', 'davis-socialprofile'),
('2016_01_11_000000_create_flagrow_images_table', 'flagrow-image-upload'),
('2016_01_13_000000_alter_flagrow_images_table', 'flagrow-image-upload'),
('2015_10_31_040129_add_is_spam_to_posts', 'flarum-akismet'),
('2015_09_15_000000_add_twitter_id_to_users_table', 'flarum-auth-twitter');

-- --------------------------------------------------------

--
-- Table structure for table `gcnotifications`
--

CREATE TABLE `gcnotifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` int(10) UNSIGNED DEFAULT NULL,
  `data` blob,
  `time` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gcpages`
--

CREATE TABLE `gcpages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` datetime NOT NULL,
  `edit_time` datetime DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `is_html` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcpages`
--

INSERT INTO `gcpages` (`id`, `title`, `slug`, `time`, `edit_time`, `content`, `is_hidden`, `is_html`) VALUES
(1, 'FAQ', 'faq', '2018-01-03 08:01:36', NULL, '<t><p>Contents Coming Soon</p></t>', 0, 0),
(2, 'Rules', 'rules', '2018-01-03 08:01:57', NULL, '<t><p>Contents Coming Soon</p></t>', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gcpassword_tokens`
--

CREATE TABLE `gcpassword_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gcpermissions`
--

CREATE TABLE `gcpermissions` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `permission` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcpermissions`
--

INSERT INTO `gcpermissions` (`group_id`, `permission`) VALUES
(2, 'viewDiscussions'),
(3, 'discussion.flagPosts'),
(3, 'discussion.likePosts'),
(3, 'discussion.reply'),
(3, 'discussion.replyWithoutApproval'),
(3, 'startDiscussion'),
(4, 'discussion.approvePosts'),
(4, 'discussion.editPosts'),
(4, 'discussion.hide'),
(4, 'discussion.lock'),
(4, 'discussion.rename'),
(4, 'discussion.sticky'),
(4, 'discussion.tag'),
(4, 'discussion.viewFlags'),
(4, 'discussion.viewIpsPosts'),
(4, 'user.suspend'),
(4, 'viewUserList'),
(5, 'discussion.startWithoutApproval'),
(5, 'flagrow.image.upload'),
(5, 'viewUserList'),
(6, 'discussion.startWithoutApproval'),
(6, 'flagrow.image.upload'),
(6, 'viewUserList');

-- --------------------------------------------------------

--
-- Table structure for table `gcposts`
--

CREATE TABLE `gcposts` (
  `id` int(10) UNSIGNED NOT NULL,
  `discussion_id` int(10) UNSIGNED NOT NULL,
  `number` int(10) UNSIGNED DEFAULT NULL,
  `time` datetime NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `edit_time` datetime DEFAULT NULL,
  `edit_user_id` int(10) UNSIGNED DEFAULT NULL,
  `hide_time` datetime DEFAULT NULL,
  `hide_user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '1',
  `is_spam` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcposts`
--

INSERT INTO `gcposts` (`id`, `discussion_id`, `number`, `time`, `user_id`, `type`, `content`, `edit_time`, `edit_user_id`, `hide_time`, `hide_user_id`, `ip_address`, `is_private`, `is_approved`, `is_spam`) VALUES
(1, 1, 1, '2018-01-03 07:55:22', 1, 'comment', '<t><p>This should be a short demo post.</p></t>', NULL, NULL, NULL, NULL, '127.0.0.1', 0, 1, 0),
(2, 1, 2, '2018-01-08 12:03:16', 1, 'comment', '<r>\n<p><IMG alt=\"image http://greencycle.forum/assets/images/1-Qy4DKxKQDL67bY1f.jpeg\" src=\"http://greencycle.forum/assets/images/1-Qy4DKxKQDL67bY1f.jpeg\"><s>![</s>image <URL url=\"http://greencycle.forum/assets/images/1-Qy4DKxKQDL67bY1f.jpeg\">http://greencycle.forum/assets/images/1-Qy4DKxKQDL67bY1f.jpeg</URL><e>](http://greencycle.forum/assets/images/1-Qy4DKxKQDL67bY1f.jpeg)</e></IMG></p>\n</r>', NULL, NULL, NULL, NULL, '127.0.0.1', 0, 1, 0),
(4, 3, 1, '2018-01-08 16:13:44', 1, 'comment', '<t><p>I want to build a fish farm</p></t>', NULL, NULL, NULL, NULL, '127.0.0.1', 0, 1, 0),
(5, 3, 2, '2018-01-08 16:14:11', 1, 'comment', '<r><p><POSTMENTION discussionid=\"3\" id=\"4\" number=\"1\" username=\"Abednego\">@Abednego#4</POSTMENTION> to do this get</p>\n\n<p><POSTMENTION discussionid=\"3\" id=\"4\" number=\"1\" username=\"Abednego\">@Abednego#4</POSTMENTION></p> \n\n<p><POSTMENTION discussionid=\"3\" id=\"4\" number=\"1\" username=\"Abednego\">@Abednego#4</POSTMENTION> a pond first</p></r>', NULL, NULL, NULL, NULL, '127.0.0.1', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gcposts_likes`
--

CREATE TABLE `gcposts_likes` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcposts_likes`
--

INSERT INTO `gcposts_likes` (`post_id`, `user_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gcsettings`
--

CREATE TABLE `gcsettings` (
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcsettings`
--

INSERT INTO `gcsettings` (`key`, `value`) VALUES
('allow_post_editing', 'reply'),
('allow_renaming', '10'),
('allow_sign_up', '1'),
('custom_less', '.WelcomeHero {\n    background-image: url(\'/green.jpg\');\n    background-size: cover;\n    color: white;\n}\n\n.Hero-close {\ndisplay: none;\n}'),
('davis.animatedtag.animationtype', '2'),
('default_locale', 'en'),
('default_route', '/all'),
('extensions_enabled', '[\"flarum-approval\",\"flarum-bbcode\",\"flarum-emoji\",\"flarum-english\",\"flarum-flags\",\"flarum-likes\",\"flarum-lock\",\"flarum-markdown\",\"flarum-mentions\",\"flarum-sticky\",\"flarum-subscriptions\",\"flarum-suspend\",\"flarum-tags\",\"flarum-pusher\",\"sijad-pages\",\"sijad-links\",\"davis-socialprofile\",\"vingle-share-social\",\"flagrow-image-upload\",\"avatar4eg-users-list\",\"flarum-auth-facebook\",\"flarum-akismet\",\"flarum-auth-twitter\",\"davis-animatedtag\"]'),
('favicon_path', 'favicon-reyizvbn.png'),
('flarum-akismet.api_key', ''),
('flarum-auth-facebook.app_id', ''),
('flarum-auth-facebook.app_secret', ''),
('flarum-auth-twitter.api_key', ''),
('flarum-auth-twitter.api_secret', ''),
('flarum-tags.max_primary_tags', '1'),
('flarum-tags.max_secondary_tags', '3'),
('flarum-tags.min_primary_tags', '1'),
('flarum-tags.min_secondary_tags', '0'),
('forum_description', '0'),
('forum_title', 'Greencycle | Community'),
('logo_path', 'logo-e7u8uyh1.png'),
('mail_driver', 'mail'),
('mail_from', 'noreply@greencycle.forum'),
('show_language_selector', '1'),
('theme_colored_header', '0'),
('theme_dark_mode', '0'),
('theme_primary_color', '#1c1b1b'),
('theme_secondary_color', '#4D698E'),
('version', '0.1.0-beta.7'),
('vingle.share.social', 'Share'),
('welcome_message', 'Connect with people of like minds'),
('welcome_title', 'Welcome to the Greencycle Community ');

-- --------------------------------------------------------

--
-- Table structure for table `gctags`
--

CREATE TABLE `gctags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_path` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_mode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `default_sort` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_restricted` tinyint(1) NOT NULL DEFAULT '0',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `discussions_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_time` datetime DEFAULT NULL,
  `last_discussion_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gctags`
--

INSERT INTO `gctags` (`id`, `name`, `slug`, `description`, `color`, `background_path`, `background_mode`, `position`, `parent_id`, `default_sort`, `is_restricted`, `is_hidden`, `discussions_count`, `last_time`, `last_discussion_id`) VALUES
(1, 'General', 'general', NULL, '#888', NULL, NULL, 0, NULL, NULL, 0, 0, 2, '2018-01-08 16:13:44', 3),
(2, 'Wiki', 'wiki', 'Knowledge based', '#40b539', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL),
(3, 'Ideas', 'ideas', 'Welcome to the brightside', '#b5397e', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gcusers`
--

CREATE TABLE `gcusers` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `avatar_path` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferences` blob,
  `join_time` datetime DEFAULT NULL,
  `last_seen_time` datetime DEFAULT NULL,
  `read_time` datetime DEFAULT NULL,
  `notifications_read_time` datetime DEFAULT NULL,
  `discussions_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `comments_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `flags_read_time` datetime DEFAULT NULL,
  `suspend_until` datetime DEFAULT NULL,
  `social_buttons` longtext COLLATE utf8mb4_unicode_ci,
  `twitter_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcusers`
--

INSERT INTO `gcusers` (`id`, `username`, `email`, `is_activated`, `password`, `bio`, `avatar_path`, `preferences`, `join_time`, `last_seen_time`, `read_time`, `notifications_read_time`, `discussions_count`, `comments_count`, `flags_read_time`, `suspend_until`, `social_buttons`, `twitter_id`) VALUES
(1, 'Abednego', 'asapabedi@gmail.com', 1, '$2y$10$qjSCLDe.oqjDQuvjjW35lOeNdv7f8SAGRhSTS8Lqcc5E5esQJ9MPK', 'A man searching for wisdom', '3g5dyqv7xykzz7gp.png', 0x7b226e6f746966795f64697363757373696f6e52656e616d65645f616c657274223a747275652c226e6f746966795f706f73744c696b65645f616c657274223a747275652c226e6f746966795f64697363757373696f6e4c6f636b65645f616c657274223a747275652c226e6f746966795f706f73744d656e74696f6e65645f616c657274223a747275652c226e6f746966795f706f73744d656e74696f6e65645f656d61696c223a66616c73652c226e6f746966795f757365724d656e74696f6e65645f616c657274223a747275652c226e6f746966795f757365724d656e74696f6e65645f656d61696c223a66616c73652c226e6f746966795f6e6577506f73745f616c657274223a747275652c226e6f746966795f6e6577506f73745f656d61696c223a747275652c22666f6c6c6f7741667465725265706c79223a747275652c22646973636c6f73654f6e6c696e65223a747275652c22696e64657850726f66696c65223a747275652c226c6f63616c65223a6e756c6c7d, '2018-01-02 22:25:39', '2018-01-15 19:21:05', NULL, '2018-01-03 08:47:53', 2, 4, '2018-01-08 14:47:33', NULL, '[{\"title\":\"Twitter\",\"url\":\"http://twitter.com/asapabedi\",\"icon\":\"favicon\",\"favicon\":\"http://twitter.com/favicon.ico\"},{\"title\":\"Facebook\",\"url\":\"http://fb.com/kingasapabedi\",\"icon\":\"favicon\",\"favicon\":\"http://fb.com/favicon.ico\"}]', NULL),
(2, 'asap', 'Yangamagent@gmail.com', 0, '$2y$10$imw3owC7aAwArT7At1y.fe6wZKkd2xUNr9DLORljNinDW5GEFD1kW', NULL, NULL, NULL, '2018-01-08 12:47:19', '2018-01-08 12:56:52', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL),
(3, 'Tom', 'farmer@likeme.com', 1, '$2y$10$.bsU3ZL1o5NVT.6Qh.D0AumVMH5vo9czLCk5apqR4Xj/Icf2E6uRq', '', NULL, NULL, '2018-01-15 19:19:50', '2018-01-15 19:23:07', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gcusers_discussions`
--

CREATE TABLE `gcusers_discussions` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `discussion_id` int(10) UNSIGNED NOT NULL,
  `read_time` datetime DEFAULT NULL,
  `read_number` int(10) UNSIGNED DEFAULT NULL,
  `subscription` enum('follow','ignore') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcusers_discussions`
--

INSERT INTO `gcusers_discussions` (`user_id`, `discussion_id`, `read_time`, `read_number`, `subscription`) VALUES
(1, 1, '2018-01-08 15:05:23', 3, NULL),
(1, 3, '2018-01-08 16:14:19', 2, 'follow'),
(3, 3, '2018-01-15 19:21:53', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gcusers_groups`
--

CREATE TABLE `gcusers_groups` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gcusers_groups`
--

INSERT INTO `gcusers_groups` (`user_id`, `group_id`) VALUES
(1, 1),
(1, 4),
(1, 5),
(1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `gcusers_tags`
--

CREATE TABLE `gcusers_tags` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `read_time` datetime DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gcaccess_tokens`
--
ALTER TABLE `gcaccess_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcapi_keys`
--
ALTER TABLE `gcapi_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcauth_tokens`
--
ALTER TABLE `gcauth_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcdiscussions`
--
ALTER TABLE `gcdiscussions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcdiscussions_tags`
--
ALTER TABLE `gcdiscussions_tags`
  ADD PRIMARY KEY (`discussion_id`,`tag_id`);

--
-- Indexes for table `gcemail_tokens`
--
ALTER TABLE `gcemail_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcflagrow_images`
--
ALTER TABLE `gcflagrow_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcflags`
--
ALTER TABLE `gcflags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcgroups`
--
ALTER TABLE `gcgroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gclinks`
--
ALTER TABLE `gclinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcmentions_posts`
--
ALTER TABLE `gcmentions_posts`
  ADD PRIMARY KEY (`post_id`,`mentions_id`);

--
-- Indexes for table `gcmentions_users`
--
ALTER TABLE `gcmentions_users`
  ADD PRIMARY KEY (`post_id`,`mentions_id`);

--
-- Indexes for table `gcnotifications`
--
ALTER TABLE `gcnotifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcpages`
--
ALTER TABLE `gcpages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcpassword_tokens`
--
ALTER TABLE `gcpassword_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gcpermissions`
--
ALTER TABLE `gcpermissions`
  ADD PRIMARY KEY (`group_id`,`permission`);

--
-- Indexes for table `gcposts`
--
ALTER TABLE `gcposts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_discussion_id_number_unique` (`discussion_id`,`number`);
ALTER TABLE `gcposts` ADD FULLTEXT KEY `content` (`content`);

--
-- Indexes for table `gcposts_likes`
--
ALTER TABLE `gcposts_likes`
  ADD PRIMARY KEY (`post_id`,`user_id`);

--
-- Indexes for table `gcsettings`
--
ALTER TABLE `gcsettings`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `gctags`
--
ALTER TABLE `gctags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Indexes for table `gcusers`
--
ALTER TABLE `gcusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `gcusers_discussions`
--
ALTER TABLE `gcusers_discussions`
  ADD PRIMARY KEY (`user_id`,`discussion_id`);

--
-- Indexes for table `gcusers_groups`
--
ALTER TABLE `gcusers_groups`
  ADD PRIMARY KEY (`user_id`,`group_id`);

--
-- Indexes for table `gcusers_tags`
--
ALTER TABLE `gcusers_tags`
  ADD PRIMARY KEY (`user_id`,`tag_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gcdiscussions`
--
ALTER TABLE `gcdiscussions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `gcflagrow_images`
--
ALTER TABLE `gcflagrow_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gcflags`
--
ALTER TABLE `gcflags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gcgroups`
--
ALTER TABLE `gcgroups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `gclinks`
--
ALTER TABLE `gclinks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gcnotifications`
--
ALTER TABLE `gcnotifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gcpages`
--
ALTER TABLE `gcpages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gcposts`
--
ALTER TABLE `gcposts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gctags`
--
ALTER TABLE `gctags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `gcusers`
--
ALTER TABLE `gcusers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
