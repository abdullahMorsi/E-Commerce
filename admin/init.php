<?php

include "connect.php";

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

//include navbar in all pages except the one with variable $noNavbar 
if (!isset($noNavbar)){
    include $tmp . "navbar.php"; 
}
