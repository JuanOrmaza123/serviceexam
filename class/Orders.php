<?php
include "Database.php";
session_start();
class Orders extends Database{

    public function getListOrders($params = []){
        $data = [];

        $sql = "SELECT od.id, od.order_number, od.status, od.client_id, cl.name FROM orders as od INNER JOIN clients as cl ON od.client_id = cl.id  WHERE 1";
        
        if(isset($params->client_id) && $params->client_id != ''){
            $sql .= " AND client_id = ".$params->client_id." ";
        }

        $query = mysqli_query($this->conn, $sql);

        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }

        return $data;
    }

    public function createOder($data){
        $exist = $this->existOrderNumber($data->order_number);

        if($exist['total'] > 0){
            return -1;
        }
        $sql = "INSERT INTO orders (user_id, order_number, client_id) VALUES (".$_SESSION['user']['id'].", ".$data->order_number.", '".$data->client_id."')";

        $query = mysqli_query($this->conn, $sql);

        if($query){
            return true;
        }
    }

    public function updateOrder($data){
        $sql = "UPDATE orders SET order_number = ".$data->order_number.", client_id = '".$data->client_id."'
        WHERE id = ".$data->id." ";

        $query = mysqli_query($this->conn, $sql);

        if($query){
            return true;
        }
    }

    public function deleteOrder($id){
        $totalItems = $this->haveItems($id);
        
        if($totalItems['total'] > 1){
            return -1;
        }

        $sql = "DELETE FROM orders 
        WHERE id = ".$id."";

        $query = mysqli_query($this->conn, $sql);

        if($query){
            return true;
        }
    }

    public function closeOrder($id){
        $totalItems = $this->haveItems($id);

        if($totalItems['total'] < 1){
            return -1;
        }

        $sql = "UPDATE orders SET status = 1 WHERE id = ".$id." ";

        $query = mysqli_query($this->conn, $sql);

        if($query){
            return true;
        }
    }

    public function openOrder($id){
        $sql = "UPDATE orders SET status = 0 WHERE id = ".$id." ";

        $query = mysqli_query($this->conn, $sql);

        if($query){
            return true;
        }
    }

    public function generateReport($params){
        $sql = "SELECT od.order_number, it.sku, it.type, it.price, cl.name as client
        FROM orders as od
        INNER JOIN items as it ON it.order_id = od.id
        INNER JOIN clients as cl ON od.client_id = cl.id
        WHERE 1";

        if ($params->client != 0) {
            $sql .= " AND od.client_id = ".$params->client."";
        }

        if ($params->order != 0) {
            $sql .= " AND od.id = ".$params->order."";
        }

        $sql .= " ORDER BY it.id";

        $query = mysqli_query($this->conn, $sql);

        $data = [];
        $headers = "Orden;Articulo;TipoArticulo;Precio;Cliente";
        $data[0] = $headers;

        $fp = fopen('../resources/data.csv', 'w');
        $dataOrders = [];
        $dataQuery = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $dataQuery[] = $row;
        }
        $sumPrices = 0;
        $dataOrder = "";
        foreach ($dataQuery as $value) {
            $client = str_replace(" ", "", $value['client']);
            $type = ($value['type'] == 0) ? "Articulo" : 'Servicio';
            $dataOrder = $value['order_number'].";".$value['sku'].";".$type.";".$value['price'].";".$client;
            array_push($data, $dataOrder);
            $sumPrices += $value['price'];
        }

        array_push($data, 'Suma;'.$sumPrices.'');

        foreach($data as $line){
            $val = explode(",",$line);
            fputcsv($fp, $val);
        }
        fclose($fp);
        return $data;
    }

    private function existOrderNumber($orderNumber){
        $sql = "SELECT COUNT(*) as total FROM orders WHERE order_number = ".$orderNumber."";

        $query = mysqli_query($this->conn, $sql);
        $orderNumber = mysqli_fetch_assoc($query);

        return $orderNumber;
    }

    private function haveItems($id){
        $sqlItems = "SELECT COUNT(*) as total FROM items WHERE order_id = ".$id."";

        $query = mysqli_query($this->conn, $sqlItems);
        $items = mysqli_fetch_assoc($query);

        return $items;
    }
}