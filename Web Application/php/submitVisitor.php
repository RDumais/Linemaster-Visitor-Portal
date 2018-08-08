<?php
/**
 * File Name: submitVisitor.php
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
session_name("VisitorPortal");
session_start();

//Accessing an external file for database connection
require('../../php/connect.php');

//If the visitor is signing out ...
if ($_SESSION['isSigningOut'] == FALSE) {

    //Handle base64 -> HEXADECIMAL conversion.
    $encodedData = $_POST['photoData'];
    $binaryData = base64_decode($encodedData);
    $_SESSION['photo'] = bin2hex($binaryData);

    //Add prefix identifier for binary data, necessary for Syteline
    $prefixIdentifier = "0x";
    $photoHex = $_SESSION['photo'];
    $photoData = $prefixIdentifier . $photoHex;

    //Get the current datetime for sign_in.
    $datetimeVariable = new DateTime();
    $dateTime = date_format($datetimeVariable, 'Y-m-d H:i:s');

    //Setsite needed for sql execution
    $setsite = "DECLARE @SiteName SiteType
              , @Infobar  InfobarType;
                SELECT @SiteName = [site].[site]
                FROM [dbo].[site]
                WHERE [site].[site] = 'LSC';
                EXEC [dbo].[SetSiteSp] @Site = @SiteName, @Infobar = @Infobar OUTPUT;";

    //If visitor has never been to Linemaster
    if ($_SESSION['isPreviousVisitor'] == FALSE) {

        $submitVisitorSTMT = $setsite . "INSERT INTO LSC14043_Visitor_mst (name, company, email, phone, visitor_id) VALUES ('{$_SESSION['name']}','{$_SESSION['company']}','{$_SESSION['email']}','{$_SESSION['phone']}', 'TBD' )";
        sqlsrv_query($conn, $submitVisitorSTMT);


        $getVisitorIDSTMT = $setsite . "SELECT TOP 1 visitor_id FROM dbo.LSC14043_Visitor_mst
                                        WHERE phone LIKE '{$_SESSION['phone']}'
                                        ORDER BY CreateDate DESC";

        $getVisitorIDEXEC = sqlsrv_query($conn, $getVisitorIDSTMT);
        while ($row = sqlsrv_fetch_array($getVisitorIDEXEC, SQLSRV_FETCH_ASSOC)) {
            $visitorID = $row['visitor_id'];
        }

        $submitVisitSTMT = $setsite . "INSERT INTO LSC14043_VisitorLog_mst (host, photo, sign_in, reason, visit_id, visitor_id) VALUES ('{$_SESSION['host']}',$photoData,'$dateTime','{$_SESSION['reason']}', 'TBD', '$visitorID' )";
        sqlsrv_query($conn, $submitVisitSTMT);

        //Execute query and kill connection to DB.
        sqlsrv_close($conn);

        //Relocate to the 'complete.php' page.
        header('Location: ../signInConfirmation.php');
        exit();

    } //If visitor has been to Linemaster before
    else if ($_SESSION['isPreviousVisitor'] == TRUE) {

        $submitVisitorSTMT = $setsite . "INSERT INTO dbo.LSC14043_Visitor_mst(name,company,email,phone,visitor_id) (SELECT TOP 1 name,company,email,phone, 'TBD' FROM dbo.LSC14043_Visitor_mst WHERE phone LIKE '{$_SESSION['phone']}')ORDER BY CreateDate DESC";
        sqlsrv_query($conn, $submitVisitorSTMT);

        $getVisitorIDSTMT = $setsite . "SELECT TOP 1 visitor_id FROM dbo.LSC14043_Visitor_mst
                                 WHERE phone LIKE '{$_SESSION['phone']}'
                                 ORDER BY CreateDate DESC";

        $getVisitorIDEXEC = sqlsrv_query($conn, $getVisitorIDSTMT);
        while ($row = sqlsrv_fetch_array($getVisitorIDEXEC, SQLSRV_FETCH_ASSOC)) {
            $visitorID = $row['visitor_id'];
        }

        $submitVisitSTMT = $setsite . "INSERT INTO LSC14043_VisitorLog_mst (host, photo, sign_in, reason, visit_id, visitor_id) VALUES ('{$_SESSION['host']}',$photoData,'$dateTime','{$_SESSION['reason']}', 'TBD', '$visitorID' )";
        sqlsrv_query($conn, $submitVisitSTMT);

        //Execute query and kill connection to DB.
        sqlsrv_close($conn);

        //Relocate to the 'complete.php' page.
        header('Location: ../signInConfirmation.php');
        exit();

    }

} elseif ($_SESSION['isSigningOut'] == TRUE) {

    //SQL Query to handle insert into database.
    $signOutVisitorSTMT = "UPDATE dbo.LSC14043_VisitorLog_mst
SET    dbo.LSC14043_VisitorLog_mst.sign_out = GETDATE()
FROM   dbo.LSC14043_VisitorLog_mst
       INNER JOIN dbo.LSC14043_Visitor_mst
         ON dbo.LSC14043_VisitorLog_mst.visitor_id = dbo.LSC14043_Visitor_mst.visitor_id
WHERE  dbo.LSC14043_Visitor_mst.phone LIKE '{$_SESSION['phone']}'
       AND LSC14043_VisitorLog_mst.sign_out IS NULL";

    //Execute query
    $getResults = sqlsrv_query($conn, $signOutVisitorSTMT);

    //Relocate to the 'complete.php' page.
    header('Location: ../signOutConfirmation.php');
    exit();
}