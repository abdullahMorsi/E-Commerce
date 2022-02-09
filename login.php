<?php 
    ob_start();
    session_start();        

    $pageTitle = "Login";

    if(isset($_SESSION["user"])){
        header("Location: index.php");
    }

    include 'init.php' ;

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        if(isset($_POST['login'])){
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);

            // check if user exists in database
            $stmt = $con->prepare("SELECT Username, Password, UserID FROM users WHERE Username=? AND Password=?");
            $stmt-> execute(array($user, $hashedPass));
            $get = $stmt->fetch();
            $count = $stmt->rowCount();

            //if count > 0 that means database contains record aabout this user
            if($count>0){

                $_SESSION["user"]   = $user; //Register session name
                $_SESSION['uid']    = $get['UserID']; //Register user id
                header("Location: index.php"); ///Redirect to dashboard
                exit();
            }else{
                $formErrors[] = 'Wrong username or password';
            }
        }else{
            $formErrors = array();
            
            $username   = $_POST['username'];
            $password   = $_POST['password'];
            $password2  = $_POST['password2'];
            $email      = $_POST['email'];

            if(isset($username)){

                $filteredUser = filter_var($username, FILTER_SANITIZE_STRING);
                
                if(strlen($filteredUser)<4){
                    
                    $formErrors[] = 'Username must be larger than 4 charcters';
                
                }
            }

            if(isset($password)&&isset($password2)){
                
                if(empty($password)){
                    
                    $formErrors[] = 'Sorry, Passowrd can\'t be empty';
                
                }


                if(sha1($password) !== sha1($password2)){
                    $formErrors[] = 'Sorry, Passowrd doesn\'t match';
                }

            }
            if(isset($email)){

                $filteredUser = filter_var($email, FILTER_SANITIZE_EMAIL);
                
                if(filter_var($filteredUser, FILTER_VALIDATE_EMAIL)!=true){
                    
                    $formErrors[] = 'This email is not valid';
                
                }
            }
            
            //if there is no errors procced to insert user operation
            if(empty($formErrors)){

                //check if user exist in database
                $check = checkItem("Username", "users", $username);

                if($check ==1){
                    $formErrors[] = 'Sorry, this user exists';


                }else{

                    //Insert user info in database
                    $stmt= $con->prepare("INSERT INTO
                                        users(Username, Password, Email, FullName, RegStatus, Date) 
                                        VALUES(:zuser, :zpass, :zemail, 'life', 0,now())");

                    $stmt->execute(array(
                    
                        'zuser' => $username,
                        'zpass' => sha1($password),
                        'zemail' => $email,
                    
                    ));

                    $successMsg = 'Congrats you are now registered user';
                }
            }
        }
        

    }
?>

<div class='container login-page'>
    <h1 class='text-center'>
        <span class='selected' data-class='login'>Login </span> | 
        <span data-class='signup'>Signup</span>
    </h1>
    <!-- Start login form -->
    <form class='login' method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="input-container">
            <input class='form-control' type='text' name='username' autocomplete='off' placeholder='Type your username' required="required"/>
        </div>
        <div class="input-container">
            <input class='form-control' type='password' name='password' autocomplete='new-password' placeholder='Type your password' required="required"/>
        </div>
        <input class='btn btn-primary btn-block' type='submit' name='login' value='login' />
    </form>
    <!-- End login form -->

    <!-- Start signup form -->
    <form class='signup' method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
        <div class="input-container">
            <input 
                pattern='.{4,}'
                title='Username must be more than 4 chars' 
                class='form-control' 
                type='text' 
                name='username' 
                autocomplete='off' 
                placeholder='Type your username' 
                required="required"
            />
        </div>
        <div class="input-container">
            <input min-length='4' class='form-control' type='password' name='password' autocomplete='new-password' placeholder='Type a complex password'required="required"/>
        </div>
        <div class="input-container">
            <input min-length='4'  class='form-control' type='password' name='password2' autocomplete='new-password' placeholder='Type password again'required="required"/>
        </div>
        <div class="input-container">
            <input class='form-control' type='email' name='email'  placeholder='Type valid email'required="required"/>
        </div>
            <input class='btn btn-success btn-block' type='submit' name='signup' value='Signup' />
    </form>
        <!-- End signup form -->
        <div class="the-errors text-center">
            <?php 
                if(!empty($formErrors)){
                    foreach($formErrors as $error){
                        echo '<div class="msg error">'.$error .'</div></br>';
                    }
                }
                if(isset($successMsg)){
                    echo '<div class="msg success">'.$successMsg.'</div>';
                }
            ?>
        </div>

</div>


<?php 
include $tmp . "footer.php"; 
ob_end_flush();
?>