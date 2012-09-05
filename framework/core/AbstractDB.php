<?php
/**
 * User: Usbuild
 * DateTime: 12-9-4 下午11:31
 */
abstract class AbstractDB
{
    public $pdo;

    public function __construct($config)
    {
        $dbconfig = $config['db'];
        $this->pdo = new PDO($dbconfig['connectionString'], $dbconfig['username'], $dbconfig['password']);
    }

}