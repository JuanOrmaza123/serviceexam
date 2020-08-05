<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
		integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="../resources/css/bootstrap.min.css">
    <link rel="stylesheet" href="../resources/css/header.css">
</head>
<body>
<div id="header">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-6">
                <span>Bienvenido</span> <strong><?php echo $_SESSION['user']['name']; ?></strong>
            </div>
            <div class="col-12 col-sm-6 text-right">
                <a class="logout" href="/logout.php">Cerrar Sesion</a>
            </div>
        </div>
    </div>
</div>