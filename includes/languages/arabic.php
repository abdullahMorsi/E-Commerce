<?php

function lang($phrase){
  
    static $lang = array(
        
        "Brand" => "الادمن الرايق",
        "Categories"   => "الاقسام",
        "ITEMS"       => "Items",
        "MEMBERS"     =>"Members",
        "COMMENTS"    =>"التعليقات على ام بي سي 3",
        "STATISTICS"  =>"Statistics",
        "LOGS"        =>"Logs"
    
    );
    
    return $lang[$phrase];
    
};