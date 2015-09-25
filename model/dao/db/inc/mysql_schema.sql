-- MySQL dump 10.13  Distrib 5.6.25, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: shaixuan
-- ------------------------------------------------------
-- Server version	5.6.25-0ubuntu0.15.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category_wlp`
--

DROP TABLE IF EXISTS `category_wlp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_wlp` (
  `display` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `written_language_id` int(10) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cell_phone`
--

DROP TABLE IF EXISTS `cell_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cell_phone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `amazon_cn_id` char(10) DEFAULT NULL,
  `jd_id` int(10) unsigned DEFAULT NULL,
  `display_size` float(4,1) unsigned NOT NULL,
  `cpu_core_count` tinyint(1) unsigned NOT NULL,
  `ram` int(10) unsigned NOT NULL,
  `rom` int(10) unsigned NOT NULL,
  `is_support_wcdma` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_support_cdma2000` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_support_td_scdma` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_support_gsm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_support_cdma` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `os` tinyint(1) unsigned NOT NULL,
  `brand` int(10) unsigned NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `taobao_uri` varchar(200) DEFAULT NULL,
  `is_has_front_camera` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_support_alarm_clock_when_powered_off` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cell_phone_attribute_value_wlp`
--

DROP TABLE IF EXISTS `cell_phone_attribute_value_wlp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cell_phone_attribute_value_wlp` (
  `attribute_name` varchar(100) NOT NULL,
  `attribute_value` varchar(20) NOT NULL,
  `display` varchar(100) NOT NULL,
  `written_language_id` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cell_phone_detail`
--

DROP TABLE IF EXISTS `cell_phone_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cell_phone_detail` (
  `product_id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `written_language_id` tinyint(1) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cell_phone_file`
--

DROP TABLE IF EXISTS `cell_phone_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cell_phone_file` (
  `filename` varchar(200) NOT NULL,
  `product_id` int(10) NOT NULL,
  `purpose` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `graphics_card`
--

DROP TABLE IF EXISTS `graphics_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `graphics_card` (
  `3dmark11_performance_preset` int(10) NOT NULL DEFAULT '0',
  `brand` int(10) NOT NULL,
  `gpu` int(10) NOT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `memory` int(10) NOT NULL,
  `output_interface_displayport` tinyint(1) NOT NULL,
  `output_interface_dvi` tinyint(1) NOT NULL,
  `output_interface_hdmi` tinyint(1) NOT NULL,
  `output_interface_vga` tinyint(1) NOT NULL,
  `power` int(10) NOT NULL,
  `height_specification` tinyint(1) NOT NULL DEFAULT '2',
  `price` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `graphics_card_attribute_value_wlp`
--

DROP TABLE IF EXISTS `graphics_card_attribute_value_wlp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `graphics_card_attribute_value_wlp` (
  `attribute_name` varchar(100) NOT NULL,
  `attribute_value` int(10) unsigned NOT NULL,
  `display` varchar(100) NOT NULL,
  `written_language_id` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `graphics_card_detail`
--

DROP TABLE IF EXISTS `graphics_card_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `graphics_card_detail` (
  `product_id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `written_language_id` tinyint(1) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `graphics_card_file`
--

DROP TABLE IF EXISTS `graphics_card_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `graphics_card_file` (
  `filename` varchar(200) NOT NULL,
  `product_id` int(10) NOT NULL,
  `purpose` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hd_media_player`
--

DROP TABLE IF EXISTS `hd_media_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hd_media_player` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cpu_architecture` tinyint(1) unsigned NOT NULL,
  `resolution` tinyint(1) unsigned NOT NULL,
  `cpu_core_count` tinyint(1) unsigned NOT NULL,
  `ram` int(10) unsigned NOT NULL,
  `brand` tinyint(2) unsigned NOT NULL,
  `disk_size` float(2,1) unsigned NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `taobao_uri` varchar(200) DEFAULT NULL,
  `wifi_speed` int(10) NOT NULL DEFAULT '0',
  `bluetooth_version` float(2,1) NOT NULL DEFAULT '0.0',
  `hdmi_version` tinyint(2) NOT NULL DEFAULT '0',
  `per_color_depth` tinyint(2) NOT NULL,
  `usb_otg_version` float(2,1) NOT NULL DEFAULT '0.0',
  `usb_phy_version` float(2,1) NOT NULL DEFAULT '0.0',
  `is_power_inside` tinyint(1) NOT NULL DEFAULT '0',
  `is_support_linux` tinyint(1) NOT NULL DEFAULT '0',
  `is_support_android_4_0` tinyint(1) NOT NULL DEFAULT '0',
  `is_support_android_2_3` tinyint(1) NOT NULL DEFAULT '0',
  `is_support_android_2_2` tinyint(1) NOT NULL DEFAULT '0',
  `wired_network` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hd_media_player_attribute_value_wlp`
--

DROP TABLE IF EXISTS `hd_media_player_attribute_value_wlp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hd_media_player_attribute_value_wlp` (
  `attribute_name` varchar(100) NOT NULL,
  `attribute_value` varchar(20) NOT NULL,
  `display` varchar(100) NOT NULL,
  `written_language_id` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hd_media_player_detail`
--

DROP TABLE IF EXISTS `hd_media_player_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hd_media_player_detail` (
  `product_id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `written_language_id` tinyint(1) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hd_media_player_file`
--

DROP TABLE IF EXISTS `hd_media_player_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hd_media_player_file` (
  `filename` varchar(200) NOT NULL,
  `product_id` int(10) NOT NULL,
  `purpose` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monitor`
--

DROP TABLE IF EXISTS `monitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor` (
  `backlight` tinyint(1) unsigned DEFAULT NULL,
  `brand` int(10) unsigned DEFAULT NULL,
  `driver` tinyint(1) unsigned DEFAULT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `amazon_cn_id` char(10) DEFAULT NULL,
  `jd_id` int(10) unsigned DEFAULT NULL,
  `input_interface_displayport` tinyint(1) unsigned DEFAULT NULL,
  `input_interface_dvi` tinyint(1) unsigned DEFAULT NULL,
  `input_interface_hdmi` tinyint(1) unsigned DEFAULT NULL,
  `input_interface_vga` tinyint(1) unsigned DEFAULT NULL,
  `panel` tinyint(1) unsigned DEFAULT NULL,
  `price` int(10) unsigned DEFAULT NULL,
  `display_size` tinyint(2) unsigned DEFAULT NULL,
  `resolution` tinyint(2) unsigned DEFAULT NULL,
  `aspect_ratio` tinyint(2) unsigned DEFAULT NULL,
  `is_has_speaker` tinyint(1) unsigned DEFAULT NULL,
  `is_has_usb_2_0_hub` tinyint(1) unsigned DEFAULT NULL,
  `is_has_front_camera` tinyint(1) unsigned DEFAULT NULL,
  `is_has_mic` tinyint(1) unsigned DEFAULT NULL,
  `is_support_erect` tinyint(1) unsigned DEFAULT NULL,
  `is_support_go_up` tinyint(1) unsigned DEFAULT NULL,
  `is_support_change_angle_of_elevation` tinyint(1) unsigned DEFAULT NULL,
  `is_support_vesa_fdmi` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monitor_attribute_value_wlp`
--

DROP TABLE IF EXISTS `monitor_attribute_value_wlp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor_attribute_value_wlp` (
  `attribute_name` varchar(100) NOT NULL,
  `attribute_value` varchar(20) NOT NULL,
  `display` varchar(100) NOT NULL,
  `written_language_id` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monitor_detail`
--

DROP TABLE IF EXISTS `monitor_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor_detail` (
  `product_id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `written_language_id` tinyint(1) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monitor_file`
--

DROP TABLE IF EXISTS `monitor_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor_file` (
  `filename` varchar(200) NOT NULL,
  `product_id` int(10) NOT NULL,
  `purpose` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `windows`
--

DROP TABLE IF EXISTS `windows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `windows` (
  `brand` int(10) NOT NULL,
  `marketing_channel` tinyint(2) NOT NULL,
  `purpose_license_demonstrate` tinyint(1) NOT NULL,
  `purpose_license_design` tinyint(1) NOT NULL,
  `purpose_license_develop` tinyint(1) NOT NULL,
  `purpose_license_evaluation` tinyint(1) NOT NULL,
  `purpose_license_office_or_play` tinyint(1) NOT NULL,
  `purpose_license_test` tinyint(1) NOT NULL,
  `role_license_business` tinyint(1) NOT NULL,
  `role_license_personal` tinyint(1) NOT NULL,
  `version` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `windows_attribute_value_wlp`
--

DROP TABLE IF EXISTS `windows_attribute_value_wlp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `windows_attribute_value_wlp` (
  `attribute_name` varchar(100) NOT NULL,
  `attribute_value` int(10) NOT NULL,
  `display` varchar(100) NOT NULL,
  `written_language_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `windows_detail`
--

DROP TABLE IF EXISTS `windows_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `windows_detail` (
  `product_id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `written_language_id` tinyint(1) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `windows_file`
--

DROP TABLE IF EXISTS `windows_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `windows_file` (
  `filename` varchar(200) NOT NULL,
  `product_id` int(10) NOT NULL,
  `purpose` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `written_language`
--

DROP TABLE IF EXISTS `written_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `written_language` (
  `id` int(10) NOT NULL,
  `tag` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-23 14:50:54
