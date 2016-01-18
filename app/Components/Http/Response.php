<?php

namespace RestService\app\Components\Http;

use RestService\app\Components\Http\RequestInterface;
use RestService\app\Components\MessageInterface;

class Response implements ResponseInterface
{
    /**
     * Request object
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Output message formatter
     *
     * @var MessageInterface
     */
    protected $message;

    public function __construct(RequestInterface $request, MessageInterface $message = null)
    {
        $this->request = $request;
        $this->message = $message;
    }

    public function send($responseStatusCode = Response::OK_CODE, $body = null)
    {
        $this->setStatus($responseStatusCode);
        return $body ? $body : Response::getStatuses()[$responseStatusCode];
    }

    public function sendJson($responseStatusCode = Response::OK_CODE, $body = null, array $errors = array())
    {
        $this->addHeader('Content-Type: '.Request::APPLICATION_JSON_CONTENT_TYPE);

        if ($this->message) {
            $this->message->createMessage(
                $responseStatusCode,
                $body,
                $errors
            );

            $body = $this->message;
        }

        return $this->send($responseStatusCode, json_encode($body));
    }

    static public function getStatuses()
    {
        return array(
            self::OK_CODE => self::OK_PHRASE,
            self::CREATED_CODE => self::CREATED_PHRASE,
            self::BAD_REQUEST_CODE => self::BAD_REQUEST_PHRASE,
            self::NOT_FOUND_CODE => self::NOT_FOUND_PHRASE,
            self::METHOD_NOT_ALLOWED_CODE => self::METHOD_NOT_ALLOWED_PHRASE,
            self::METHOD_NOT_ACCEPTABLE_CODE => self::METHOD_NOT_ACCEPTABLE_PHRASE,
            self::INTERNAL_SERVER_ERROR_CODE => self::INTERNAL_SERVER_ERROR_PHRASE
        );
    }

    /**
     * Set response status
     *
     * @param int $responseStatusCode   HTTP Status code
     */
    private function setStatus($responseStatusCode = Response::OK_CODE)
    {
        $this->addHeader("HTTP/1.1 {$responseStatusCode} ".Response::getStatuses()[$responseStatusCode]);
        if ($responseStatusCode == self::METHOD_NOT_ALLOWED_CODE) {
            $this->addHeader("Allow: ".implode(', ', $this->request->getSupportedRequestMethods()));
        }
    }

    /**
     * Add response header
     *
     * @param string $header
     */
    private function addHeader($header)
    {
        if (!headers_sent()) {
            header($header);
        }
    }
}
