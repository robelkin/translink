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
				case "StopOnRoute":
					if( !$this->params['stopref'] || !$this->params['route'] )
					{
						throw new Exception( "Invalid Params passed", 02 );
					}
					$sql = sprintf( "SELECT * FROM tblJourneyIntermediate INNER JOIN tblStop ON (tblJourneyIntermediate.Location = tblStop.StopReference) WHERE Location = '%s' AND UniqueJourneyIdentifier = '%s'", $this->params['stopref'], $this->params['route'] );
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