<?php

namespace HttpServer\tars;

use Monolog\Logger;
use Tars\App;

class Boot
{
    private static $booted = false;

    protected static function getCenterConfig()
    {
        return \Phalcon\DI::getDefault()->getShared('centerConfig');
    }

    public static function handle()
    {
        if (!self::$booted) {
            $centerConfig = self::getCenterConfig();

//            $logLevel = isset($localConfig['log_level']) ? $localConfig['log_level'] : Logger::INFO;

            $deployConfig = App::getTarsConfig();
            $tarsServerConf = $deployConfig['tars']['application']['server'];
            $appName = $tarsServerConf['app'];
            $serverName = $tarsServerConf['server'];

            self::fetchConfig($centerConfig->tarsDeployCfg, $appName, $serverName);

//            self::setTarsLog($localConfig['deploy_cfg'], $logLevel);

            self::$booted = true;
        }
    }

    private static function fetchConfig($deployConfigPath, $appName, $serverName)
    {
        $configtext = Config::fetch($deployConfigPath, $appName, $serverName);
        if ($configtext) {
            $remoteConfig = json_decode($configtext, true);
            $centerConfig = self::getCenterConfig();
            $centerConfig['tars'] = $remoteConfig;
        }
    }

    private static function setTarsLog($deployConfigPath, $level = Logger::INFO)
    {
//        $communicatorConfig = Config::communicatorConfig($deployConfigPath);
//        $tarsLogHandler = new \Tars\log\handler\TarsHandler($communicatorConfig, 'tars.tarslog.LogObj', $level);
//
//        $logger = app()->make('log');
//        if ($logger instanceof Logger) {
//            $logger->pushHandler($tarsLogHandler);
//        } elseif (method_exists($logger, 'driver')) {
//            $logger->driver()->pushHandler($tarsLogHandler);
//        } else {
//            $reflectionObj = new \ReflectionObject($logger);
//            $monologProp = $reflectionObj->getProperty('monolog');
//            $monologProp->setAccessible(true);
//            $monolog = $monologProp->getValue($logger);
//
//            $monolog->pushHandler($tarsLogHandler);
//        }
    }
}
