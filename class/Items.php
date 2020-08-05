<?php
include "Database.php";
session_start();
class Items extends Database{

    public function getListItems($orderId){
        $data = [];

        $sql = "SELECT id, sku, name, type, price FROM items WHERE 1";

        if($orderId != ''){
            $sql .= " AND order_id = ".$orderId." ";
        }

        $query = mysqli_query($this->conn, $sql);

        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }

        return $data;
    }

    public function createItem($data){
        $sql = "INSERT INTO items (order_id, user_id, sku, name, type, price) 
        VALUES (".$data->order_id.", ".$_SESSION['user']['id'].",'".$data->sku."', '".$data->name."', ".$data->type.", '".$data->price."')";
        $query = mysqli_query($this->conn, $sql);

        if($query){
            return true;
        }
    }

    public function updateItem($data){
        $sql = "UPDATE items 
        SET sku = ".$data->sku.", name = '".$data->name."', type = '".$data->type."', price = '".$data->price."'
        WHERE id = ".$data->id." ";

        $query = mysqli_query($this->conn, $sql);

        if($query){
            return true;
        }
    }

    public function deleteItem($id){

        $sql = "DELETE FROM items 
        WHERE id = ".$id."";

        $query = mysqli_query($this->conn, $sql);

        if($query){
            return true;
        }
    }
}