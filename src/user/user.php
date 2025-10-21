<?php
//dito class ng mga bisaya
//i mean dito ilalagay ang data na nakuha galing sa 
//dito ilalagay ang data tas dito
// include "../../data/Db.php";

class User {
    private $id;
    private $name;
    private $username;
    private $passHash;
    private $auth;

    function __construct($id, $name, $username, $passHash, $auth){
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->passHash = $passHash;
        $this->auth = $auth;
    }

    function registerUser() {

    }

    function getUserToDb(){

    }

}




?>