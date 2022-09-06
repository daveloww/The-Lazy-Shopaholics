<?php

class Account {

    private $id;
    private $email;
    private $username;    
    private $pass;
    
    public function __construct($id, $email, $username, $pass) {
        $this->id = $id;
        $this->email = $email;
        $this->username = $username;
        $this->pass = $pass;
    }

    public function getID() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPass() {
        return $this->pass;
    }
}

?>