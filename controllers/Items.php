<?php
    error_reporting(0);
    include "../class/Items.php";
    $params = json_decode(file_get_contents("php://input"));
    $item = new Items();

    if($params->action == 'list' && $params->orderId != ''){
        print json_encode($item->getListItems($params->orderId));
        die();
    }

    if($params->action == 'create'){
        print json_encode($item->createItem($params));
        die();
    }

    if($params->action == 'update'){
        print json_encode($item->updateItem($params));
        die();
    }

    if($params->action == 'delete'){
        print json_encode($item->deleteItem($params->id));
        die();
    }
?>