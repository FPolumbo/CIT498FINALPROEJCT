<?php
    require_once 'debug.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Find Your Frequency</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!--justs added -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" id="bootstrap-css" 
integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/css/styles.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<style>
body {font-family: "Lato", sans-serif}

</style>
</head>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-black w3-card">
    <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="index.php" class="w3-bar-item w3-button w3-padding-large">HOME</a>
	 <a href="terms.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">TERMS</a>
	
		<?php 
		if(isset($_SESSION['loginUsername'])){
			echo '<a href="profile.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">PROFILE</a>
			<a href="artists.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">ARTISTS</a>';
		}else{
		    ?>
            <div class="w3-dropdown-hover w3-hide-small">
                <button class="w3-padding-large w3-button" title="More">REGISTRATION<i class="fa fa-caret-down"></i></button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="index.php#userRegistrationSection" class="w3-bar-item w3-button">Listener Registration</a>
                </div>
            </div>
            <?php
        }
		?>

    <!--Login modal button-->
	<?php 
		if(isset($_SESSION['loginUsername'])){
			echo '<a href="includes/logout.php">
			<button type="button" class="btn btn-primary mx-2">Logout</button>
			</a>';
		}else{
			echo '<button type="button" class="btn btn-primary w3-right" data-toggle="modal" data-target="#loginModal">Login</button>';
		}
		?>
  </div>
</div>

