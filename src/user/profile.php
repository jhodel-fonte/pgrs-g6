<?php
//for modify sa data then pag kuha na din, bale madalas gamitin ito for profile page
require 'user.php';

class profileMng {  //Profile functions for user
    private $User;

    function __construct(UserAcc $userObj) {//use user object or i think better the Id, hmm lets seee
        $this->User = $userObj;
    }
    
    function getProfile() {

    }

    

    function updateUser(){//still not sure how to update by certain object

    }



}


?>