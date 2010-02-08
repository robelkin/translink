CREATE TABLE IF NOT EXISTS `tblImportHistory` (
  `ImportHistoryID` int(11) NOT NULL AUTO_INCREMENT,
  `FileDate` datetime NOT NULL,
  `ImportDate` datetime NOT NULL,
  PRIMARY KEY (`ImportHistoryID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourney`
--

CREATE TABLE IF NOT EXISTS `tblJourney` (
  `Operator` varchar(4) NOT NULL,
  `UniqueJourneyIdentifier` varchar(6) NOT NULL,
  `FirstDateOfOperation` datetime NOT NULL,
  `LastDateOfOperation` datetime NOT NULL,
  `OperatesOnMondays` tinyint(1) NOT NULL,
  `OperatesOnTuesdays` tinyint(1) NOT NULL,
  `OperatesOnWednesdays` tinyint(1) NOT NULL,
  `OperatesOnThursdays` tinyint(1) NOT NULL,
  `OperatesOnFridays` tinyint(1) NOT NULL,
  `OperatesOnSaturdays` tinyint(1) NOT NULL,
  `OperatesOnSundays` tinyint(1) NOT NULL,
  `SchoolTermTime` enum('','School Term Only','School Holidays Only') NOT NULL,
  `BankHolidays` enum('','Additionally on Bank Holiday','Bank Holiday Only','Except Bank Holiday') NOT NULL,
  `RouteNumber` varchar(4) NOT NULL,
  `RunningBoard` varchar(6) NOT NULL,
  `VehicleType` varchar(8) NOT NULL,
  `RegistrationNumber` varchar(8) NOT NULL,
  `RouteDirection` varchar(1) NOT NULL,
  PRIMARY KEY (`UniqueJourneyIdentifier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyDateRunning`
--

CREATE TABLE IF NOT EXISTS `tblJourneyDateRunning` (
  `JourneyDateRunningID` int(11) NOT NULL AUTO_INCREMENT,
  `StartOfExceptionalPeriod` datetime NOT NULL,
  `EndOfExceptionalPeriod` datetime NOT NULL,
  `OperationCode` varchar(1) NOT NULL,
  `UniqueJourneyIdentifier` varchar(6) NOT NULL,
  PRIMARY KEY (`JourneyDateRunningID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyDestination`
--

CREATE TABLE IF NOT EXISTS `tblJourneyDestination` (
  `JourneyDestinationID` int(11) NOT NULL AUTO_INCREMENT,
  `Location` varchar(12) NOT NULL,
  `ArrivalTime` time NOT NULL,
  `BayNumber` varchar(3) NOT NULL,
  `TimingPoint` enum('Timing Point','Not Timing Point') NOT NULL,
  `FareStage` enum('Fare Stage','Not Fare Stage') NOT NULL,
  `UniqueJourneyIdentifier` varchar(6) NOT NULL,
  PRIMARY KEY (`JourneyDestinationID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyIntermediate`
--

CREATE TABLE IF NOT EXISTS `tblJourneyIntermediate` (
  `JourneyIntermediateID` int(11) NOT NULL AUTO_INCREMENT,
  `Location` varchar(12) NOT NULL,
  `ArrivalTime` time NOT NULL,
  `DepartureTime` time NOT NULL,
  `Activity` enum('Pick up and Set down','Pick up','Set down','Neither') NOT NULL,
  `BayNumber` varchar(3) NOT NULL,
  `TimingPoint` enum('Timing Point','Not Timing Point') NOT NULL,
  `FareStage` enum('Fare Stage','Not Fare Stage') NOT NULL,
  `UniqueJourneyIdentifier` varchar(6) NOT NULL,
  PRIMARY KEY (`JourneyIntermediateID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyNote`
--

CREATE TABLE IF NOT EXISTS `tblJourneyNote` (
  `JourneyNoteID` int(11) NOT NULL AUTO_INCREMENT,
  `NoteCode` varchar(5) NOT NULL,
  `NoteText` mediumtext NOT NULL,
  `UniqueJourneyIdentifier` varchar(6) NOT NULL,
  PRIMARY KEY (`JourneyNoteID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyOrigin`
--

CREATE TABLE IF NOT EXISTS `tblJourneyOrigin` (
  `JourneyOriginID` int(11) NOT NULL AUTO_INCREMENT,
  `Location` varchar(12) NOT NULL,
  `DepartureTime` time NOT NULL,
  `BayNumber` varchar(3) NOT NULL,
  `TimingPoint` enum('Timing Point','Not Timing Point') NOT NULL DEFAULT 'Not Timing Point',
  `FareStage` enum('Fare Stage','Not Fare Stage') NOT NULL DEFAULT 'Not Fare Stage',
  `UniqueJourneyIdentifier` varchar(6) NOT NULL,
  PRIMARY KEY (`JourneyOriginID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyRepetition`
--

CREATE TABLE IF NOT EXISTS `tblJourneyRepetition` (
  `JourneyRepetitionID` int(11) NOT NULL AUTO_INCREMENT,
  `Location` varchar(12) NOT NULL,
  `DepartureTime` time NOT NULL,
  `UniqueJourneyIdentifier` varchar(6) NOT NULL,
  `RuninngBoard` varchar(6) NOT NULL,
  `VehicleType` varchar(8) NOT NULL,
  `DuplicatedJourneyIdentifier` varchar(6) NOT NULL,
  PRIMARY KEY (`JourneyRepetitionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `tblStop`
--

CREATE TABLE IF NOT EXISTS `tblStop` (
  `StopID` int(11) NOT NULL AUTO_INCREMENT,
  `StopName` varchar(100) NOT NULL,
  `StopLat` double NOT NULL,
  `StopLong` double NOT NULL,
  `StopReference` varchar(50) NOT NULL,
  PRIMARY KEY (`StopID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
