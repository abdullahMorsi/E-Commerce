<?php 
    session_start();
    include "init.php";
?>
    <div class='container'>
        <div class='row'>
            <?php
                // $category = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                if(isset($_GET['name'])){
                    $tag = $_GET['name'];
                    echo "<h1 class='text-center'>".$tag."</h1>";

                    $tagItems = getAllFrom("*", "items","WHERE Tags LIKE '%$tag%'" , "AND Approve = 1","Item_ID");
                    foreach($tagItems as $item){
                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                                echo '<span class="price-tag">'.$item['Price'].'</span>';
                                echo '<img class="img-responsive" src="avatar.png" alt=""/>';
                                echo '<div class="caption">';
                                    echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">'.$item['Name'].'</a></h3>';
                                    echo '<p>'.$item['Description'].'</p>';
                                    echo '<div class="date">'.$item['Add_Date'].'</div>';

                                echo'</div>';
                            echo'</div>';
                        echo'</div>';
                    }
                }else{
                    echo "<div class='alert alert-danger'>You didn't specify Tag name</div>";
                }
            ?>
        </div>
    </div>
    <?php include $tmp . "footer.php"; ?>