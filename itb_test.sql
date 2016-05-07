-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: May 07, 2016 at 01:14 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE `products` (
  `id` int(11) NOT NULL PRIMARY KEY AUTOINCREMENT,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `quantityInStock` int(11) NOT NULL,
  `restockQuantity` int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5;


-- CREATE TABLE `products` (
--   `id` int(11) NOT NULL PRIMARY KEY AUTOINCREMENT,
--   `description` text NOT NULL,
--   `price` float NOT NULL,
--   `quantityInStock` int(11) NOT NULL,
--   `restockQuantity` int(11) NOT NULL,
--   PRIMARY KEY (id)
-- ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5;
