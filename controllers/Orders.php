<?php
    error_reporting(0);
    include "../class/Orders.php";
    $params = json_decode(file_get_contents("php://input"));
    $item = new Orders();

    if($params->action == 'list'){
        print json_encode($item->getListOrders($params));
        die();
    }

    if($params->action == 'create'){
        print json_encode($item->createOder($params));
        die();
    }

    if($params->action == 'update'){
        print json_encode($item->updateOrder($params));
        die();
    }

    if($params->action == 'delete'){
        print json_encode($item->deleteOrder($params->id));
        die();
    }

    if($params->action == 'close'){
        print json_encode($item->closeOrder($params->id));
        die();
    }

    if($params->action == 'open'){
        print json_encode($item->openOrder($params->id));
        die();
    }

    if($params->action == 'report'){
        print json_encode($item->generateReport($params->report));
        die();
    }
?>