<?php
    session_start();
    $pageTitle = "Create New Ad";

    include "init.php";
        
    if(isset($_SESSION['user'])){


        if($_SERVER['REQUEST_METHOD']=='POST'){

            $formErrors = array();

            $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            if(strlen($name)<4){
                $formErrors[] = 'Item title must be at least 4 characters';
            }
            if(strlen($desc)<10){
                $formErrors[] = 'Item description must be at least 10 characters';
            }
            if(strlen($country)<2){
                $formErrors[] = 'Item country must be at least 2 characters';
            }
            if(empty($price)){
                $formErrors[] = 'Item price must be not empty';
            }
            if(empty($status)){
                $formErrors[] = 'Item status must be not empty';
            }
            if(empty($category)){
                $formErrors[] = 'Item category must be not empty';
            }
            //if there is no errors procced to update operation
            if(empty($formErrors)){

            

                //Insert user info in database
                $stmt= $con->prepare("INSERT INTO
                                    items(Name, Description, Price, Country_Made, Status ,Add_Date, Cat_ID, Member_ID, Tags) 
                                    VALUES(:zname, :zdescription, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");

                $stmt->execute(array(
                
                    'zname'         => $name,
                    'zdescription'  => $desc,
                    'zprice'        => $price,
                    'zstatus'       => $status,
                    'zcountry'      => $country,
                    'zcat'          => $category,
                    'ztags'          => $tags,
                    'zmember'       => $_SESSION['uid']
                ));

                if($stmt){
                    $successMsg = 'Item Added successfully';
                    ;
                }
                // $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . " Row Inserted</div>";
                // redirectHome($theMsg, "back");

            }

            
        }

    
?>

<h1 class='text-center'><?php echo $pageTitle; ?></h1>

<div class='information block'>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo $pageTitle; ?>
            </div>
            <div class="panel-body">
                <div class='row'>
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <!-- Start Name field -->
                            <div class="form-group form-group-lg" >
                                <label class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-10 col-md-9">
                                    <input pattern='.{4,}' title='This field requires at least 4 charcters' type="text" name="name" class="form-control live" data-class='.live-title' required="required" placeholder="Name Of The Item">
                                </div>
                            </div>
                            
                            <!-- End Name field -->
                            <!-- Start Description field -->
                            <div class="form-group form-group-lg" >
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-10 col-md-9">
                                    <input pattern='.{10,}' title='This field requires at least 10 charcters' type="text" name="description"  class="form-control live" data-class='.live-description' required="required" placeholder="Description Of The Item">
                                </div>
                            </div>
                                
                            <!-- End Description field -->
                            <!-- Start Price field -->
                            <div class="form-group form-group-lg" >
                                <label class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="price" class="form-control live" data-class='.live-price' required="required" placeholder="Price Of The Item">
                                </div>
                            </div>
                                
                            <!-- End Price field -->
                            <!-- Start Country field -->
                            <div class="form-group form-group-lg" >
                                <label class="col-sm-3 control-label">Country</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made">
                                </div>
                            </div>
                                
                            <!-- End Country field -->
                            <!-- Start status field -->
                            <div class="form-group form-group-lg" >
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-10 col-md-9">
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
                            
                            <!-- Start Category field -->
                            <div class="form-group form-group-lg" >
                                <label class="col-sm-3 control-label">Category</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="category">
                                        <option value="0">...</option>
                                        <?php

                                          
                                            $cats = getAllFrom('*', 'categories', '', '', 'ID');
                                            foreach($cats as $cat){
                                                echo "<option value='".$cat[ID]."'>".$cat[Name]."</option>";
                                            }

                                        ?>
                                        
                                    </select>
                                </div>
                            </div>
                
                            <!-- End Category field -->
                            <!-- Start Tags field -->
                            <div class="form-group form-group-lg" >
                                        <label class="col-sm-3 control-label">Tag</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="tags" class="form-control" placeholder="Separate tags with comma (,)">
                                        </div>
                                    </div>
                                
                            <!-- End Tags field -->
                            <!-- Start Submit field -->
                                <div class="form-group form-group-lg" >
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <input type="Submit" value="Add Item" class="btn btn-primary btn-sm">
                                    </div>
                                </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag ">$<span class='live-price'>0</span>
                            </span>
                            <img class="img-responsive " src="avatar.png" alt=""/>
                            <div class="caption">
                                <h3 class='live-title'>title</h3>
                                <p class='live-description'>Description</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if(!empty($formErrors)){
                            foreach($formErrors as $error){
                                echo '<div class="alert alert-danger">'.$error .'</div></br>';
                            }
                    }
                    if(isset($successMsg)){
                        echo '<div class="alert alert-success">'.$successMsg.'</div>';
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
?>