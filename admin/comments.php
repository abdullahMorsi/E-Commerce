<?php

    /*
    ======================
    ==Manage Comments page
    ==You can Approve | edit | delete Comments from here 
    ======================
    */

    session_start();

    $pageTitle = "Comments";

    if(isset($_SESSION['Username'])){

        include "init.php";
        
        $do = isset($_GET["do"]) ? $_GET["do"] : 'Manage';

        //start manage page
        if($do == "Manage"){ //Manage page 

            //Select all users except admin
            $stmt = $con->prepare(" SELECT
                                        comments.*, items.Name AS Item_Name, users.Username 
                                    FROM 
                                        comments
                                    INNER JOIN
                                        items
                                    ON
                                        comments.item_id = items.Item_ID
                                    INNER JOIN 
                                        users
                                    ON
                                    comments.user_id = users.UserID
                                    ORDER BY
                                    c_id DESC
                                        ");
            
            //execute the statment
            $stmt->execute();

            //Assign to variables
            $rows = $stmt->fetchAll();
            if(!empty($rows)){

        ?>
        
            <h1 class="text-center">Manage Comments</h1>
            <div class="container" >
                <div class="table-responsive">
                    <table class="table-main text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                                
                        </tr>

                        <?php

                            foreach($rows as $row){
                                echo "<tr>";
                                    echo"<td>" . $row["c_id"] . "</td>";    
                                    echo"<td>" . $row["comment"] . "</td>";    
                                    echo"<td>" . $row["Item_Name"] . "</td>";    
                                    echo"<td>" . $row["Username"] . "</td>";    
                                    echo"<td>" . $row["comment_date"] . "</td>";    
                                    echo'<td>
                                        <a href="comments.php?do=Edit&comid=' .$row["c_id"]. '"class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="comments.php?do=Delete&comid=' .$row["c_id"]. '"class="btn btn-danger confirm"><i class="far fa-times-circle"></i> Delete</a>';

                                        if($row["status"]==0){
                                            echo '<a href="comments.php?do=Approve&comid=' .$row["c_id"]. '"class="btn btn-info activate"><i class="fas fa-check"></i> Approve</a>';
                                        }
                                    echo '</td>';

                                
                                echo"<tr>";
                            }

                        ?>

                    </table>
                </div>
            </div>
            <?php
            }else{
                echo "<div class='container'>";
                    echo '<div class="nice-message">There\'s No Records To Show </div>';
                echo "</div>";
            }
            ?>
            <?php
            }elseif($do=="Edit"){  //edit page

            //check if GET request comid is numeric and get the integer value of it
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            // check if user exists in database
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id= ?");
            $stmt-> execute(array($comid));
            $row = $stmt-> fetch();
            $count = $stmt->rowCount();

            if($count > 0){ ?>
                <h1 class="text-center">Edit Comment</h1>
                <div class="container" >
                    <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid; ?>"
                <!-- Start Username field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Comment </label>
                            <div class="col-sm-10 col-md-6">
                                <textarea class='form-control' name='comment' ><?php echo $row['comment'] ?></textarea>
                        </div>
                        </div>
                    
                <!-- End Username field -->
                
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
            
            echo "<h1 class='text-center'>Update Comment</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD']== 'POST'){

                //get variables from the form
                $comid = $_POST["comid"];
                $comment = $_POST["comment"];

                

                    //Update database with info
                    $stmt= $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                    $stmt->execute(array($comment,$comid));

                    $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows affected</div>";
                    redirectHome($theMsg, "back");
                

            }else{

                $theMsg = "<div class='alert alert-danger'>Sorry, You can't browse this page directly.</div>";
                redirectHome($theMsg);

            }

            echo "</div>";
            
        }elseif($do =="Delete"){    //start delete

            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";

                //check if GET request comid is numeric and get the integer value of it
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

                // check if user exists in database
                $check = checkItem("c_id", "comments", $comid);

                if($check > 0){
                    $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
                    $stmt->bindParam(":zid", $comid);
                    $stmt->execute();


                    $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows Deleted</div>";
                    redirectHome($theMsg, "back");

                }else{
                    $theMsg = "<div class='alert alert-danger'>This id does not exist</div>";
                    redirectHome($theMsg);

                }
            echo"</div>";
            
        }elseif($do == "Approve"){ //start approve
            echo "<h1 class='text-center'>Approve Comment</h1>";
            echo "<div class='container'>";

                //check if GET request comid is numeric and get the integer value of it
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

                // check if user exists in database
                $check = checkItem("c_id", "comments", $comid);

                if($check > 0){
                    $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = :zid");
                    $stmt->bindParam(":zid", $comid);
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