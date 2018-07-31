<!--This page is for visitors that are not new to Linemaster.-->

<?php
/**
 * File Name: lookupForm.php
 * Created by PhpStorm
 * User: ryand
 * Date: 7/26/2018
 * Time: 12:15 PM
 * Company: Linemaster Switch Corporation
 */

//If any errors arise display them
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Start the session for the visitor
session_start();

//Set session variables based on visitor's status
$_SESSION['isPreviousVisitor'] = TRUE;
$_SESSION['isSigningOut'] = FALSE;

//Accessing an external file for database connection
require('../php/connect.php');

//If unable to connect to database
if ($conn === false) {
    echo "Could not connect.\n";
    die(print_r(sqlsrv_errors(), true));
}

//Reformat the name from the original database table, gather results
$getHostsSTMT = "SELECT DISTINCT SUBSTRING(name,CHARINDEX(',',name)+1,LEN(name) - CHARINDEX(',',name) )+ ' ' + SUBSTRING(name,1,CHARINDEX(',',name)-1) AS name FROM dbo.employee_mst WHERE term_date IS NULL";
$getHostsEXEC = sqlsrv_query($conn, $getHostsSTMT);

//Gather static table of reasons of a visitor visiting
$getReasonsSTMT = "SELECT reason FROM dbo.LSC14043_VisitorReasons_mst";
$getReasonsEXEC = sqlsrv_query($conn, $getReasonsSTMT);

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
    <form method="post" action="webcam.php">
        <div class="row fullHeightRow">
            <div class="col-md-12">
                <h2 class="text-center questionText">Welcome to Linemaster - let's sign you in.</h2>
            </div>
            <div class="offset-md-2 col-md-9">
                <div id="cellPhoneGroup" class="form-group">
                    <label for="phone">
                        <span>Cellphone</span></label>
                    <input type="tel" id="phone" name="phone" minlength="10" maxlength="11"
                           pattern="^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$"
                           data-pattern-error="Please follow the format including area code: 8609741000" required>
                    <div class="help-block with-errors"></div>

                </div>
                <div id="hostGroup" class="form-group">
                    <label for="hostSelected">
                        <span>Host</span>
                    </label>
                    <select id="hostSelected" class="chosen" name="host" required>
                        <option></option>
                        <?php

                        //Populate drop-down with names of hosts at Linemaster

                        while ($row = sqlsrv_fetch_array($getHostsEXEC, SQLSRV_FETCH_ASSOC)) {
                            echo "<option>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div id="reasonGroup" class="form-group">
                    <label for="reasonSelected">
                        <span>REASON</span>
                    </label>
                    <select id="reasonSelected" class="chosen" name="reason" required>
                        <option></option>
                        <?php

                        //Populate drop-down with reasons to visit Linemaster

                        while ($row = sqlsrv_fetch_array($getReasonsEXEC, SQLSRV_FETCH_ASSOC)) {
                            echo "<option>" . $row['reason'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div id="visitorConfirmation" class="offset-md-2 col-md-8 text-center">
            </div>
        </div>
        <div class="row submitRow">
            <div class="col-md-4">
                <button class="btn outlineButton" onclick="document.location.href='index.php'" type="button"><i
                            class="far fa-arrow-alt-circle-left pull-left"></i><span>BACK</span>
                </button>
            </div>
            <div class="offset-md-4 col-md-4">
                <button id="submitPreviousVisitorBtn" class="btn fillButton" data-disable="true" disabled>
                    <span>NEXT</span><i class="far fa-arrow-alt-circle-right pull-right"></i></button>
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

    //The following few blocks of code allow the visitor to move on from their input box as the complete each one
    //This allows the interface to be guided


    //Listen for keyups to tell if the visitor is finished typing
    var typingTimer;
    var doneTypingInterval = 1000;

    //After the visitor types in their phone number a call will be made to see if they have previously visited
    $('#phone').keyup(function () {
        var inputValidity = document.getElementById("phone");
        clearTimeout(typingTimer);
        if ($('#phone').val()) {
            if (inputValidity.checkValidity()) {
                typingTimer = setTimeout(findPreviousVisitor, doneTypingInterval);
            }
        }
    });

    //Make AJAX call to see if the visitor previously visited, if not prompt the visitor to sign in
    function findPreviousVisitor() {
        var phoneData = $('#phone').val();
        var hostData = $('#hostSelected').find(":selected").text();
        var reasonData = $('#reasonSelected').find(":selected").text();
        $.ajax({
            type: "POST",
            url: "php/findVisitor.php",
            data: {
                'phoneData': phoneData,
                'hostData': hostData,
                'reasonData': reasonData
            },
            success: function (data) {
                $("#visitorConfirmation").html(data);
                //If visitor was found
                if ($("h1").length > 0) {
                    $('#hostGroup').show();
                }
                else {
                    $("#submitPreviousVisitorBtn").prop("disabled", true);
                    $('#hostGroup').hide();
                    $("#hostSelected").val('').trigger('change');
                    $('#reasonGroup').hide();
                    $("#reasonSelected").val('').trigger('change');
                    $('#submitPreviousVisitorBtn').hide();
                }
            }
        });
    }

    //Hide these inputs by default
    $('#hostGroup').hide();
    $('#reasonGroup').hide();
    $('#submitPreviousVisitorBtn').hide();

    //Dynamically show the input fields
    $('#hostSelected').on('select2:select', function (e) {
        $('#reasonGroup').show();
    });
    $('#reasonSelected').on('select2:select', function (e) {
        $('#submitPreviousVisitorBtn').show();
        $("#submitPreviousVisitorBtn").prop("disabled", false);
    });

    //Start validator on #visitorInformation field
    $('#visitorInformation').validator();

    //Set placeholder text for dynamic drop-downs
    $(document).ready(function () {
        $('#hostSelected').select2({
            placeholder: "Select a host"
        });
        $('#reasonSelected').select2({
            placeholder: "Select a reason for visiting"
        });
    });
</script>
</body>
</html>