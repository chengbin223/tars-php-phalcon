<?php

namespace PFrame\Controllers;

class TarsController extends BaseController
{
    public function httpAction()
    {
        $this->response->setContent('Hello Tars phalcon');
    }
}
