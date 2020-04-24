<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/2/24
 * Time: 下午2:51.
 */

// 以namespace的方式,在psr4的框架下对代码进行加载
return array(
    'obj' => [
        'protocolName' => 'http', //http, json, tars or other
        'serverType' => 'http', //http(no_tars default), websocket, tcp(tars default), udp
        'namespaceName' => 'HttpServer\\',
        'monitorStoreConf' => [
            //使用redis缓存主调上报信息
            //'className' => Tars\monitor\cache\RedisStoreCache::class,
            //'config' => [
            // 'host' => '127.0.0.1',
            // 'port' => 6379,
            // 'password' => ':'
            //],
            //使用swoole_table缓存主调上报信息（默认）
            'className' => Tars\monitor\cache\SwooleTableStoreCache::class,
            'config' => [
                'size' => 40960
            ]
        ]
    ],
    'tarsObj' => [
        'protocolName' => 'tars', //http, json, tars or other
        'serverType' => 'tcp', //http(no_tars default), websocket, tcp(tars default), udp
        'home-api' => '\HttpServer\tars\servant\PHPTest\TarsPhalcon\tarsObj\TestTafServiceServant', //根据实际情况替换，遵循PSR-4即可，与tars.proto.php配置一致
        'home-class' => '\PFrame\Services\TestTafServiceImpl', //根据实际情况替换，遵循PSR-4即可
    ]
);
