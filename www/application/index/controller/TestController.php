<?php
namespace app\index;
use core\controller\BaseController as BaseController;
use app\index\model\Index as Index;
use app\auth\middle\Login as Auth;

class TestController extends BaseController{
    public function init () {
        Auth::doValid($this->view);
    }

    public function indexAction(){
        $model = new Index();
        $res = $model->demo();
        $this->view->assign('re', $res);
        $this->view->assign('project','马赛直播间');
        $this->view->assign('navNum', '1');
        $this->view->setTplPath('index');
        $this->view->display('test.php');
    }
}