<?php

// db.inc.php
// connect to database

global $settings;
$settings = parse_ini_file( "settings.ini" );

mysql_connect( $settings[ 'dbHost' ], $settings[ 'dbUsername' ], $settings[ 'dbPassword' ] );
mysql_select_db( $settings[ 'dbDatabase' ] );

?>
