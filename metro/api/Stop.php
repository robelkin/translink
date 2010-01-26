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
			switch( $this->data )
			{
				case "IndividualStop":
					// deprecated - see default case below
					// want to keep url as tidy as possible
					// will be removed at some stage
					// @robertfalconer
					if( !$this->params['stopref'] )
					{
						throw new Exception( "Invalid Params passed", 02 );
					}
					$data = new DataHelper( "tblStop", "StopReference" );
					$data->LoadRecord($this->params['stopref']);
					print json_encode($data->data);
					exit;
				break;
				case "RoutesForStop":
					if( !$this->params['stopref'] )
					{
						throw new Exception( "Invalid Params passed", 02 );
					}
					$sql = sprintf( "SELECT UniqueJourneyIdentifier FROM tblJourneyIntermediate WHERE Location = '%s' GROUP BY UniqueJourneyIdentifier", $this->params['stopref'] );
					$results = DataHelper::LoadTableFromSql( $sql );
					print json_encode( $results );
					exit;
				break;
				case "NearestStop":
					if( !$this->params['lat'] || !$this->params['long'] || !$this->params['distance'] )
					{
						throw new Exception( "Invalid Params pass", 02 );
					}
					$sql = sprintf( "SELECT StopID, StopName, 3963.191 * ACOS( (
SIN( PI( ) * '%1$d' /180 ) * SIN( PI( ) * StopLat /180 ) ) + ( COS( PI( ) * '%1$d' /180 ) * COS( PI( ) * StopLat /180 ) * COS( PI( ) * StopLong /180 - PI( ) * - '%2$d' /180 ) ) ) AS distance FROM tblStop WHERE distance < '%3$d' ORDER BY distance LIMIT 0 , 30", $this->params['lat'], $this->params['long'], $this->params['distance'] );
					$results = DataHelper::LoadTableFromSql( $sql );
					print json_encode( $results);
					exit;
				default:
					if( is_numeric( $this->data ) )
					{
						$data = new DataHelper( "tblStop", "StopReference" );
						$data->LoadRecord( $this->data );
						print json_encode( $data->data );
						exit;
					}
					else
					{	
						throw new Exception( "Invalid Object Requested", 03 );
					}
				break;
			}

		}
	}
}
?>
