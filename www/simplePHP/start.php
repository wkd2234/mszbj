<?php
const APP_PATH = ROOT_PATH . "application". SLASH;

//按命名空间自动加载 
require_once CORE_PATH . "Loader.php";
$_loader = new core\Loader();
$_loader->register();
$_loader->addNameSpace('app', APP_PATH);
$_loader->addNameSpace('core', CORE_PATH);

//环境配置设置
use core\Config as Config;
require_once CORE_PATH . "config/config.php";
require_once ROOT_PATH . "config/config.php";
$_config = Config::getInstance($default_config, $user_config);

function _config($field){
    global $_config;
    if(is_array($field)){
        return $_config->put($field[0], $field[1]);
    }else{
        return $_config->get($field);
    }
}
//路由设置 
use core\Router as Router;
require_once ROOT_PATH . "route/config.php";
// var_dump($_SERVER['REQUEST_URI']);exit;
$_router = Router::getInstance($route_config);