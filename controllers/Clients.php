<?php
    error_reporting(0);
    include "../class/Clients.php";
    $params = json_decode(file_get_contents("php://input"));
    $client = new Clients();

    if($params->action == 'list'){
        print json_encode($client->getListClients());
        die();
    }

    if($params->action == 'create'){
        print json_encode($client->createClient($params));
        die();
    }
?>