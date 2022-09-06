<?php
    require_once 'include/common.php';

    if (!isset($_SESSION['loggedIn'])) {
        
        header("Location: index.php");
        exit;
    } else {
        $dao = New DAO();
        $cart_count = $dao->count_cart($_SESSION['loggedIn'][1]);
    }

    if (isset($_SESSION['invalid_old_pwd'])) {
        $error = "Incorrect Old Password";

        unset($_SESSION['invalid_old_pwd']);
    }

    if (isset($_SESSION['pwd_mismatch'])) {
        $error = "New Passwords do not match";

        unset($_SESSION['pwd_mismatch']);
    }

    if (isset($_SESSION['success'])) {
        $success = "Password has been changed successfully!";

        unset($_SESSION['success']);
    }

    if (isset($_SESSION['fail'])) {
        $fail = "Something went wrong... Refresh and try again!";

        unset($_SESSION['fail']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Font Awesome-->
    <script src="https://kit.fontawesome.com/996cf4158f.js" crossorigin="anonymous"></script>

    <!-- Animation.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <title>Change Password</title>

    <style>
        @media(max-width: 991px) {
            #loginbox {
                padding-left: 5% !important;
                padding-right: 5% !important;
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
                        <a class="nav-link" href="favourites.php">Favourites <i class="far fa-heart"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart(<?= $cart_count ?>) <i class="fas fa-shopping-cart"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

    <!-- Register -->
    <div class="container text-center" id="loginbox" style="margin-top: 100px; padding-left: 16%; padding-right: 16%;">
        <div class="card border-dark mb-3">
            <div class="card-header">
                <h4 style="margin: 0;">Change Password</h4>
            </div>
                
            <div class="card-body text-dark" style="padding-left: 15%; padding-right: 15%;">
                
                <?php
                    if (isset($error)) {
                        echo "<div class='alert alert-danger' role='alert'>
                                <i class='fas fa-exclamation-circle'></i> $error
                            </div>";
                    } 

                    if (isset($success)) {
                        echo "<div class='alert alert-success' role='alert'>
                                <i class='far fa-check-circle'></i> $success
                            </div>";
                    } 

                    if (isset($fail)) {
                        echo "<div class='alert alert-success' role='alert'>
                                <i class='far fa-check-circle'></i> $fail
                            </div>";
                    } 
                    
                ?>

                <form name="change_pass" method="POST" action="change_pass_process.php" style="margin-bottom: 15px;">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">Old Pass</span>
                        </div>

                        <input type="password" class="form-control" name="old_pwd" required>
                    </div>

                    <hr>
                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">New Pass</span>
                        </div>

                        <input type="password" class="form-control" name="new_pwd1" required>
                        
                    </div>

                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">Confirm Pass</span>
                        </div>

                        <input type="password" class="form-control" name="new_pwd2" required>
                    </div>

                    <input type="submit" class="btn btn-secondary btn-block" value="Change"></input>
                
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>