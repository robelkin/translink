<?php

class Stop extends Base
{
	public function __construct( $data = "", $params = array() )
	{
		parent::__construct( $data, $params );
		$this->data = $data;
		$this->params = $params;
	}
    
	public function Get()
	{
		if( !empty( $this->data ) )
        {
            if( $this->method_or_data( $this->data ) == "method" )
            {
                list( $method, $stuffToPass ) = explode( '/', $this->data, 2 );
                $this->$method( $stuffToPass );
            }
            else
            {
                /*if( is_numeric( $this->data ) )
                {
                    $results = new DataHelper( "tblLocation", "Location" );
                    $results->LoadRecord( $this->data );
                    $resultJson = json_encode( $results->data );
                    $this->send_json_output( $resultJson );
                    exit;
                }
                else
                { */   
                    throw new Exception( "Invalid Object Requested", 03 );
                //}
            }
        }
        
        throw new Exception( "Invalid Object Requested", 03 );
    }
    
    private function IndividualStop( $data )
    {
        if( $data == "" )
        {
            throw new Exception( "Invalid Params passed", 02 );
        }
        $results = new DataHelper( "tblLocation", "Location" );
        $results->LoadRecord( $data );
        $resultJson = json_encode( $results->data );
        $this->send_json_output( $resultJson );
        exit;
    }
    
    private function RoutesForStop( $data )
    {
        if( $data == "" )
        {
            throw new Exception( "Invalid Params passed", 02 );
        }
        
        $sql = sprintf( "SELECT UniqueJourneyIdentifier FROM tblJourneyIntermediate WHERE Location = '%s' GROUP BY UniqueJourneyIdentifier", mysql_real_escape_string( $data ) );
        $results = DataHelper::LoadTableFromSql( $sql );
        $resultJson = json_encode( $results );
        $this->send_json_output( $resultJson );
        exit;
    }

	/*private function RouteByNumber( $data )
	{
		if( $data == "" )
		{
			throw new Exception( "Invalid Params passed", 02 );
		}

		$sql = sprintf("SELECT RouteNumber, DepartureTime, tblJourney.UniqueJourneyIdentifier, Location AS OriginStop FROM tblJourney INNER JOIN tblJourneyOrigin ON (tblJourney.UniqueJourneyIdentifier = tblJourneyOrigin.UniqueJourneyIdentifier) WHERE RouteNumber='%s'", $data );

		$results = DataHelper::LoadTableFromSql( $sql );
		$resultJson = json_encode( $results );

		$this->send_json_output($resultJson);
		exit;
	}*/
    
    private function NearestStop( $data )
    {
        list( $lat, $long, $dist ) = explode( ',', $data, 3 );

        if( $lat == "" || $long == "" || $dist == "" )
        {
            throw new Exception( "Invalid Params passed", 02 );
        }
        
list( $lat, $long, $dist ) = explode( ',', $data, 3 );

        if( $lat == "" || $long == "" || $dist == "" )
        {
            throw new Exception( "Invalid Params passed", 02 );
        }
        
        $sql = sprintf( 
        "SELECT 
            Location, 
            Name, 
			(3963.191 * 
                ACOS( 
                    ( 
                        SIN( 
                            PI( ) * '%1\$F' /180 
                        ) * 
                        SIN( 
                            PI( ) * LocationLat /180 
                        ) 
                    ) + 
                    ( 
                        COS( 
                            PI( ) * '%1\$F' /180 
                        ) * 
                        COS( 
                            PI( ) * LocationLat /180 
                        ) * 
                        COS( 
                            PI( ) * LocationLong /180 - PI( ) * '%2\$F' /180 
                        ) 
                    )
                ) )
                AS distance 
                FROM tblLocation 
                WHERE 
                (3963.191 * 
                ACOS( 
                    ( 
                        SIN( 
                            PI( ) * '%1\$F' /180 
                        ) * 
                        SIN( 
                            PI( ) * LocationLat /180 
                        ) 
                    ) + 
                    ( 
                        COS( 
                            PI( ) * '%1\$F' /180 
                        ) * 
                        COS( 
                            PI( ) * LocationLat /180 
                        ) * 
                        COS( 
                            PI( ) * LocationLong /180 - PI( ) * '%2\$F' /180 
                        ) 
                    )
                )) < %3\$d ORDER BY distance LIMIT 0 , 30", mysql_real_escape_string( $lat ), mysql_real_escape_string( $long ), mysql_real_escape_string( $dist ) );
        $results = DataHelper::LoadTableFromSql( $sql );
        $resultJson = json_encode( $results );
        $this->send_json_output( $resultJson );
        exit;
    }
}
?>
