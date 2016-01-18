<?php

namespace RestService\app\Components;

class Message implements MessageInterface, \JsonSerializable
{
    /**
     * HTTP Status code
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Body of a response
     *
     * @var string
     */
    protected $message;

    /**
     * List of errors
     * 
     * @var array
     */
    protected $errors = array();

    public function createMessage($statusCode, $message, array $errors = array())
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->errors = $errors;
    }

    public function jsonSerialize()
    {
        $message = array(
            'code' => $this->statusCode,
            'message' => $this->message
        );

        if (count($this->errors)) {
            $message['errors'] = $this->errors;
        }

        return $message;
    }
}
