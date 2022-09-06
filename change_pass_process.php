<?php

require_once 'include/common.php';

$dao = New DAO();

if (isset($_POST['old_pwd']) && isset($_POST['new_pwd1']) && isset($_POST['new_pwd2']) && isset($_SESSION['loggedIn'][0])) {
    $old_pwd = $_POST['old_pwd'];
    $new_pwd1 = $_POST['new_pwd1'];
    $new_pwd2 = $_POST['new_pwd2'];
    $username = $_SESSION['loggedIn'][0];

    $account = $dao->retrieve_username($username);

    if (!password_verify($old_pwd, $account->getPass())) {
        $_SESSION['invalid_old_pwd'] = True;

        header("Location: change_pass.php");
        exit;
    }

    if ($new_pwd1 != $new_pwd2) {
        $_SESSION['pwd_mismatch'] = True;

        header("Location: change_pass.php");
        exit;
    }

    $change_result = $dao->change_password($username, password_hash($new_pwd1, PASSWORD_DEFAULT));

    if ($change_result) {
        $_SESSION['success'] = True;
    } else {
        $_SESSION['fail'] = True;
    }

    header("Location: change_pass.php");
    exit;

} else {
    header("Location: index.php");
    exit;
}

?>