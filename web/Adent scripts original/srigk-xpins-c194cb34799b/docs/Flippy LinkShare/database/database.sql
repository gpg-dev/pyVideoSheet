-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2015 at 10:01 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `linkshare2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL,
  `adminuser` varchar(500) NOT NULL,
  `adminpassword` varchar(999) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `adminuser`, `adminpassword`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(500) CHARACTER SET utf8 NOT NULL,
  `description` varchar(250) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `dt` varchar(255) NOT NULL,
  `uid` int(255) NOT NULL,
  `postid` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `favip`
--

CREATE TABLE IF NOT EXISTS `favip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` int(40) NOT NULL,
  `postid` int(255) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL,
  `page` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce porttitor\r\n lobortis tortor sit amet auctor. Praesent pretium, leo eget luctus \r\ntempor, lectus erat vulputate libero, in viverra dolor velit quis ante. \r\nVivamus viverra pulvinar sollicitudin. Vivamus dictum orci sed lorem \r\nvenenatis quis rutrum risus dictum. Ut non enim elit. In consectetur, \r\nnibh sed iaculis pellentesque, nisi ligula egestas enim, sagittis \r\nfermentum nulla nisl sit amet velit. Aliquam erat volutpat. Suspendisse \r\nac mi tortor, at vestibulum augue. Suspendisse ut nisl quam, euismod \r\nscelerisque urna. Donec non leo et nisi tempus fermentum. Cum sociis \r\nnatoque penatibus et magnis dis parturient montes, nascetur ridiculus \r\nmus. Donec gravida sodales est vitae tincidunt.<br><br>Curabitur neque \r\ndui, adipiscing a dignissim sed, congue ut sem. Vivamus eget tellus \r\nlectus. Nullam ut tempus purus. Vestibulum pellentesque lorem nec velit \r\nhendrerit porta. Cras sit amet mauris odio. Sed sit amet libero nec \r\nipsum venenatis interdum. Class aptent taciti sociosqu ad litora \r\ntorquent per conubia nostra, per inceptos himenaeos. Sed at ligula eu \r\nenim congue molestie. Lorem ipsum dolor sit amet, consectetur adipiscing\r\n elit. Praesent tincidunt diam at metus facilisis aliquam. Vivamus a \r\norci nunc, molestie consectetur purus. Vivamus aliquam, diam eu rhoncus \r\nmollis, est lorem aliquam neque, elementum molestie sapien velit id \r\nsapien. Maecenas quis dolor nisl.<br><br>Morbi nisi quam, suscipit eu \r\naccumsan a, porttitor sed risus. Donec laoreet, dolor semper eleifend \r\nsodales, erat lacus pretium velit, vitae dignissim felis risus non \r\nmagna. Suspendisse potenti. Proin at nulla massa, et dictum magna. Lorem\r\n ipsum dolor sit amet, consectetur adipiscing elit. Integer pharetra, \r\npurus vel egestas egestas, orci lectus vehicula quam, non placerat massa\r\n lacus id justo. Integer posuere laoreet porttitor.<br><br>Nam nulla \r\nmetus, rutrum in suscipit ac, hendrerit eget nunc. In hac habitasse \r\nplatea dictumst. Mauris at justo magna. Class aptent taciti sociosqu ad \r\nlitora torquent per conubia nostra, per inceptos himenaeos. Duis \r\nmalesuada ultricies hendrerit. Vivamus ullamcorper consectetur \r\ndignissim. Praesent nunc lorem, elementum vitae scelerisque vitae, \r\npellentesque quis ipsum. Cras volutpat, erat eu mollis pulvinar, justo \r\nlibero dictum leo, ac accumsan tellus ipsum eget magna. Suspendisse \r\ntristique mauris nec odio fringilla sagittis. Donec rhoncus euismod \r\ntortor, nec commodo magna cursus at. Nunc ut euismod dui. Suspendisse \r\nsollicitudin, magna eget auctor euismod, dolor mi aliquet tellus, et \r\nsuscipit dui est nec odio. Aliquam rutrum tellus in nisi ultricies sed \r\nelementum nisi molestie. Praesent sit amet ligula id lorem ultrices \r\ntristique.<br><br>Suspendisse potenti. Sed urna est, fringilla a \r\ncondimentum eu, dapibus et erat. Cras ac aliquet erat. Integer eget \r\nrisus magna, non egestas nulla. Maecenas ut ante tortor. Pellentesque \r\nhabitant morbi tristique senectus et netus et malesuada fames ac turpis \r\negestas. Morbi vel eros nec quam cursus porttitor et nec orci. Nulla vel\r\n odio sit amet nisi feugiat dignissim. Mauris tincidunt enim quam, non \r\npharetra risus. Sed sed mi nulla, sit amet aliquam ipsum. Ut faucibus \r\nvestibulum feugiat. Nulla ut dui eget massa pellentesque scelerisque. \r\nAenean ut ipsum at orci lacinia mollis id nec urna. Proin eros dui, \r\nfeugiat vitae adipiscing ut, rutrum vitae quam. Nam et convallis augue. \r\nQuisque ut odio eu ante consequat elementum. <br>'),
(2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce porttitor\r\n lobortis tortor sit amet auctor. Praesent pretium, leo eget luctus \r\ntempor, lectus erat vulputate libero, in viverra dolor velit quis ante. \r\nVivamus viverra pulvinar sollicitudin. Vivamus dictum orci sed lorem \r\nvenenatis quis rutrum risus dictum. Ut non enim elit. In consectetur, \r\nnibh sed iaculis pellentesque, nisi ligula egestas enim, sagittis \r\nfermentum nulla nisl sit amet velit. Aliquam erat volutpat. Suspendisse \r\nac mi tortor, at vestibulum augue. Suspendisse ut nisl quam, euismod \r\nscelerisque urna. Donec non leo et nisi tempus fermentum. Cum sociis \r\nnatoque penatibus et magnis dis parturient montes, nascetur ridiculus \r\nmus. Donec gravida sodales est vitae tincidunt.<br><br>Curabitur neque \r\ndui, adipiscing a dignissim sed, congue ut sem. Vivamus eget tellus \r\nlectus. Nullam ut tempus purus. Vestibulum pellentesque lorem nec velit \r\nhendrerit porta. Cras sit amet mauris odio. Sed sit amet libero nec \r\nipsum venenatis interdum. Class aptent taciti sociosqu ad litora \r\ntorquent per conubia nostra, per inceptos himenaeos. Sed at ligula eu \r\nenim congue molestie. Lorem ipsum dolor sit amet, consectetur adipiscing\r\n elit. Praesent tincidunt diam at metus facilisis aliquam. Vivamus a \r\norci nunc, molestie consectetur purus. Vivamus aliquam, diam eu rhoncus \r\nmollis, est lorem aliquam neque, elementum molestie sapien velit id \r\nsapien. Maecenas quis dolor nisl.<br><br>Morbi nisi quam, suscipit eu \r\naccumsan a, porttitor sed risus. Donec laoreet, dolor semper eleifend \r\nsodales, erat lacus pretium velit, vitae dignissim felis risus non \r\nmagna. Suspendisse potenti. Proin at nulla massa, et dictum magna. Lorem\r\n ipsum dolor sit amet, consectetur adipiscing elit. Integer pharetra, \r\npurus vel egestas egestas, orci lectus vehicula quam, non placerat massa\r\n lacus id justo. Integer posuere laoreet porttitor.<br><br>Nam nulla \r\nmetus, rutrum in suscipit ac, hendrerit eget nunc. In hac habitasse \r\nplatea dictumst. Mauris at justo magna. Class aptent taciti sociosqu ad \r\nlitora torquent per conubia nostra, per inceptos himenaeos. Duis \r\nmalesuada ultricies hendrerit. Vivamus ullamcorper consectetur \r\ndignissim. Praesent nunc lorem, elementum vitae scelerisque vitae, \r\npellentesque quis ipsum. Cras volutpat, erat eu mollis pulvinar, justo \r\nlibero dictum leo, ac accumsan tellus ipsum eget magna. Suspendisse \r\ntristique mauris nec odio fringilla sagittis. Donec rhoncus euismod \r\ntortor, nec commodo magna cursus at. Nunc ut euismod dui. Suspendisse \r\nsollicitudin, magna eget auctor euismod, dolor mi aliquet tellus, et \r\nsuscipit dui est nec odio. Aliquam rutrum tellus in nisi ultricies sed \r\nelementum nisi molestie. Praesent sit amet ligula id lorem ultrices \r\ntristique.<br><br>Suspendisse potenti. Sed urna est, fringilla a \r\ncondimentum eu, dapibus et erat. Cras ac aliquet erat. Integer eget \r\nrisus magna, non egestas nulla. Maecenas ut ante tortor. Pellentesque \r\nhabitant morbi tristique senectus et netus et malesuada fames ac turpis \r\negestas. Morbi vel eros nec quam cursus porttitor et nec orci. Nulla vel\r\n odio sit amet nisi feugiat dignissim. Mauris tincidunt enim quam, non \r\npharetra risus. Sed sed mi nulla, sit amet aliquam ipsum. Ut faucibus \r\nvestibulum feugiat. Nulla ut dui eget massa pellentesque scelerisque. \r\nAenean ut ipsum at orci lacinia mollis id nec urna. Proin eros dui, \r\nfeugiat vitae adipiscing ut, rutrum vitae quam. Nam et convallis augue. \r\nQuisque ut odio eu ante consequat elementum. <br>'),
(3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce porttitor lobortis tortor sit amet auctor. Praesent pretium, leo eget luctus tempor, lectus erat vulputate libero, in viverra dolor velit quis ante. Vivamus viverra pulvinar sollicitudin. Vivamus dictum orci sed lorem venenatis quis rutrum risus dictum. Ut non enim elit. In consectetur, nibh sed iaculis pellentesque, nisi ligula egestas enim, sagittis fermentum nulla nisl sit amet velit. Aliquam erat volutpat. Suspendisse ac mi tortor, at vestibulum augue. Suspendisse ut nisl quam, euismod scelerisque urna. Donec non leo et nisi tempus fermentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec gravida sodales est vitae tincidunt.<br><br>Curabitur neque dui, adipiscing a dignissim sed, congue ut sem. Vivamus eget tellus lectus. Nullam ut tempus purus. Vestibulum pellentesque lorem nec velit hendrerit porta. Cras sit amet mauris odio. Sed sit amet libero nec ipsum venenatis interdum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed at ligula eu enim congue molestie. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tincidunt diam at metus facilisis aliquam. Vivamus a orci nunc, molestie consectetur purus. Vivamus aliquam, diam eu rhoncus mollis, est lorem aliquam neque, elementum molestie sapien velit id sapien. Maecenas quis dolor nisl.<br><br>Morbi nisi quam, suscipit eu accumsan a, porttitor sed risus. Donec laoreet, dolor semper eleifend sodales, erat lacus pretium velit, vitae dignissim felis risus non magna. Suspendisse potenti. Proin at nulla massa, et dictum magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer pharetra, purus vel egestas egestas, orci lectus vehicula quam, non placerat massa lacus id justo. Integer posuere laoreet porttitor.<br><br>Nam nulla metus, rutrum in suscipit ac, hendrerit eget nunc. In hac habitasse platea dictumst. Mauris at justo magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis malesuada ultricies hendrerit. Vivamus ullamcorper consectetur dignissim. Praesent nunc lorem, elementum vitae scelerisque vitae, pellentesque quis ipsum. Cras volutpat, erat eu mollis pulvinar, justo libero dictum leo, ac accumsan tellus ipsum eget magna. Suspendisse tristique mauris nec odio fringilla sagittis. Donec rhoncus euismod tortor, nec commodo magna cursus at. Nunc ut euismod dui. Suspendisse sollicitudin, magna eget auctor euismod, dolor mi aliquet tellus, et suscipit dui est nec odio. Aliquam rutrum tellus in nisi ultricies sed elementum nisi molestie. Praesent sit amet ligula id lorem ultrices tristique.<br><br>Suspendisse potenti. Sed urna est, fringilla a condimentum eu, dapibus et erat. Cras ac aliquet erat. Integer eget risus magna, non egestas nulla. Maecenas ut ante tortor. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi vel eros nec quam cursus porttitor et nec orci. Nulla vel odio sit amet nisi feugiat dignissim. Mauris tincidunt enim quam, non pharetra risus. Sed sed mi nulla, sit amet aliquam ipsum. Ut faucibus vestibulum feugiat. Nulla ut dui eget massa pellentesque scelerisque. Aenean ut ipsum at orci lacinia mollis id nec urna. Proin eros dui, feugiat vitae adipiscing ut, rutrum vitae quam. Nam et convallis augue. Quisque ut odio eu ante consequat elementum. <br>'),
(4, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce porttitor\r\n lobortis tortor sit amet auctor. Praesent pretium, leo eget luctus \r\ntempor, lectus erat vulputate libero, in viverra dolor velit quis ante. \r\nVivamus viverra pulvinar sollicitudin. Vivamus dictum orci sed lorem \r\nvenenatis quis rutrum risus dictum. Ut non enim elit. In consectetur, \r\nnibh sed iaculis pellentesque, nisi ligula egestas enim, sagittis \r\nfermentum nulla nisl sit amet velit. Aliquam erat volutpat. Suspendisse \r\nac mi tortor, at vestibulum augue. Suspendisse ut nisl quam, euismod \r\nscelerisque urna. Donec non leo et nisi tempus fermentum. Cum sociis \r\nnatoque penatibus et magnis dis parturient montes, nascetur ridiculus \r\nmus. Donec gravida sodales est vitae tincidunt.<br><br>Curabitur neque \r\ndui, adipiscing a dignissim sed, congue ut sem. Vivamus eget tellus \r\nlectus. Nullam ut tempus purus. Vestibulum pellentesque lorem nec velit \r\nhendrerit porta. Cras sit amet mauris odio. Sed sit amet libero nec \r\nipsum venenatis interdum. Class aptent taciti sociosqu ad litora \r\ntorquent per conubia nostra, per inceptos himenaeos. Sed at ligula eu \r\nenim congue molestie. Lorem ipsum dolor sit amet, consectetur adipiscing\r\n elit. Praesent tincidunt diam at metus facilisis aliquam. Vivamus a \r\norci nunc, molestie consectetur purus. Vivamus aliquam, diam eu rhoncus \r\nmollis, est lorem aliquam neque, elementum molestie sapien velit id \r\nsapien. Maecenas quis dolor nisl.<br><br>Morbi nisi quam, suscipit eu \r\naccumsan a, porttitor sed risus. Donec laoreet, dolor semper eleifend \r\nsodales, erat lacus pretium velit, vitae dignissim felis risus non \r\nmagna. Suspendisse potenti. Proin at nulla massa, et dictum magna. Lorem\r\n ipsum dolor sit amet, consectetur adipiscing elit. Integer pharetra, \r\npurus vel egestas egestas, orci lectus vehicula quam, non placerat massa\r\n lacus id justo. Integer posuere laoreet porttitor.<br><br>Nam nulla \r\nmetus, rutrum in suscipit ac, hendrerit eget nunc. In hac habitasse \r\nplatea dictumst. Mauris at justo magna. Class aptent taciti sociosqu ad \r\nlitora torquent per conubia nostra, per inceptos himenaeos. Duis \r\nmalesuada ultricies hendrerit. Vivamus ullamcorper consectetur \r\ndignissim. Praesent nunc lorem, elementum vitae scelerisque vitae, \r\npellentesque quis ipsum. Cras volutpat, erat eu mollis pulvinar, justo \r\nlibero dictum leo, ac accumsan tellus ipsum eget magna. Suspendisse \r\ntristique mauris nec odio fringilla sagittis. Donec rhoncus euismod \r\ntortor, nec commodo magna cursus at. Nunc ut euismod dui. Suspendisse \r\nsollicitudin, magna eget auctor euismod, dolor mi aliquet tellus, et \r\nsuscipit dui est nec odio. Aliquam rutrum tellus in nisi ultricies sed \r\nelementum nisi molestie. Praesent sit amet ligula id lorem ultrices \r\ntristique.<br><br>Suspendisse potenti. Sed urna est, fringilla a \r\ncondimentum eu, dapibus et erat. Cras ac aliquet erat. Integer eget \r\nrisus magna, non egestas nulla. Maecenas ut ante tortor. Pellentesque \r\nhabitant morbi tristique senectus et netus et malesuada fames ac turpis \r\negestas. Morbi vel eros nec quam cursus porttitor et nec orci. Nulla vel\r\n odio sit amet nisi feugiat dignissim. Mauris tincidunt enim quam, non \r\npharetra risus. Sed sed mi nulla, sit amet aliquam ipsum. Ut faucibus \r\nvestibulum feugiat. Nulla ut dui eget massa pellentesque scelerisque. \r\nAenean ut ipsum at orci lacinia mollis id nec urna. Proin eros dui, \r\nfeugiat vitae adipiscing ut, rutrum vitae quam. Nam et convallis augue. \r\nQuisque ut odio eu ante consequat elementum. <br>');

-- --------------------------------------------------------

--
-- Table structure for table `pms`
--

CREATE TABLE IF NOT EXISTS `pms` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) DEFAULT NULL,
  `from_name` varchar(500) DEFAULT NULL,
  `to_id` int(11) DEFAULT NULL,
  `to_name` varchar(500) DEFAULT NULL,
  `avatar` varchar(999) DEFAULT NULL,
  `message` longtext,
  `pm_date` varchar(255) DEFAULT NULL,
  `from_read` int(11) DEFAULT NULL,
  `to_read` int(11) DEFAULT NULL,
  `from_delete` int(11) DEFAULT NULL,
  `to_delete` int(11) DEFAULT NULL,
  `last_msg` varchar(255) DEFAULT NULL,
  `msg_order` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pm_replies`
