<?php

namespace HttpServer\controller;

use HttpServer\component\Controller;
use PFrame\Libs\Common\ShareTrait;

class BaseController extends Controller
{
    use ShareTrait;

    /**
     * @return \CenterConfig;
     */
    protected function centerConfig()
    {
        return $this->getShared('centerConfig');
    }
}
