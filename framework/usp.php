<?php
require_once  (dirname(__FILE__).DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'Application.php');

class USP
{
    private static $app;

    public static function createApp($config)
    {
        self::$app = new Application($config);
        return self::$app;
    }

    public static function  app()
    {
        return self::$app;
    }

}
