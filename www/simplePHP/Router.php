<?php 
namespace core;

class Router {
    private static $instance;
    private $setting;
    private $uri;
    private $custom = [];

    private function __construct($setting = []) {
        $this->setting = $setting;
        $this->init();
    }

    private function init() {
        $this->setUri()
             ->getCustomUrl()
             ->dispatch();
    }

    public static function getInstance($setting = []) {
        if(self::$instance instanceof self) {
            return self::$instance;
        } else {
            self::$instance = new self($setting);
            return self::$instance;
        }
    }

    public function setUri() {
        $this->uri = parse_url($_SERVER['REQUEST_URI']);
        return $this;
    }

    public function getCustomUrl() {
        $path = $this->uri['path'];

        if(!empty($this->setting['customMap'])) {
            foreach($this->setting['customMap'] as $custom => $info) {
                if ($custom == $path) {
                    $this->custom = $info;
                    break;
                }
            }
        }

        return $this;
    }

    public function dispatch() {
        if(!empty($this->custom)) {
            $className = array_shift($this->custom);
            if(!empty($this->custom)) {
                $action = array_shift($this->custom);
            }
        } else {
            $path = !empty($this->uri['path']) ? explode('/',$this->uri['path']) : [];
            array_shift($path);
            $module     = $this->setting['default_module'];
            $controller = ucfirst($this->setting['default_controller']);
            $action     = $this->setting['default_action'];
            if(isset($path[0])) {
                $module = $path[0];
            }
            if(isset($path[1])) {
                $controller = ucfirst($path[1]);
            }
            if(isset($path[2])) {
                $action = $path[2];
            }

            $className = 'app\\' . $module . '\\' . $controller . 'Controller';
        }

        $controller = new $className;
        $action .= "Action";

        if (isset($action) && method_exists($controller, $action)) {
            $controller->$action();
        }
    }

}