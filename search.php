<?php
    require_once 'include/common.php';

    if (!isset($_GET["search"])) {
        header("Location: index.php");
        exit;
    } elseif ($_GET["search"] == "") {
        $_SESSION["empty_keyword"] = true;
        header("Location: index.php");
        exit;
    }

    $price_state = "active";
    $popularity_state = "";

    if (!isset($_GET["sort_by"])) {
        $sort_by = "current_price";
    } else {
        $sort_by = "relative_popularity";

        $price_state = "";
        $popularity_state = "active";
    }

    if (isset($_GET["category"])) {
        $category = $_GET["category"];
    } else {
        $category = "false";
    }

    $login_status = "false";
    $cart_count = 0;

    if (isset($_SESSION["loggedIn"])) {
        $login_status = "true";

        $dao = New DAO();
        $cart_count = $dao->count_cart($_SESSION['loggedIn'][1]);
    }

    if (isset($_SESSION['cart_list'])) {
        $cart_count = count($_SESSION['cart_list']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/996cf4158f.js" crossorigin="anonymous"></script>

    <!-- Animation.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <title>Searched for "<?= $_GET["search"] ?>"</title>

    <style>
        .iconHover:hover {
            background: lightskyblue;
            color: black;
        }

        @media(max-width: 768px) {
            #main_msg {
                font-size: 30px !important;
            }

            #sub_msg, #reco {
                font-size: 18px !important;
            }
        }
    </style>

    <!-- Call API javascript -->
    <script src="script/populate_category.js"></script>
    <script src="script/populate_search.js"></script>

</head>
<body>
    <div id="login" style="display: none;"><?= $login_status ?></div>
    <div id="keyword" style="display: none;"><?= $_GET["search"] ?></div>
    <div id="category" style="display: none;"><?= $category ?></div>
    <div id="sort_by" style="display: none;"><?= $sort_by ?></div>

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
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    
                    <?php
                        if (isset($_SESSION['loggedIn'])) {
                            echo '<li class="nav-item">
                                    <a class="nav-link" href="favourites.php">Favourites <i class="far fa-heart"></i></a>
                                </li>';
                        } 
                    ?>

                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart(<?= $cart_count ?>) <i class="fas fa-shopping-cart"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Account
                        </a>
                        <div class="dropdown-menu text-center" aria-labelledby="navbarDropdownMenuLink">
                            <?php
                                if (isset($_SESSION['loggedIn'])) {
                                    echo '<a class="dropdown-item" href="change_pass.php">Change Password</a>
                                    <a class="dropdown-item" href="logout.php">Log out</a>';
                                } else {
                                    echo '<a class="dropdown-item" href="login.php">Log in</a>
                                    <a class="dropdown-item" href="register.php">Register</a>';
                                }
                            ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--Welcome message & Search Bar-->
    <div class="container text-center" style="margin-top: 100px;">
        <form name="search_bar" action="search.php" style="margin-top: 30px; padding-left: 15%; padding-right: 15%;">
            <div class="input-group mb-3">
                <input name="search" type="text" class="form-control" placeholder="Search another!" aria-describedby="button-addon2">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                </div>
            </div>
        </form>

        <div style="margin-top: 30px; padding-left: 15%; padding-right: 15%;">
            <b style="margin-right: 3px">Sort by: </b>
            <a style="margin-right: 3px" class="btn btn-sm btn-outline-secondary <?= $price_state ?>" href="search.php?search=<?= $_GET["search"] ?>&category=<?= $category ?>">Price</a> 
            <a class="btn btn-sm btn-outline-secondary <?= $popularity_state ?>" href="search.php?search=<?= $_GET["search"] ?>&sort_by=relative_popularity&category=<?= $category ?>">Popularity</a>
        </div>
    </div>

    <!-- Search results -->
    <div class="container" style="margin-top: 50px;">
        <div id="reco" class="h4" style="margin-bottom: 20px;"><i class="fas fa-clipboard-list"></i> Search result for "<?= $_GET['search'] ?>"</div>
        
        <div name="product_div" id="product_div_1" class="row row-cols-2 row-cols-md-3 row-cols-lg-4">
            
        </div> 

        <div id="scroller" name="main" class="text-center">
            <div id="infinite_scroll_trigger" style="visibility:hidden; padding:2em;">
                <h4 class="text-info">Wait ah... Searching "<?= $_GET['search'] ?>" for you!</h4>
                <div class="spinner-border" style="width: 4rem; height: 4rem; margin-top: 20px;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>