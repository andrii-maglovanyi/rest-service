<?php

namespace RestService\tests\app\Components;

use RestService\tests\BaseTestCase;
use RestService\app\Components\Message;

class MessageTest extends BaseTestCase
{
    public function testMesageIsSerialized()
    {
        $message = new Message();
        $message->createMessage(200, 'Test went well', array());

        $this->assertTrue(is_array($message->jsonSerialize()));
        $this->assertArrayHasKey('code', $message->jsonSerialize());
    }

    public function testMesageIsSerializedWithErrors()
    {
        $message = new Message();
        $message->createMessage(200, 'Test went well', array(
            'error' => 'Nice error message'
        ));

        $this->assertTrue(is_array($message->jsonSerialize()));
        $this->assertArrayHasKey('errors', $message->jsonSerialize());
    }
}
