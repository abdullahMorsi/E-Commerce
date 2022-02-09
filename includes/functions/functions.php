<?php

/**
 * Get All items Function V2.0
 * Function to get All items from any table
 */

function getAllFrom($field, $table, $where=null,$and=null, $orderfield, $ordering = 'DESC'){
    global $con;
    // $sql = $where==null? '' : $where;
    $getAll = $con->prepare("Select $field FROM $table $where $and ORDER BY $orderfield $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
} 

/**
 * Get Latest categories Function V1.0
 * Function to get categories from database
 */

function getCat(){
    global $con;
    $getCat = $con->prepare("Select * FROM categories ORDER BY ID ASC");
    $getCat->execute();
    $cats = $getCat->fetchAll();
    return $cats;
} 

/**
 * Get Latest items Function V1.0
 * Function to get Items from database
 */

function getItems($where, $value, $approve=null){
    global $con;

    if($approve==null){
        $sql = 'AND Approve = 1';
    }else{
        $sql = null;
    }
    $getItems = $con->prepare("Select * FROM items WHERE $where=? $sql ORDER BY Item_ID ASC");
    
    $getItems->execute(array($value));
    
    $items = $getItems->fetchAll();
    
    return $items;
} 




/** Backend
 * Title V1.0
 * 
 *  **/
function getTitle(){

    global $pageTitle;
    
    if(isset($pageTitle)){
    
        echo $pageTitle;
    
    }else{

        echo "Default";
        
    }

}

/*
** Home redirect function V2.0
**[This Function Accepts Parameteres] 
** $theMsg = echo The message [error, success, Warining]
** $url = The link You want to redirect to
** $seconds = seconds before redirection
*/
function redirectHome($theMsg, $url=null, $seconds = 3){

    if($url===null){

        $url ="index.php";
        $link = "Home Page";

    }else{

        if(isset($_SERVER["HTTP_REFERER"])&&$_SERVER["HTTP_REFERER"]!==""){
            $url = $_SERVER["HTTP_REFERER"];
            $link = "Previous Page";

        }else{
            $url = "index.php";
            $link = "Home Page";

        }
    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You will be redirected to $link after $seconds</div>";
    header("refresh:$seconds; url=$url");
    exit;

}

/*
 *  Check items function V1.0
 *  Function to check items in Database[Function Accepts Parameters]  
 *  $select = The item to select [Example: user, item, category]
 *  $from = The table to select from [Example: users, items, categories ] 
 *  $value = The value of select [Example: Osama, Box, Electronics]
 */

function checkItem($select, $from, $value){

    global $con;

    $statment2 = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statment2->execute(array($value));
    $count = $statment2->rowCount( );
    return $count;


}

/*
** Count Number of items function V1.0
** Function to count number of item rows
** $items -> The items to count
** $table -> The table to choose from
*/

function countItems($item, $table){

    global $con;
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();

}

/**
 * Get Latest Records Function V1.0
 */

function getLatest($select, $table, $order, $limit=5){
    global $con;
    $getStmt = $con->prepare("Select $select FROM $table ORDER BY $order DESC LIMIT $limit ");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
} 

/**
* check if user is not activated
* Function to check regStatus of User
 */

function checkUserStatus($user){
    global $con;
    $stmtx = $con->prepare("SELECT Username, RegStatus FROM users WHERE Username = ? AND RegStatus=0 ");
    $stmtx-> execute(array($user));
    $status = $stmtx->rowCount();
    return $status;
}