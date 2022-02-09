<?php

    /*
    ======================
    ==Manage member page
    ==You can add | edit | delete members from here 
    ======================
    */

    session_start();

    $pageTitle = "Members";

    if(isset($_SESSION['Username'])){

        include "init.php";
        
        $do = isset($_GET["do"]) ? $_GET["do"] : 'Manage';

        //start manage page
        if($do == "Manage"){ //Manage page 

            $query ="";
            if(isset($_GET["page"]) && $_GET["page"]=="Pending" ){
                $query = "AND RegStatus = 0";
            }

            //Select all users except admin
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupId != 1 $query ORDER BY
            UserID DESC");
            
            //execute the statment
            $stmt->execute();

            //Assign to variables
            $rows = $stmt->fetchAll();

            if(!empty($rows)){
        ?>
        
            <h1 class="text-center">Manage Members</h1>
            <div class="container" >
                <div class="table-responsive">
                    <table class="table-main manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registred date</td>
                            <td>control</td>
                                
                        </tr>

                        <?php

                            foreach($rows as $row){
                                echo "<tr>";
                                    echo"<td>" . $row["UserID"] . "</td>";    
                                    echo"<td>";
                                    if(!empty($row['Avatar'])){
                                        echo"<img src='uploads/avatar/" . $row["Avatar"] . "'alt =''/>";
                                    }else{
                                        echo"<img src='uploads/avatar/avatar.png' alt =''/>";
                                    }
                                    echo "</td>";    
                                    echo"<td>" . $row["Username"] . "</td>";    
                                    echo"<td>" . $row["Email"] . "</td>";    
                                    echo"<td>" . $row["FullName"] . "</td>";    
                                    echo"<td>" . $row["Date"] . "</td>";    
                                    echo'<td>
                                        <a href="members.php?do=Edit&userid=' .$row["UserID"]. '"class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="members.php?do=Delete&userid=' .$row["UserID"]. '"class="btn btn-danger confirm"><i class="far fa-times-circle"></i> Delete</a>';

                                        if($row["RegStatus"]==0){
                                            echo '<a href="members.php?do=Activate&userid=' .$row["UserID"]. '"class="btn btn-info activate"><i class="fas fa-check"></i> Activate</a>';
                                        }
                                    echo '</td>';

                                
                                echo"<tr>";
                            }

                        ?>

                    </table>
                </div>
                <a href='members.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
            </div>
            <?php
            }else{
                echo "<div class='container'>";
                    echo '<div class="nice-message">There\'s No Records To Show </div>';
                    echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';

                echo "</div>";
            }
            ?>
            <?php
            }elseif($do == "Add"){
            //Add page
            ?>
            <h1 class="text-center">Add Member</h1>
            <div class="container" >
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype='multipart/form-data'>
            <!-- Start Username field -->
                    <div class="form-group form-group-lg" >
                        <label class="col-sm-2 control-label">Username </label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop">
                        </div>
                    </div>
                
            <!-- End Username field -->
            <!-- Start Password field -->
                    <div class="form-group form-group-lg" >
                        <label class="col-sm-2 control-label">Password </label>
                        <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex">
                        <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>

            <!-- End Password field -->
            <!-- Start Email field -->
                    <div class="form-group form-group-lg" >
                        <label class="col-sm-2 control-label">Email </label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid">
                        </div>
                    </div>
            <!-- End Email field -->
            <!-- Start Fullname field -->
                    <div class="form-group form-group-lg" >
                        <label class="col-sm-2 control-label">Full Name </label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="fullname" class="form-control" required="required" placeholder="Fullname Appears In Your Profile Page">
                        </div>
                    </div>
            <!-- End Fullname field -->
            <!-- Start Avatar field -->
                    <div class="form-group form-group-lg" >
                        <label class="col-sm-2 control-label">Avatar </label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" name="avatar" class="form-control" required="required" >
                        </div>
                    </div>
            <!-- End Avatar field -->
            <!-- Start Submit field -->
                    <div class="form-group form-group-lg" >
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="Submit" value="Add Member" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                </form>
            </div>
            
            <?php

            }elseif($do == "Insert"){

                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>"; 
                    
                //Upload Variables
                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTmp  = $_FILES['avatar']['tmp_name'];
                $avatarType = $_FILES['avatar']['type'];

                //List of allowed file upload variables
                $avatarAllowedExtension = array("jpeg", 'jpg', 'png', 'gif');

                // get avatar extension
                $avatarExtensionRef = explode('.', $avatarName);
                $avatarExtension = strtolower(end($avatarExtensionRef));
                
                


                //get variables from the form
                $user = $_POST["username"];
                $email = $_POST["email"];
                $name = $_POST["fullname"];
                $pass = $_POST['password'];
                $hashPass = sha1($_POST['password']);

                //validate the form
                $formErrors = array();

                if(strlen($user) < 4){
                    $formErrors[] = "Username can't be less than<strong> 4 Characters</strong>";
                }if(empty($user)){
                    $formErrors[] = "Username can't be <strong>empty</strong>";
                }if(empty($email)){
                    $formErrors[] = "Email can't be <strong>empty</strong>";
                }if(empty($pass)){
                    $formErrors[] = "Password can't be <strong>empty</strong>";
                }if(empty($name)){
                    $formErrors[] = "Fullname can't be <strong>empty</strong>";
                }if(!empty($avatarName)&&!in_array($avatarExtension, $avatarAllowedExtension)){
                    $formErrors[] = 'This extension is not <strong>allowed</strong> ';
                }if(empty($avatarName)){
                    $formErrors[] = 'Avatar is <strong>required</strong> ';
                }if($avatarSize > 4194304 ){
                    $formErrors[] = 'Avatar can\'t be larger than  <strong>4MB</strong> ';
                }
                
                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }

                //if there is no errors procced to update operation
                if(empty($formErrors)){

                    $avatar = rand(0, 1000000000) .'_'. $avatarName;

                    move_uploaded_file($avatarTmp, 'uploads\avatar\\'.$avatar);
                    // echo $avatarTmp;
                    //check if user exist in database
                    $check = checkItem("Username", "users", $user);

                    if($check ==1){

                        $theMsg = "<div class='alert alert-danger'>Sorry, this user exists</div>";
                        redirectHome($theMsg, "back");

                    }else{

                        //Insert user info in database
                        $stmt= $con->prepare("INSERT INTO
                                            users(Username, Password, Email, Fullname, RegStatus,Date, Avatar) 
                                            VALUES(:zuser, :zpass, :zemail, :zname, 1,now(), :zavatar)");

                        $stmt->execute(array(
                        
                            'zuser' => $user,
                            'zpass' => $hashPass,
                            'zemail' => $email,
                            'zname' => $name,
                            'zavatar' => $avatar
                        
                        ));

                        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Row Inserted</div>";
                        redirectHome($theMsg, "back");
                    }
                }
                
            }else{
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-danger'>Sorry, You can't browse this page directly.</div>";
                    
                    redirectHome($theMsg);
                    echo"</div>";

            }

            echo "</div>";

            }elseif($do=="Edit"){  //edit page

            //check if GET request userid is numeric and get the integer value of it
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            // check if user exists in database
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID= ? LIMIT 1 ");
            $stmt-> execute(array($userid));
            $row = $stmt-> fetch();
            $count = $stmt->rowCount();

            if($count > 0){ ?>
                <h1 class="text-center">Edit Member</h1>
                <div class="container" >
                    <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>"
                <!-- Start Username field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Username </label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="username" class="form-control" value="<?php echo $row["Username"] ?>" autocomplete="off" required="required">
                            </div>
                        </div>
                    
                <!-- End Username field -->
                <!-- Start Password field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Password </label>
                            <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row["Password"] ?>">
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change">
                            </div>
                        </div>

                <!-- End Password field -->
                <!-- Start Email field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Email </label>
                            <div class="col-sm-10 col-md-6">
                                <input type="email" value="<?php echo $row["Email"] ?>" name="email" class="form-control" required="required">
                            </div>
                        </div>
                <!-- End Email field -->
                <!-- Start Fullname field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Full Name </label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" value="<?php echo $row["FullName"] ?>"  name="fullname" class="form-control" required="required">
                            </div>
                        </div>
                <!-- End Fullname field -->
                <!-- Start Submit field -->
                        <div class="form-group form-group-lg" >
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="Submit" value="Update" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End Submit field -->

            <?php
        }else{

                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                redirectHome($theMsg);
                echo "</div>";

            }
        }
        elseif($do=="Update"){  //start Updtae
            
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD']== 'POST'){

                //get variables from the form
                $id = $_POST["userid"];
                $user = $_POST["username"];
                $email = $_POST["email"];
                $name = $_POST["fullname"];

                //password trick
                $pass = empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] : $pass= sha1($_POST['newpassword']);

                //checkitem function
                // $check = checkItem("Username", "users", $user);

                // if($check ==1){

                //     $theMsg = "<div class='alert alert-danger'>Sorry, this user exists</div>";
                //     redirectHome($theMsg, "back");

                // }
                
                //validate the form
                $formErrors = array();

                if(strlen($user) < 4){
                    $formErrors[] = "<div class='alert alert-danger'>Username can't be less than<strong> 4 Characters</strong></div>";
                }if(empty($user)){
                    $formErrors[] = "<div class='alert alert-danger'>Username can't be <strong>empty</strong></div>";
                }if(empty($email)){
                    $formErrors[] = "<div class='alert alert-danger'>Email can't be <strong>empty</strong></div>";
                }if(empty($name)){
                    $formErrors[] = "<div class='alert alert-danger'>Fullname can't be <strong>empty</strong></div>";
                }
                
                foreach($formErrors as $error){
                    echo $error;
                }

                

                //if there is no errors procced to update operation
                if(empty($formErrors)){

                    $stmt2 = $con->prepare('SELECT * FROM users WHERE Username =? AND UserID !=?');

                    $stmt2->execute(array($user, $id));

                    $count = $stmt2->rowCount();
                    
                    if($count == 1){
                        $theMsg = "<div class='alert alert-danger'>Sorry, this user exists</div>";
                        redirectHome($theMsg, "back");
                    }else{
                        //Update database with info
                        $stmt= $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                        $stmt->execute(array($user, $email, $name,$pass, $id));

                        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows affected</div>";
                        redirectHome($theMsg, "back");
                    }
                    
                }
            

        }else{

            $theMsg = "<div class='alert alert-danger'>Sorry, You can't browse this page directly.</div>";
            redirectHome($theMsg);

        }

            echo "</div>";
            
        }elseif($do =="Delete"){    //start delete

            echo "<h1 class='text-center'>Delete Member</h1>";
            echo "<div class='container'>";

                //check if GET request userid is numeric and get the integer value of it
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

                // check if user exists in database
                $check = checkItem("userid", "users", $userid);

                if($check > 0){
                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                    $stmt->bindParam(":zuser", $userid);
                    $stmt->execute();


                    $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows Deleted</div>";
                    redirectHome($theMsg, "back");

                }else{
                    $theMsg = "<div class='alert alert-danger'>This id does not exist</div>";
                    redirectHome($theMsg);

                }
            echo"</div>";
            
        }elseif($do == "Activate"){ //start approve
            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";

                //check if GET request userid is numeric and get the integer value of it
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

                // check if user exists in database
                $check = checkItem("userid", "users", $userid);

                if($check > 0){
                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = :zuser");
                    $stmt->bindParam(":zuser", $userid);
                    $stmt->execute();


                    $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows Updated</div>";
                    redirectHome($theMsg, "back");

                }else{
                    $theMsg = "<div class='alert alert-danger'>This id does not exist</div>";
                    redirectHome($theMsg);

                }
            echo"</div>";
        }


        include $tmp . "footer.php";

    }else{

    header("Location: index.php");
    
}

?>