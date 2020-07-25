<?php
use \Tars\cmd\Command;

class TarsTask
{
    use \PFrame\Libs\Common\ShareTrait;

    /**
     * 调用 swoole 服务
     * @param $argv
     */
    public function runAction($argv)
    {
        class_alias(\HttpServer\tars\router\TarsRouteFactory::class, \Tars\route\RouteFactory::class);

        //php tarsCmd.php  conf restart
        $configPath = $argv[1];
        $pos = strpos($configPath, '--config=');

        $configPath = substr($configPath, $pos + 9);

        $centerConfig = $this->getShared('centerConfig');
        $centerConfig['tarsDeployCfg'] = $configPath;

        $cmd = strtolower($argv[2]);

        $class = new Command($cmd, $configPath);
        $class->run();
    }
}
