<!--This script is for finding the previous visitor for sign out-->

<?php
/**
 * File Name: findVisitor.php
 * Created by PhpStorm
 * User: ryand
 * Date: 7/26/2018
 * Time: 12:15 PM
 * Company: Linemaster Switch Corporation
 */

//Start the session for the visitor
session_name("VisitorPortal");
session_start();

//If any errors arise display them
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Checks if the phone number was completed and successfully sent to this script
if (isset($_POST['phoneData'])) {

    //Sets session variables
    $_SESSION['phone'] = $_POST['phoneData'];

    //Sets session variables
    if (isset($_POST['hostData'])) {
        $_SESSION['host'] = $_POST['hostData'];
        $_SESSION['reason'] = $_POST['reasonData'];
    }

    //Accessing an external file for database connection
    require('../../php/connect.php');

    //If the visitor is signing out ...
    if ($_SESSION['isSigningOut'] == TRUE) {

        //Gather previous records based on the phone number
        $findCurrentVisitorSTMT = "SELECT TOP 1 * FROM dbo.LSC14043_Visitor_mst
                INNER JOIN dbo.LSC14043_VisitorLog_mst ON LSC14043_VisitorLog_mst.visitor_id = LSC14043_Visitor_mst.visitor_id
                WHERE LSC14043_Visitor_mst.phone LIKE '{$_POST['phoneData']}' AND LSC14043_VisitorLog_mst.sign_out IS NULL
                ORDER BY LSC14043_Visitor_mst.CreateDate DESC";

        //Execute query
        $findCurrentVisitorEXEC = sqlsrv_query($conn, $findCurrentVisitorSTMT);

        //If there is a previous record, display the name to the visitor for confirmation
        if (sqlsrv_has_rows($findCurrentVisitorEXEC)) {
            while ($row = sqlsrv_fetch_array($findCurrentVisitorEXEC)) {
                echo '<h1>Hi <span class="variableHolder">' . $row['name'] . '!</span></h1>
            <h2 class="text-center bottomPadding">Come back soon!</h2>';
                $_SESSION['name'] = $row['name'];
            }
        } else
            echo "The entered phone number is not in our records. Please verify the phone number or ask the receptionist to sign you out.";

      //If the visitor is NOT signing out ...
    }
    elseif ($_SESSION['isSigningOut'] == FALSE) {

        //Gather previous records based on the phone number
        $findCurrentVisitorSTMT = "SELECT TOP 1 * FROM dbo.LSC14043_Visitor_mst
                WHERE phone  LIKE '{$_SESSION['phone']}' 
                ORDER BY CreateDate DESC";
        $findCurrentVisitorEXEC = sqlsrv_query($conn, $findCurrentVisitorSTMT);

        //If there is a previous record, display the name to the visitor, if not allow the visitor to sign in as a new visitor
        if (sqlsrv_has_rows($findCurrentVisitorEXEC)) {
            while ($row = sqlsrv_fetch_array($findCurrentVisitorEXEC)) {
                echo '<h1 class="questionText">Hello <span class="variableHolder">' . $row['name'] . '!</span></h1>';
                $_SESSION['name'] = $row['name'];
            }
        } else {
        echo 'The entered phone number is not in our records. Please verify the phone number or sign in with the button below' . '<br><br>' . '<div class="offset-md-3 col-md-6"><button class="fillButton btn" onclick="document.location.href=\'newVisitorForm.php\'">SIGN IN</button></div>';
        }
    }
}