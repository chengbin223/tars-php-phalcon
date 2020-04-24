<?php

namespace PFrame\Services;

use HttpServer\tars\servant\PHPTest\TarsPhalcon\tarsObj\TestTafServiceServant;

class TestTafServiceImpl implements TestTafServiceServant
{
    public function test()
    {
        return 666;
    }
}
