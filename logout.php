<?php
session_start();

foreach ($_SESSION as $key=>$value)
{
    if (isset($GLOBALS[$key]))
        unset($GLOBALS[$key]);
}
header('Location: /index.php');
?>