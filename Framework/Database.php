<?php

class Database {
    public $_database;

    public function __construct($datasource) {
        if ($this->_database == null) {
           try {
                $dsn = "mysql:dbname={$datasource->dbname};host={$datasource->host}";
                $this->_database = new PDO($dsn, $datasource->user, $datasource->password);
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}