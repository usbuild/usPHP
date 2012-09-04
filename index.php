<?php
$usp_path = dirname(__FILE__).'/framework';
$usp = $usp_path.'/usp.php';
$config = dirname(__FILE__).'/protected/config/config.php';
define('WEBROOT', dirname(__FILE__));
require_once $usp;
USP::createApp($config) -> run();
