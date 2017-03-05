<?php
namespace core\view;

class View {
    private $includePath;
    private $datas;

    public function __contruct(){
        
    }

    public function assign($key,$val = null){
        if($val === null) {
            foreach ($key as $k => $v) {
                $this->datas[$k] = $v;
            }
        }else{
            $this->datas[$key] = $val;
        }
    }

    public function setTplPath($path) {
        $path = '/'.trim($path, '/');
        $this->includePath .= $path;
    }

    public function display($tpl){
        $tpl = trim($tpl, '/');
        set_include_path($this->includePath);
        if(!empty($this->datas)){
            foreach ($this->datas as $key => $val) {
                ${$key} = $val;
            }
        }
        require_once($tpl);

        exit;
    }
}