<?php
    
class BaseManager {
    private $_table;
    private $_object;
    private $_database;

    public function __construct($table, $object, $datasource) {
        $this->_table = $table;
        $this->_object = $object;
        $db = new Database($datasource);
        $this->_database = $db->_database;
    }

    public function getDatabase() {
        return $this->_database;
    }

    public function getById(int $id): object{
        $req = $this->_database->prepare('SELECT * FROM '.$this->_table.' WHERE id=:id');
        $param = [ 'id'=>$id];
        $req->execute($param);
        $req->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $this->_object);
        return $req->fetch();
    }

    public function getAll(): object{
        $req = $this->_database->prepare('SELECT * FROM '.$this->_table);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $this->_object);
        return $req->fetchAll();
    }

    public function create(object $obj, array $param): void{
        $paramNumber = count($param);
        $valueArray = array_fill(1,$param_number, '?');
        $valueString = implode($valueArray, ', ');
        $sql = 'INSERT INTO '.$this->_table.' ('.implode($param, ', ').') VALUES('.$valueString.')';
        $req = $_database->prepare($sql);
        $boundParam = array();
        foreach ($param as $paramName) {
            $boundParam[$paramName] = $obj->$paramName;
        }
        $req->execute($boundParam); 
    }

    public function update(object $obj, array $param): void{
        $sql = 'UPDATE '.$this->_table.' SET ';
        foreach ($param as $paramName) {
            $sql .+ $paramName.' = ?,';
        }
        $sql .= ' WHERE id = ? ';
        $req = $_database->prepare($sql);
        $param[] = 'id';
        $boundParam = array();
        foreach ($param as $paramName) {
            if (property_exists($obj, $paramName)) {
                $boundParam[$paramName] = $obj->$paramName;
            } else {
                throw new PropertyNotFoundException($this->_object, $paramName);
            }
        }
        $req->execute($boundParam);
    }

    public function delete(object $obj): bool{
        if (property_exists($obj, 'id')) {
            $req = $this->_database->prepare('DELETE FROM '.$this->_table.' WHERE id=:id');
            $param = [ 'id'=>$obj->id];
            return $req->execute($param);
        } else {
            throw new PropertyNotFoundException($this->_object, 'id');
        }

    }
}