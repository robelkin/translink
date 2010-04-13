-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 13, 2010 at 10:58 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

--
-- Run nirailways.sql first to create database
--
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `translink_test6`
--

--
-- Dumping data for table `tblLocation`
--

INSERT INTO `tblLocation` (`Location`, `LocationLat`, `LocationLong`, `Name`) VALUES
('AD', 54.578413, -5.955014, 'Adelaide'),
('AN', 54.718218, -6.21154, 'Antrim'),
('BC', 54.777527, -5.725851, 'Ballycarry'),
('BA', 54.864167, -6.284722, 'Ballymena'),
('BY', 55.066572, -6.51405, 'Ballymoney'),
('BL', 54.568696, -5.968465, 'Balmoral'),
('BR', 54.658861, -5.671129, 'Bangor'),
('BW', 54.660003, -5.693235, 'Bangor West'),
('CL', 54.595226, -5.9172, 'Belfast Central'),
('BN', 55.12845, -6.95143, 'Bellarena'),
('BT', 54.588407, -5.932939, 'Botanic'),
('BE', 54.601912, -5.906391, 'Bridge End'),
('CA', 54.666078, -5.705579, 'Carnalea'),
('CS', 54.717567, -5.809708, 'Carrickfergus'),
('CK', 55.165126, -6.787121, 'Castlerock'),
('CH', 54.588765, -5.94032, 'City Hospital'),
('CP', 54.71754, -5.818838, 'Clipperstown'),
('CE', 55.133696, -6.662661, 'Coleraine'),
('CY', 54.88812, -6.34469, 'Cullybackey'),
('CT', 54.652253, -5.805188, 'Cultra'),
('DH', 54.541715, -6.018381, 'Derriaghy'),
('DV', 55.196317, -6.661293, 'Dhu Varren'),
('DP', 54.720939, -5.790739, 'Downshire'),
('DG', 53.712158, -6.333017, 'Drogheda'),
('DN', 53.3527, -6.247042, 'Dublin Connolly'),
('DK', 54.002138, -6.412883, 'Dundalk'),
('DM', 54.553108, -6.003237, 'Dunmurry'),
('FY', 54.563775, -5.986664, 'Finaghy'),
('GN', 54.827095, -5.807052, 'Glynn'),
('GV', 54.594145, -5.936522, 'Great Victoria Street'),
('GD', 54.70024, -5.872965, 'Greenisland'),
('HB', 54.666292, -5.740544, 'Helen''s Bay'),
('HD', 54.522304, -6.029402, 'Hilden'),
('HW', 54.641153, -5.83962, 'Holywood'),
('JN', 54.687265, -5.894959, 'Jordanstown'),
('LG', 54.529605, -6.029665, 'Lambeg'),
('LR', 54.84789, -5.79833, 'Larne Harbour'),
('LN', 54.84974, -5.81321, 'Larne Town'),
('LB', 54.51395, -6.045892, 'Lisburn'),
('LY', 54.992126, -7.31388, 'Londonderry'),
('LU', 54.467138, -6.338897, 'Lurgan'),
('MM', 54.815398, -5.766921, 'Magheramorne'),
('MO', 54.646905, -5.817642, 'Marino'),
('MR', 54.492145, -6.214056, 'Moira'),
('MW', 54.696815, -5.951084, 'Mossley West'),
('NY', 54.18574, -6.36177, 'Newry'),
('PD', 54.425, -6.446, 'Portadown'),
('PH', 55.203202, -6.653316, 'Portrush'),
('PS', 54.29308, -6.36984, 'Poyntzpass'),
('RPSI', 54.75562, -5.71041, 'Railway Preservation Socie'),
('SA', 54.331565, -6.36632, 'Scarva'),
('SL', 54.660927, -5.767946, 'Seahill'),
('SY', 54.609291, -5.877374, 'Sydenham'),
('TE', 54.709963, -5.849635, 'Trooperslane'),
('UV', 55.150194, -6.668471, 'University'),
('WA', 54.672254, -5.904508, 'Whiteabbey'),
('WD', 54.752985, -5.709608, 'Whitehead'),
('YG', 54.610434, -5.922124, 'Yorkgate');
