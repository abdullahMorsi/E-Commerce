<?php
    ob_start();
    session_start();
    $pageTitle = "Show Items";

    include "init.php";
        
    //check if GET request itemid is numeric and get the integer value of it
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    // check if user exists in database
    $stmt = $con->prepare("SELECT 
                                items.*, categories.Name AS category_name, users.Username
                            FROM 
                                items
                            INNER JOIN 
                                categories
                            ON
                                categories.ID = items.Cat_ID
                            INNER JOIN
                                users
                            ON
                                users.UserID = items.Member_ID
                            WHERE
                            Item_ID= ?
                            AND
                            Approve =1");
    $stmt-> execute(array($itemid));
    $count = $stmt->rowCount();
    if($count>0){
        $item = $stmt-> fetch();

    
?>

<h1 class='text-center'><?php echo $item['Name']; ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img src="avatar.png" alt="" class="img-responsive img-thumbnail center-block">
        </div>
        <div class="col-md-9 item-info">
            <h2><?php echo $item['Name'] ?></h2>
            <p><?php echo $item['Description'] ?></p>
            <ul class='list-unstyled'>
                <li>
                    <i class='far fa-calendar-alt fa-fw'></i>
                    <span>Added Date</span> : <?php echo $item['Add_Date'] ?>
                </li>
                <li>
                    <i class="far fa-money-bill-alt fa-fw"></i>
                    <span>Price</span> : $<?php echo $item['Price'] ?>
                </li>
                <li>
                    <i class="far fa-building fa-fw"></i>
                    <span>Made In </span> : <?php echo $item['Country_Made'] ?>
                </li>
                <li>
                    <i class='fas fa-tags fa-fw'> </i>
                    <span>Category </span> : <a href='categories.php?pageid=<?php echo $item['Cat_ID'] ?>'><?php echo $item['category_name'] ?></a>
                </li>
                <li>
                    <i class='fa fa-user fa-fw'></i>
                    <span>Added By </span> : <a href='#'><?php echo $item['Username'] ?></a>
                </li>
                <li class='tags-items'>
                    <i class='fa fa-user fa-fw'></i>
                    <span>Tags</span> : 
                    <?php
                    $allTags = explode(',', $item['Tags']);
                    foreach($allTags AS $tag){
                        $tag = str_replace(" ","", $tag);
                        $lowerTag = strtolower($tag);
                        if(!empty($tag)){
                            echo "<a href='tags.php?name={$lowerTag}'>". $tag ."</a>";
                        }
                    }
                    ?>
                    
                </li>
            </ul>  
        </div>
    </div>
    <hr class="custom-hr">
    <?php     if(isset($_SESSION['user'])){ ?>
        <!-- Start add comment -->
    <div class="row">
        <div class="col-md-offset-3">
            <div class="add-comment">
                <h3>Add your comments</h3>
                <form action='<?php echo $_SERVER['PHP_SELF'].'?itemid='.$item['Item_ID'] ?>' method='post'>
                    <textarea name="comment" required></textarea>
                    <input class='btn btn-primary' type="submit" value='Add Comment'>
                </form>
                <?php
                    if($_SERVER['REQUEST_METHOD']=='POST'){

                        $comment    = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                        $userid     = $_SESSION['uid'];
                        $itemid     = $item['Item_ID'];

                        if(!empty($comment)){
                            $stmt = $con->prepare("INSERT INTO 
                                    comments (comment, status, comment_date, item_id, user_id) 
                                    VALUES (:zcomment, 0, now(), :zitemid, :zuserid)");
                            $stmt->execute(array(
                                'zcomment' => $comment,
                                'zitemid' => $itemid,
                                'zuserid' => $userid
                            ));
                            if($stmt){
                                echo '<div class="alert alert-success">Comment added</div>';
                            }
                        }else{
                            echo '<div class="alert alert-danger">You must add comment</div>';
                        }

                    }
                ?>
            </div>
        </div>
    </div>
          <!-- End add comment -->

    <?php }else{
        echo '<a href="login.php">Login</a> or <a href="register.php">Register</a> to comment';
    }
        ?>
    <hr class="custom-hr">
    <?php
        $stmt = $con->prepare(" SELECT
                                    comments.*, users.Username
                                FROM 
                                    comments
                                INNER JOIN 
                                    users
                                ON
                                    comments.user_id = users.UserID
                                WHERE
                                    item_id = ?
                                AND 
                                    Status =1
                                ORDER BY
                                    c_id DESC
        ");

        //execute the statment
        $stmt->execute(array($item['Item_ID']));

        //Assign to variables
        $comments = $stmt->fetchAll();

        foreach($comments as $comment){
        ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img src="avatar.png" alt="" class="img-responsive img-thumbnail img-circle center-block"> 
                        <?php echo $comment['Username'] ?>
                    </div>
                    <div class="col-sm-10">
                        <p class="lead"><?php echo $comment['comment'] ?></p>
                    </div>
                </div>
            </div>
            <hr class='custom-hr'>

        <?php } ?>
    
</div>
<?php
    }else{
        echo '<div class="container">';
            echo '<div class="alert alert-danger">There\'s no such id or this item is waiting approval </div>';
        echo '</div>';
    }
    include $tmp . "footer.php"; 
    ob_end_flush();
?>