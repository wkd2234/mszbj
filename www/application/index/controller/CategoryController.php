<?php
namespace app\index;
use core\controller\BaseController as BaseController;
use app\index\model\Index as Index;

class CategoryController extends BaseController {
    public function init() {

    }

    public function indexAction(){
        $model = new Index();
        $res = $model->demo();
        $this->view->assign("project", "马赛直播间");
        $this->view->assign('navNum','2');
        $this->view->setTplPath('index');
        $this->view->display('test.php');
    }
}