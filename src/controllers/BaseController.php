<?php

namespace PFrame\Controllers;

use PFrame\Libs\Common\ShareTrait;
use Phalcon\Mvc\Controller;

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
