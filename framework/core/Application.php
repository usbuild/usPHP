<?php
/**
 * User: Usbuild
 * DateTime: 12-9-5 上午12:06
 */
class Application
{
    public $config;
    static $default_config;

    public function __construct($config)
    {
        $this->config = require($config);
        self::$default_config = require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'default_config.php');
        self::loadDirectory(dirname(__FILE__));
        self::import('application.controller.*');
        self::import('application.model.*');
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    public function __get($key)
    {
        return null;
    }


    public function handleRequest()
    {
        list($controllerID, $actionID) = self::getControllerAction();
        $controllerName = ucfirst($controllerID) . 'Controller';
        $actionName = $actionID . 'Action';
        if (!class_exists($controllerName)) {
            throw new Exception("No such Controller");
        }
        $controller = new $controllerName($controllerID);
        if (!method_exists($controller, $actionName)) {
            throw new Exception("No such Action");
        }
        $controller->$actionName();

    }

    public function run()
    {
        $this->handleRequest();
    }

    public static function import($alias)
    {
        $str_array = explode('.', $alias);
        $last_flag = end($str_array);
        $str_array = array_slice($str_array, 0, -1);
        if ($last_flag == '*') {
            self::loadDirectory(self::getPathByAlias(implode('.', $str_array)));
        } else {
            require_once(self::getPathByAlias(implode('.', $str_array) . $last_flag . 'php'));
        }
    }

    public static function getPathByAlias($alias)
    {
        $str_array = explode('.', $alias);
        $res = '';
        foreach ($str_array as $value) {
            $res .= self::$default_config['alias'][$value];
            $res .= DIRECTORY_SEPARATOR;
        }
        return WEBROOT . DIRECTORY_SEPARATOR . substr($res, 0, -1);
    }

    public static function getControllerAction()
    {
        $redirect_url = $_SERVER['REDIRECT_URL'];
        $res = array_slice(explode('/', $redirect_url), -2);
        return $res;
    }

    private static function loadDirectory($path)
    {
        $dirhandler = opendir($path);
        while ($filename = readdir($dirhandler)) {
            if (!is_dir($filename)) {
                $path_info = pathinfo($filename);
                if ($path_info['extension'] == 'php') {
                    $filepath = $path . DIRECTORY_SEPARATOR . $filename;
                    require_once($filepath);
                }
            }
        }
    }
}