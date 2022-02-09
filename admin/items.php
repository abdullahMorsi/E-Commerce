
<?php

/*
===========================
== template page
===========================

*/
ob_start();

session_start();

$pageTitle = 'Items';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == "Manage"){  //Manage page 

        
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
                                    ORDER BY
                                    Item_ID DESC
        ");
        
        //execute the statment
        $stmt->execute();

        //Assign to variables
        $items = $stmt->fetchAll();
        if(!empty($items)){
    ?>
    
        <h1 class="text-center">Manage Items</h1>
        <div class="container" >
            <div class="table-responsive">
                <table class="table-main text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Member</td>
                        <td>control</td>
                            
                    </tr>

                    <?php

                        foreach($items as $item){
                            echo "<tr>";
                                echo"<td>" . $item["Item_ID"] . "</td>";    
                                echo"<td>" . $item["Name"] . "</td>";    
                                echo"<td>" . $item["Description"] . "</td>";    
                                echo"<td>" . $item["Price"] . "</td>";    
                                echo"<td>" . $item["Add_Date"] . "</td>";    
                                echo"<td>" . $item["category_name"] . "</td>";    
                                echo"<td>" . $item["Username"] . "</td>";    
                                echo'<td>
                                    <a href="items.php?do=Edit&itemid=' .$item["Item_ID"]. '"class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="items.php?do=Delete&itemid=' .$item["Item_ID"]. '"class="btn btn-danger confirm"><i class="far fa-times-circle"></i> Delete</a>';

                                    if($item["Approve"]==0){
                                        echo '<a href="items.php?do=Approve&itemid=' .$item["Item_ID"]. '"class="btn btn-info activate"><i class="fas fa-check"></i> Approve</a>';
                                    }
                                    
                                echo '</td>';

                            
                            echo"<tr>";
                        }

                    ?>

                </table>
            </div>
            <a href='items.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>
        </div>
        <?php
            }else{
                echo "<div class='container'>";
                    echo '<div class="nice-message">There\'s No Records To Show </div>';
                    echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>';

                echo "</div>";
            }
            ?>
        <?php

    }elseif($do == 'Add'){
        ?>

        <h1 class="text-center">Add New Item</h1>
        <div class="container" >
            <form class="form-horizontal" action="?do=Insert" method="POST">
        <!-- Start Name field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Item">
                    </div>
                </div>
            
        <!-- End Name field -->
        <!-- Start Description field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" required="required" placeholder="Description Of The Item">
                    </div>
                </div>
            
        <!-- End Description field -->
        <!-- Start Price field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control" required="required" placeholder="Price Of The Item">
                    </div>
                </div>
            
        <!-- End Price field -->
        <!-- Start Country field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made">
                    </div>
                </div>
            
        <!-- End Country field -->
        <!-- Start status field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>
            
        <!-- End status field -->
        <!-- Start members field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">...</option>
                            <?php
                                $allMembers = getAllFrom("*", "users", "", "", 'UserID');
                                foreach($allMembers as $user){
                                    echo "<option value='".$user[UserID]."'>".$user[Username]."</option>";
                                }

                            ?>
                            
                        </select>
                    </div>
                </div>
            
        <!-- End members field -->
        <!-- Start Category field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
                                $allcats = getAllFrom("*", "categories", "WHERE Parent=0", "", 'ID');
                                foreach($allcats as $cat){
                                    echo "<option value='".$cat[ID]."'>".$cat[Name]."</option>";
                                    $childCats = getAllFrom("*", "categories", "WHERE Parent={$cat['ID']}", "", 'ID');
                                    foreach($childCats as $child){
                                        echo "<option vlaue='".$child['ID']."'>---".$child['Name']."</option>";
                                    }
                                    
                                }

                            ?>
                            
                        </select>
                    </div>
                </div>
            
        <!-- End Category field -->
        <!-- Start Tags field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Tag</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="tags" class="form-control" placeholder="Separate tags with comma (,)">
                    </div>
                </div>
            
        <!-- End Tags field -->
        <!-- Start Submit field -->
                <div class="form-group form-group-lg" >
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="Submit" value="Add Item" class="btn btn-primary btn-sm">
                    </div>
                </div>
            </form>
        </div>
        <!-- End Submit field -->

        <?php

    }elseif($do == 'Insert'){
        

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo "<h1 class='text-center'>Insert Item</h1>";
            echo "<div class='container'>"; 
                
            //get variables from the form
            $name           = $_POST["name"];
            $description    = $_POST["description"];
            $price          = $_POST["price"];
            $country        = $_POST['country'];
            $status         = $_POST['status'];
            $member         = $_POST['member'];
            $cat            = $_POST['category'];
            $tags           = $_POST['tags'];

            //validate the form
            $formErrors = array();

            if(empty($name)){
                $formErrors[] = "Name can't be <strong>empty</strong>";
            }if(empty($description)){
                $formErrors[] = "Description can't be <strong>empty</strong>";
            }if(empty($price)){
                $formErrors[] = "Price can't be <strong>empty</strong>";
            }if(empty($country)){
                $formErrors[] = "Country can't be <strong>empty</strong>";
            }if($status==0){
                $formErrors[] = "You Must Choose <strong>Status</strong>";
            }if($member==0){
                $formErrors[] = "You Must Choose <strong>Member</strong>";
            }if($cat==0){
                $formErrors[] = "You Must Choose <strong>Category</strong>";
            }
            
            foreach($formErrors as $error){
                echo "<div class='alert alert-danger'>" . $error . "</div>";

            }

            //if there is no errors procced to update operation
            if(empty($formErrors)){

            

                    //Insert user info in database
                    $stmt= $con->prepare("INSERT INTO
                                        items(Name, Description, Price, Country_Made, Status ,Add_Date, Cat_ID, Member_ID, Tags) 
                                        VALUES(:zname, :zdescription, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");

                    $stmt->execute(array(
                    
                        'zname'         => $name,
                        'zdescription'  => $description,
                        'zprice'        => $price,
                        'zstatus'       => $status,
                        'zcountry'      => $country,
                        'zcat'          => $cat,
                        'zmember'       => $member,
                        'ztags'          => $tags
                    ));

                    $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Row Inserted</div>";
                    redirectHome($theMsg, "back");

            }
            
        }else{
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>Sorry, You can't browse this page directly.</div>";
                
                redirectHome($theMsg);
                echo"</div>";

        }

        echo "</div>";
    }elseif($do=="Edit"){    //edit page

        //check if GET request itemid is numeric and get the integer value of it
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // check if user exists in database
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID= ?");
        $stmt-> execute(array($itemid));
        $item = $stmt-> fetch();
        $count = $stmt->rowCount();

        if($count > 0){ ?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container" >
            <form class="form-horizontal" action="?do=Update" method="POST">

            <input type="hidden" name='itemid' value='<?php echo $itemid ?>'/>
        <!-- Start Name field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input  type="text" 
                                name="name" 
                                class="form-control" 
                                required="required" 
                                placeholder="Name Of The Item" 
                                value="<?php echo $item['Name'] ?>">
                    </div>
                </div>
            
        <!-- End Name field -->
        <!-- Start Description field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" required="required" placeholder="Description Of The Item"value="<?php echo $item['Description'] ?>">
                    </div>
                </div>
            
        <!-- End Description field -->
        <!-- Start Price field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control" required="required" placeholder="Price Of The Item"value="<?php echo $item['Price'] ?>">
                    </div>
                </div>
            
        <!-- End Price field -->
        <!-- Start Country field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made"value="<?php echo $item['Country_Made'] ?>">
                    </div>
                </div>
            
        <!-- End Country field -->
        <!-- Start status field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="1" <?php if($item['Status']==1){echo 'selected';} ?> >New</option>
                            <option value="2" <?php if($item['Status']==2){echo 'selected';} ?>>Like New</option>
                            <option value="3" <?php if($item['Status']==3){echo 'selected';} ?>>Used</option>
                            <option value="4" <?php if($item['Status']==4){echo 'selected';} ?>>Very Old</option>
                        </select>
                    </div>
                </div>
            
        <!-- End status field -->
        <!-- Start members field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <?php
                                $allMembers2 = getAllFrom("*", "users", "", "", 'UserID');
                                foreach($allMembers2 as $user){
                                    echo "<option value='".$user[UserID]."'";
                                    if($item['Member_ID']==$user["UserID"] ){echo 'selected';}
                                    echo ">".$user['Username']."</option>";
                                }

                            ?>
                            
                        </select>
                    </div>
                </div>
            
        <!-- End members field -->
        <!-- Start Category field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <?php
                                $allcats2 = getAllFrom("*", "categories", "", "", 'ID');
                                foreach($allcats2 as $cat){
                                    echo "<option value='".$cat[ID]."'";
                                    if($item['Cat_ID']==$cat['ID']){echo 'selected';}
                                    echo ">".$cat['Name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
            
        <!-- End Category field -->
        <!-- Start Tags field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Tag</label>
                    <div class="col-sm-10 col-md-6">
                        <input  
                                type="text"
                                name="tags"
                                class="form-control"
                                placeholder="Separate tags with comma (,)"
                                value="<?php echo $item['Tags'] ?>">
                    </div>
                </div>
            
        <!-- End Tags field -->
        <!-- Start Submit field -->
                <div class="form-group form-group-lg" >
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="Submit" value="Save Item" class="btn btn-primary btn-sm">
                    </div>
                </div>
            </form>

            <?php

            //Select all users except admin
            $stmt = $con->prepare(" SELECT
                                        comments.*, users.Username AS members
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        users
                                    ON
                                        comments.user_id = users.UserID
                                    WHERE
                                        item_id = ?
                                        ");
            
            //execute the statment
            $stmt->execute(array($itemid));

            //Assign to variables
            $rows = $stmt->fetchAll();

            if(!empty($rows)){
            ?>
        
            <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
                <div class="table-responsive">
                    <table class="table-main text-center table table-bordered">
                        <tr>
                            <td>Comment</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                                
                        </tr>

                        <?php

                            foreach($rows as $row){
                                echo "<tr>";
                                    echo"<td>" . $row["comment"] . "</td>";    
                                    echo"<td>" . $row["members"] . "</td>";    
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
                <?php } ?>
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

    elseif($do=="Update"){    //start Updtae
            
        echo "<h1 class='text-center'>Update Item</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD']== 'POST'){

            //get variables from the form
            $id             = $_POST["itemid"];
            $name           = $_POST["name"];
            $description    = $_POST["description"];
            $price          = $_POST["price"];
            $country        = $_POST["country"];
            $status         = $_POST["status"];
            $member         = $_POST["member"];
            $cat            = $_POST["category"];
            $tags           = $_POST["tags"];

            
            
            //validate the form
            $formErrors = array();

            if(empty($name)){
                $formErrors[] = "Name can't be <strong>empty</strong>";
            }if(empty($description)){
                $formErrors[] = "Description can't be <strong>empty</strong>";
            }if(empty($price)){
                $formErrors[] = "Price can't be <strong>empty</strong>";
            }if(empty($country)){
                $formErrors[] = "Country can't be <strong>empty</strong>";
            }if($status==0){
                $formErrors[] = "You Must Choose <strong>Status</strong>";
            }if($member==0){
                $formErrors[] = "You Must Choose <strong>Member</strong>";
            }if($cat==0){
                $formErrors[] = "You Must Choose <strong>Category</strong>";
            }
            
            foreach($formErrors as $error){
                echo "<div class='alert alert-danger'>" . $error . "</div>";

            }

            //if there is no errors procced to update operation
            if(empty($formErrors)){
                //Update database with info
                $stmt= $con->prepare("UPDATE items 
                                        SET 
                                            Name = ?, Description = ?, Price = ?, Country_Made = ?, Status=?, Cat_ID=?, Member_ID=?, Tags=?
                                        WHERE 
                                            Item_ID = ?");
                $stmt->execute(array($name, $description, $price, $country, $status, $cat, $member, $tags, $id));

                $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows affected</div>";
                redirectHome($theMsg, "back");
            }

        }else{

            $theMsg = "<div class='alert alert-danger'>Sorry, You can't browse this page directly.</div>";
            redirectHome($theMsg);

        }

        echo "</div>";
        
    
    }elseif($do =="Delete"){//start delete

        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";

            //check if GET request itemid is numeric and get the integer value of it
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // check if user exists in database
            $check = checkItem("Item_ID", "items", $itemid);

            if($check > 0){
                $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
                $stmt->bindParam(":zid", $itemid);
                $stmt->execute();


                $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows Deleted</div>";
                redirectHome($theMsg, "back");

            }else{
                $theMsg = "this id does not exist";
                redirectHome($theMsg);

            }
        echo"</div>";
        
        

    }elseif($do == "Approve"){  //start approve
        echo "<h1 class='text-center'>Approve Item</h1>";
        echo "<div class='container'>";

            //check if GET request item id is numeric and get the integer value of it
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // check if user exists in database
            $check = checkItem("Item_ID", "items", $itemid);

            if($check > 0){
                $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = :zid");
                $stmt->bindParam(":zid", $itemid);
                $stmt->execute();


                $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows Updated</div>";
                redirectHome($theMsg, "back");

            }else{
                $theMsg = "this id does not exist";
                redirectHome($theMsg);

            }
        echo"</div>";
    
    }

    include $tmp . 'footer.php';

}else{

    header('Location: index.php');
    exit();

}

ob_end_flush();

?>