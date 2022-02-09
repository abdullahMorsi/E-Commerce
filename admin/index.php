<?php
    session_start();

    $noNavbar = "";
    $pageTitle = "Login";

    if(isset($_SESSION["Username"])){
        header("Location: dashboard.php");
    }
    include "init.php";
    

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        // check if user exists in database
        $stmt = $con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password=? AND GroupId = 1 LIMIT 1 ");
        $stmt-> execute(array($username, $hashedPass));
        $row = $stmt-> fetch();
        $count = $stmt->rowCount();

        //if count>0 that means database contains record aabout this user
        if($count>0){
            echo 'Welcome ' . $username;
            $_SESSION["Username"] = $username; //Register session name
            $_SESSION["ID"] = $row['UserID']; //Register session id
            // print_r($row);
            header("Location: dashboard.php"); ///Redirect to dashboard
            exit();

        }else{
            echo $count;
        }


        // echo "<pre>";
        //   print_r($_SERVER);
    }

?>
    
    <form class="login" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off">
        <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
        <input type="submit" class="btn btn-primary btn-block" value="login">
        
    </form>

    

<?php include $tmp . "footer.php"; ?>