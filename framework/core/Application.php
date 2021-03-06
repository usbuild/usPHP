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
        $this->DBInit();
        self::import('application.model.*');
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    public function __get($key)
    {
        if (isset($this->$key)) return $this->$key;
        return null;
    }

    public function DBInit()
    {
        $this->db = new MySQLDB($this->config);
    }


    public function handleRequest()
    {
        list($controllerID, $actionID) = self::getControllerAction();
        $controllerName = ucfirst($controllerID) . 'Controller';
        $actionName = $actionID . 'Action';

        self::import('application.controller.' . $controllerName);

        if (!class_exists($controllerName)) {
            throw new Exception("No such Controller");
        }
        $controller = new $controllerName($controllerID);
        if (method_exists($controller, "init")) {
            $controller->init();
        }
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
            require_once(self::getPathByAlias(implode('.', $str_array)) . DIRECTORY_SEPARATOR . $last_flag . '.php');
        }
    }

    public static function getPathByAlias($alias)
    {
        $str_array = explode('.', $alias);
        $res = '';
        foreach ($str_array as $value) {
            if(array_key_exists($value, self::$default_config['alias']))
                $res .= self::$default_config['alias'][$value];
            else
                $res .= $value;
            $res .= DIRECTORY_SEPARATOR;
        }
        return WEBROOT . DIRECTORY_SEPARATOR . substr($res, 0, -1);
    }

    public static function getControllerAction()
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        $res = array_slice(explode('/', $request_uri), -2);
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
