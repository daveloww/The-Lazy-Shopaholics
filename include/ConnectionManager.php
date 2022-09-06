<?php

class ConnectionManager {

    public function getConnection() {
        $servername = 'localhost';
        $username = '';  // enter username
        $password = '';  // enter password
        $port = ''; // enter port number
        $dbname = 'lazy_shopaholics';
        
        // Create connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;port=$port", $username, $password);     
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // if fail, exception will be thrown

        // Return connection object
        return $conn;
    }

}

?>