<?php

class Base
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
	}
}
?>