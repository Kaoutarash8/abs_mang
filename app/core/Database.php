<?php
class Database {
    private $conn;
    private $stmt;

    public function __construct() {
        $config = require __DIR__.'/../config/database.php';
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            $this->conn = new PDO($dsn, $config['username'], $config['password']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur DB: '.$e->getMessage());
        }
    }

  
    public function getConnection() {
        return $this->conn;
    }
    public function beginTransaction() {
    return $this->conn->beginTransaction();
}

    public function commit() {
    return $this->conn->commit();
}

    public function rollBack() {
    return $this->conn->rollBack();
}

    public function query($sql) {
        $this->stmt = $this->conn->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value): $type = PDO::PARAM_INT; break;
                case is_bool($value): $type = PDO::PARAM_BOOL; break;
                case is_null($value): $type = PDO::PARAM_NULL; break;
                default: $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    public function resultSet() {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function execute() {
        return $this->stmt->execute();
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}