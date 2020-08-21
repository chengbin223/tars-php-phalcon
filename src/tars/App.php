<?php

namespace HttpServer\tars;

class App
{
    public static $tarsDeployCfg;

    public static $app;

    public static function setTarsDeployCfg($tarsDeployCfg)
    {
        static::$tarsDeployCfg = $tarsDeployCfg;
    }

    public static function getTarsDeployCfg()
    {
        return static::$tarsDeployCfg;
    }

    protected static function getCenterConfig()
    {
        return Config::getPhalconCenterConfig();
    }

    public static function getApp()
    {
        if (static::$app) {
            return static::$app;
        }
        $centerConfig = self::getCenterConfig();
        static::setTarsDeployCfg($centerConfig->tarsDeployCfg);
        static::$app = static::createApp();
        $centerConfig = self::getCenterConfig();
        $centerConfig['tarsDeployCfg'] = static::getTarsDeployCfg();
        Boot::handle(true);
        return static::$app;
    }

    public static function createApp()
    {
        return include PROJECT_PATH . '/bootstrap/app.php';
    }
}
