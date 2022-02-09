<?php 
    session_start();
    include "init.php";
?>
    <div class='container'>
        <div class='row'>
            <?php
                $catName = getAllFrom('Name', 'categories', "WHERE ID={$_GET['pageid']}", "","ID", "DESC LIMIT 1");
                echo "<h1 class='text-center'>". $catName[0][0]."</h1>";
                $category = intval($_GET['pageid']);
                if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
                    $allItems = getAllFrom("*", "items","WHERE Cat_ID={$category}" , "AND Approve = 1","Item_ID");
                    foreach($allItems as $item){
                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
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
                }else{
                    echo "<div class='alert alert-danger'>You didn't specify ID</div>";
                }
            ?>
        </div>
    </div>
    <?php include $tmp . "footer.php"; ?>