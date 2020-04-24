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

return $router;
