<?php
//dito class ng mga bisaya
//i mean dito ilalagay ang data na nakuha galing sa db
//dito ilalagay ang data tas dito
// include "../../data/Db.php";

//this is for account this class mostly use when verifying user
//Account Table

require __DIR__ ."../../../data/Db.php";
require __DIR__ ."../../common.php";

class UserAcc {//this class communicates to database
    private $conn;
    private $accId;
    private $name;
    private $username;
    private $passHash;//always salt the password the time it enter the server
    private $auth; //not sure for this variable but mostly it is for role i guess

    function __construct(){//get database
        $dbObj = new Database();
        $this->conn = $dbObj->getConn();
    }

    function getAccById($id) {//arrau returns all results based on Id
        try {
            $query = $this->conn->prepare("Call SelectUserAccById(?)");
            $id = sanitizeInput($id);
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();
            if (!$result) {
                throw new Exception("Empty Value!");
            } else {
                return $result->fetch_assoc();
            }
            

        } catch (Exception $r) {
            echo "Error Message: " .$r->getMessage();
        }





        
        
        
    }

    function register($name, $username, $passHash, $auth) {//create account 

    }


    function update($key, $value){//update

    }

    function remove($id, $isValid) {

    }

}

$temp = new UserAcc();
$data = $temp->getAccById(10);
var_dump($data);

// foreach ($data as $user) {
//     echo $user;
// }


?>