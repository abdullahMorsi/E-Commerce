<?php

//error reporting
ini_set('display_errors', 'on');
error_reporting(E_ALL);

include "admin/connect.php";

$sessionUser = 'NO User';
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}

// Routes

$tmp = "includes/templates/"; //Template Directory
$lang = "includes/languages/"; //language directory
$func = "includes/functions/"; // functions directory
$css = "layout/css/"; //Css directory
$js  = "layout/js/"; //Js Directory

//include the important files
include $func . "functions.php";
include $lang  . "english.php"; 
include $tmp . "header.php"; 