--

CREATE TABLE IF NOT EXISTS `pm_replies` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) DEFAULT NULL,
  `from_name` varchar(500) DEFAULT NULL,
  `to_id` int(11) DEFAULT NULL,
  `to_name` varchar(500) DEFAULT NULL,
  `avatar` varchar(999) DEFAULT NULL,
  `message` longtext,
  `pm_date` varchar(255) DEFAULT NULL,
  `pm_rid` int(11) DEFAULT NULL,
  PRIMARY KEY (`reply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(999) NOT NULL,
  `url` varchar(999) NOT NULL,
  `embed` varchar(999) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(999) NOT NULL,
  `catid` int(11) NOT NULL,
  `uid` int(255) NOT NULL,
  `likes` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `link_hits` int(11) NOT NULL,
  `tot` int(11) NOT NULL,
  `date` varchar(500) NOT NULL,
  `type` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `siteurl` varchar(500) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `descrp` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fbapp` varchar(255) NOT NULL,
  `active` int(11) NOT NULL,
  `fbpage` varchar(900) NOT NULL,
  `twitter` varchar(500) NOT NULL,
  `pinterest` varchar(500) NOT NULL,
  `google_plus` varchar(500) NOT NULL,
  `template` varchar(255) NOT NULL,
  `site_hits` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `siteurl`, `keywords`, `descrp`, `email`, `fbapp`, `active`, `fbpage`, `twitter`, `pinterest`, `google_plus`, `template`, `site_hits`) VALUES
(1, '', '', '', '', '', '', 1, '', '', '', '', 'default', 0);

-- --------------------------------------------------------

--
-- Table structure for table `siteads`
--

CREATE TABLE IF NOT EXISTS `siteads` (
  `id` int(11) NOT NULL,
  `ad1` varchar(500) NOT NULL,
  `ad2` varchar(500) NOT NULL,
  `ad3` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `siteads`
--

INSERT INTO `siteads` (`id`, `ad1`, `ad2`, `ad3`) VALUES
(1, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(500) CHARACTER SET utf8 NOT NULL,
  `password` varchar(999) CHARACTER SET utf8 NOT NULL,
  `avatar` varchar(500) CHARACTER SET utf8 NOT NULL,
  `about` varchar(999) CHARACTER SET utf8 NOT NULL,
  `reg_date` varchar(500) CHARACTER SET utf8 NOT NULL,
  `notifications` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
