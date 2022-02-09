<?php

    session_start();

    if(isset($_SESSION['Username'])){

        $pageTitle = "Dashboard";

        include "init.php";
        //Start dasboard page
        /**
         * Get Latest Users
         */
        $numUsers = 5; //number of latest users
        $latestUsers = getLatest("*", "users", "UserID", $numUsers); //latest users array

        $numItems    = 5; //num of latest items
        $latestItems = getLatest("*", "items", "Item_ID", $numItems) ;

        $numComments   = 5; //num of latest items
        $latestCommetns = getLatest("*", "comments", "c_id", $numComments) ;

        ?>

        <div class="home-stats">
            <div class="container text-center">
                <h1>Dashboard</h1>
                <div class="row">
                <div class="col-md-3">
                        <div class="stat st-members">
                            <i class='fa fa-users'></i>
                            <div class="info">
                                Total Members
                                <span><a href="members.php"><?php echo countItems("UserID", "users") ?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-pending">
                            <i class="fa fa-user-plus"></i>
                            <div class="info">
                                Pending Members
                                <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem("RegStatus", "users", 0)?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-items">
                            <i class='fa fa-tag'></i>
                            <div class="info">
                                Total Items
                                <span><a href="items.php"><?php echo countItems("Item_ID", "items") ?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-comments">
                            <i class='fa fa-comments'></i>
                            <div class="info">
                                Total Comments
                                <span><a href="comments.php"><?php echo countItems("c_id", "comments") ?></a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="latest">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Latest <?php echo $numUsers ?> Registered Users
                                <span class="toggle-info pull-right ">
                                    <i class='fa fa-plus fa-lg' ></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                    if(!empty($latestUsers )){

                                        foreach($latestUsers as $user){
                                            echo '<li>';
                                                echo $user["Username"];
                                                echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                                    echo '<span class="btn btn-success pull-right">';
                                                        echo '<i class="fa fa-edit"></i>Edit';
                                                        if($user["RegStatus"]==0){
                                                            echo '<a href="members.php?do=Activate&userid=' .$user["UserID"]. '"class="btn btn-info pull-right activate"><i class="far fa-times-circle"></i> Activate</a>';
                                                        }
                                                    echo '</span>';
                                                echo '</a>';
                                            echo '</li>';
                                        }
                                    }else{
                                        echo '<span class="nice-message">There\'s No Record To Show</span>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Latest  <?php echo $numItems ?>  Items
                                <span class="toggle-info pull-right ">
                                    <i class='fa fa-plus fa-lg' ></i>
                                </span>
                            </div>
                            <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                    <?php
                                    if(!empty($latestUsers )){

                                        foreach($latestItems as $item){
                                            echo '<li>';
                                                echo $item["Name"];
                                                echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                                    echo '<span class="btn btn-success pull-right">';
                                                        echo '<i class="fa fa-edit"></i>Edit';
                                                        if($item["Approve"]==0){
                                                            echo '<a href="items.php?do=Approve&itemid=' .$item["Item_ID"]. '"class="btn btn-info pull-right activate"><i class="fas fa-check"></i> Activate</a>';
                                                        }
                                                    echo '</span>';
                                                echo '</a>';
                                            echo '</li>';
                                        }
                                    }else{
                                        echo '<span class="nice-message">There\'s No Record To Show</span>';

                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Start latest comments -->

                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest  <?php echo $numComments ?>  Comments
                                <span class="toggle-info pull-right ">
                                    <i class='fa fa-plus fa-lg' ></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <?php
                                
                                    //Select all users except admin
                                    $stmt = $con->prepare("SELECT
                                                            comments.*, users.Username AS member
                                                        FROM 
                                                            comments
                                                        INNER JOIN 
                                                            users
                                                        ON
                                                            comments.user_id = users.UserID
                                                        ORDER BY
                                                            c_id DESC
                                                        LIMIT
                                                            $numComments
                                            ");
                                $stmt->execute();
                                $comments = $stmt->fetchAll();
                                if(!empty($comments)){
                                    foreach($comments as $comment){
                                        echo'<div class="comment-box">';
                                            echo '<a class="member-n" href="members.php?do=Edit&userid=' .$comment["user_id"]. '">'.$comment['member'].'</a>';
                                            echo '<p class="member-c">'.$comment['comment'].'</p>';
                                            // echo '<div class="control-c">';
                                            //     echo '<a href="comments.php?do=Edit&comid=' .$comment["c_id"]. '"class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                                            //     echo '<a href="comments.php?do=Delete&comid=' .$comment["c_id"]. '"class="btn btn-danger confirm"><i class="far fa-times-circle"></i> Delete</a>';

                                            //     if($comment["status"]==0){
                                            //         echo '<a href="comments.php?do=Approve&comid=' .$comment["c_id"]. '"class="btn btn-info activate"><i class="fas fa-check"></i> Approve</a>';
                                            //     }
                                            // echo '</div>';
                                        echo '</div>';
                                    }
                                }else{
                                    echo '<span class="nice-message">There\'s No Record To Show</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- End latest Comments -->
            </div>
        </div>

        <?php
        //End dasboard page

        include $tmp . "footer.php";

    }else{
    header("Location: index.php");
    }



?>