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
			switch( $this->data )
			{
				case "AllRoutes":
					$sql = "SELECT RouteNumber, RunningBoard, UniqueJourneyIdentifier FROM tblJourney";
					$results = DataHelper::LoadTableFromSql( $sql );
					print json_encode( $results );
					exit;
				break;
				default:
					throw new Exception( "Invalid Object Requested", 03 );
				break;
			}

		}
	}
}

?>