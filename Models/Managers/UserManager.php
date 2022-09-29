<?php

class UserManager extends BaseManager {
    public function __construct($datasource) {
        parent::__construct('users', 'User', $datasource);
    }

    public function getByName($name)
		{	
			$db = $this->getDatabase();
			$req = $db->prepare("SELECT * FROM users WHERE name=?");
			$req->execute(array($name));
			$req->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"User");
			return $req->fetch();
		}

	public function register(string $name, string $password): bool{
		$done = null;
		$db = $this->getDatabase();
		$req = $db->prepare("INSERT INTO users (name, password) VALUES (:name, :password);");
		$params = [
			":name"=>$name,
			":password"=>password_hash($password, PASSWORD_DEFAULT)
		];
		$req->execute($params);
		$db->errorInfo()[0] === "00000" ? $done = true : $done = false;
		return $done;
		
	}
	
	public function login(string $name, string $password): array{
		$data = [];
		$db = $this->getDatabase();
		$req = $db->prepare("SELECT * FROM users WHERE name = :name LIMIT 1;");
		$params = [ ":name"=>$name];
		$req->execute($params);
		$verif =$req->fetch(PDO::FETCH_ASSOC);
		password_verify($password, $verif['password']) ? $data = $verif : $data[] = "bouge de là !";
		return $data;
	}
}