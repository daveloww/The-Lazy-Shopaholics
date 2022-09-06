<?php

require_once 'include/common.php';

$dao = New DAO();

if ($_GET) {
    $pid = $_GET["pid"];
    $p_name = $_GET["p_name"];
    $photo = $_GET["photo"];
    $url = $_GET["url"];
    $price = $_GET["price"];
    $ecom = $_GET["ecom"];

    if (!isset($_SESSION["loggedIn"])) {
        if (!isset($_SESSION['cart_list'])) {
            $_SESSION['cart_list'] = [
                new Product(
                    0,
                    $pid,
                    $p_name,
                    $photo,
                    $url,
                    $price,
                    $ecom
                )
            ];
        } else {
            $duplicate = false;

            foreach ($_SESSION['cart_list'] as $item) {
                if ($item->getPid() == $pid && $item->getEcommerce() == $ecom) {
                    $duplicate = true;
                }
            }

            if (!$duplicate) {
                $_SESSION['cart_list'][] = 
                new Product(
                    0,
                    $pid,
                    $p_name,
                    $photo,
                    $url,
                    $price,
                    $ecom
                );
            } else {
                $_SESSION["add_cart_error"] = "duplicate";
            }
        }

        header("Location: cart.php");
        exit;

    } else {
        // Check if item is already added to cart
        $duplicate = $dao->retrieve_product_cart($_SESSION["loggedIn"][1], $pid, $ecom);

        if (!$duplicate) {
            $status = $dao->add_to_cart($_SESSION["loggedIn"][1], $pid, $p_name, $photo, $url, $price, $ecom);

            if (!$status) {
                $_SESSION["add_cart_error"] = "db";
            }
        } else {
            $_SESSION["add_cart_error"] = "duplicate";
        }

        header("Location: cart.php");
        exit;
    }

} else {
    header("Location: index.php");
    exit;
}

?>