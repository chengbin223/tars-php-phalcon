<?php

// 使用CLI工厂类作为默认的服务容器
$di = new Phalcon\Di\FactoryDefault\Cli();
return $di;
