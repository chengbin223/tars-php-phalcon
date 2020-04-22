<?php

return [
    'appName' => 'PHPTest',
    'serverName' => 'TarsPhalcon',
    'objName' => 'obj',
    'withServant' => true, //决定是服务端,还是客户端的自动生成
    'tarsFiles' => [
        './example.tars',
    ],
    'dstPath' => '../src/tars/servant',
    'namespacePrefix' => 'HttpServer\tars\servant',
];
