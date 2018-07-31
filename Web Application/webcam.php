<!--This page is for capturing photo identification of the visitor via camera on device-->

<?php
/**
 * File Name: webcam.php
 * Created by PhpStorm
 * User: ryand
 * Date: 7/26/2018
 * Time: 12:15 PM
 * Company: Linemaster Switch Corporation
 */

//Start the session for the visitor
session_start();

//Set session variables, trim potential whitespace of variables
$_SESSION['phone'] = trim($_SESSION['phone']);
$_SESSION['host'] = trim($_POST['host']);
$_SESSION['reason'] = trim($_POST['reason']);

//Determine which variables to set based on if the visitor is a previous visitor
if ($_SESSION['isPreviousVisitor'] == FALSE){
    $_SESSION['name'] = trim($_POST['name']);
    $_SESSION['company'] = trim($_POST['company']);
    $_SESSION['email'] = trim($_POST['email']);
}

//Create new session variables based on the visitor's name
$_SESSION['firstnamevisitor'] = strtok($_SESSION['name'], ' ');

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
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/fontawesome-all.css">
</head>
<body>
<header>
    <a class="homeReset" href="php/reset.php">
        <div><i class="fal fa-home"></i></div>
    </a>
    <img id="miniLogo" src="/img/linemaster.svg" alt="">
</header>
<div class="container-fluid">
    <form id="webcamForm" method="post" action="php/submitVisitor.php" enctype="multipart/form-data">
        <div class="row fullHeightRow">
            <div class="col-md-12">
                <h2 class="text-center questionText">We need a photo of you,
                    <span class="variableHolder"><?php echo $_SESSION['firstnamevisitor']; ?></span>. </h2>
            </div>
            <div class="offset-md-2 col-md-8 mx-auto" id="cameraView"></div>
            <div class="offset-md-2 col-md-8 mx-auto" id="cameraResult"></div>
            <div class="offset-md-4 col-md-4">
                <a id="snapshotButton" href="javascript:void(take_snapshot())" type="button">
                    <button class="btn fillButton" type="button"><i class="far fa-camera"></i></button>
                </a>
                <button id="clearButton" class="btn outlineButton" type="button"><i class="far fa-undo"></i></button>
            </div>
            <input id="photoData" type="hidden" name="photoData" value=""/>
        </div>
        <div class="row submitRow">
            <div class="offset-md-3 col-md-6">
                <button id="finishButton" class="btn fillButton toUppercase" disabled><span>Finish</span></button>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/webcam/webcam.js"></script>
<script src="js/webcamHandler.js"></script>
</body>
</html>