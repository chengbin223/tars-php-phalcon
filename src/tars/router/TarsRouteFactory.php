<?php

namespace HttpServer\tars\router;

class TarsRouteFactory
{
    public static function getRoute($routeName = '')
    {
        return new TarsRoute();
    }
}
