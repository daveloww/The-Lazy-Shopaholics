<?php
    require_once 'include/common.php';

    if(isset($_SESSION['loggedIn'])) {
        $dao = New DAO();

        $fav_list = $dao->retrieve_favourites($_SESSION['loggedIn'][1]);

        $cart_count = $dao->count_cart($_SESSION['loggedIn'][1]);
    } else {
        header("Location: index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Font Awesome - for heart and cart icon -->
    <script src="https://kit.fontawesome.com/996cf4158f.js" crossorigin="anonymous"></script>

    <!-- Animation.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <title>Your Favourites</title>

    <style>
        @media(max-width: 768px) {
            #main_msg {
                font-size: 30px !important;
            }

            #sub_msg, #reco {
                font-size: 18px !important;
            }
        }
    </style>

</head>
<body>
    <!-- Navigation bar-->
    <nav class="navbar fixed-top navbar-expand-md navbar-light" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" width="50" height="40" class="d-inline-block align-top" alt="" loading="lazy">
            </a>
            <a href="index.php">
                <span class="navbar-text text-body" style="font-size: 20px;">Lazy Shopaholics</span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
            </button>
                      
            <div class="collapse navbar-collapse justify-content-end" id="navbarTogglerDemo02">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="favourites.php">Favourites <i class="far fa-heart"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart(<?= $cart_count ?>) <i class="fas fa-shopping-cart"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Account
                        </a>
                        <div class="dropdown-menu text-center" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="change_pass.php">Change Password</a>
                            <a class="dropdown-item" href="logout.php">Log out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
 
    <!--Welcome message & Search Bar-->
    <div class="container text-center" style="margin-top: 100px;">

    <?php
        // Add to fav errors
        if (isset($_SESSION["add_fav_error"])) {
            if ($_SESSION["add_fav_error"] == "duplicate") {
                $msg = "<i class='far fa-angry'></i> <b>Excuse me.</b> Item was favourited previously. Don't be too greedy!";
            } else {
                $msg = "<b>Opps! Database error bug.</b> <i class='fas fa-bug'></i> Go back and try adding again.";
            }
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    $msg
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            
            unset($_SESSION["add_fav_error"]);
        }


        // Add to cart errors
        if (isset($_SESSION["fav_cart_error"])) {
            $msg = "<i class='fas fa-exclamation-circle'></i> <b>Unable to add to cart.</b> Item exists in your cart already!";
            
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    $msg
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            
            unset($_SESSION["fav_cart_error"]);
        }

        
        // Delete cart errors (account user)
        if (isset($_SESSION["delete_fav_error"])) {
            $msg = "<i class='fas fa-bug'></i> <b>Opps! Database error bug.</b> Refresh and try removing again.";
            
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    $msg
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            
            unset($_SESSION["delete_fav_error"]);
        }
    ?> 

        <span id="main_msg" class="text-center lead d-block" style="margin-bottom: 15px; font-size: 40px;"><i class="far fa-heart"></i><br>Your Favourite Items</span>
    </div>

    <!-- Favourite Items -->
    <div class="container" style="margin-top: 50px; margin-bottom: 50px;">
        
        <table class="table table-striped table-hover table-bordered table-sm">

            <?php
                if (!$fav_list) {
                    echo 
                    "<hr>
                    <h1 class='text-center animate__animated animate__heartBeat'>Empty? Go find something you like <i class='far fa-smile-wink'></i></h1>";
                } else {
                    echo 
                    "<thead>
                        <tr>
                            <th scope='col'>Product</th>
                            <th scope='col'>Price</th>
                            <th scope='col'>Actions</th>
                        </tr>
                    </thead>
                    
                    <tbody>";

                    foreach ($fav_list as $product) {
                        $price = number_format($product->getPrice(), 2);

                        $details = "Click <a href='{$product->getUrl()}' target='_blank'>here</a> to view product description";

                        echo
                        "<tr>
                            <td class='pt-2'>
                                <img src='{$product->getPhoto()}' width='80' height='80' class='float-left mr-2'>
                                <strong>{$product->getName()} <span class='badge badge-pill badge-warning'>{$product->getEcommerce()}</span></strong> <br><br>
                                <i>$details</i>
                            </td> 
        
                            <td class='align-middle'>S$$price</td>
        
                            <td class='align-middle'>
                                <a href='favourites_process.php?type=delete&id={$product->getId()}' class='btn btn-danger btn-sm mb-2'>Remove</a> <a href='favourites_process.php?type=cart&id={$product->getId()}&pid={$product->getPid()}&ecom={$product->getEcommerce()}' class='btn btn-primary btn-sm mb-2'>Move to cart <i class='fas fa-shopping-cart'></i></a>
                            </td>
                        </tr>";
                    }

                    echo "</tbody>";
                }
            ?>
        </table>

    </div>
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>