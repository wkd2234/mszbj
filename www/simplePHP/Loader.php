<?php
namespace core;

class Loader {
    private static $prefixes = [];

    function __construct (){}

    public function register () 
    {
        spl_autoload_register("core\\Loader::loadClass");
    }

    public function addNameSpace($prefix, $dir)
    {
        if(!isset(self::$prefixes[$prefix])) 
            self::$prefixes[$prefix] = $dir;
    }

    public static function loadClass($class)
    {
        $class = rtrim($class, '\\');
        $className = '';

        while ($pos = strrpos($class, '\\')){
            $className = rtrim(substr($class, $pos + 1) . '/' . $className, '/');
            $class     = substr($class, 0, $pos);

            if($isValidFile = self::findFile($class, $className)) {
                return true;
            }
        }

        return false;
    }

    public static function findFile($prefix, $className)
    {
        $return = false;

        if(isset(self::$prefixes[$prefix])) {
            $path = self::$prefixes[$prefix] . $className . '.php';
            if(self::requireFile($path)) {
                return true;
            }elseif ($prefix == 'app') {
                $pos = strrpos($className, '/');
                if(preg_match("/Controller$/", $className, $matches)){
                    $className = substr($className, 0, $pos + 1) . 'controller' . substr($className, $pos);
                }
                $path = self::$prefixes[$prefix] . $className . '.php';

                return self::requireFile($path);
            }
        }
        return false;
    }

    public static function requireFile($path) {
        if(is_file($path) && file_exists($path)) {
            require_once $path;
            return true;
        }
        return false;
    }

}