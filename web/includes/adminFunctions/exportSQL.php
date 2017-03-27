<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

//TODO Test this
if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	exportAllCSV($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Data Export Failed, invalid permissions';
   	header('Location: ../../pages/exportAllSQL');
}

function exportAllCSV($mysqli)
{
	$USER = USER;
	$PASSWORD = PASSWORD;
	$DATABASE = DATABASE;

	$filename = "backup-" . date("Y-m-d") . ".sql.gz";
	$mime = "application/x-gzip";

	header( "Content-Type: " . $mime );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

	$cmd = "mysqldump -u $USER --password=$PASSWORD $DATABASE | gzip --best";   

	passthru( $cmd );

	exit(0);
}

?>
