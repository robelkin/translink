<?

class Stop
{
	public function __construct( $data = "", $params = array() )
	{
		if( !$data )
		{
			throw new Exception( "Invalid Object Requested", 03 );
		}
		if( !$params )
		{
			throw new Exception( "No Params Supplied", 02 );
		}
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
				case "Routes":
					
				break;
				default:
					throw new Exception( "Invalid Object Requested", 03 );
				break;
			}

		}
	}
}
?>