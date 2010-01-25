<?

include_once("library/db.inc.php");

print "Current host selected in settings.ini:".$settings[ 'dbHost' ]."<br />";
print "Current database selected in settings.ini".$settings[ 'dbDatabase' ]."<br />";

?>

You can choose from one of the following options: <br /><br />

<a href="metro/import/read_cif.php">Import Metro Data</a>

<br />
<br />

<a href="nirailways/import/read_cif.php">Import Railways Data</a>