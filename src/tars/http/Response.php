<?php

namespace HttpServer\tars\http;

use Tars\core\Response as TarsResponse;

class Response
{
    /**
     * @var TarsResponse
     */
    protected $tarsResponse;

    /**
     * @var \Phalcon\Http\Response
     */
    protected $phalconResponse;

    /**
     * Make a response.
     *
     * @param $phalconResponse
     * @param TarsResponse $tarsResponse
     * @return static
     */
    public static function make($phalconResponse, TarsResponse $tarsResponse)
    {
        return new static($phalconResponse, $tarsResponse);
    }

    /**
     * Response constructor.
     *
     * @param mixed $phalconResponse
     * @param TarsResponse $tarsResponse
     */
    public function __construct($phalconResponse, TarsResponse $tarsResponse)
    {
        $this->setPhalconResponse($phalconResponse);
        $this->setTarsResponse($tarsResponse);
    }

    /**
     * Sends HTTP headers and content.
     *
     * @throws \InvalidArgumentException
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * Sends HTTP headers.
     *
     * @throws \InvalidArgumentException
     */
    protected function sendHeaders()
    {
        $phalconResponse = $this->getPhalconResponse();

        /* RFC2616 - 14.18 says all Responses need to have a Date */
        if (! $phalconResponse->getHeaders()->get('Date')) {
            $phalconResponse->setHeader('Date', \DateTime::createFromFormat('U', time()));
        }

        // status
        $this->tarsResponse->status($phalconResponse->getStatusCode());
    }

    /**
     * Sends HTTP content.
     */
    protected function sendContent()
    {
        $phalconResponse = $this->getPhalconResponse();
        $this->tarsResponse->resource->end($phalconResponse->getContent());
    }

    /**
     * @param TarsResponse $tarsResponse
     * @return $this
     */
    protected function setTarsResponse(TarsResponse $tarsResponse)
    {
        $this->tarsResponse = $tarsResponse;

        return $this;
    }

    /**
     * @return tarsResponse
     */
    public function getTarsResponse()
    {
        return $this->tarsResponse;
    }

    /**
     * @param $phalconResponse
     * @return $this
     */
    protected function setPhalconResponse($phalconResponse)
    {
        $this->phalconResponse = $phalconResponse;

        return $this;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function getPhalconResponse()
    {
        return $this->phalconResponse;
    }
}
