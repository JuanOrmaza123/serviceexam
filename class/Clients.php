<?php
include "Database.php";
session_start();
class Clients extends Database
{
    public function getListClients()
    {
        $data = [];

        $sql = "SELECT id, name FROM clients WHERE 1";

        $query = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }

        return $data;
    }

    public function createClient($data)
    {
        $sql = "INSERT INTO clients (name) 
        VALUES ('".$data->name."')";
        $query = mysqli_query($this->conn, $sql);

        if ($query) {
            return true;
        }
    }
}

?>