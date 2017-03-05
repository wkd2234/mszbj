<?php
namespace app\index\model;
use core\model\BaseModel as BaseModel;

class Index extends BaseModel {
    public function init(){
        $this->table('test1');
    }

    public function demo(){
        return $this->find();
    }
}