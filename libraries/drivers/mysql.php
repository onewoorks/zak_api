<?php

class Mysql_Driver {

    private $connection;
    private $query;
    private $result;
    private $bind;
    private $timezone = '+8:00';
    public $tenantDB;
    

    public function connect() {
        $host = 'localhost';
        $user = 'root';
        $password = 're^mp123';
        $database = 'zak';
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            //$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $result = TRUE;
        } catch (PDOException $e) {
            $this->connection = null;
            echo $e->getMessage();
            $result = FALSE;
        }
        return $result;
    }
    
    public function dc() {
        
    }

    public function disconnect() {
        $this->connection = null;
        return TRUE;
    }

    public function getLastId() {
        return $this->connection->lastInsertId();
    }
    
    public function lastId(){
        $this->connect();
        return $this->getLastId();
    }

    public function error() {
        return $this->connection->errorCode();
    }

    public function errorInfo() {
        return $this->connection->errorInfo();
    }

    public function begintransaction() {
        $this->connection->beginTransaction();
        return true;
    }

    public function rollback() {
        $this->connection->rollBack();
        return true;
    }

    public function execute($query) {
        $this->connection->exec($query);
        //return true;
    }

    public function commit() {
        $this->connection->commit();
        return true;
    }

    public function prepare($query) {
        $this->query = $query;
        return TRUE;
    }

    public function insertPrepare($query) {
        try {
            $this->bind = $this->connection->prepare($query);
        } catch (PDOException $e) {
            $this->connection->query("INSERT INTO log_error (error_message) VALUES ('test je insert prepare')");
        }
    }

    public function insertBind($column, $value) {
        try {
            $this->bind->bindValue($column, $value);
        } catch (PDOException $e) {
            $this->connection->query("INSERT INTO log_error (error_message) VALUES ('test je')");
        }
    }

    public function insertExecute() {
        try {
            $this->bind->execute();
        } catch (PDOException $e) {
            $error = $e->getMessage();
            $this->connection->query("INSERT INTO log_error (error_message) VALUES ($error)");
        }
    }

    public function bindParam($params) {
        $this->connection->bind_param($params);
    }

    public function queryexecute() {
        $this->connection->query("SET time_zone='$this->timezone'");
        if (isset($this->query)) {
            $this->result = $this->connection->query($this->query);
            return TRUE;
        }
        return FALSE;
    }

    public function transaction(array $query) {
        $this->connect();
        $this->begintransaction();
        $stmt = $query;
        for ($i = 0; $i < count($stmt); $i++):
            $this->connection->exec($stmt[$i]);
        endfor;
        if ($this->error() == 0) {
            $this->commit();
        } else {
            $this->rollback();
        }
        echo $this->error();
    }

    public function fetchOut($type = 'object') {
        $row = false;
        if (isset($this->result)) {
            switch ($type) {
                case 'array':
                    try {
                        $row = $this->result->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        // error_log($e->getMessage());
                    }
                    break;
                case 'single':
                    $r = $this->result->fetchAll(PDO::FETCH_ASSOC);
                    $row = ($r) ? $r[0] : false;
                    break;
                case 'object':
                    $row = $this->result->fetchAll(PDO::FETCH_BOTH);
                    break;
                case 'json';
                    $row = $this->result->fetchAll(PDO::FETCH_ASSOC);
                    break;
                case 'count':
                    $row = count($this->result->fetchAll(PDO::FETCH_ASSOC));
                    break;
                default:
                    //$row = $this->result->fetch_object();
                    $row = $this->result->fetchAll(PDO::FETCH_ASSOC);
                    break;
            }
        }
        return $row;
    }

    public function escape($value) {
        return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
    }
    
    public function executeQuery($query, $fetchout = 'array'){
        $this->connect();
        $this->prepare($query);
        $this->queryexecute();
        try {
            $result = $this->fetchOut($fetchout);
        } catch (Exception $ex) {
            print_r($ex);
        }
        return $result;
    }

}
