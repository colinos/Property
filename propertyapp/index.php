<?php

define ( "APPLICATION_PATH", "application" );

include (APPLICATION_PATH . "/inc/config.inc.php");
include (APPLICATION_PATH . "/inc/db.inc.php");

session_start();	// creates a session or resumes the current one

$loginFailed = false;	// boolean used to toggle display of a "failed login" message

if (isset($_POST['username']) || isset($_POST['password'])) {
	// this page presents the login form as well as processes login attempts
	
	if (validateUser($_POST['username'], $_POST['password'])) {
		// username/password match
		$_SESSION['loggedIn'] = true;
	} else {
		// username/password don't match
		// set $loginFailed to true so a "failed login" message will be displayed
		$loginFailed = true;
	}
} elseif (isset($_POST['logout'])) {
	$_SESSION['loggedIn'] = false;
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
	var enteredImage = document.forms["addForm"]["image"].value;
	
	if (enteredAddress == null || enteredAddress == "" || enteredPrice == null || enteredPrice == "" || enteredImage == null || enteredImage == "") {
		alert("All fields must be filled out");
		return false;
	}
	
	var priceRE = /[0-9]+/;

	if (!priceRE.test(enteredPrice)) {
		alert("Please enter the price as a whole number with no punctuation or currency symbol");
		return false;
	}
	
	var imageNameRE = /\.jpe?g$/;

	if (!imageNameRE.test(enteredImage)) {
		alert("Image must be in JPEG format");
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
		
<?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) { ?>

	<!-- current user is logged in -->
	<!-- display logout button -->
          <form class="navbar-form navbar-right" method="POST" action="index.php">
		  <!-- logged in as Admin -->
		<input type="hidden" name="logout">
            <button type="submit" class="btn btn-success">Logout</button>
          </form>

<?php } else { ?>

	<!-- current user is not logged in -->
	<!-- display login form -->
          <form class="navbar-form navbar-right" method="POST" action="index.php">
            <div class="form-group">
              <input type="text" placeholder="Username" class="form-control" name="username">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-success">Login</button>
          </form>

<?php } ?>	<!-- endif user is not logged in -->

        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <?php if ($loginFailed) { ?>
    <div class="jumbotron">
      <div class="container">
        <p style="color:red;">Incorrect username/password pair. Please try again.</p>
      </div>
    </div>
    <?php } ?>

    <?php if (isset($_SESSION['propAdded']) && $_SESSION['propAdded'] == 1) { ?>
    <div class="jumbotron">
      <div class="container">
        <p>Property successfully added.</p>
      </div>
    </div>
    <?php
	$_SESSION['propAdded'] = 0;	// reset propAdded session variable
    }
    ?>

    <?php if (isset($_SESSION['propDeleted']) && $_SESSION['propDeleted'] == 1) { ?>
    <div class="jumbotron">
      <div class="container">
        <p>Property successfully deleted.</p>
      </div>
    </div>
    <?php
	$_SESSION['propDeleted'] = 0;	// reset propDeleted session variable
    }
    ?>

    <?php if (isset($_SESSION['propUpdated']) && $_SESSION['propUpdated'] == 1) { ?>
    <div class="jumbotron">
      <div class="container">
        <p>Property successfully updated.</p>
      </div>
    </div>
    <?php
	$_SESSION['propUpdated'] = 0;	// reset propUpdated session variable
    }
    ?>
	  
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-lg-4">
		
          <h2>Properties for Sale</h2>
	  
	  <table border="0" cellpadding="10">
		  
<?php

$dbLink = connectToPropertyDatabase();
$queryString = "SELECT * FROM property";
$query = mysql_query($queryString, $dbLink);
if (!$query) {
	die("Could not query the database");
}

while ($row = mysql_fetch_array($query)) {
	echo "<tr>";
	echo " <td>";
	echo "  <img src='prop_images/" . $row["imagename"] . ".jpg' alt='property image' width='300' height='200' />";
	echo " </td>";
	echo " <td>";
	echo "  <h4>" . $row["street"] . "<br />";	// street
	echo propCountyToText($row["county"]) . "</h4><br />";	// county
	echo "  <ul>";
	echo "   <li><strong>Price:</strong> &euro;" . $row["price"] . "</li>";
	echo "   <li><strong>Type:</strong> " . propTypeToText($row["type"]) . "</li>";
	echo "   <li><strong>Sold:</strong> " . propSoldToText($row["sold"]) . "</li>";
	if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
		// record update time is only shown to admin
		echo "   <li><strong>Updated:</strong> " . $row["date"] . "</li>";
	}
	echo "  </ul>";
	echo " </td>";
	if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
		// if admin is logged in, display admin functions
		echo "<td>";
		echo "<a href='deleteProp.php?propid=" . $row["property_id"] . "'>Delete this property</a><br />";
		echo "<a href='editProp.php?propid=" . $row["property_id"] . "'>Edit this property</a>";
		echo "</td>";
	}
	echo "</tr>";
}

mysql_close($dbLink);

?>
		  
	  </table>
	  
<?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) { ?>
	  
	  <h3>Add a Property</h3>
	  
	  <form enctype="multipart/form-data" name="addForm" method="POST" action="addProp.php" onsubmit="return validateForm();">
		<table border="0" cellpadding="1">
			<tr>
				<td>Address (Street and Suburb/Town):</td>
				<td><input type="text" name="address"></td>
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
				<td>Price:</td>
				<td><input type="text" name="price"></td>
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
				<td colspan="2"><button type="submit" class="btn btn-success">Add Property</button></td>
			</tr>
		</table>
	  </form>
	  
<?php } ?>
	  
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
