<!--This page is for visitors that are new to Linemaster.-->

<?php
/**
 * File Name: newVisitorForm.php
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

session_start();
$_SESSION['isPreviousVisitor'] = FALSE;

//Accessing an external file for database connection
require('../php/connect.php');

//If unable to connect to database, display errors
if ($conn === false) {
    echo "Could not connect.\n";
    die(print_r(sqlsrv_errors(), true));
}

//Reformat the name from the original database table, gather results
$getHostsSTMT = "SELECT DISTINCT SUBSTRING(name,CHARINDEX(',',name)+1,LEN(name) - CHARINDEX(',',name) )+ ' ' + SUBSTRING(name,1,CHARINDEX(',',name)-1) AS name FROM dbo.employee_mst WHERE term_date IS NULL";
$getHostsEXEC = sqlsrv_query($conn, $getHostsSTMT);

//Gather all companies that previous visitors represent
$getCompaniesSTMT = "SELECT DISTINCT company FROM dbo.LSC14043_Visitor_mst
ORDER BY company ASC";
$getCompaniesEXEC = sqlsrv_query($conn, $getCompaniesSTMT);

//Gather static table of reasons of a visitor visiting
$getReasonsSTMT = "SELECT reason FROM dbo.LSC14043_VisitorReasons_mst";
$getReasonsEXEC = sqlsrv_query($conn, $getReasonsSTMT);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Linemaster Vistors</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/fontawesome-all.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="js/validator.js"></script>
    <script src="js/bootstrap.js"></script>
</head>
<body>
<header>
    <a class="homeReset" href="php/reset.php">
        <div><i class="fal fa-home"></i></div>
    </a>
    <img id="miniLogo" src="img/linemaster.svg" alt="">
</header>
<div class="container-fluid">
    <form id="visitorInformation" method="post" action="webcam.php">
        <div class="row fullHeightRow">
            <div class="col-md-12">
                <h1 class="text-center questionText">Welcome to Linemaster. <br>
                    Please provide us information about
                    yourself.</h1>
            </div>

            <div class="offset-md-2 col-md-9">
                <div id="phoneGroup" class="form-group">
                    <label for="phone"><span>Cellphone</span></label>
                    <input type="tel" id="phone" name="phone" minlength="10" maxlength="11"
                           pattern="^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$"
                           data-pattern-error="Please follow the format including area code: 8609741000" tabindex="1"
                           value="<?php echo $_SESSION['phone'] ?>"
                           required>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="offset-md-2 col-md-9">
                <div id="companyGroup" class="form-group">
                    <label for="company"><span>Company</span></label>
                    <input type="text" id="company" name="company" maxlength="50" tabindex="2" list="companies"
                           required>
                    <datalist id="companies">
                        <?php

                        //Populate drop-down with previous visitor's companies

                        while ($row = sqlsrv_fetch_array($getCompaniesEXEC, SQLSRV_FETCH_ASSOC)) {
                            echo "<option value=\"{$row['company']}\">";
                        }
                        ?>
                    </datalist>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="offset-md-2 col-md-9">
                <div id="nameGroup" class="form-group">
                    <label for="name"><span>Full name</span></label>
                    <input type="text" id="name" name="name" pattern="^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$"
                           maxlength="40" tabindex="3" required>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="offset-md-2 col-md-9">
                <div id="emailGroup" class="form-group">
                    <label for="email"><span>Email</span></label>
                    <input type="email" id="email" name="email" maxlength="320"
                           pattern="[a-zA-Z0-9.-_]{1,}@[a-zA-Z0-9.-]{1,}[.]{1}[a-zA-Z0-9]{2,}"
                           data-pattern-error="Please follow the format: example@example.com" tabindex="4" required>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div id="hostGroup" class="offset-md-2 col-md-9 ">
                <label for="host" id="hostLabel">
                    <span>HOST</span></label>
                <select id="host" class="chosen" name="host" tabindex="5" required>
                    <option></option>
                    <?php

                    //Populate drop-down with names of hosts at Linemaster

                    while ($row = sqlsrv_fetch_array($getHostsEXEC, SQLSRV_FETCH_ASSOC)) {
                        echo "<option>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div id="reasonGroup" class="offset-md-2 col-md-9 ">
                <label for="reason" id="reasonLabel">
                    <span>REASON</span></label>
                <select id="reason" class="chosen" name="reason" tabindex="6" required>
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
        <div class="row submitRow">
            <div class="col-md-4">
                <button class="btn outlineButton toUppercase" onclick="document.location.href='index.php'" type="button"
                        tabindex="-1"><i class="fas fa-arrow-alt-circle-left pull-left"></i><span>Back</span>
                </button>
            </div>
            <div class="offset-md-4 col-md-4">
                <button id="nextNewVisitorBtn" class="btn fillButton toUppercase" data-disable="true" tabindex="-1"
                        disabled><span>Next</span><i
                            class="fas fa-arrow-alt-circle-right pull-right"></i></button>
            </div>
        </div>
    </form>


</div>

<script>

    //The following few blocks of code allow the visitor to move on from their input box as the complete each one.
    //This allows the interface to be guided

    //Listen for keyups to tell if the visitor is finished typing
    var typingTimer;
    var doneTypingInterval = 1000;

    $('#company').keyup(function () {
        var inputValidity = document.getElementById("company");
        var param = $('#nameGroup');
        clearTimeout(typingTimer);
        if ($('#company').val()) {
            if (inputValidity.checkValidity()) {
                typingTimer = setTimeout(function () {
                    param.show();
                }, doneTypingInterval);
            }
        }
    });
    $('#name').keyup(function () {
        var inputValidity = document.getElementById("name");
        var param = $('#emailGroup');
        clearTimeout(typingTimer);
        if ($('#name').val()) {
            if (inputValidity.checkValidity()) {
                typingTimer = setTimeout(function () {
                    param.show();
                }, doneTypingInterval);
            }
        }
    });
    $('#email').keyup(function () {
        var inputValidity = document.getElementById("email");
        var param = $('#hostGroup');
        clearTimeout(typingTimer);
        if ($('#email').val()) {
            if (inputValidity.checkValidity()) {
                typingTimer = setTimeout(function () {
                    param.show();
                }, doneTypingInterval);
            }
        }
    });
    $('#host').on('select2:select', function (e) {
        var inputValidity = document.getElementById("host");
        var param = $('#reasonGroup');
        clearTimeout(typingTimer);
        if ($('#host').val()) {
            if (inputValidity.checkValidity()) {
                typingTimer = setTimeout(function () {
                    param.show();
                }, doneTypingInterval);
            }
        }
    });
    $('#reason').on('select2:select', function (e) {
        var inputValidity = document.getElementById("host");
        var param = $('#nextNewVisitorBtn');
        clearTimeout(typingTimer);
        if ($('#host').val()) {
            if (inputValidity.checkValidity()) {
                typingTimer = setTimeout(function () {
                    param.prop("disabled", false);
                    $('#nextNewVisitorBtn').show();
                }, doneTypingInterval);
            }
        }
    });


    //Set placeholder text for dynamic drop-downs
    $(document).ready(function () {
        $('#reason').select2({
            placeholder: "Select a reason for visiting"
        });
        $('#host').select2({
            placeholder: "Select a host"
        });
    });

    //Start with the following components as hidden, as the visitor progresses through the form the components will reveal
    $('#nextNewVisitorBtn').hide();
    $('#nameGroup').hide();
    $('#emailGroup').hide();
    $('#hostGroup').hide();
    $('#reasonGroup').hide();

    //Force validation of the entire form
    $('#visitorInformation').validator();

</script>

</body>
</html>