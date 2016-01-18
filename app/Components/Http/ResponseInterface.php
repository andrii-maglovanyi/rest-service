<?php

namespace RestService\app\Components\Http;

interface ResponseInterface
{
    // Status codes
    const OK_CODE = 200;
    const CREATED_CODE = 201;
    const BAD_REQUEST_CODE = 400;
    const NOT_FOUND_CODE = 404;
    const METHOD_NOT_ALLOWED_CODE = 405;
    const METHOD_NOT_ACCEPTABLE_CODE = 406;
    const INTERNAL_SERVER_ERROR_CODE = 500;

    // Status names
    const OK_PHRASE = 'OK';
    const CREATED_PHRASE = 'Created';
    const BAD_REQUEST_PHRASE = 'Bad Request';
    const NOT_FOUND_PHRASE = 'Not Found';
    const METHOD_NOT_ALLOWED_PHRASE = 'Method Not Allowed';
    const METHOD_NOT_ACCEPTABLE_PHRASE = 'Method Not Acceptable';
    const INTERNAL_SERVER_ERROR_PHRASE = 'Internal Server Error';

    /**
     * Respond to a client
     *
     * @param  int $responseStatusCode    HTTP Status code
     * @param  mixed $body                Response body
     *
     * @return mixed                      Response body
     */
    public function send();

    /**
     * Respond to a client in JSON format
     *
     * @param  int $responseStatusCode    HTTP Status code
     * @param  string $body               Response body
     *
     * @return string                     Response body
     */
    public function sendJson();

    /**
     * Get statuses array
     *
     * @return array
     */
    static public function getStatuses();
}
