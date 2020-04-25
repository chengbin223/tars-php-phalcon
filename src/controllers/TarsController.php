<?php

namespace PFrame\Controllers;

use HttpServer\tars\cservant\PHPTest\TarsPhalcon\tarsObj\TestTafServiceServant;

class TarsController extends BaseController
{
    public function httpAction()
    {
        $this->response->setContent('Hello Tars phalcon, 入参:' .
            json_encode($this->request->getQuery()));
        return $this->response;
    }

    /**
     * @throws \Exception
     */
    public function tarsAction()
    {
        $config = new \Tars\client\CommunicatorConfig(); //这里配置的是tars主控地址
        $config->init($this->centerConfig()->tarsDeployCfg);
        $config->setCharsetName("UTF-8"); //字符集
        $config->setSocketMode(2);

        $cservent = new TestTafServiceServant($config);

        $this->response->setContent((string)($cservent->test()));
        return $this->response;
    }
}
