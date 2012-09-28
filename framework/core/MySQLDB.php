<?php
/**
 * Created by JetBrains PhpStorm.
 * User: usbuild
 * Date: 12-9-5
 * Time: 下午9:18
 * To change this template use File | Settings | File Templates.
 */
require_once "AbstractDB.php";
class MySQLDB extends AbstractDB
{
    public function __construct($config)
    {
        parent::__construct($config);
    }
}
