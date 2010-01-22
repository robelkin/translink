<?

include_once("../library/db.inc.php");
include_once("../classes/DataHelper.class.php");

try
{
	$request = $_SERVER["SCRIPT_NAME"];

	list( $blank, $class, $data ) = explode( '/', $request );

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