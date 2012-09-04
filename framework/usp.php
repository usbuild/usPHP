<?php
class USPApp {
    public $config;
    public function __construct($config) {
        $this->config = require($config);
    }
    public function handleRequest() {
        list($controllerID, $actionID) = USP::getControllerAction();
        $controllerName = ucfirst($controllerID).'Controller';
        $actionName = $actionID.'Action';
        if(!class_exists($controllerName)) {
            throw new Exception("No such Controller");
        }
        $controller = new $controllerName($controllerID);
        if(!method_exists($controller, $actionName)) {
            throw new Exception("No such Action");
        }
        $controller->$actionName();

    }
    public function run() {
        $this->handleRequest();
    }
}
class USP {
    static $default_config;
    public function __construct() {
    }
    public static function createApp($config) {
        self::$default_config = require(dirname(__FILE__).DIRECTORY_SEPARATOR.'default_config.php');
        self::loadCoreClasses();
        self::loadControllers();
        return new USPApp($config);
    }
    private static function loadCoreClasses() {
        self::loadDirectory(dirname(__FILE__).DIRECTORY_SEPARATOR.'core');

    }
    private static function loadControllers() {
        $controller_path = self::getPathByAlias('application.controller');
        self::loadDirectory($controller_path);
    }
    public static function autoload() {
        
    }
    public static function import($alias) {
    }
    public static function getPathByAlias($alias) {
        $str_array = explode('.', $alias);
        $res = '';
        foreach($str_array as $value) {
            $res .= self::$default_config['alias'][$value];
            $res .= DIRECTORY_SEPARATOR;
        }
        return WEBROOT.DIRECTORY_SEPARATOR.substr($res, 0, - 1);
    }
    public static function getControllerAction() {
        $redirect_url = $_SERVER['REDIRECT_URL'];
        $res = array_slice(explode('/', $redirect_url), -2);
        return $res;
    }
    private static function loadDirectory($path) {
        $dirhandler = opendir($path);
        while($filename = readdir($dirhandler)) {
            if(!is_dir($filename))  {
                $path_info = pathinfo($filename);
                if($path_info['extension'] == 'php') {
                    $filepath = $path.DIRECTORY_SEPARATOR.$filename;
                    require($filepath);
                }
            }
        }
    }
}
