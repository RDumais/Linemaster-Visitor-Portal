<!--This page is for signing the visitor out. It will look up the visitor's entered phone number and complete his visit.-->

<?php
/**
 * File Name: signOut.php
 * Created by PhpStorm
 * User: ryand
 * Date: 7/26/2018
 * Time: 12:15 PM
 * Company: Linemaster Switch Corporation
 */

//Start the session for the visitor
session_name("VisitorPortal");
session_start();

//Set session variable based on visitor's status
$_SESSION['isSigningOut'] = TRUE;

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
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/fontawesome-all.css">
</head>
<body>
<header>
    <a class="homeReset" href="php/reset.php">
        <div><i class="fal fa-home"></i></div>
    </a>
    <img id="miniLogo" src="img/linemaster.svg" alt="">
</header>
<div class="container-fluid">
    <form id="signOutForm" method="post" action="php/submitVisitor.php">
        <div class="row fullHeightRow">
            <div class="col-md-12">
                <h2 class="text-center questionText">We hope you enjoyed your time at Linemaster - let's look you up.</h2>
            </div>
            <div class="offset-md-2 col-md-9 bottomPadding">
                <div id="cellPhoneGroup" class="form-group">
                    <label for="phone"><span>Cellphone</span></label>
                    <input type="tel" id="phone" name="phone" maxlength="11"
                           pattern="^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$"
                           data-pattern-error="Please follow the format including area code: 8609741000" required>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div id="visitorConfirmation" class="offset-md-2 col-md-8 text-center"></div>
        </div>
        <div class="row submitRow">
            <div class="offset-md-3 col-md-6">
                <button id="submitSignOutBtn" class="btn fillButton toUppercase" data-disable="true" disabled><span>Finish</span>
                </button>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="js/validator.js"></script>
<script src="js/bootstrap.js"></script>
<script>
    //Listen for keyups to tell if the visitor is finished typing
    var typingTimer; //timer identifier
    var doneTypingInterval = 1000; //time in ms (5 seconds)

    //Start validator on #signOutForm field
    $('#signOutForm').validator();

    $('#phone').keyup(function () {
        var inputValidity = document.getElementById("phone");
        clearTimeout(typingTimer);
        if ($('#phone').val()) {
            if (inputValidity.checkValidity()) {
                typingTimer = setTimeout(findPreviousVisitor, doneTypingInterval);
            }
        }
    });

    //Create an AJAX call to find the visitor by phone number
    function findPreviousVisitor() {
        var phoneData = $('#phone').val();
        $.ajax({
            type: "POST",
            url: "php/findVisitor.php",
            data: {
                'phoneData': phoneData
            },
            success: function (data) {
                $("#visitorConfirmation").html(data);
                if ($("h1").length > 0) {
                    $("#submitSignOutBtn").prop("disabled", false);
                    $('#submitSignOutBtn').show();
                }
                else {
                    $("#submitPreviousVisitorBtn").prop("disabled", true);
                    $('#submitSignOutBtn').hide();
                }
            }
        });
    }

    $('#submitSignOutBtn').hide();
</script>
</body>
</html>