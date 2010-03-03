<?php

set_time_limit( 0 );

include_once( "../../classes/DataHelper.class.php" );

// get the file - entire timetable is in single file
$file = ( '../../data/nirailways/nir-full-20091125.CIF' );

$fh = fopen( $file, "r" );

while( $line = fgets( $fh ) )
{
	#cuts off line-numbering at beginning of lines. Not sure what it's doing there.
	$line = substr ($line, -82, 80);
	
	if( substr( $line, 0, 2 ) == "HD" )
	{
		if ( substr ( $line, 46, 1 ) != "F" )
		{
			echo "Only full extract files supported.";
			exit;
		}
		$date = ParseDate( substr( $line, 22, 6 ) );
		$time = ParseTime( substr( $line, 28, 4 ) );

		$helper = new DataHelper( "tblImportHistory", "ImportHistoryID" );
		$helper->data[ 'FileDate' ] = $date." ".$time;
		$helper->data[ 'ImportDate' ] = date( "Y-m-d H:i:s" );
		$helper->data[ 'UniqueFileReference' ] = substr( $line, 32, 7 );
		$helper->SaveRecord();

		continue;
	}

	$headerCode = substr( $line, 0, 2 );

	switch( $headerCode )
	{
		case "BS":
			$helper = new DataHelper( "tblJourney", "UniqueJourneyIdentifier" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = substr( $line, 3, 6 );
			$helper->data[ 'FirstDateOfOperation' ] = ParseDate( substr( $line, 9, 6 ) );
			$helper->data[ 'LastDateOfOperation' ] = ParseDate( substr( $line, 15, 6 ) );
			$helper->data[ 'OperatesOnMondays' ] = substr( $line, 21, 1 );
			$helper->data[ 'OperatesOnTuesdays' ] =  substr( $line, 22, 1 );
			$helper->data[ 'OperatesOnWednesdays' ] =  substr( $line, 23, 1 );
			$helper->data[ 'OperatesOnThursdays' ] =  substr( $line, 24, 1 );
			$helper->data[ 'OperatesOnFridays' ] =  substr( $line, 25, 1 );
			$helper->data[ 'OperatesOnSaturdays' ] =  substr( $line, 26, 1 );
			$helper->data[ 'OperatesOnSundays' ] =  substr( $line, 27, 1 );
			$helper->data[ 'CourseIndicator' ] =  substr( $line, 40, 1 );

			$bankHolidays = substr( $line, 28, 1 );
			if( $bankHolidays == "X" )
			{
				$helper->data[ 'BankHolidays' ] = "Does not run on specified Bank Holidays."; #wording from ATOC spec
			}
			elseif( $bankHolidays == "E" )
			{
				$helper->data[ 'BankHolidays' ] = "Does not run on specified Edinburgh Holidays dates.";
			}
			elseif( $bankHolidays == "G" )
			{
				$helper->data[ 'BankHolidays' ] = "Does not run on specified Glasgow Holiday dates.";
			}
			else
			{
				$helper->data[ 'BankHolidays' ] = "";
			}

			$status = substr( $line, 29, 1 );
			if( $status == "B" )
			{
				$helper->data[ 'TrainStatus' ] = "Bus (Permanent)"; #wording from ATOC spec
			}
			elseif( $status == "F" )
			{
				$helper->data[ 'TrainStatus' ] = "Freight (Permanent - WTT)";
			}
			elseif( $status == "P" )
			{
				$helper->data[ 'TrainStatus' ] = "Passengers & Parcels (Permanent - WTT)";
			}
			elseif( $status == "S" )
			{
				$helper->data[ 'TrainStatus' ] = "Ship (Permanent)";
			}
			elseif( $status == "T" )
			{
				$helper->data[ 'TrainStatus' ] = "Trip (Permanent)";
			}
			elseif( $status == "5" )
			{
				$helper->data[ 'TrainStatus' ] = "STP Bus";
			}
			elseif( $status == "2" )
			{
				$helper->data[ 'TrainStatus' ] = "STP Freight";
			}
			elseif( $status == "1" )
			{
				$helper->data[ 'TrainStatus' ] = "STP Passengers & Parcels";
			}
			elseif( $status == "4" )
			{
				$helper->data[ 'TrainStatus' ] = "STP Ship";
			}
			elseif( $status == "3" )
			{
				$helper->data[ 'TrainStatus' ] = "STP Trip";
			}
			else
			{
				$helper->data[ 'TrainStatus' ] = "";
			}

			$helper->data[ 'TrainCategory' ] =  substr( $line, 30, 2 );
			$helper->data[ 'TrainIdentity' ] =  substr( $line, 32, 4 );
			$helper->data[ 'Headcode' ] =  substr( $line, 36, 4 );
			$helper->data[ 'TrainServiceCode' ] =  substr( $line, 41, 8 );
			$helper->data[ 'PowerType' ] =  substr( $line, 50, 3 );
			$helper->data[ 'TimingLoad' ] =  substr( $line, 53, 4 );
			$helper->data[ 'Speed' ] =  substr( $line, 57, 3 );
						
			$connindicator = substr( $line, 69, 1 );
			if( $connindicator == "C" )
			{
				$helper->data[ 'ConnectionIndicator' ] = 'Connections not allowed into this train';
			}
			elseif( $connindicator == "S" )
			{
				$helper->data[ 'ConnectionIndicator' ] = 'Connections not allowed out of this train';
			}
			elseif( $connindicator == "X" )
			{
				$helper->data[ 'ConnectionIndicator' ] = 'Connections not allowed at all';
			}
			else
			{
				$helper->data[ 'ConnectionIndicator' ] = '';
			}
			
			
			$trainclass = substr( $line, 66, 1 );
			if( $trainclass == "" OR $trainclass == "B" )
			{
				$helper->data[ 'TrainClass' ] = 'First & Standard seats';
			}
			elseif( $trainclass == "S" )
			{
				$helper->data[ 'TrainClass' ] = 'Standard class only';
			}
			else
			{
				$helper->data[ 'TrainClass' ] = '';
			}
			
			$reservations = substr( $line, 68, 1 );
			if( $reservations == "A" )
			{
				$helper->data[ 'Reservations' ] = 'Seat Reservations Compulsory';
			}
			elseif( $reservations == "E" )
			{
				$helper->data[ 'Reservations' ] = 'Seat Reservations for Bicycles Essential';
			}
			elseif( $reservations == "R" )
			{
				$helper->data[ 'Reservations' ] = 'Seat Reservations Recommended';
			}
			elseif( $reservations == "S" )
			{
				$helper->data[ 'Reservations' ] = 'Seat Reservations possible from any station';
			}
			else
			{
				$helper->data[ 'Reservations' ] = '';
			}
			
			$sleepers = substr( $line, 67, 1 );
			if( $sleepers == "B" )
			{
				$helper->data[ 'Sleepers' ] = 'First & Standard Class';
			}
			elseif( $sleepers == "F" )
			{
				$helper->data[ 'Sleepers' ] = 'First Class only';
			}
			elseif( $sleepers == "S" )
			{
				$helper->data[ 'Sleepers' ] = 'Standard Class only';
			}
			else
			{
				$helper->data[ 'Sleepers' ] = '';
			}
			
			$bussec = substr( $line, 49, 1 );
			if( $bussec == "Z" )
			{
				$helper->data[ 'BusinessSector' ] = 'Train may be used to convey Red Star parcels';
			}
			else
			{
				$helper->data[ 'BusinessSector' ] = '';
			}
			
			$stpindicator = substr( $line, 79, 1 );
			if( $stpindicator == "C" )
			{
				$helper->data[ 'STPIndicator' ] = "STP Cancellation of Permanent schedule"; #wording from ATOC spec
			}
			elseif( $stpindicator == "N" )
			{
				$helper->data[ 'STPIndicator' ] = "New STP schedule (not an overlay)";
			}
			elseif( $stpindicator == "O" )
			{
				$helper->data[ 'STPIndicator' ] = "STP overlay of Permanent schedule";
			}
			elseif( $stpindicator == "P" )
			{
				$helper->data[ 'STPIndicator' ] = "Permanent";
			}
			elseif ($stpindicator == " ")
			{
				$helper->data[ 'STPIndicator' ] = "if non overlay user"; #?
			}
			else
			{
				$helper->data[ 'STPIndicator' ] = ""; 
			}
			
			$helper->SaveRecord();
			$operchar = str_split( substr( $line, 57, 3 ), 1 );
			foreach ( $operchar as $curr ) {
				$curr = trim ( $curr );
				if( !empty ( $curr ) )
				{
					$helper2 = new DataHelper( "tblOperChars", "OperCharsID" );
					$helper2->data[ 'JourneyOriginID' ] = $helper->data [ 'JourneyOriginID' ];
					$helper2->data[ 'OperChar' ] = $curr;
					$helper2->SaveRecord();
				}
			}
			
			$catering = str_split( substr( $line, 70, 4 ), 1 );
			foreach ( $catering as $curr ) {
				$curr = trim ( $curr );
				if( $curr == "C" )
				{
					$curr = "Buffet Service";
				}
				elseif( $curr == "F")
				{
					$curr = "Restaurant Car available for First Class passengers";
				}
				elseif( $curr =="H")
				{
					$curr = "Service of hot food available";
				}
				elseif ($curr == "M")
				{
					$curr = "Meal included for First Class passengers";
				}
				elseif ($curr == "P")
				{
					$curr = "Wheelchair only reservations";
				}
				elseif ($curr == "R")
				{
					$curr = "Restaurant";
				}
				elseif ($curr == "T")
				{
					$curr = "Trolley Service";
				}
				else
				{
					$curr = "";
				}

				if( !empty ( $curr ) )
				{
					$helper2 = new DataHelper( "tblCateringCodes", "CateringCodesID" );
					$helper2->data[ 'JourneyOriginID' ] = $helper->data [ 'JourneyOriginID' ];
					$helper2->data[ 'CateringCode' ] = $curr;
					$helper2->SaveRecord();
				}
			}
			
			$servicebranding = str_split( substr( $line, 74, 4 ), 1 );
			foreach ( $servicebranding as $curr ) {
				$curr = trim ( $curr );
				if( !empty ( $curr ) )
				{
					$helper2 = new DataHelper( "tblServiceBranding", "ServiceBrandingID" );
					$helper2->data[ 'JourneyOriginID' ] = $helper->data [ 'JourneyOriginID' ];
					if ( $curr == 'E' )
					{
						$helper2->data[ 'ServiceBranding' ] = 'Eurostar';
					}
					elseif ($curr == 'U')
					{
						$helper2->data[ 'ServiceBranding' ] = 'Alphaline';
					}
					else
					{
						$helper2->data[ 'ServiceBranding' ] = $curr;
					}
					$helper2->SaveRecord();
				}
			}
			
			$lastJourney = $helper->data[ 'UniqueJourneyIdentifier' ];
		break;

		case "BX":
			$helper = new DataHelper( "tblJourney", "UniqueJourneyIdentifier" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'UICCode' ] = substr( $line, 6, 5 );
			$helper->data[ 'ATOCCode' ] = substr( $line, 11, 2 );
			
			$atc = substr( $line, 13, 1 );
			if ( $atc == "Y" )
			{
				$helper->data[ 'ApplicableTimetableCode' ] = 'Train is subject to performance monitoring';
			}
			elseif ( $atc == "N" )
			{
				$helper->data[ 'ApplicableTimetableCode' ] = 'Train is not subject to performance monitoring';
			}
			else
			{
				$helper->data[ 'ApplicableTimetableCode' ] = $atc;
			}
			#$helper->data[ 'RSID' ] = substr( $line, 14, 8 ); #reserved field?
			#$helper->data[ 'DataSource' ] = substr( $line, 22, 1 ); #reserved field?
			$helper->SaveRecord();
		break;
		
		case "LO":
			$helper = new DataHelper( "tblJourneyOrigin", "JourneyOriginID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'Location' ] = substr( $line, 2, 7 );
			$helper->data[ 'LocationSuffix' ] = substr( $line, 9, 1 );
			$helper->data[ 'ScheduledDeparture' ] = ParseTime( substr( $line, 10, 5 ) );
			$helper->data[ 'PublicDeparture' ] = ParseTime( substr( $line, 15, 4 ) );
			$helper->data[ 'Platform' ] = substr( $line, 19, 3 );
			$helper->data[ 'Line' ] = substr( $line, 22, 3 );
			$helper->data[ 'EngineeringAllowance' ] = ParseWait( substr ($line, 25, 2) );
			$helper->data[ 'PathingAllowance' ] = ParseWait( substr ($line, 27, 2) );
			$helper->data[ 'PerformanceAllowance' ] = ParseWait( substr ($line, 41, 2) );
			
			$helper->SaveRecord();
			$lastLocation = $helper->data[ 'Location' ];
			$lastSuffix = $helper->data[ 'LocationSuffix' ];
			
			$activity = str_split( substr( $line, 29, 12 ), 2 );
			foreach ( $activity as $act ) {
				$act = trim ( $act );
				if( !empty ( $act ) )
				{
					$helper2 = new DataHelper( "tblActivity", "ActivityID" );
					$helper2->data[ 'JourneyOriginID' ] = $helper->data [ 'JourneyOriginID' ];
					$helper2->data[ 'Activity' ] = $act;
					$helper2->SaveRecord();
				}
			}
		break;

		case "LI":
			$helper = new DataHelper( "tblJourneyIntermediate", "JourneyIntermediateID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'Location' ] = substr( $line, 2, 7 );
			$helper->data[ 'LocationSuffix' ] = substr( $line, 9, 1 );
			$helper->data[ 'ScheduledArrival' ] = ParseTime( substr( $line, 10, 5 ) );	
			$helper->data[ 'ScheduledDeparture' ] = ParseTime( substr( $line, 15, 5 ) );
			$helper->data[ 'ScheduledPass' ] = ParseTime( substr( $line, 20, 5 ) );	
			$helper->data[ 'PublicArrival' ] = ParseTime( substr( $line, 25, 4 ) );
			$helper->data[ 'PublicDeparture' ] = ParseTime( substr( $line, 29, 4 ) );
			$helper->data[ 'Platform' ] = substr( $line, 33, 3 );
			$helper->data[ 'Line' ] = substr( $line, 36, 3 );
			$helper->data[ 'Path' ] = substr( $line, 39, 3 );
			$helper->data[ 'EngineeringAllowance' ] = ParseWait( substr( $line, 54, 2 ) );
			$helper->data[ 'PathingAllowance' ] = ParseWait( substr( $line, 56, 2 ) );
			$helper->data[ 'PerformanceAllowance' ] = ParseWait( substr( $line, 58, 2 ) );

			$helper->SaveRecord();
			
			$lastLocation = $helper->data[ 'Location' ];
			$lastSuffix = $helper->data[ 'LocationSuffix' ];
			
			$activity = str_split( substr( $line, 42, 12 ), 2 );
			foreach ( $activity as $act ) {
				$act = trim ( $act );
				if( !empty ( $act ) )
				{
					$helper2 = new DataHelper( "tblActivity", "ActivityID" );
					$helper2->data[ 'JourneyOriginID' ] = $helper->data [ 'JourneyIntermediateID' ];
					$helper2->data[ 'Activity' ] = $act;
					$helper2->SaveRecord();
				}
			}
		break;

		case "LT":
			$helper = new DataHelper( "tblJourneyDestination", "JourneyDestinationID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'Location' ] = substr( $line, 2, 7 );
			$helper->data[ 'LocationSuffix' ] = substr( $line, 9, 1 );
			$helper->data[ 'ScheduledArrival' ] = ParseTime( substr( $line, 10, 5 ) );
			$helper->data[ 'PublicArrival' ] = ParseTime( substr( $line, 15, 4 ) );
			$helper->data[ 'Platform' ] = substr( $line, 19, 3 );
			$helper->data[ 'Path' ] = substr( $line, 22, 3 );
			
			$helper->SaveRecord();
			
			$lastLocation = $helper->data[ 'Location' ];
			$lastSuffix = $helper->data[ 'LocationSuffix' ];
		
			$activity = str_split( substr( $line, 25, 12 ), 2 );
			foreach ( $activity as $act ) {
				$act = trim ( $act );
				if( !empty ( $act ) )
				{
					$helper2 = new DataHelper( "tblActivity", "ActivityID" );
					$helper2->data[ 'JourneyIntermediateID' ] = $helper->data [ 'JourneyDestinationID' ];
					$helper2->data[ 'Activity' ] = $act;
					$helper2->SaveRecord();
				}
			}
		break;
		
		case "CR":
			$helper = new DataHelper( "tblTrainChange", "TrainChangeID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = substr( $line, 3, 6 );
			$helper->data[ 'Location' ] = substr( $line, 2, 7 );
			$helper->data[ 'LocationSuffix' ] = substr( $line, 9, 1 );
			$helper->data[ 'TrainCategory' ] =  substr( $line, 10, 2 );
			$helper->data[ 'TrainIdentity' ] =  substr( $line, 12, 4 );
			$helper->data[ 'Headcode' ] =  substr( $line, 16, 4 );
			$helper->data[ 'CourseIndicator' ] =  substr( $line, 20, 1 );
			$helper->data[ 'TrainServiceCode' ] =  substr( $line, 21, 8 );
			$helper->data[ 'PowerType' ] =  substr( $line, 30, 3 );
			$helper->data[ 'TimingLoad' ] =  substr( $line, 33, 4 );
			$helper->data[ 'Speed' ] =  substr( $line, 37, 3 );
			
			$sleepers = substr( $line, 47, 1 );
			if( $sleepers == "B" )
			{
				$helper->data[ 'Sleepers' ] = 'First & Standard Class';
			}
			elseif( $sleepers == "F" )
			{
				$helper->data[ 'Sleepers' ] = 'First Class only';
			}
			elseif( $sleepers == "S" )
			{
				$helper->data[ 'Sleepers' ] = 'Standard Class only';
			}
			else
			{
				$helper->data[ 'Sleepers' ] = '';
			}
			
			$connindicator = substr( $line, 49, 1 );
			if( $connindicator == "C" )
			{
				$helper->data[ 'ConnectionIndicator' ] = 'Connections not allowed into this train';
			}
			elseif( $connindicator == "S" )
			{
				$helper->data[ 'ConnectionIndicator' ] = 'Connections not allowed out of this train';
			}
			elseif( $connindicator == "X" )
			{
				$helper->data[ 'ConnectionIndicator' ] = 'Connections not allowed at all';
			}
			else
			{
				$helper->data[ 'ConnectionIndicator' ] = '';
			}
			
			
			$trainclass = substr( $line, 46, 1 );
			if( $trainclass == "" OR $trainclass == "B" )
			{
				$helper->data[ 'TrainClass' ] = 'First & Standard seats';
			}
			elseif( $trainclass == "S" )
			{
				$helper->data[ 'TrainClass' ] = 'Standard class only';
			}
			else
			{
				$helper->data[ 'TrainClass' ] = '';
			}
			
			$reservations = substr( $line, 48, 1 );
			if( $reservations == "A" )
			{
				$helper->data[ 'Reservations' ] = 'Seat Reservations Compulsory';
			}
			elseif( $reservations == "E" )
			{
				$helper->data[ 'Reservations' ] = 'Seat Reservations for Bicycles Essential';
			}
			elseif( $reservations == "R" )
			{
				$helper->data[ 'Reservations' ] = 'Seat Reservations Recommended';
			}
			elseif( $reservations == "S" )
			{
				$helper->data[ 'Reservations' ] = 'Seat Reservations possible from any station';
			}
			else
			{
				$helper->data[ 'Reservations' ] = '';
			}
			
			$sleepers = substr( $line, 67, 1 );
			if( $sleepers == "B" )
			{
				$helper->data[ 'Sleepers' ] = 'First & Standard Class';
			}
			elseif( $sleepers == "F" )
			{
				$helper->data[ 'Sleepers' ] = 'First Class only';
			}
			elseif( $sleepers == "S" )
			{
				$helper->data[ 'Sleepers' ] = 'Standard Class only';
			}
			else
			{
				$helper->data[ 'Sleepers' ] = '';
			}
			
			$bussec = substr( $line, 29, 1 );
			if( $bussec == "Z" )
			{
				$helper->data[ 'BusinessSector' ] = 'Train may be used to convey Red Star parcels';
			}
			else
			{
				$helper->data[ 'BusinessSector' ] = '';
			}
			
			$stpindicator = substr( $line, 79, 1 );
			if( $stpindicator == "C" )
			{
				$helper->data[ 'STPIndicator' ] = "STP Cancellation of Permanent schedule"; #wording from ATOC spec
			}
			elseif( $stpindicator == "N" )
			{
				$helper->data[ 'STPIndicator' ] = "New STP schedule (not an overlay)";
			}
			elseif( $stpindicator == "O" )
			{
				$helper->data[ 'STPIndicator' ] = "STP overlay of Permanent schedule";
			}
			elseif( $stpindicator == "P" )
			{
				$helper->data[ 'STPIndicator' ] = "Permanent";
			}
			elseif( $stpindicator == " ")
			{
				$helper->data[ 'STPIndicator' ] = "if non overlay user"; #?
			}
			else
			{
				$helper->data[ 'STPIndicator' ] = ""; 
			}
			
			$helper->SaveRecord();
			$operchar = str_split( substr( $line, 40, 3 ), 1 );
			foreach ( $operchar as $curr ) {
				$curr = trim ( $curr );
				if( !empty ( $curr ) )
				{
					$helper2 = new DataHelper( "tblOperChars", "JourneyOriginID" );
					$helper2->data[ 'JourneyOriginID' ] = $helper->data [ 'JourneyOriginID' ];
					$helper2->data[ 'OperChar' ] = $curr;
					$helper2->SaveRecord();
				}
			}
			
			$catering = str_split( substr( $line, 50, 4 ), 1 );
			foreach ( $catering as $curr ) {
				$curr = trim ( $curr );
				if( $curr == "C" )
				{
					$curr = "Buffet Service";
				}
				elseif( $curr == "F")
				{
					$curr = "Restaurant Car available for First Class passengers";
				}
				elseif( $curr =="H")
				{
					$curr = "Service of hot food available";
				}
				elseif ($curr == "M")
				{
					$curr = "Meal included for First Class passengers";
				}
				elseif ($curr == "P")
				{
					$curr = "Wheelchair only reservations";
				}
				elseif ($curr == "R")
				{
					$curr = "Restaurant";
				}
				elseif ($curr == "T")
				{
					$curr = "Trolley Service";
				}
				else
				{
					$curr = "";
				}

				if( !empty ( $curr ) )
				{
					$helper2 = new DataHelper( "tblCateringCodes", "JourneyOriginID" );
					$helper2->data[ 'JourneyOriginID' ] = $helper->data [ 'JourneyOriginID' ];
					$helper2->data[ 'CateringCode' ] = $curr;
					$helper2->SaveRecord();
				}
			}
			
			$servicebranding = str_split( substr( $line, 54, 4 ), 1 );
			foreach ( $servicebranding as $curr ) {
				$curr = trim ( $curr );
				if( !empty ( $curr ) )
				{
					$helper2 = new DataHelper( "tblServiceBranding", "JourneyOriginID" );
					$helper2->data[ 'JourneyOriginID' ] = $helper->data [ 'JourneyOriginID' ];
					if ( $curr == 'E' )
					{
						$helper2->data[ 'ServiceBranding' ] = 'Eurostar';
					}
					elseif ($curr == 'U')
					{
						$helper2->data[ 'ServiceBranding' ] = 'Alphaline';
					}
					else
					{
						$helper2->data[ 'ServiceBranding' ] = $curr;
					}
					$helper2->SaveRecord();
				}
			}
			$helper->SaveRecord();
		break;
		
		# TN line currently not in use
		case "TN":
			$helper = new DataHelper( "tblTrainNote", "TrainNoteID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'Note' ] = substr( $line, 3, 77 );
						
			$notetype = substr( $line, 2, 1 );
			if ( $notetype == 'G' ) {
				$helper->data[ 'NoteType' ] = 'GBTT';
			} elseif ( $notetype = 'W' ) {
				$helper->data[ 'NoteType' ] = 'WTT';
			} else {
				$helper->data[ 'NoteType' ] = '';
			}
			
			$helper->SaveRecord();
		break;
		
		# LN line currently not in use
		case "LN":
			$helper = new DataHelper( "tblLocationNote", "LocationNoteID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'Location' ] = $lastLocation;
			$helper->data[ 'LocationSuffix' ] = $lastSuffix;
			$helper->data[ 'Note' ] = substr( $line, 3, 77 );
						
			$notetype = substr( $line, 2, 1 );
			if ( $notetype == 'G' ) {
				$helper->data[ 'NoteType' ] = 'GBTT';
			} elseif ( $notetype = 'W' ) {
				$helper->data[ 'NoteType' ] = 'WTT';
			} else {
				$helper->data[ 'NoteType' ] = '';
			}
			
			$helper->SaveRecord();
		break;
		
		case "AA":
			$helper = new DataHelper ( "tblAssociations", "AssociationID");
			$helper->data[ 'MainTrainID' ] = substr ( $line, 4, 6);
			$helper->data[ 'AssociatedTrainID' ] = substr ( $line, 10, 6);
			$helper->data[ 'AssociationStartDate' ] = ParseDate ( substr ( $line, 16, 6) );
			$helper->data[ 'AssociationEndDate' ] = ParseDate ( substr ( $line, 22, 6) );
			$helper->data[ 'AssociationOnMondays' ] = substr ( $line, 28, 1);
			$helper->data[ 'AssociationOnTuesdays' ] = substr ( $line, 29, 1);
			$helper->data[ 'AssociationOnWednesdays' ] = substr ( $line, 30, 1);
			$helper->data[ 'AssociationOnThursdays '] = substr ( $line, 31, 1);
			$helper->data[ 'AssociationOnFridays' ] = substr ( $line, 32, 1);
			$helper->data[ 'AssociationOnSaturdays' ] = substr ( $line, 33, 1);
			$helper->data[ 'AssociationOnSundays' ] = substr ( $line, 34, 1);
			$helper->data[ 'AssociationDateIndicator' ] = substr ( $line, 36, 1);
			$helper->data[ 'Location' ] = substr ( $line, 37, 7);
			$helper->data[ 'BaseLocationSuffix' ] = substr ( $line, 44, 1);
			$helper->data[ 'AssocLocationSuffix' ] = substr ( $line, 45, 1);

			$associationtype = substr( $line, 47, 1 );
			if( $associationtype == "P" )
			{
				$helper->data[ 'AssociationType' ] = "Passenger use"; #wording from ATOC spec
			}
			elseif( $associationtype == "O" )
			{
				$helper->data[ 'AssociationType' ] = "Operating use only";
			}
			else
			{
				$helper->data ['AssociationType' ] = "";
			}
			
			$associationcategory = substr( $line, 34, 2 );
			if( $associationcategory == "JJ" )
			{
				$helper->data[ 'AssociationCategory' ] = "Join"; #wording from ATOC spec
			}
			elseif( $associationcategory == "VV" )
			{
				$helper->data[ 'AssociationCategory' ] = "Divide";
			}
			elseif( $associationcategory == "NP" )
			{
				$helper->data[ 'AssociationCategory' ] = "Next";
			}
			else
			{
				$helper->data[ 'AssocationCategory' ] = "";
			}

			$stpindicator = substr( $line, 79, 1 );
			if( $stpindicator == "C" )
			{
				$helper->data[ 'STPIndicator' ] = "STP Cancellation of Permanent assoc"; #wording from ATOC spec
			}
			elseif( $stpindicator == "N" )
			{
				$helper->data[ 'STPIndicator' ] = "New STP assoc (not an overlay)";
			}
			elseif( $stpindicator == "O" )
			{
				$helper->data[ 'STPIndicator' ] = "STP overlay of Permanent Association";
			}
			elseif( $stpindicator == "P" )
			{
				$helper->data[ 'STPIndicator' ] = "Permanent assoc";
			}
			elseif( $stpindicator == " " )
			{
				$helper->data[ 'STPIndicator' ] = "if non overlay user"; #?
			}
			else
			{
				$helper->data[ 'STPIndicator' ] = "";
			}
			
			$helper->SaveRecord();
		break;
		
		case "TI":
			$helper = new DataHelper ( "tblLocation", "Location" );
			$helper->data[ 'Location' ] = substr ( $line, 2, 7 );
			$helper->data[ 'CapitalsIdentification' ] = substr ( $line, 9, 2 );
			$helper->data[ 'Nalco' ] = substr ( $line, 11, 6 );
			$helper->data[ 'NLCCheckCharacter' ] = substr ( $line, 17, 1 );
			$helper->data[ 'TPSDescription' ] = substr ( $line, 18, 26 );
			$helper->data[ 'Stanox' ] = substr ( $line, 44, 5 );
			$helper->data[ 'CRSCode' ] = substr ( $line, 53, 3 );
			$helper->data[ 'CAPRIDescription' ] = substr ( $line, 56, 16 );
			#Lat/long?
			$helper->SaveRecord();
		break;
	}
}

// takes a 2 digit time string with possible 
// half minutes and creates a mysql string
function ParseWait( $time )
{
	if( substr( $time, 1, 1 ) == 'H')
	{
		return "00:0".substr( $time, 0, 1 ).":30";
	}
	else
	{
    	return "00:".$time;
    }
}


// takes a 6 digit date string
//  and creates a mysql formatted string
function ParseDate( $date )
{
    $year = ( substr( $date, 0, 2 ) < 60 ? "20" . substr( $date, 0, 2 ) : "19" . substr( $date, 0, 2 ) );
    $month = substr( $date, 2, 2 );
	$day = substr( $date, 4, 2 );

    return $year."-".$month."-".$day;
}

// takes a 4 or 5 digit time string
//  and creates a mysql formatted time
function ParseTime( $time )
{
	if( strlen( $time ) == 4 )
	{
		$hour = substr( $time, 0, 2 );
		$minute = substr( $time, 2, 2 );

		return $hour.":".$minute;
	}
	else
	{
		$hour = substr( $time, 0, 2 );
		$minute = substr( $time, 2, 2 );
		$second = ( substr( $time, 5, 1 ) == "H" ? "30" : "00" );

		return $hour.":".$minute.":".$second;
	}
}


?>
