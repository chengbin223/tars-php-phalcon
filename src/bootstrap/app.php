<?php

(!defined('SITE_NAME')) && define ( 'SITE_NAME', 'limi-user-server' );

(!defined('APP_NAME')) && define ( 'APP_NAME', 'user-httpserver' );

(!defined('PROJECT_NAME')) && define ( 'PROJECT_NAME', SITE_NAME.'/'.APP_NAME);

(!defined('PROJECT_PATH')) && define ( 'PROJECT_PATH', realpath (__DIR__ . '/../' ) );

$CenterConfig = include PROJECT_PATH."/conf/CenterConfig.php";

$MultiDbConfig = include PROJECT_PATH . "/database/MultiDbconfig.php";

$di = include PROJECT_PATH . '/libs/di/default.php';

include PROJECT_PATH . "/libs/FrameworkInit.php";
$di->set(
    'router',
    function () {
        return include PROJECT_PATH . '/routes/api.php';
    }
);
$di->set(
    'view',
    function () {
        return new \Phalcon\Mvc\View();
    }
);
$di->set('dispatcher', function () {
    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setDefaultNamespace('PFrame\Controllers');
    return $dispatcher;
});

if ($Config->application->debug) {
    ini_set ( 'display_errors', '1' );
    error_reporting ( E_ALL);
}else{
    error_reporting ( E_ERROR );
}

require PROJECT_PATH.'/vendor/autoload.php';

$app = new \Phalcon\Mvc\Application();
$app->setDI($di);
return $app;
