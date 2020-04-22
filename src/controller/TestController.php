<?php

namespace HttpServer\controller;

use HttpServer\tars\cservant\PHPTest\TarsPhalcon\tarsObj\TestTafServiceServant;

class TestController extends BaseController
{
    public function actionHttp()
    {
        $this->getResponse()->send('Hello Tars phalcon');
    }

    /**
     * @throws \Exception
     */
    public function actionTars()
    {
        $config = new \Tars\client\CommunicatorConfig(); //这里配置的是tars主控地址
        $config->init($this->centerConfig()->tarsDeployCfg);
        $config->setCharsetName("UTF-8"); //字符集
        $config->setSocketMode(2);

        $cservent = new TestTafServiceServant($config);

        $this->getResponse()->send($cservent->test());
    }
}
