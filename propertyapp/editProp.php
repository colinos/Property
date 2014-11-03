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

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Property App</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron.css" rel="stylesheet">
    
    <script type="text/javascript">
<!--
function validateForm() {
	var enteredAddress = document.forms["addForm"]["address"].value;
	var enteredPrice = document.forms["addForm"]["price"].value;
	
	if (enteredAddress == null || enteredAddress == "" || enteredPrice == null || enteredPrice == "") {
		alert("All fields must be filled out");
		return false;
	}
	
	var priceRE = /[0-9]+/;

	if (!priceRE.test(enteredPrice)) {
		alert("Please enter the price as a whole number with no punctuation or currency symbol");
		return false;
	}
}
//-->
    </script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Property App</a>
        </div>
	      
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
          </ul>
		
	<!-- current user is logged in -->
	<!-- display logout button -->
          <form class="navbar-form navbar-right" method="POST" action="index.php">
		  <!-- logged in as Admin -->
		<input type="hidden" name="logout">
            <button type="submit" class="btn btn-success">Logout</button>
          </form>

        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-lg-4">
		
          <h2>Edit Property</h2>
		  
<?php

$propertyToEdit = $_GET['propid'];

$dbLink = connectToPropertyDatabase();
$queryString = "SELECT * FROM property WHERE property_id=$propertyToEdit";
$query = mysql_query($queryString, $dbLink);
if (!$query) {
	die("Could not query the database: " . mysql_error());
}

$row = mysql_fetch_array($query);

$address = $row["street"];
$county = $row["county"];
$price = $row["price"];
$type = $row["type"];
$sold = $row["sold"];
$date = $row["date"];
$image = $row["imagename"];

mysql_close($dbLink);

?>

	<table border="0" cellpadding="10">
		<tr>
			<td>
				<img src="<?php echo("prop_images/" . $image . ".jpg") ?>" alt="property image" width="300" height="200" />
			</td>
			<td>
				<h4><?php echo($address) ?><br />
				<?php echo(propCountyToText($county)) ?></h4><br />
				<ul>
					<li><strong>Price:</strong> &euro;<?php echo($price) ?></li>
					<li><strong>Type:</strong> <?php echo(propTypeToText($type)) ?></li>
					<li><strong>Sold:</strong> <?php echo(propSoldToText($sold)) ?></li>
					<li><strong>Updated:</strong> <?php echo($date) ?></li>
				</ul>
			</td>
		</tr>
	</table>
	  
	  <form enctype="multipart/form-data" name="addForm" method="POST" action="editProp2.php" onsubmit="return validateForm();">
		  <input type="hidden" name="propid" value="<?php echo($propertyToEdit) ?>">
		<table border="0" cellpadding="1">
			<tr>
				<td>Address (Street and Suburb/Town):</td>
				<td><input type="text" name="address" value="<?php echo($address) ?>"></td>
			</tr>
			<tr>
				<td>County:</td>
				<td>
					<select name="county">
					  <option value="1">Antrim</option>
					  <option value="2">Armagh</option>
					  <option value="3">Carlow</option>
					  <option value="4">Cavan</option>
					  <option value="5">Clare</option>
					  <option value="6">Cork</option>
					  <option value="7">Derry</option>
					  <option value="8">Donegal</option>
					  <option value="9">Down</option>
					  <option value="10">Dublin</option>
					  <option value="11">Fermanagh</option>
					  <option value="12">Galway</option>
					  <option value="13">Kerry</option>
					  <option value="14">Kildare</option>
					  <option value="15">Kilkenny</option>
					  <option value="16">Laois</option>
					  <option value="17">Leitrim</option>
					  <option value="18">Limerick</option>
					  <option value="19">Longford</option>
					  <option value="20">Louth</option>
					  <option value="21">Mayo</option>
					  <option value="22">Meath</option>
					  <option value="23">Monaghan</option>
					  <option value="24">Offaly</option>
					  <option value="25">Roscommon</option>
					  <option value="26">Sligo</option>
					  <option value="27">Tipperary</option>
					  <option value="28">Tyrone</option>
					  <option value="29">Waterford</option>
					  <option value="30">Westmeath</option>
					  <option value="31">Wexford</option>
					  <option value="32">Wicklow</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Property Type:</td>
				<td>
					<select name="type">
					  <option value="1">Detached</option>
					  <option value="2">Semi-detached</option>
					  <option value="3">Terraced</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Sold:</td>
				<td><input type="checkbox" name="sold" value="Yes"></td>
			</tr>
			<tr>
				<td>Price:</td>
				<td><input type="text" name="price" value="<?php echo($price) ?>"></td>
			</tr>
			<tr>
				<td>Image (JPEG format, max 1MB):</td>
				<td>
					<!-- MAX_FILE_SIZE must precede the file input field -->
					<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
					<!-- Name of input element (propimage) determines name in $_FILES array -->
					<input name="propimage" type="file" />
				</td>
			</tr>
			<tr>
				<td colspan="2"><button type="submit" class="btn btn-success">Update Property</button></td>
			</tr>
		</table>
	  </form>
	  
        </div>
      </div>

      <hr>

      <footer>
        <p>&nbsp;</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
