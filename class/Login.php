<?php

include "Database.php";
class Login extends Database{

    public function loginUser($user, $password){
        $sql = "SELECT id, name, user, password FROM users WHERE user = '".$user."' ";

        $query = mysqli_query($this->conn, $sql);

        $result = mysqli_fetch_assoc($query);
        if($result){
            if($result['password'] == $password){
                return $result;
            }else{
                return -1;
            }
        }else{
            return -1;
        }
    }

}