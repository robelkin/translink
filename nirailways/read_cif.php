<?php

set_time_limit( 0 );

include_once( "classes/DataHelper.class.php" );

// get the file - entire timetable is in single file
$file ( 'nir.CIF' );

$fh = fopen( $file, "r" );

while( $line = fgets( $fh ) )
{
	#cuts off odd line-numbering at beginning of lines. Not sure what it's doing there.
	$line = substr ($line, -82, 80)
	
	if( stripos( $line, "hd" ) !== false )
	{
		$date = ParseDate( substr( $line, 22, 6 ) ); #fix func
		$time = ParseTime( substr( $line, 28, 4 ) ); #fix func

		$helper = new DataHelper( "tblImportHistory", "ImportHistoryID" );
		$helper->data[ 'FileDate' ] = $date." ".$time;
		$helper->data[ 'ImportDate' ] = date( "Y-m-d H:i:s" );
		$helper->SaveRecord();

		continue;
	}

	$headerCode = substr( $line, 0, 2 );

	switch( $headerCode )
	{
		case "BS":
			$helper = new DataHelper( "tblJourney", "UniqueJourneyIdentifier" );
			#$helper->data[ 'Operator' ] = substr( $line, 3, 4 );
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

			$schoolTermTime = substr( $line, 36, 1 );
			if( $schoolTermTime == "S" )
			{
				$helper->data[ 'SchoolTermTime' ] = "School Term Only";
			}
			elseif( $schoolTermTime == "H" )
			{
				$helper->data[ 'SchoolTermTime' ] = "School Holidays Only";
			}
			else
			{
				$helper->data[ 'SchoolTermTime' ] = "";
			}

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
			if( $bankHolidays == "B" )
			{
				$helper->data[ 'Status' ] = "Bus (Permanent)"; #wording from ATOC spec
			}
			elseif( $bankHolidays == "F" )
			{
				$helper->data[ 'Status' ] = "Freight (Permanent - WTT)";
			}
			elseif( $bankHolidays == "P" )
			{
				$helper->data[ 'Status' ] = "Passengers & Parcels (Permanent - WTT)";
			}
			elseif( $bankHolidays == "S" )
			{
				$helper->data[ 'Status' ] = "Ship (Permanent)";
			}
			elseif( $bankHolidays == "T" )
			{
				$helper->data[ 'Status' ] = "Trip (Permanent)";
			}
			elseif( $bankHolidays == "5" )
			{
				$helper->data[ 'Status' ] = "STP Bus";
			}
			elseif( $bankHolidays == "2" )
			{
				$helper->data[ 'Status' ] = "STP Freight";
			}
			elseif( $bankHolidays == "1" )
			{
				$helper->data[ 'Status' ] = "STP Passengers & Parcels";
			}
			elseif( $bankHolidays == "4" )
			{
				$helper->data[ 'Status' ] = "STP Ship";
			}
			elseif( $bankHolidays == "3" )
			{
				$helper->data[ 'Status' ] = "STP Trip";
			}
			else
			{
				$helper->data[ 'Status' ] = "";
			}

			$helper->data[ 'TrainCategory' ] =  substr( $line, 30, 2 );
			$helper->data[ 'TrainIdentity' ] =  substr( $line, 32, 4 );
			#$helper->data[ 'Headcode' ] =  substr( $line, 36, 4 ); #appears to be unused
			#$helper->data[ 'TrainServiceCode' ] =  substr( $line, 41, 8 ); #appears to be unused
			#$helper->data[ 'BusinessSector' ] =  substr( $line, 49, 1 ); #appears to be unused
			#$helper->data[ 'PowerType' ] =  substr( $line, 50, 3 ); #appears to be unused
			#$helper->data[ 'PowerType' ] =  substr( $line, 50, 3 ); #appears to be unused - value doesn't match spec
			#$helper->data[ 'TimingLoad' ] =  substr( $line, 53, 4 ); #appears to be unused
			#$helper->data[ 'Speed' ] =  substr( $line, 57, 3 ); #appears to be unused
			#$helper->data[ 'OperatingCharacteristics' ] =  substr( $line, 57, 3 ); #appears to be unused
			#$helper->data[ 'Class' ] =  substr( $line, 66, 1 ); #appears to be unused - apparently all trains have first class & standard seating...
			#lots of unused things, unset things
			#$helper->data[ 'STPIndicator' ] =  substr( $line, 79, 1 ); #appears to be unused
			$helper->SaveRecord();
			$lastJourney = $helper->data[ 'UniqueJourneyIdentifier' ];
		break;
#BX lines contain little useful data - possibly train monitoring
		/*case "QE":
			$helper = new DataHelper( "tblJourneyDateRunning", "JourneyDateRunningID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'StartOfExceptionalPeriod' ] = ParseDate( substr( $line, 2, 8 ) );
			$helper->data[ 'EndOfExceptionalPeriod' ] = ParseDate( substr( $line, 10, 8 ) );
			$helper->data[ 'OperationCode' ] = substr( $line, 18, 1 );
			$helper->SaveRecord();
		break;

		case "QN":
			$helper = new DataHelper( "tblJourneyNote", "JourneyNoteID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'NoteCode' ] = substr( $line, 2, 5 );
			$helper->data[ 'NoteText' ] = substr( $line, 7 );
			$helper->SaveRecord();
		break;*/

		case "LO":
			$helper = new DataHelper( "tblJourneyOrigin", "JourneyOriginID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'Location' ] = substr( $line, 2, 8 );
			$helper->data[ 'ScheduledDeparture' ] = ParseTime( substr( $line, 10, 5 ) );
			$helper->data[ 'PublicDeparture' ] = ParseTime( substr( $line, 15, 4 ) );
			$helper->data[ 'Platform' ] = substr( $line, 19, 3 );
			$helper->data[ 'Line' ] = substr( $line, 22, 3 );
			#$helper->data[ 'EngineeringAllowance' ] = substr( $line, 25, 2 ); #appears unused
			#$helper->data[ 'PathingAllowance' ] = substr( $line, 27, 2 ); #appears unused
			#$helper->data[ 'PerformanceAllowance' ] = substr( $line, 41, 2 ); #appears unused
			$helper->data[ 'Activity' ] = substr( $line, 29, 12 );
			$helper->SaveRecord();
		break;

		case "LI":
			$helper = new DataHelper( "tblJourneyIntermediate", "JourneyIntermediateID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'Location' ] = substr( $line, 2, 8 );
			$helper->data[ 'ScheduledArrival' ] = ParseTime( substr( $line, 10, 5 ) );		#These 5 char time fields
			$helper->data[ 'ScheduledDeparture' ] = ParseTime( substr( $line, 15, 5 ) );	#allow for half-minutes. 
			$helper->data[ 'ScheduledPass' ] = ParseTime( substr( $line, 20, 5 ) );			#Any thoughts on dealing with this?
			$helper->data[ 'PublicArrival' ] = ParseTime( substr( $line, 25, 4 ) );
			$helper->data[ 'PublicDeparture' ] = ParseTime( substr( $line, 29, 4 ) );
			$helper->data[ 'Platform' ] = substr( $line, 33, 3 );
			$helper->data[ 'Line' ] = substr( $line, 36, 3 );
			$helper->data[ 'Path' ] = substr( $line, 39, 3 );
			#$helper->data[ 'EngineeringAllowance' ] = substr( $line, 54, 2 ); #appears unused
			#$helper->data[ 'PathingAllowance' ] = substr( $line, 56, 2 ); #appears unused
			#$helper->data[ 'PerformanceAllowance' ] = substr( $line, 58, 2 ); #appears unused

			$helper->data[ 'Activity'] = substr( $line, 42, 12 );	#will parse later.
			/*if( $activity == "B" )
			{
				$helper->data[ 'Activity' ] = "Pick up and Set down";
			}
			elseif( $activity == "P" )
			{
				$helper->data[ 'Activity' ] = "Pick up'";
			}
			elseif( $activity == "S" )
			{
				$helper->data[ 'Activity' ] = "Set down";
			}
			elseif( $activity == "N" )
			{
				$helper->data[ 'Activity' ] = "Neither";
			}
			else
			{
				$helper->data[ 'Activity' ] = "";
			}
*/
			$helper->SaveRecord();
		break;

		case "LT":
			$helper = new DataHelper( "tblJourneyOrigin", "JourneyOriginID" );
			$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
			$helper->data[ 'Location' ] = substr( $line, 2, 8 );
			$helper->data[ 'ScheduledArrival' ] = ParseTime( substr( $line, 10, 5 ) );
			$helper->data[ 'PublicArrival' ] = ParseTime( substr( $line, 15, 4 ) );
			$helper->data[ 'Platform' ] = substr( $line, 19, 3 );
			$helper->data[ 'Path' ] = substr( $line, 22, 3 );
			$helper->data[ 'Activity' ] = substr( $line, 25, 12 );
			$helper->SaveRecord();
		break;
	}
}

// takes a 6 digit date string
//  and creates a mysql formatted string
function ParseDate( $date )
{
	$day = substr( $date, 0, 2 );
    $month = substr( $date, 2, 2 );
    $year = substr( $date, 4, 2 );

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
