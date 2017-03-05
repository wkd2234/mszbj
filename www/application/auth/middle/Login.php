<?php
namespace app\auth\middle;
use core\controller\BaseController as BaseController;
use app\auth\model\Index as Index;

class Login extends BaseController {
    public $model;
    public static $instance;
    //引用请求模块的view
    public static function getInstance($view){
        if(empty(self::$instance) || self::$instance instanceof self == false) {
            self::$instance = new self();
            self::$instance->view = $view;
        }

        return self::$instance;
    }

    public static function doValid($view){
        if(empty(self::$instance) || self::$instance instanceof self == false) {
            self::$instance = self::getInstance($view);
        }

        self::$instance->indexAction();
    }

    public function init(){
        $this->model = new Index();
    }

    public function indexAction($isAjax = false){
        $session_id = $this->getCookie('session_id') ?? null;
        $email = $this->request('email') ?? null;
        $password = $this->request('password') ?? null;
        
        if($session_id != null) {
            $isValidUsr = $this->model->validate($session_id);
        }else{
            $isValidUsr = $this->model->validate($email, $password);
        }
        
        if($isAjax){
            echo int($isValidUsr);
            exit;
        }

        $this->view->assign('has_login', $isValidUsr);
    }
}