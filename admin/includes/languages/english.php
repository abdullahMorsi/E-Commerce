<?php

function lang($phrase){

    static $lang = array(
        
        "HOME_ADMIN" => "Home",
        "Categories"    => "Categories",
        "ITEMS"         => "Items",
        "MEMBERS"       =>"Members",
        "COMMENTS"       =>"Comments",
        "STATISTICS"    =>"Statistics",
        "LOGS"          =>"Logs"
    );
    
    return $lang[$phrase];
    
};