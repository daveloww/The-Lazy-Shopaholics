<?php
    require_once 'include/common.php';

    if (isset($_SESSION['loggedIn'])) {
        
        header("Location: index.php");
        exit;
    } else {
        $cart_count = 0;

        if (isset($_SESSION['cart_list'])) {
            $cart_count = count($_SESSION['cart_list']);
        }
    }

    $email_taken = "";
    if (isset($_SESSION['email_taken'])) {
        $email_taken = "is-invalid";
    }

    $username_taken = "";
    if (isset($_SESSION['username_taken'])) {
        $username_taken = "is-invalid";
    }

    $email_value = "";
    $username_value = "";
    if (isset($_SESSION['register_details'])) {
        $email_value = $_SESSION['register_details'][0];
        $username_value = $_SESSION['register_details'][1];

        unset($_SESSION['register_details']);
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

    <title>Registration</title>

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
                        <a class="nav-link" href="cart.php">Cart(<?= $cart_count ?>) <i class="fas fa-shopping-cart"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Account
                        </a>
                        <div class="dropdown-menu text-center" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="login.php">Login</a>
                            <a class="dropdown-item" href="register.php">Register</a>
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
                <h4 style="margin: 0;">Register</h4>
            </div>
                
            <div class="card-body text-dark" style="padding-left: 15%; padding-right: 15%;">
                
                <?php
                    if (isset($_SESSION["create_fail"])) {
                        echo '<div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-circle"></i> Something went wrong... Refresh and try again!
                            </div>';
                        
                        unset($_SESSION["create_fail"]);
                    }
                ?>

                <form name="register" method="POST" action="register_process.php" style="margin-bottom: 30px;">

                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">Email</span>
                        </div>

                        <input type="email" class="form-control <?= $email_taken ?>" name="email" value="<?= $email_value ?>" required>
                        <?php
                            if (isset($_SESSION['email_taken'])) {
                                echo '<div class="invalid-feedback">
                                        Email has been taken 
                                    </div>';

                                unset($_SESSION['email_taken']);
                            }
                        ?>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">Username</span>
                        </div>

                        <input type="text" class="form-control <?= $username_taken ?>" name="username" value="<?= $username_value ?>" required>
                        <?php
                            if (isset($_SESSION['username_taken'])) {
                                echo '<div class="invalid-feedback">
                                        Username has been taken 
                                    </div>';

                                unset($_SESSION['username_taken']);
                            }
                        ?>
                    </div>

                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">Password</span>
                        </div>

                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <input type="submit" class="btn btn-secondary btn-block" value="Register"></input>
                
                </form>

                <hr>

                <h6>Have an account?</h6>
                <a href="login.php" class="btn btn-outline-primary">Log in here!</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>