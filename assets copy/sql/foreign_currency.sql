-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2015 at 01:08 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `foreign_currency`
--

-- --------------------------------------------------------

--
-- Table structure for table `fcurrency`
--

CREATE TABLE IF NOT EXISTS `fcurrency` (
`id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `abreviation` varchar(3) NOT NULL,
  `rates` decimal(10,8) NOT NULL,
  `surchage` decimal(4,3) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `fcurrency`
--

INSERT INTO `fcurrency` (`id`, `name`, `abreviation`, `rates`, `surchage`) VALUES
(7, 'US Dollars', 'USD', '0.08082790', '0.075'),
(8, 'British Pound', 'GBP', '0.05270320', '0.050'),
(9, 'Euro', 'EUR', '0.07187100', '0.050'),
(10, 'Kenyan Shilling', 'KES', '7.81498000', '0.025');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE IF NOT EXISTS `purchase_order` (
`id` int(11) NOT NULL,
  `amount_purchased` decimal(10,2) NOT NULL,
  `amount_to_be_paid` decimal(10,2) NOT NULL,
  `amount_surcharged` decimal(10,2) NOT NULL,
  `after_discount` decimal(10,2) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `fcurrency_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fcurrency`
--
ALTER TABLE `fcurrency`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_order_fcurrency_idx` (`fcurrency_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fcurrency`
--
ALTER TABLE `fcurrency`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
ADD CONSTRAINT `fk_order_fcurrency` FOREIGN KEY (`fcurrency_id`) REFERENCES `fcurrency` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
