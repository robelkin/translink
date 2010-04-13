-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 13, 2010 at 10:58 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

--
-- Run location.sql to insert location information afterwards
--
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `translink_nir1`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblActivity`
--

CREATE TABLE IF NOT EXISTS `tblActivity` (
  `ActivityID` int(11) NOT NULL AUTO_INCREMENT,
  `JourneyID` int(11) NOT NULL,
  `Activity` char(2) NOT NULL,
  `JourneyType` enum('Origin','Intermediate','Destination') NOT NULL,
  PRIMARY KEY (`ActivityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblImportHistory`
--

CREATE TABLE IF NOT EXISTS `tblImportHistory` (
  `ImportHistoryID` int(11) NOT NULL,
  `FileDate` datetime NOT NULL,
  `ImportDate` datetime NOT NULL,
  `UniqueFileReference` char(7) NOT NULL,
  PRIMARY KEY (`ImportHistoryID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourney`
--

CREATE TABLE IF NOT EXISTS `tblJourney` (
  `UniqueJourneyIdentifier` int(11) NOT NULL AUTO_INCREMENT,
  `ProviderJourneyIdentifier` int(11) NOT NULL,
  `FirstDateOfOperation` datetime NOT NULL,
  `LastDateOfOperation` datetime NOT NULL,
  `OperatesOnMondays` tinyint(1) NOT NULL,
  `OperatesOnTuesdays` tinyint(1) NOT NULL,
  `OperatesOnWednesdays` tinyint(1) NOT NULL,
  `OperatesOnThursdays` tinyint(1) NOT NULL,
  `OperatesOnFridays` tinyint(1) NOT NULL,
  `OperatesOnSaturdays` tinyint(1) NOT NULL,
  `OperatesOnSundays` tinyint(1) NOT NULL,
  `TrainStatus` enum('','Bus (Permanent)','Freight (Permanent - WTT)','Passengers & Parcels (Permanent - WTT)','Ship (Permanent)','Trip (Permanent)','STP Bus','STP Freight','STP Passengers & Parcels','STP Ship','STP Trip') NOT NULL,
  `TrainCategory` char(2) NOT NULL,
  `TrainIdentity` char(4) NOT NULL,
  `PowerType` char(3) NOT NULL,
  `TimingLoad` char(4) NOT NULL,
  `Speed` char(3) NOT NULL,
  `Reservations` enum('','Seat Reservations Compulsory','Seat Reservations for Bicycles Essential','Seat Reservations Recommended','Seat Reservations possible from any station') NOT NULL,
  `TrainClass` enum('','First & Standard seats','Standard seats only') NOT NULL,
  `STPIndicator` enum('','STP Cancellation of Permanent schedule','New STP schedule (not an overlay)','STP overlay of Permanent schedule','Permanent','if non overlay user') NOT NULL,
  `CourseIndicator` char(1) NOT NULL,
  `ApplicableTimetableCode` enum('','Train is subject to performance monitoring','Train is not subject to performance monitoring') NOT NULL,
  PRIMARY KEY (`UniqueJourneyIdentifier`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyDestination`
--

CREATE TABLE IF NOT EXISTS `tblJourneyDestination` (
  `JourneyDestinationID` int(11) NOT NULL AUTO_INCREMENT,
  `ProviderJourneyIdentifier` int(11) NOT NULL,
  `UniqueJourneyIdentifier` int(11) NOT NULL,
  `Location` char(7) NOT NULL,
  `LocationSuffix` char(1) NOT NULL,
  `ScheduledArrival` time NOT NULL,
  `PublicArrival` time NOT NULL,
  `Platform` char(3) NOT NULL,
  `Path` char(3) NOT NULL,
  PRIMARY KEY (`JourneyDestinationID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyIntermediate`
--

CREATE TABLE IF NOT EXISTS `tblJourneyIntermediate` (
  `JourneyIntermediateID` int(11) NOT NULL AUTO_INCREMENT,
  `ProviderJourneyIdentifier` int(11) NOT NULL,
  `UniqueJourneyIdentifier` int(11) NOT NULL,
  `Location` char(7) NOT NULL,
  `LocationSuffix` char(1) NOT NULL,
  `ScheduledArrival` time NOT NULL,
  `ScheduledPass` time NOT NULL,
  `ScheduledDeparture` time NOT NULL,
  `PublicArrival` time NOT NULL,
  `PublicDeparture` time NOT NULL,
  `Platform` char(3) NOT NULL,
  `Line` char(3) NOT NULL,
  `Path` char(3) NOT NULL,
  `EngineeringAllowance` time NOT NULL,
  `PathingAllowance` time NOT NULL,
  `PerformanceAllowance` time NOT NULL,
  PRIMARY KEY (`JourneyIntermediateID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyOrigin`
--

CREATE TABLE IF NOT EXISTS `tblJourneyOrigin` (
  `JourneyOriginID` int(11) NOT NULL AUTO_INCREMENT,
  `ProviderJourneyIdentifer` int(11) NOT NULL,
  `UniqueJourneyIdentifier` int(11) NOT NULL,
  `Location` char(8) NOT NULL,
  `LocationSuffix` char(1) NOT NULL,
  `ScheduledDeparture` time NOT NULL,
  `PublicDeparture` time NOT NULL,
  `Platform` char(3) NOT NULL,
  `Line` char(3) NOT NULL,
  `EngineeringAllowance` time NOT NULL,
  `PathingAllowance` time NOT NULL,
  `PerformanceAllowance` time NOT NULL,
  PRIMARY KEY (`JourneyOriginID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblLocation`
--

CREATE TABLE IF NOT EXISTS `tblLocation` (
  `Location` char(7) NOT NULL,
  `LocationLat` double NOT NULL,
  `LocationLong` double NOT NULL,
  `Name` char(26) NOT NULL,
  PRIMARY KEY (`Location`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblOperChars`
--

CREATE TABLE IF NOT EXISTS `tblOperChars` (
  `OperCharsID` int(11) NOT NULL AUTO_INCREMENT,
  `JourneyOriginID` int(11) NOT NULL,
  `OperChar` char(1) NOT NULL,
  PRIMARY KEY (`OperCharsID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblServiceBranding`
--

CREATE TABLE IF NOT EXISTS `tblServiceBranding` (
  `ServiceBrandingID` int(11) NOT NULL AUTO_INCREMENT,
  `JourneyOriginID` int(11) NOT NULL,
  `ServiceBranding` varchar(225) NOT NULL,
  PRIMARY KEY (`ServiceBrandingID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
