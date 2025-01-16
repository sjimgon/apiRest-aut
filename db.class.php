<?php

require_once 'config/config.php';

class DB {
    private $servername;
    private $username;
    private $password;
    private $dbname ;
    private $port;
    private $conn;

    //Se ha modificado el constructor para que tome los valores del arcchivo config.php
    public function __construct() {
        $this->servername = DB_SERVER;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->dbname = DB_DATABASE;
        $this->port = DB_PORT;
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
    public function closeConnection() {
        $this->conn->close();
    }
    public function query($query) {
        return $this->conn->query($query);
        
    }
    public function getError() {
        return $this->conn->error;
    }
    public function getAffectedRows() {
        return $this->conn->affected_rows;
    }
    public function getInsertId() {
        return $this->conn->insert_id;
    }
}