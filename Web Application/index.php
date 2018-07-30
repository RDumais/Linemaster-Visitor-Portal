<!--The landing/home page of the application-->

<?php
/**
 * File Name: index.php
 * Created by PhpStorm
 * User: ryand
 * Date: 7/26/2018
 * Time: 12:15 PM
 * Company: Linemaster Switch Corporation
 */
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Linemaster Vistors</title>
	<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
	<link rel="stylesheet" href="css/fontawesome-all.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/master.css">
	<script src="https://code.jquery.com/jquery-3.3.1.js"
			integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/time.js"></script>
</head>
<body data-gr-c-s-loaded="true">
<header id="landingHeader">
	<div id="time" class="text-center"></div>
</header>
<div class="container-fluid">
	<form>
		<img id="logo" src="img/linemaster.svg">
		<h1 id="welcomeText" class="text-center toUppercase">Thank you for visiting</h1>
	</form>
	<div class="row">
		<div class="col-md-6">
			<button class="btn outlineButton toUppercase" onclick="document.location.href='signOut.php'"><i
						class="fas fa-arrow-alt-circle-left pull-left"></i><span>Sign out</span></button>
		</div>
		<div class="col-md-6">
			<button class="btn fillButton toUppercase" onclick="document.location.href='lookupForm.php'">
				<span>Sign in</span><i
						class="fas fa-arrow-alt-circle-right pull-right"></i>
			</button>
		</div>
	</div>
</div>
</body>
</html>