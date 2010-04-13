<?php

// db.inc.php
// connect to database

global $settings;
$settings = parse_ini_file( "settings.ini" );

mysql_connect( $settings[ 'dbHost' ], $settings[ 'dbUsername' ], $settings[ 'dbPassword' ] );
//temporary hack til something better can be found
if ( stristr ( $_SERVER['SCRIPT_NAME'], 'metro' ) )
{
	mysql_select_db( $settings[ 'dbMetro' ] );
}
elseif ( stristr ( $_SERVER['SCRIPT_NAME'], 'nirailways' ) )
{
	mysql_select_db( $settings[ 'dbNIRailways' ] );
}

?>
