<!--This is an utility script that destroys any saved session variable and data-->

<?php
/**
 * File Name: reset.php
 * Created by PhpStorm
 * User: ryand
 * Date: 7/26/2018
 * Time: 12:15 PM
 * Company: Linemaster Switch Corporation
 */

//Kills the visitor's session and any saved variable data
session_destroy();

//Navigate back to the landing page
header('Location: ../index.php');
exit();