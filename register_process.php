<?php

require_once 'include/common.php';

$dao = New DAO();

if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $email_taken = $dao->retrieve_email($email);
    $username_taken = $dao->retrieve_username($username);


    if (!$email_taken && !$username_taken) {
        
        $create_status = $dao->create($email, $username, password_hash($password, PASSWORD_DEFAULT));

        if ($create_status) {
            $_SESSION["create_success"] = True;

            header("Location: login.php");
            exit;
        } else {
            $_SESSION["create_fail"] = True;

            header("Location: register.php");
            exit;
        }


    } else {
        if ($email_taken) {
            $_SESSION['email_taken'] = True;
        }

        if($username_taken) {
            $_SESSION['username_taken'] = True;
        }

        $_SESSION["register_details"] = [$email, $username];

        header("Location: register.php");
        exit;
    }

} else {
    header("Location: index.php");
    exit;
}

?>