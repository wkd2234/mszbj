<?php
namespace core\controller;
use core\view\View as View;
use app\auth\LoginController as LoginMid;

class BaseController {
    public $view;
    private $req;

    public function __construct() {
        $this->view = new View();
        $path = get_class($this);
        $pos = strpos($path, '\\');
        $rpos = strrpos($path, '\\');
    
        $module = substr($path, $pos+1, $rpos-$pos-1);
        $this->view->setTplPath(APP_PATH . $module . SLASH .'view');   

        $this->setRequest();

        if(method_exists($this, 'init')){
            $this->init();
        }
    }

    public function setRequest(){
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->req = $_GET;
                break;
            case 'POST':
                $this->req = $_POST;
                break;
        }
    }

    public function getCookie($key){
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : false;
    }

    public function getRequest($key) {
        return isset($this->req[$key]) ? $this->req[$key] : false;
    }

    public function request($key) {
        return $this->getRequest($key);
    }

}