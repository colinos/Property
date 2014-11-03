<?php

define ( "APPLICATION_PATH", "application" );

include (APPLICATION_PATH . "/inc/config.inc.php");
include (APPLICATION_PATH . "/inc/db.inc.php");

session_start();	// creates a session or resumes the current one

if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
	// if the user doesn't have rights to see this page, redirect to index
	header("Location: index.php");
}

if (!isset($_GET['propid'])) {
	// also redirect user back to index if no property has been specified
	header("Location: index.php");
}

$propertyToDelete = $_GET['propid'];

$dbLink = connectToPropertyDatabase();
$queryString = "DELETE FROM property WHERE property_id=$propertyToDelete";
$query = mysql_query($queryString, $dbLink);
if (!$query) {
	die("Could not query the database: " . mysql_error());
}

mysql_close($dbLink);

$_SESSION['propDeleted'] = 1;	// passed back to index to display success message
header("Location: index.php");

?>
