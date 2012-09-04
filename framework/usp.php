<?php
class USPApp {
    public $config;
    public function __construct($config) {
        $this->config = require($config);
    }
    public function handleRequest() {
        list($controllerID, $actionID) = USP::getControllerAction();
        $controllerID .= 'Controller';
        $actionID .= 'Action';
        if(!class_exists($controllerID)) {
            throw new Exception("No such Controller");
        }
        $controller = new $controllerID();
        if(!method_exists($controller, $actionID)) {
            throw new Exception("No such Action");
        }
        $controller->$actionID();

    }
    public function run() {
        $this->handleRequest();
    }
}
class USP {
    static $common;
    public function __construct() {
    }
    public static function createApp($config) {
        self::$common = require(dirname(__FILE__).DIRECTORY_SEPARATOR.'common.php');
        self::loadControllers();
        return new USPApp($config);
    }
    private static function loadControllers() {
        $controller_path = WEBROOT.DIRECTORY_SEPARATOR.self::getPathByAlias('application.controller');
        $dirhandler = opendir($controller_path);
        while($filename = readdir($dirhandler)) {
            if(!is_dir($filename))  {
                $path_info = pathinfo($filename);
                if($path_info['extension'] == 'php') {
                    $filepath = $controller_path.DIRECTORY_SEPARATOR.$filename;
                    require($filepath);
                }
            }
        }
    }
    public static function autoload() {
        
    }
    public static function import($alias) {
    }
    public static function getPathByAlias($alias) {
        $str_array = explode('.', $alias);
        $res = '';
        foreach($str_array as $value) {
            $res .= self::$common['alias'][$value];
            $res .= DIRECTORY_SEPARATOR;
        }
        return substr($res, 0, - 1);
    }
    public static function getControllerAction() {
        $redirect_url = $_SERVER['REDIRECT_URL'];
        $res = array_slice(explode('/', $redirect_url), -2);
        return $res;
    }
}
