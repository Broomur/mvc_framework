<?php

class UserController extends BaseController {
    public function Login() {
        $this->view("login");
    }

    public function Registration() {
        $this->view("register");
    }

    public function Register($name, $password) {
        $user = $this->_usermanager->register($name, $password);
        $res = $user === true ? "<p>Youpi, {$name}, tu es inscrit !</p>" : "<p>Bouge de là, {$name}, j'veux pas te connaître !</p>";
        echo $res;
    }

    public function Authenticate($name, $password) {
        $user = $this->_usermanager->login($name, $password);
        $res = count($user) > 0 ? "<p>Youpi, {$name}, tu es connecté !</p>" : "<p>Bouge de là, {$name}, j'te connais pas !</p>";
        echo $res;
    }
}