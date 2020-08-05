<?php
class Database
{
    public $conn;
    public function __construct()
    {
        $this->conn = mysqli_connect("localhost", "root", "", "serviceexam");

        if (!$this->conn) {
            echo "No conected" . mysqli_error();
        }
    }
}

$dataBase = new Database();