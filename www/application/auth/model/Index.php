<?php
namespace app\auth\model;
use core\model\BaseModel as BaseModel;

class Index extends BaseModel {
    public function init() {
        $this->table('test1');
    }

    public function validate($email, $password) {
        ///todo 要添加验证规则
        session_start();
        return (!empty($email) && !empty($password));
    }

    public function setSession() {

    }
}