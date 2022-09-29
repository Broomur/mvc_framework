<?php

class BaseController {
    private $_param;
    private $_httpRequest;
    private $_config;
    protected $_usermanager;

    public function __construct($httpRequest, $config) {
        $this->_httpRequest = $httpRequest;
        $this->_config = $config;
        $this->_param = [];
        $this->addParam('httpRequest', $this->_httpRequest);
        $this->addParam('config', $this->_config);
        $this->bindManager();
    }

    protected function view($filename) {
        if (file_exists('./Views/'.$this->_httpRequest->getRoute()->getController().'/'.$filename.'.phtml')) {
            ob_start();
            extract($this->_param);
            include('./Views/'.$this->_httpRequest->getRoute()->getController().'/'.$filename.'.phtml');
            $content = ob_get_clean();
            include('./Views/layout.phtml');
        } else {
            throw new ViewNotFoundException();
        }
    }

    public function bindManager() {
        foreach ($this->_httpRequest->getRoute()->getManager() as $manager) {
            $manager = $manager.'Manager';
            $this->_usermanager = new $manager($this->_config);
        }
    }

    public function addParam($name, $value) {
        $this->_param [$name] = $value;
    }
}