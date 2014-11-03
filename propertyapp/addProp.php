<?php

define ( "APPLICATION_PATH", "application" );

include (APPLICATION_PATH . "/inc/config.inc.php");
include (APPLICATION_PATH . "/inc/db.inc.php");

session_start();	// creates a session or resumes the current one

if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
	// if the user doesn't have rights to see this page, redirect to index
	header("Location: index.php");
}

if (!isset($_POST['address']) || !isset($_POST['county']) || !isset($_POST['type']) || !isset($_POST['price']) || !isset($_POST['propimage'])) {
	// also redirect to index if no property details have been specified
	header("Location: index.php");
}

$address = prepareDataForDBEntry($_POST['address']);
$county = $_POST['county'];
$type = $_POST['type'];
$price = $_POST['price'];
$uniqueImageName = uniqid();	// bit of a hack, but it will do for now

$imagepath = "prop_images/$uniqueImageName.jpg";

if (move_uploaded_file($_FILES['propimage']['tmp_name'], $imagepath)) {
	chmod($imagepath, 0644);
} else {
	die("Error uploading property image");
}

$dbLink = connectToPropertyDatabase();
$queryString = "INSERT INTO property SET street = '$address', county = '$county', price = '$price', type = '$type', sold = 0, imagename = '$uniqueImageName'";
$query = mysql_query($queryString, $dbLink);
if (!$query) {
	die("Could not query the database: " . mysql_error());
}

mysql_close($dbLink);

$_SESSION['propAdded'] = 1;	// passed back to index to display success message
header("Location: index.php");

?>
