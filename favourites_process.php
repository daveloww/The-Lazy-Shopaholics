<?php

require_once 'include/common.php';

$dao = New DAO();

if (isset($_GET["type"]) && isset($_SESSION["loggedIn"])) {
    
    if ($_GET["type"] == "add") {
        $pid = $_GET["pid"];
        $p_name = $_GET["p_name"];
        $photo = $_GET["photo"];
        $url = $_GET["url"];
        $price = $_GET["price"];
        $ecom = $_GET["ecom"];

        $duplicate = $dao->retrieve_product_fav($_SESSION["loggedIn"][1], $pid, $ecom);

        if (!$duplicate) {
            $status = $dao->add_to_fav($_SESSION["loggedIn"][1], $pid, $p_name, $photo, $url, $price, $ecom);

            if (!$status) {
                $_SESSION["add_fav_error"] = "db";
            }
        } else {
            $_SESSION["add_fav_error"] = "duplicate";
        }

        header("Location: favourites.php");
        exit;


    } elseif ($_GET["type"] == "delete") {
        $id = $_GET["id"];

        $status = $dao->delete_from_fav($id);

        if (!$status) {
            $_SESSION["delete_fav_error"] = true;
        } 

        header("Location: favourites.php");
        exit;


    } else {
        $id = $_GET["id"];
        $pid = $_GET["pid"];
        $ecom = $_GET["ecom"];

        // Check if product exists in cart
        $duplicate = $dao->retrieve_product_cart($_SESSION["loggedIn"][1], $pid, $ecom);

        if ($duplicate) {
            $_SESSION["fav_cart_error"] = true;

            header("Location: favourites.php");
            exit;
        } else {

            // Retrieve product from fav list
            $fav_product = $dao->retrieve_product_fav($_SESSION["loggedIn"][1], $pid, $ecom);

            $photo = $fav_product->getPhoto();
            $url = $fav_product->getUrl();
            $price = $fav_product->getPrice();
            $p_name = $fav_product->getName();

            // Add product to cart
            $status = $dao->add_to_cart($_SESSION["loggedIn"][1], $pid, $p_name, $photo, $url, $price, $ecom);

            if (!$status) {
                $_SESSION["add_cart_error"] = "db";

                header("Location: cart.php");
                exit;

            } else {
                
                // Delete product from fav - since product is added to cart
                $status = $dao->delete_from_fav($id);

                if (!$status) {
                    $_SESSION["delete_fav_error"] = true;

                    header("Location: favourites.php");
                    exit;

                } else {
                    $_SESSION["success_move"] = true;
                    
                    header("Location: cart.php");
                    exit;
                }
            }
        }
    }

} else {
    header("Location: index.php");
    exit;
}

?>