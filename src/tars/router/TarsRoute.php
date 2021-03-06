<?php

namespace HttpServer\tars\router;

use HttpServer\tars\App;
use HttpServer\tars\Boot;
use Phalcon\Mvc\Application;
use Tars\core\Request;
use Tars\core\Response;
use Tars\route\Route;

class TarsRoute implements Route
{
    public function dispatch(Request $request, Response $response)
    {
        Boot::handle();

        try {
            $this->clean();

            $phalconResponse = $this->handle($request);

            $this->response($response, $phalconResponse);

            $this->clean();
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

        $phalconResponse = $application->handle(
            $application->request->getURI()
        );

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

        $_REQUEST = array_merge($_GET, $_POST, $_COOKIE);

        $headers = isset($tarsRequest->data['header']) ? $tarsRequest->data['header'] : [];
        foreach ($headers as $name => $value) {
            $key = str_replace('-', '_', $name);
            $key = strtoupper($key);

            if (! in_array($key, ['CONTENT_LENGTH', 'CONTENT_MD5', 'CONTENT_TYPE', 'REMOTE_ADDR', 'SERVER_PORT', 'HTTPS'])) {
                $key = 'HTTP_' . $key;
            }

            $_SERVER[$key] = $value;
        }
        if ('cli-server' === PHP_SAPI) {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
                $_SERVER['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
                $_SERVER['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }

        $content = isset($tarsRequest->data['post']) ?
            (is_array($tarsRequest->data['post']) ? http_build_query($tarsRequest->data['post']) : $tarsRequest->data['post']) :
            null;
        $GLOBALS['HTTP_RAW_POST_DATA'] = $content;

        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
        $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
        if (0 === stripos($contentType, 'application/x-www-form-urlencoded')
            && in_array(strtoupper($requestMethod), array('PUT', 'DELETE', 'PATCH'))
        ) {
            parse_str($content, $data);
            $_POST = array_merge($_POST, $data);
        } elseif (0 === stripos($contentType, 'application/json')) {
            $_POST = array_merge($_POST, json_decode($content, true));
        }
    }

    protected function clean()
    {
        clearstatcache();
        if ($this->app()->session->isStarted()) {
            $this->app()->session->destroy();
        }
        $this->app()->cookies->reset();
        $_SERVER = [];
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
        $_FILES = [];
        $_REQUEST = [];
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
        return App::getApp();
    }
}
