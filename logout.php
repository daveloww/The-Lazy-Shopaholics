<?php
    session_start();

    unset($_SESSION['loggedIn']);

    $_SESSION["logout_success"] = True;

    header("Location: index.php");
    exit;
?>