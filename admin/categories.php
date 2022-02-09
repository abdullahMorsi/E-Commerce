
<?php

/*
===========================
== Categories page
===========================

*/

session_start();
echo $_SESSION['Username'];

$pageTitle = 'Categories';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == "Manage"){ //Manage page 

        $sort = 'asc';

        $sort_array = array('asc', 'desc');

        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){
            $sort = $_GET['sort'];
        }

        $stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent =0 ORDER BY Ordering $sort") ;

        $stmt2->execute();

        $categories = $stmt2->fetchAll();
        
        if(!empty($categories)){
        ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class='container categories'>
            <div class="panel panel-default">
                <div class="panel-heading">
                <i class='fa fa-edit'></i> Manage Categories

                    <div class="option pull-right">
                        <i class='fa fa-sort'></i> Ordering: [
                        <a class="<?php if($sort=="asc"){echo 'active';} ?>" href="?sort=asc">ASC</a> |
                        <a class="<?php if($sort=="desc"){echo 'active';} ?>" href="?sort=desc">DESC</a> ]
                        <i class='fa fa-eye'></i> View: [
                        <span class="active" data-view="full">Full</span> |
                        <span data-view="classic">Classic</span> ]
                        
                    </div>

                </div>
                    <div class="panel-body">
                        <?php
                            foreach($categories as $cat){
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                    echo "<a href='categories.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                    echo "<a href='categories.php?do=Delete&catid=". $cat['ID'] ."' class=' confirm btn btn-xs btn-danger'><i class='fas fa-times'></i> Delete</a>";
                                    echo "</div>";
                                    echo "<h3>" . $cat['Name'] . '</h3>';
                                    echo "<div class='full-view'>";
                                        echo "<p>"; if( $cat['Description']==""){echo "This category has no description";} else{echo  $cat['Description'];} echo '</p>';
                                        if($cat['Visibility']==1){echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';};
                                        if($cat['Allow_Comment']==1){echo '<span class="commenting"><i class="fas fa-comment-slash"></i> Comment Disabled</span>';};
                                        if($cat['Allow_Ads']==1){echo '<span class="advertises"><i class="fa fa-times"></i> Ads Disabled</span>';};
                                    echo "</div>";
                                    //Get Child Category
                                    $childCats = getAllFrom("*", "categories", "WHERE Parent ={$cat['ID']}", "", "ID", "ASC") ;
                                    if(!empty($childCats)){
                                        echo'<h4 class="child-head">Child Categories</h4>';
                                        echo '<ul class="list-unstyled child-cats">';
                                        foreach($childCats as $c){
                                            echo"<li class='child-link'>
                                            <a href='categories.php?do=Edit&catid=". $c['ID'] ."'>".$c['Name']."</a>
                                            <a href='categories.php?do=Delete&catid=". $c['ID'] ."' class='show-delete confirm '>Delete</a>
                                            </li>";
                                        }
                                        echo'</ul>';
                                    }
                                echo "</div>";
                                echo "<hr>";

                                

                
                            }
                        ?>

                    </div>
            </div>
            <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add new category</a>
        </div>
        <?php
            }else{
                echo "<div class='container'>";
                    echo '<div class="nice-message">There\'s No Records To Show </div>';
                    echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Category</a>';

                echo "</div>";
            }
            ?>
        <?php
    }elseif($do == 'Add'){?>

        <h1 class="text-center">Add Category</h1>
        <div class="container" >
            <form class="form-horizontal" action="?do=Insert" method="POST">
        <!-- Start Category field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Category </label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of The Category">
                    </div>
                </div>
            
        <!-- End Category field -->
        <!-- Start Description field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Description </label>
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="description" class="form-control"placeholder="Describe The Category">
                    </div>
                </div>

        <!-- End Description field -->
        <!-- Start Ordering field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Ordering </label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="ordering" class="form-control"placeholder="Number To Arrange The Categories">
                    </div>
                </div>
        <!-- End Ordering field -->
        <!-- Start Category type  field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Parent? </label>
                    <div class="col-sm-10 col-md-6">
                        <select name='parent'>
                            <option value='0'>None</option>
                            <?php
                                $allCats = getAllFrom('*', 'categories', "WHERE Parent =0",'','ID','ASC');
                                foreach($allCats as $c){
                                    echo'<option value="'.$c['ID'].'">'.$c['Name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
        <!-- End Category type field -->
        <!-- Start Visibility field -->
                <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">visible </label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="visible-yes" type="radio" name="visibility" value="0" checked/>
                            <label for="visible-yes">Yes</label>
                        </div>
                        <div>
                            <input id="visible-no" type="radio" name="visibility" value="1"/>
                            <label for="visible-no">No</label>
                        </div>
                    </div>
                </div>
        <!-- End Visibility field -->
        <!-- Start Commenting field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="comment-yes" type="radio" name="commenting" value="0" checked/>
                            <label for="comment-yes">Yes</label>
                        </div>
                        <div>
                            <input id="comment-no" type="radio" name="commenting" value="1"/>
                            <label for="comment-no">No</label>
                        </div>
                    </div>
                </div>
        <!-- End Commenting field -->
        <!-- Start Ads field -->
        <div class="form-group form-group-lg" >
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="Ads-yes" type="radio" name="ads" value="0" checked/>
                            <label for="Ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="Ads-no" type="radio" name="ads" value="1"/>
                            <label for="Ads-no">No</label>
                        </div>
                    </div>
            </div>
        <!-- End Ads field -->
        <!-- Start Submit field -->
                <div class="form-group form-group-lg" >
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="Submit" value="Add Category" class="btn btn-primary btn-lg">
                    </div>
                </div>
            </form>
        </div>

        <?php
        }elseif($do == 'Insert'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>"; 
                    
                //get variables from the form
                $name           = $_POST["name"];
                $description    = $_POST["description"];
                $parent         = $_POST["parent"];
                $order          = $_POST["ordering"];
                $visibility     = $_POST['visibility'];
                $comment        = $_POST['commenting'];
                $ads            = $_POST['ads'];



                    //check if user exist in database
                    $check = checkItem("Name", "categories", $name);

                    if($check ==1){

                        $theMsg = "<div class='alert alert-danger'>Sorry, this category exists</div>";
                        redirectHome($theMsg, "back");

                    }else{

                        //Insert user info in database
                        $stmt= $con->prepare("INSERT INTO
                                            categories(Name, Description, Ordering, Parent, Visibility, Allow_comment, Allow_Ads) 
                                            VALUES(:zname, :zdescription, :zorder, :zparent, :zvisible, :zcomment, :zads)");

                        $stmt->execute(array(
                        
                            'zname'         => $name,
                            'zdescription'  => $description,
                            'zparent'       => $parent,
                            'zorder'        => $order,
                            'zvisible'      => $visibility,
                            'zcomment'      => $comment,
                            'zads'          => $ads
                        
                        ));

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Row Inserted</div>";
                        redirectHome($theMsg, "back");
                    }
                
                
            }else{
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-danger'>Sorry, You can't browse this page directly.</div>";
                    
                    redirectHome($theMsg);
                    echo"</div>";

            }

            echo "</div>";

        }elseif($do=="Edit"){  //edit page

            //check if GET request catid is numeric and get the integer value of it
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // check if user exists in database
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
            //execute query
            $stmt-> execute(array($catid));
            //fetch the data
            $cat = $stmt-> fetch();
            //the row count
            $count = $stmt->rowCount();
            //if there is such id show the form
            if($count > 0){ ?>
                <h1 class="text-center">Edit Category</h1>
                <div class="container" >
                    <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $catid; ?>">

                <!-- Start Category field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Category </label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Category" value="<?php echo $cat['Name'] ?>">
                            </div>
                        </div>
                    
                <!-- End Category field -->
                <!-- Start Description field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Description </label>
                            <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" placeholder="Describe The Category" value="<?php echo $cat['Description'] ?>">
                            </div>
                        </div>

                <!-- End Description field -->
                
                <!-- Start Ordering field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Ordering </label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering'] ?>">
                            </div>
                        </div>
                <!-- End Ordering field -->
                <!-- Start Category type  field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Parent? </label>
                            <div class="col-sm-10 col-md-6">
                                <select name='parent'>
                                    <option value='0'>None</option>
                                    <?php
                                        $allCats = getAllFrom('*', 'categories', "WHERE Parent =0",'','ID','ASC');
                                        foreach($allCats as $c){
                                            echo'<option value="'.$c['ID'].'"';
                                                if($cat['Parent']==$c['ID']){echo ' selected';}
                                            echo '>'.$c['Name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                <!-- End Category type field -->
                <!-- Start Visibility field -->
                        <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">visible </label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="visible-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility']==0){echo 'checked';} ?> />
                                    <label for="visible-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="visible-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility']==1){echo 'checked';} ?> />
                                    <label for="visible-no">No</label>
                                </div>
                            </div>
                        </div>
                <!-- End Visibility field -->
                <!-- Start Commenting field -->
                <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="comment-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment']==0){echo 'checked';} ?>/>
                                    <label for="comment-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="comment-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment']==1){echo 'checked';} ?>/>
                                    <label for="comment-no">No</label>
                                </div>
                            </div>
                        </div>
                <!-- End Commenting field -->
                <!-- Start Ads field -->
                <div class="form-group form-group-lg" >
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="Ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads']==0){echo 'checked';} ?>/>
                                    <label for="Ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="Ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads']==1){echo 'checked';} ?>/>
                                    <label for="Ads-no">No</label>
                                </div>
                            </div>
                    </div>
                <!-- End Ads field -->
                <!-- Start Submit field -->
                        <div class="form-group form-group-lg" >
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="Submit" value="Save" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>
                </div>

        <?php
    }else{

            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>There Is No Such ID</div>";
            redirectHome($theMsg);
            echo "</div>";

        }

    }
    elseif($do=="Update"){

        echo "<h1 class='text-center'>Update Category</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD']== 'POST'){

            //get variables from the form
            $id             = $_POST["catid"];
            $name           = $_POST["name"];
            $description    = $_POST["description"];
            $order          = $_POST["ordering"];
            $parent             = $_POST["parent"];
            $comment        = $_POST["commenting"];
            $visibility     = $_POST["visibility"];
            $ads            = $_POST["ads"];

            
            //Update database with info
            $stmt= $con->prepare("UPDATE 
                                    categories
                                    SET
                                    Name = ?, 
                                    Description = ?, 
                                    Ordering = ?, 
                                    Parent=?,
                                    Visibility = ?, 
                                    Allow_Comment = ?, 
                                    Allow_Ads=? 
                                    WHERE 
                                    ID = ?");
            $stmt->execute(array($name, $description, $order, $parent, $comment, $visibility, $ads, $id));

            $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows affected</div>";
            redirectHome($theMsg, "back");

        }else{

            $theMsg = "<div class='alert alert-danger'>Sorry, You can't browse this page directly.</div>";
            redirectHome($theMsg);

        }

        echo "</div>";
        
    
    }elseif($do =="Delete"){


        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

            //check if GET request catid is numeric and get the integer value of it
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // check if user exists in database
            $check = checkItem("ID", "categories", $catid);

            if($check > 0){
                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
                $stmt->bindParam(":zid", $catid);
                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Rows Deleted</div>";
                redirectHome($theMsg, "back");

            }else{
                $theMsg = "this id does not exist";
                redirectHome($theMsg);

            }
        echo"</div>";
    }elseif($do == "Activate"){
    
    }

    include $tmp . 'footer.php';

}else{

    header('Location: index.php');
    exit();

}


?>