<?php

function connectToPropertyDatabase() {
	$dbLink = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$dbLink){
		echo(mysql_error());
		return 0;
	}

	$selected = mysql_select_db(DB_DATABASE, $dbLink);
	if(!$selected){
		echo(mysql_error());
		return 0;
	}
	
	return $dbLink;
}

function prepareDataForDBEntry($string) {
	// escape backslashes in user input
	$string = str_replace("\\", "\\\\", $string);
	
	// convert & " ' < > to HTML entity references
	$string = htmlspecialchars($string, ENT_QUOTES);
	
	return $string;
}

function validateUser($username, $password){
	// Currently this function queries the database for the password of the user in question, and compares the correct password to the entered password.
	// It might be better to query the database for the username/password pair, and see how many rows are returned; 1 would mean success.
	
	$dbLink = connectToPropertyDatabase();
	
	$queryString = "SELECT password FROM adminuser WHERE username = '$username'";
	$query = mysql_query($queryString, $dbLink);
	if (!$query) {
		die("Could not query the database");
	}

	$row = mysql_fetch_array($query);

	mysql_close($dbLink);

	if (!$row) {
		// username does not exist in adminuser Table
		return false;
	} else {
		// username exists, now check if entered password matches correct password
		if ($password == $row["password"]) {
			return true;
		} else {
			return false;
		}
	}
}

function propCountyToText($county) {
	switch ($county) {
		case 1:
			return "Antrim";
			break;
		case 2:
			return "Armagh";
			break;
		case 3:
			return "Carlow";
			break;
		case 4:
			return "Cavan";
			break;
		case 5:
			return "Clare";
			break;
		case 6:
			return "Cork";
			break;
		case 7:
			return "Derry";
			break;
		case 8:
			return "Donegal";
			break;
		case 9:
			return "Down";
			break;
		case 10:
			return "Dublin";
			break;
		case 11:
			return "Fermanagh";
			break;
		case 12:
			return "Galway";
			break;
		case 13:
			return "Kerry";
			break;
		case 14:
			return "Kildare";
			break;
		case 15:
			return "Kilkenny";
			break;
		case 16:
			return "Laois";
			break;
		case 17:
			return "Leitrim";
			break;
		case 18:
			return "Limerick";
			break;
		case 19:
			return "Longford";
			break;
		case 20:
			return "Louth";
			break;
		case 21:
			return "Mayo";
			break;
		case 22:
			return "Meath";
			break;
		case 23:
			return "Monaghan";
			break;
		case 24:
			return "Offaly";
			break;
		case 25:
			return "Roscommon";
			break;
		case 26:
			return "Sligo";
			break;
		case 27:
			return "Tipperary";
			break;
		case 28:
			return "Tyrone";
			break;
		case 29:
			return "Waterford";
			break;
		case 30:
			return "Westmeath";
			break;
		case 31:
			return "Wexford";
			break;
		case 32:
			return "Wicklow";
			break;
		default:
			return "Undefined";
	}
}

function propTypeToText($type) {
	switch ($type) {
		case 1:
			return "Detached";
			break;
		case 2:
			return "Semi-detached";
			break;
		case 3:
			return "Terraced";
			break;
		default:
			return "Undefined";
	}
}

function propSoldToText($sold) {
	if ($sold == 0) {
		return "N";
	} else {
		return "Y";
	}
}

?>
