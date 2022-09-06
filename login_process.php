<?php

require_once 'include/common.php';

$dao = New DAO();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $account = $dao->retrieve_username($username);

    if (!$account) {
        $_SESSION['invalid_login'] = True;

        header("Location: login.php");
        exit;
    } else {
        if (!password_verify($password, $account->getPass())) {
            $_SESSION['invalid_login'] = True;
    
            header("Location: login.php");
            exit;
        }
    }

    $_SESSION['loggedIn'] = [$username, $account->getID()];

    if (isset($_SESSION['cart_list'])) {
        unset($_SESSION['cart_list']);
    }
    
    header("Location: index.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}

?>