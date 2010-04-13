<?

error_reporting( 0 );

include_once("../../library/db.inc.php");
include_once("../../classes/DataHelper.class.php");
include_once("Base.php");

try
{
	$_root = substr($_SERVER["SCRIPT_NAME"], 0, strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
	$_query = str_ireplace($_root, "", $_SERVER["REQUEST_URI"]);

	$requestParts = explode( '?', $_query );

	list( $class, $data ) = explode( '/', $requestParts[0], 2 );

	// deal with GET variables
	// will always need done - due to how the rewrite is working
	$_REQUEST = array();
	$gets = explode( '&', $requestParts[1] );

	foreach( $gets as $get )
	{
		$varParts = explode( '=', $get );
		$_REQUEST[ $varParts[0] ] = $varParts[1];
	}

	// deal with POST variables
	if( empty( $_POST ) )
	{
		// get the raw data
		$rawdata = fopen( "php://input", "r" );
		$raw = "";

		while( $line = fgets( $rawdata ) )
		{
		  $raw .= $line;
		}

		if( $raw != "" )
		{
			// parse into $_REQUEST
			$vars = explode( '&', $raw );

			foreach( $vars as $var )
			{
				$params = explode( '=', $var, 2 );
				$_REQUEST[ $params[0] ] = $params[1];
			}
		}
	}

	if( file_exists($class.".php") )
	{
		include_once( $class.".php" );
		$object = new $class($data, $_REQUEST);
		$requestMethod = $_SERVER["REQUEST_METHOD"];
		switch( $requestMethod )
		{
			// More handlers can be added in the future to make this puppy fully featured, e.g. POST to insert etc
			case "GET":
				$output = $object->Get();
				break;
		}
		print $output;
	}
	else
	{
		throw new Exception("Invalid Request Type", 01);
	}
}
catch(Exception $ex)
{
	//	Code		Message

	//	99			General Exception
	//	01			Invalid Request Type
	//	02			No Params Supplied
	//	03			Invalid object requested
	//	04			Invalid Params passed...

	header("Status: 406 Not Acceptable");
	header("Content-Type: text/plain");

	if( $ex->getCode() == 0 )
	{
		$returnCode = 99;
	}
	else
	{
		$returnCode = $ex->getCode();
	}

	$errorText = '{"Result":"'.(int)$returnCode.'","Message":"'.$ex->getMessage().'"}';
	echo $errorText;
	exit();



}

?>
