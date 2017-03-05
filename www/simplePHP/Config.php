<?php
namespace core;

class Config {
    private static $instance;
    private $config = [];

    private function __construct($setting = [], $user_setting = []){
        $this->config = array_merge($setting, $user_setting);
    }
    
    public static function getInstance($setting = [], $user_setting = []) {
        if(self::$instance instanceof self) {
            return self::$instance;
        } else {
            self::$instance = new self($setting, $user_setting);
            return self::$instance;
        }
    }

    public function put($key, $val = 0) {
        $this->config[$key] = $val;
    }

    public function get($key) {
        return $this->config[$key] ?? false;
    }
}