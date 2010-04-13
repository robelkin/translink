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
    
    protected function method_or_data( $data )
    {
        if( stripos( $data, '/' ) !== false )
        {
            $parts = explode( '/', $data );  
            $candidate = $parts[0];          
        }
        else
        {
            $candidate = $data; 
        }

        if( method_exists( $this, $candidate ) )
        {
            return "method";
        }
        else
        {
            return "data";
        }
    }
	
	protected function send_json_output($json)
	{
		if( isset( $this->params[ 'callback' ] ) ) 
        {
			$callback = $this->params[ 'callback' ];
			echo $callback . '(' . $json . ')';
		}
		else 
        {
			echo $json;
		}
	}
}
?>