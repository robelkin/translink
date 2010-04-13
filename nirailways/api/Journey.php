<?php

class Journey extends Base
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
                throw new Exception( "Invalid Object Requested", 03 );
            }
        }
        
        throw new Exception( "Invalid Object Requested", 03 );
    }
    
    private function AllRoutes( $data )
    {
        $sql = "SELECT tblJourney.UniqueJourneyIdentifier, Location AS OriginStop FROM tblJourney INNER JOIN tblJourneyOrigin ON (tblJourney.UniqueJourneyIdentifier = tblJourneyOrigin.UniqueJourneyIdentifier)";
        $results = DataHelper::LoadTableFromSql( $sql );
        $resultJson = json_encode( $results );
        $this->send_json_output($resultJson);
        exit;
    }
    
    private function StopsOnRoute( $data )
    {
        if( $data == "" )
        {
            throw new Exception( "Invalid Params passed", 02 );
        }
        
        $sql = sprintf( "SELECT * FROM tblJourneyIntermediate WHERE UniqueJourneyIdentifier = '%s'", mysql_real_escape_string( $data ) );
        $results = DataHelper::LoadTableFromSql( $sql );
        $resultJson = json_encode( $results );
        $this->send_json_output( $resultJson );
        exit;
    }
    
    private function RouteOrigin( $data )
    {
        if( $data == "" )
        {
            throw new Exception( "Invalid Params passed", 02 );
        }
        
        $sql = sprintf( "SELECT * FROM tblJourneyOrigin INNER JOIN tblLocation ON ( tblJourneyOrigin.Location = tblLocation.Location ) WHERE UniqueJourneyIdentifier = '%s'", mysql_real_escape_string( $data ) );
        $results = DataHelper::LoadTableFromSql( $sql );
        $resultJson = json_encode( $results );
        $this->send_json_output( $resultJson );
        exit;
    }
    
    private function RouteDestination( $data )
    {
        if( $data == "" )
        {
            throw new Exception( "Invalid Params passed", 02 );
        }
        
        $sql = sprintf( "SELECT * FROM tblJourneyDestination INNER JOIN tblLocation ON ( tblJourneyDestination.Location = tblLocation.Location ) WHERE UniqueJourneyIdentifier = '%s'", mysql_real_escape_string( $data ) );
        $results = DataHelper::LoadTableFromSql( $sql );
        $resultJson = json_encode( $results );
        $this->send_json_output( $resultJson );
        exit;
    }
}

?>
