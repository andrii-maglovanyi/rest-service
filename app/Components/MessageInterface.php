<?php

namespace RestService\app\Components;

interface MessageInterface
{
    /**
     * Create http body Message
     *
     * @param  int $statusCode HTTP Status Code
     * @param  string $message Body of a response
     * @param  array $errors List of errors
     *
     * @return void
     */
    public function createMessage($statusCode, $message, array $errors = array());

    /**
     * Return a response array
     *
     * @return array
     */
    public function jsonSerialize();
}
