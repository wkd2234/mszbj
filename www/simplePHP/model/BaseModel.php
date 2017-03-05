<?php
namespace core\model;
use core\orm\Db as Db; 

class BaseModel {
    protected $db;
    private $hasCreateAndUpdate;

    public function __construct() {
        $defalut = _config('default_db');
        $this->setDb($defalut);
        if(method_exists($this, 'init')){
            $this->init();
        }
    }

    public function setDb($db) {
        $dbConfig = _config($db);
        $this->db = Db::getInstance($db, $dbConfig);
    }

    public function __set($name, $value) {
        if($name == 'hasCreateAndUpdate') {
            $this->db->setCreateAndUpdate($value);
        }
        $this->$name = $value;
    }

    public function __call($name, $args) {
        return call_user_func_array([&$this->db, $name], $args);
    }

    public function __destruct() {
        $this->db->close();
    }
}