<?php
    include "../class/Login.php";

    $login = new Login;

    if($_POST['user'] && $_POST['user'] != '' && $_POST['password'] && $_POST['password'] != '' ){
        $loginUser = $login->loginUser($_POST['user'], $_POST['password']);

        if($loginUser == -1){
            header('Location: /index.php?loginFailed=1');
        }else{
            session_start();
            $_SESSION['user'] = $loginUser;
            header('Location: /views');
        }
    }
?>