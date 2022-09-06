<?php

require_once 'include/common.php';

$dao = New DAO();

if ($_GET) {
    $list = $_GET['list'];
    $type = $_GET['type'];
    $id = $_GET['id'];
    
    if ($list == "cart") {

        if ($type == "non-user") {
            unset($_SESSION['cart_list'][$id]);

            $_SESSION['cart_list'] = array_values ($_SESSION['cart_list']);

            header("Location: cart.php");
            exit;
        } else {
            $status = $dao->delete_from_cart($id);

            if (!$status) {
                $_SESSION["delete_cart_error"] = true;
            }

            header("Location: cart.php");
            exit;
        }

    } 

} else {
    header("Location: index.php");
    exit;
}

?>