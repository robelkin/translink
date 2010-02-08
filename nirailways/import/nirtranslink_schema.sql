
--
-- Table structure for table `tblActivity`
--

CREATE TABLE IF NOT EXISTS `tblActivity` (
  `ActivityID` int(11) NOT NULL AUTO_INCREMENT,
  `JourneyOriginID` int(11) NOT NULL,
  `Activity` char(2) NOT NULL,
  PRIMARY KEY (`ActivityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblAssocations`
--

CREATE TABLE IF NOT EXISTS `tblAssocations` (
  `AssocationID` int(11) NOT NULL AUTO_INCREMENT,
  `MainTrainID` int(11) NOT NULL,
  `AssociatedTrainID` int(11) NOT NULL,
  `AssocationStartDate` datetime NOT NULL,
  `AssocationEndDate` datetime NOT NULL,
  `AssocationOnMondays` int(1) NOT NULL,
  `AssocationOnTuesdays` int(1) NOT NULL,
  `AssocationOnWednesdays` int(1) NOT NULL,
  `AssocationOnThursdays` int(1) NOT NULL,
  `AssocationOnFridays` int(1) NOT NULL,
  `AssocationOnSaturdays` int(1) NOT NULL,
  `AssocationOnSundays` int(1) NOT NULL,
  `AssociationDateIndicator` char(1) NOT NULL,
  `Location` char(7) NOT NULL,
  `BaseLocationSuffix` char(1) NOT NULL,
  `AssocLocationSuffix` char(1) NOT NULL,
  `AssocationType` enum('','Passenger use','Operating use only') NOT NULL,
  `AssocationCategory` enum('','Join','Divide','Next') NOT NULL,
  `STPIndicator` enum('','STP Cancellation of Permanent assoc','New STP assoc (not an overlay)','STP overlay of Permanent Association','Permanent assoc','if non overlay user') NOT NULL,
  PRIMARY KEY (`AssocationID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblCateringCodes`
--

CREATE TABLE IF NOT EXISTS `tblCateringCodes` (
  `CateringCodesID` int(11) NOT NULL AUTO_INCREMENT,
  `JourneyIntermediateID` int(11) NOT NULL,
  `CateringCode` enum('','Buffet Service','Restaurant Car available for First Class passengers','Service of hot food available','Meal included for First Class passengers','Wheelchair only reservations','Restaurant','Trolley Service') NOT NULL,
  PRIMARY KEY (`CateringCodesID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `UniqueJourneyIdentifier` char(6) NOT NULL,
  `FirstDateOfOperation` datetime NOT NULL,
  `LastDateOfOperation` datetime NOT NULL,
  `OperatesOnMondays` tinyint(1) NOT NULL,
  `OperatesOnTuesdays` tinyint(1) NOT NULL,
  `OperatesOnWednesdays` tinyint(1) NOT NULL,
  `OperatesOnThursdays` tinyint(1) NOT NULL,
  `OperatesOnFridays` tinyint(1) NOT NULL,
  `OperatesOnSaturdays` tinyint(1) NOT NULL,
  `OperatesOnSundays` tinyint(1) NOT NULL,
  `BankHolidays` enum('','Does not run on specified Bank Holidays.','Does not run on specified Edinburgh Holiday dates.','Does not run on specified Glasgow Holiday dates.') NOT NULL,
  `TrainStatus` enum('','Bus (Permanent)','Freight (Permanent - WTT)','Passengers & Parcels (Permanent - WTT)','Ship (Permanent)','Trip (Permanent)','STP Bus','STP Freight','STP Passengers & Parcels','STP Ship','STP Trip') NOT NULL,
  `TrainCategory` char(2) NOT NULL,
  `TrainIdentity` char(4) NOT NULL,
  `Headcode` char(4) NOT NULL,
  `TrainServiceCode` char(8) NOT NULL,
  `BusinessSector` enum('','Train may be used to convey Red Star parcels') NOT NULL,
  `PowerType` char(3) NOT NULL,
  `TimingLoad` char(4) NOT NULL,
  `Speed` char(3) NOT NULL,
  `Sleepers` enum('','First & Standard Class','First Class only','Standard Class only') NOT NULL,
  `Reservations` enum('','Seat Reservations Compulsory','Seat Reservations for Bicycles Essential','Seat Reservations Recommended','Seat Reservations possible from any station') NOT NULL,
  `TrainClass` enum('','First & Standard seats','Standard seats only') NOT NULL,
  `ConnectionIndicator` enum('','Connections not allowed into this train','Connections not allowed out of this train','Connections not allowed at all') NOT NULL,
  `STPIndicator` enum('','STP Cancellation of Permanent schedule','New STP schedule (not an overlay)','STP overlay of Permanent schedule','Permanent','if non overlay user') NOT NULL,
  `CourseIndicator` char(1) NOT NULL,
  `UICCode` char(5) NOT NULL,
  `ATOCCode` char(2) NOT NULL,
  `ApplicableTimetableCode` enum('','Train is subject to performance monitoring','Train is not subject to performance monitoring') NOT NULL,
  PRIMARY KEY (`UniqueJourneyIdentifier`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblJourneyDestination`
--

CREATE TABLE IF NOT EXISTS `tblJourneyDestination` (
  `JourneyDestinationID` int(11) NOT NULL AUTO_INCREMENT,
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
  `CapitalsIdentification` char(2) NOT NULL,
  `Nalco` char(6) NOT NULL,
  `NLCCheckCharacter` char(1) NOT NULL,
  `TPSDescription` char(26) NOT NULL,
  `Stanox` char(44) NOT NULL,
  `CRSCode` char(3) NOT NULL,
  `CAPRIDescription` char(16) NOT NULL,
  PRIMARY KEY (`Location`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblLocationNote`
--

CREATE TABLE IF NOT EXISTS `tblLocationNote` (
  `LocationNoteID` int(11) NOT NULL AUTO_INCREMENT,
  `UniqueJourneyIdentifier` int(11) NOT NULL,
  `Location` char(7) NOT NULL,
  `LocationSuffix` char(1) NOT NULL,
  `NoteType` char(4) NOT NULL,
  `Note` char(77) NOT NULL,
  PRIMARY KEY (`LocationNoteID`)
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblTrainChange`
--

CREATE TABLE IF NOT EXISTS `tblTrainChange` (
  `TrainChangeID` int(11) NOT NULL AUTO_INCREMENT,
  `UniqueJourneyIdentifier` char(6) NOT NULL,
  `Location` char(7) NOT NULL,
  `LocationSuffix` char(1) NOT NULL,
  `TrainCategory` char(2) NOT NULL,
  `TrainIdentity` char(4) NOT NULL,
  `Headcode` char(4) NOT NULL,
  `TrainServiceCode` char(8) NOT NULL,
  `CourseIndicator` char(1) NOT NULL,
  `BusinessSector` enum('','Train may be used to convey Red Star parcels') NOT NULL,
  `PowerType` char(3) NOT NULL,
  `TimingLoad` char(4) NOT NULL,
  `Speed` char(3) NOT NULL,
  `Sleepers` enum('','First & Standard Class','First Class only','Standard Class only') NOT NULL,
  `Reservations` enum('','Seat Reservations Compulsory','Seat Reservations for Bicycles Essential','Seat Reservations Recommended','Seat Reservations possible from any station') NOT NULL,
  `TrainClass` enum('','First & Standard seats','Standard seats only') NOT NULL,
  `ConnectionIndicator` enum('','Connections not allowed into this train','Connections not allowed out of this train','Connections not allowed at all') NOT NULL,
  `STPIndicator` enum('','STP Cancellation of Permanent schedule','New STP schedule (not an overlay)','STP overlay of Permanent schedule','Permanent','if non overlay user') NOT NULL,
  PRIMARY KEY (`TrainChangeID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblTrainNote`
--

CREATE TABLE IF NOT EXISTS `tblTrainNote` (
  `TrainNoteID` int(11) NOT NULL AUTO_INCREMENT,
  `NoteType` char(3) NOT NULL,
  `Note` char(77) NOT NULL,
  `UniqueLocationIdentifier` int(11) NOT NULL,
  PRIMARY KEY (`TrainNoteID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

