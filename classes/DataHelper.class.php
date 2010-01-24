<?php

class DataHelper
{
    public $data;
    private $table;
    private $primaryKeyField;
    private $fields;

    public function __construct( $table, $primaryKeyField )
    {
        $this->table = $table;
        $this->primaryKeyField = $primaryKeyField;

        $this->fields = array();
        $this->GetFields();

        $this->data = array();
    }

    public function LoadRecord( $id )
    {
    	include_once( "../library/db.inc.php" );

    	$loadSql = "SELECT * FROM $this->table WHERE $this->primaryKeyField = '".mysql_real_escape_string( $id )."'";
    	$loadQuery = mysql_query( $loadSql );
    	$this->data = mysql_fetch_assoc( $loadQuery );
	}
	public static function LoadTableFromSql( $sql )
	{
		include_once( "../library/db.inc.php" );

    	$loadQuery = mysql_query( $sql );
		$rows = '';
		while( $row = mysql_fetch_assoc($loadQuery))
		{
			$rows[] = $row;			
		}
    	return $rows;
	}

	public function SaveRecord()
	{
		include_once( "library/db.inc.php" );

		$existsSql = "SELECT COUNT(*) FROM $this->table WHERE $this->primaryKeyField = '".mysql_real_escape_string( $this->data[ $this->primaryKeyField ] )."'";
		$existsQuery = mysql_query( $existsSql );
		$exists = mysql_result( $existsQuery );

		if( $exists > 0 )
		{
			$setString = "";
			
			foreach( $this->data as $key => $value )
			{
				if( in_array( $key, $this->fields ) )
				{
					$setString = $key." = '".mysql_real_escape_string( $value )."', ";
				}
			}

			$setString = trim( $setString, ', ' );
			$saveSql = "UPDATE $this->table SET $setString WHERE $this->primaryKeyField = '".mysql_real_escape_string( $this->data[ $this->primaryKeyField ] )."'";
		}
		else
		{
			$fieldsString = "";
			$valuesString = "";

			foreach( $this->data as $key => $value )
			{
				if( in_array( $key, $this->fields ) )
				{
					$fieldsString .= $key.", ";
					$valuesString .= "'".mysql_real_escape_string( $value )."', ";
				}
			}

			$fieldsString = trim( $fieldsString, ', ' );
			$valuesString = trim( $valuesString, ', ' );
			$saveSql = "INSERT INTO $this->table ( $fieldsString ) VALUES ( $valuesString )";
		}

		$saveQuery = mysql_query( $saveSql );
		$insertedId = mysql_insert_id();

		if( $saveQuery )
		{
			if( $insertedId > 0 )
			{
				$this->data[ $this->primaryKeyField ] = $insertedId;
			}
		}
		else
		{
			throw new Exception( "Couldn't save data" );
		}
	}

	private function GetFields()
	{
		include_once( "../library/db.inc.php" );

		$fieldSql = "SHOW COLUMNS FROM $this->table";
		$fieldQuery = mysql_query( $fieldSql );

		while( $field = mysql_fetch_object( $fieldQuery ) )
		{
			$this->fields[] = $field->Field;
		}
	}
}

?>
