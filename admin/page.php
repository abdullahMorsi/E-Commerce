<?php
/*
    categories => [Manage | Edit | Update | Add | Insert | Delete | Stats]
*/

$do = isset($_GET["do"]) ? $_GET["do"] : 'Manage';

if($do == "Manage"){
    echo "Welcome, You are in manage category page";
    echo "<a href='?do=Add'> Add new Category +</a>";
    echo "<a href='?do=Insert'> Insert new Category +</a>";

}elseif($do=="Add"){
    echo "Welcome, You are in add category page";
}
elseif($do=="Insert"){
    echo "Welcome, You are in Insert category page";
}
else{
    echo "Error Bitch!";
}







?>