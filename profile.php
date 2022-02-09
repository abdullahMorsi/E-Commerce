<?php
    ob_start();
    session_start();
    $pageTitle = "Profile";

    include "init.php";
        
    if(isset($_SESSION['user'])){

        $getUser = $con->prepare('SELECT * From users WHERE Username = ?');
        
        $getUser->execute(array($sessionUser));

        $info = $getUser->fetch();

        $userid = $info['UserID'];

    
?>

<h1 class='text-center'>My Profile</h1>

<div class='information block'>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                My Information
            </div>
            <div class="panel-body">
                <ul class='list-unstyled'>
                    <li>
                        <i class='fa fa-unlock-alt fa-fw'></i>
                        <span>Name:</span> <?php echo $info['Username'] ?>
                    </li>
                    <li>
                        <i class="far fa-envelope-open fa-fw"></i>
                        <span>Email:</span> <?php echo $info['Email'] ?>
                    </li>
                    <li>
                        <i class='fa fa-user fa-fw'></i>
                        <span>Full Name:</span> <?php echo $info['FullName'] ?>
                    </li>
                    <li>
                        <i class="far fa-calendar-alt fa-fw"></i>
                        <span>Register Date:</span> <?php echo $info['Date'] ?>
                    </li>
                    <li>
                        <i class='fa fa-tags fa-fw'></i>
                        <span>Fav Category:</span> <?php echo $info['Username'] ?>
                    </li>
                </ul>
                <a href="#" class="btn btn-default">Edit My Information</a>
            </div>
        </div>
    </div>
</div>
<div id='my-items' class='my-advertises block'>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                My Items
            </div>
            <div class="panel-body">
            <?php
                $allitems = getAllFrom("*", "items", "WHERE Member_ID=$userid", "", "Item_ID");
                if(!empty($allitems)){
                    echo '<div class="row">';
                    
                    foreach($allitems as $item){

                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                                if($item['Approve']==0){
                                    echo '<span class="approve-status">Waiting Approval</span>';
                                }
                                echo '<span class="price-tag">$'.$item['Price'].'</span>';
                                echo '<img class="img-responsive" src="avatar.png" alt=""/>';
                                echo '<div class="caption">';
                                    echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">'.$item['Name'].'</a></h3>';
                                    echo '<p>'.$item['Description'].'</p>';
                                    echo '<div class="date">'.$item['Add_Date'].'</div>';
                                echo'</div>';
                            echo'</div>';
                        echo'</div>';
                    }
                    echo '</div>';
                }else{
                    echo 'Sorry, There\'re no ads to show, Create <a href="newad.php">New Ad</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class='my-comments block'>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                My Comments
            </div>
            <div class="panel-body">
            <?php

                $myComments = getAllFrom("comment", "comments", "WHERE user_id=$userid", "", "c_id");
                
                if(!empty($myComments)){

                    foreach($myComments as $comment){
                        echo '<p>'.$comment['comment'].'</p>';
                    }

                }else{
                    echo 'There\'re no comments';
                }

            ?>
            </div>
        </div>
    </div>
</div>

<?php
    }else{
        header('Location:login.php');
        exit();

    }
    include $tmp . "footer.php"; 
    ob_end_flush();
?>