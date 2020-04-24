<?php

namespace HttpServer\tars\router;

use HttpServer\tars\Boot;
use Phalcon\Mvc\Application;
use Tars\core\Request;
use Tars\core\Response;
use Tars\route\Route;

class TarsRoute implements Route
{
    protected static $app;

    public function dispatch(Request $request, Response $response)
    {
        Boot::handle();

        try {
            clearstatcache();

            $phalconResponse = $this->handle($request);

            $this->clean();

            $this->response($response, $phalconResponse);
        } catch (\Exception $e) {
            $response->status(500);
            $response->send($e->getMessage() . '|' . $e->getTraceAsString());
        }
    }

    protected function handle($tarsRequest)
    {
        ob_start();
        $isObEnd = false;

        $this->transformRequest($tarsRequest);

        $application = $this->app();

        $phalconResponse = $application->handle();

        $content = $phalconResponse->getContent();
        if (strlen($content) === 0 && ob_get_length() > 0) {
            $phalconResponse->setContent(ob_get_contents());
            ob_end_clean();
            $isObEnd = true;
        }

        if (!$isObEnd) {
            ob_end_flush();
        }

        return $phalconResponse;
    }

    protected function transformRequest(Request $tarsRequest)
    {
        $_SERVER = [
            'SCRIPT_FILENAME' => '/index.php',
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '',
            'ORIG_SCRIPT_NAME' => '',
            'DOCUMENT_ROOT' => '',
        ];
        $server = isset($tarsRequest->data['server']) ? $tarsRequest->data['server'] : [];
        foreach ($server as $key => $value) {
            $_SERVER[strtoupper($key)] = $value;
        }
        if (isset($_SERVER['argv'])) {
            unset($_SERVER['argv']);
        }
        if (isset($_SERVER['argc'])) {
            unset($_SERVER['argc']);
        }

        $_GET = isset($tarsRequest->data['get']) ? $tarsRequest->data['get'] : [];
        $_POST = isset($tarsRequest->data['post']) ?
            (is_array($tarsRequest->data['post']) ? $tarsRequest->data['post'] : []) : [];
        $_COOKIE = isset($tarsRequest->data['cookie']) ? $tarsRequest->data['cookie'] : [];
        $_FILES = isset($tarsRequest->data['files']) ? $tarsRequest->data['files'] : [];

        $headers = isset($tarsRequest->data['header']) ? $tarsRequest->data['header'] : [];
        foreach ($headers as $name => $value) {
            $key = str_replace('-', '_', $name);
            $key = strtoupper($key);

            if (! in_array($key, ['REMOTE_ADDR', 'SERVER_PORT', 'HTTPS'])) {
                $key = 'HTTP_' . $key;
            }

            $_SERVER[$key] = $value;
        }

        $content = $tarsRequest->data['post'] ?
            (is_array($tarsRequest->data['post']) ? http_build_query($tarsRequest->data['post']) : $tarsRequest->data['post']) :
            null;
        $GLOBALS['HTTP_RAW_POST_DATA'] = $content;
    }

    protected function clean()
    {
        $this->app()->session->destroy();
        $this->app()->cookies->reset();
        $_SERVER = [];
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
        $_FILES = [];
        unset($GLOBALS['HTTP_RAW_POST_DATA']);
    }

    protected function response($tarsResponse, $phalconResponse)
    {
        \HttpServer\tars\http\Response::make($phalconResponse, $tarsResponse)->send();
    }

    /**
     * @return Application
     */
    protected function app()
    {
        if (self::$app) {
            return self::$app;
        }
        return self::$app = $this->createApp();
    }

    /**
     * @return Application
     */
    protected function createApp()
    {
        return include PROJECT_PATH . '/bootstrap/app.php';
    }
}
