<?php

$router = new \Phalcon\Mvc\Router();

$router->addGet(
    '/test/http',
    [
        'namespace'  => 'PFrame\Controllers',
        'controller' => 'tars',
        'action'     => 'http',
    ]
);

$router->addGet(
    '/test/tars',
    [
        'namespace'  => 'PFrame\Controllers',
        'controller' => 'tars',
        'action'     => 'tars',
    ]
);

return $router;
