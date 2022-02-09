<?php

    $dsn    = "mysql:host=localhost;dbname=shop new";
    $user   = "root";
    $pass   = "";
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES utf8",
    );

    // $dsn    = "pgsql:host=ec2-52-3-130-181.compute-1.amazonaws.com;dbname=d1i33cj78p7gof";
    // $user   = "jbijuiiccatqrx";
    // $pass   = "1ec65daad535df360c20a337e3e6b7a35292a2e061508f5218f1d4442d1cc604";
    // $option = array(
    //     PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES utf8",
    // );

try{
    
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "You Are Connected Welcome To Database";
}
catch(PDOExcepion $e){
    echo "field to connect" . $e->getMessage();
    
}