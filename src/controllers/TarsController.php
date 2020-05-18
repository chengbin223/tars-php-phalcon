<?php

namespace PFrame\Controllers;

use HttpServer\tars\Config;
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
        $config = Config::communicatorConfig($this->centerConfig()->tarsDeployCfg);

        $cservent = new TestTafServiceServant($config);

        $this->response->setContent((string)($cservent->test()));
        return $this->response;
    }
}
