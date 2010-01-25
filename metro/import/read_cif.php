<?php

set_time_limit( 0 );

include_once( "../../classes/DataHelper.class.php" );
// get the files
$files = glob( "../../data/metro/*" );
print_r($files);
foreach( $files as $file )
{
    $fh = fopen( $file, "r" );

    while( $line = fgets( $fh ) )
    {
        if( stripos( $line, "atco" ) !== false )
        {
            $date = ParseDate( substr( $line, 60, 8 ) );
            $time = ParseTime( substr( $line, 68, 6 ) );

            $helper = new DataHelper( "tblImportHistory", "ImportHistoryID" );
            $helper->data[ 'FileDate' ] = $date." ".$time;
            $helper->data[ 'ImportDate' ] = date( "Y-m-d H:i:s" );
            $helper->SaveRecord();

            continue;
        }
        $headerCode = substr( $line, 0, 2 );

        switch( $headerCode )
        {
        	case "QS":
        		$helper = new DataHelper( "tblJourney", "UniqueJourneyIdentifier" );
        		$helper->data[ 'Operator' ] = substr( $line, 3, 4 );
        		$helper->data[ 'UniqueJourneyIdentifier' ] = substr( $line, 7, 6 );
        		$helper->data[ 'FirstDateOfOperation' ] = ParseDate( substr( $line, 13, 8 ) );
        		$helper->data[ 'LastDateOfOperation' ] = ParseDate( substr( $line, 21, 8 ) );
        		$helper->data[ 'OperatesOnMondays' ] = substr( $line, 29, 1 );
        		$helper->data[ 'OperatesOnTuesdays' ] =  substr( $line, 30, 1 );
        		$helper->data[ 'OperatesOnWednesdays' ] =  substr( $line, 31, 1 );
        		$helper->data[ 'OperatesOnThursdays' ] =  substr( $line, 32, 1 );
        		$helper->data[ 'OperatesOnFridays' ] =  substr( $line, 33, 1 );
        		$helper->data[ 'OperatesOnSaturdays' ] =  substr( $line, 34, 1 );
        		$helper->data[ 'OperatesOnSundays' ] =  substr( $line, 35, 1 );

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

				$bankHolidays = substr( $line, 37, 1 );
				if( $bankHolidays == "A" )
				{
					$helper->data[ 'BankHolidays' ] = "Additionally on Bank Holiday";
				}
				elseif( $bankHolidays == "B" )
				{
					$helper->data[ 'BankHolidays' ] = "Bank Holiday Only";
				}
				elseif( $bankHolidays == "X" )
				{
					$helper->data[ 'BankHolidays' ] = "Except Bank Holiday";
				}
				else
				{
					$helper->data[ 'BankHolidays' ] = "";
				}

        		$helper->data[ 'RouteNumber' ] =  substr( $line, 38, 4 );
        		$helper->data[ 'RunningBoard' ] =  substr( $line, 42, 6 );
        		$helper->data[ 'VehicleType' ] =  substr( $line, 48, 8 );
        		$helper->data[ 'RegistrationNumber' ] =  substr( $line, 56, 8 );
        		$helper->data[ 'RouteDirection' ] =  substr( $line, 64, 1 );
        		$helper->SaveRecord();

        		$lastJourney = $helper->data[ 'UniqueJourneyIdentifier' ];
        	break;

        	case "QE":
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
        	break;

        	case "QO":
				$helper = new DataHelper( "tblJourneyOrigin", "JourneyOriginID" );
				$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
				$helper->data[ 'Location' ] = substr( $line, 2, 12 );
				$helper->data[ 'DepartureTime' ] = ParseTime( substr( $line, 14, 4 ) );
				$helper->data[ 'BayNumber' ] = substr( $line, 18, 3 );
				$helper->data[ 'TimingPoint' ] = ( substr( $line, 21, 2 ) == "T1" ? "Timing Point" : "Not Timing Point" );
				$helper->data[ 'FareStage' ] = ( substr( $line, 21, 2 ) == "F1" ? "Fare Stage" : "Not Fare Stage" );
				$helper->SaveRecord();
        	break;

        	case "QI":
				$helper = new DataHelper( "tblJourneyIntermediate", "JourneyIntermediateID" );
				$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
				$helper->data[ 'Location' ] = substr( $line, 2, 12 );
				$helper->data[ 'ArrivalTime' ] = ParseTime( substr( $line, 14, 4 ) );
				$helper->data[ 'DepartureTime' ] = ParseTime( substr( $line, 18, 4 ) );

				$activity = substr( $line, 22, 1 );
				if( $activity == "B" )
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

				$helper->data[ 'BayNumber' ] = substr( $line, 23, 3 );
				$helper->data[ 'TimingPoint' ] = ( substr( $line, 27, 2 ) == "T1" ? "Timing Point" : "Not Timing Point" );
				$helper->data[ 'FareStage' ] = ( substr( $line, 29, 2 ) == "F1" ? "Fare Stage" : "Not Fare Stage" );
				$helper->SaveRecord();
        	break;

        	case "QT":
        		$helper = new DataHelper( "tblJourneyDestination", "JourneyDestinationID" );
				$helper->data[ 'UniqueJourneyIdentifier' ] = $lastJourney;
				$helper->data[ 'Location' ] = substr( $line, 2, 12 );
				$helper->data[ 'ArrivalTime' ] = ParseTime( substr( $line, 14, 4 ) );
				$helper->data[ 'BayNumber' ] = substr( $line, 18, 3 );
				$helper->data[ 'TimingPoint' ] = ( substr( $line, 21, 2 ) == "T1" ? "Timing Point" : "Not Timing Point" );
				$helper->data[ 'FareStage' ] = ( substr( $line, 21, 2 ) == "F1" ? "Fare Stage" : "Not Fare Stage" );
				$helper->SaveRecord();
        	break;

        	case "QR":
        		$helper = new DataHelper( "tblJourneyRepetition", "JourneyRepetitionID" );
				$helper->data[ 'DuplicatedJourneyIdentifier' ] = $lastJourney;
				$helper->data[ 'Location' ] = substr( $line, 2, 12 );
				$helper->data[ 'DepartureTime' ] = ParseTime( substr( $line, 14, 4 ) );
				$helper->data[ 'UniqueJourneyIdentifier' ] = substr( $line, 18, 6 );
				$helper->data[ 'RunningBoard' ] = substr( $line, 24, 6 );
				$helper->data[ 'VehicleType' ] = substr( $line, 30, 8 );
				$helper->SaveRecord();
        	break;
        }
    }
}

// takes a translink 8 digit date string
//  and creates a mysql formatted string
function ParseDate( $date )
{
	$year = substr( $date, 0, 4 );
    $month = substr( $date, 4, 2 );
    $day = substr( $date, 6, 2 );

    return $year."-".$month."-".$day;
}

// takes a translink 6 or 4 digit time string
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
		$second = substr( $time, 4, 2 );

		return $hour.":".$minute.":".$second;
	}
}


?>
