<!--This page is for confirmation of sign in-->

<?php
/**
 * File Name: signInConfirmation.php
 * Created by PhpStorm
 * User: ryand
 * Date: 7/26/2018
 * Time: 12:15 PM
 * Company: Linemaster Switch Corporation
 */

//Start the session for the visitor
session_start();

//Create new session variables based on the visitor's and host's name
$_SESSION['firstnamevisitor'] = strtok($_SESSION['name'], ' ');
$_SESSION['firstnamehost'] = strtok($_SESSION['host'], ' ');

//Accessing an external file for database connection
require('../php/connect.php');

//Get receptionist's full name and convert to just first name
$receptionistSTMT = "SELECT DISTINCT SUBSTRING(receptionist_name,CHARINDEX(',',receptionist_name)+1,LEN(receptionist_name) - CHARINDEX(',',receptionist_name) )+ ' ' 
+ SUBSTRING(receptionist_name,1,CHARINDEX(',',receptionist_name)-1) AS receptionist_name FROM dbo.LSC14043_Parameters_mst";

$getReceptionistNameEXEC = sqlsrv_query($conn, $receptionistSTMT);
while ($row = sqlsrv_fetch_array($getReceptionistNameEXEC, SQLSRV_FETCH_ASSOC)) {
    $receptionistFName = strtok($row['receptionist_name'], ' ');
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="description" content="Linemaster Visitor Portal">
    <title>Linemaster Vistors</title>
	<link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/fontawesome-all.css">
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <a class="homeReset" href="php/reset.php">
        <div><i class="fal fa-home"></i></div>
    </a>
    <img id="miniLogo" src="img/linemaster.svg" alt="">
</header>
<div class="container-fluid">
    <form>
        <div id="completeRow" class="col-md-12">
            <div>
                <span class="finishedCheck text-center"><i class="far fa-check-circle"></i></span>
                <h1 id="completeMainText" class="text-center bottomPadding">You're all set, <span
                            class="variableHolder"><?php echo $_SESSION['firstnamevisitor']; ?></span>!
                </h1>
                <h2 class="text-center"><span
                            class="variableHolder"><?php echo $receptionistFName ?></span> will print you a visitor's badge shortly and <span
                            class="variableHolder"><?php echo $_SESSION['firstnamehost']; ?></span> will be notified
                    about your arrival. <br> The visitor's badge includes our Guest WiFi password in the bottom-right corner.</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="breeding-rhombus-spinner mx-auto">
                    <div class="rhombus child-1"></div>
                    <div class="rhombus child-2"></div>
                    <div class="rhombus child-3"></div>
                    <div class="rhombus child-4"></div>
                    <div class="rhombus child-5"></div>
                    <div class="rhombus child-6"></div>
                    <div class="rhombus child-7"></div>
                    <div class="rhombus child-8"></div>
                    <div class="rhombus big"></div>
                </div>
                <p id="countdown" class="text-center"></p>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/reset.js"></script>
</body>
</html>