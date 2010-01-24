<?php

class Journey extends Base
{
	public function __construct( $data = "", $params = array() )
	{
		parent::__construct();
		$this->data = $data;
		$this->params = $params;
	}
	public function Get()
	{
		if( !empty( $this->data ) )
		{
			switch( $this->data )
			{
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