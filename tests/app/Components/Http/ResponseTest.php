<?php

namespace RestService\tests\app\Components\Http;

use RestService\tests\BaseTestCase;
use RestService\app\Components\Http\Response;

class ResponseTest extends BaseTestCase
{
    protected $response;

    public function setUp()
    {
        $this->response = $this->container['response'];
    }

    /**
     * @runInSeparateProcess
     */
    public function testResponseObjectReturnsOkStatusPhraseByDefault()
    {
        $this->assertEquals(
            $this->response->send(),
            Response::getStatuses()[Response::OK_CODE]
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testResponseObjectReturnsBodyIfAvailable()
    {
        $body = 'Test message';
        $this->assertEquals($this->response->send(Response::OK_CODE, $body), $body);
    }

    public function testResponseObjectReturnsJsonResponse()
    {
        $body = array('key' => 'value');
        $response = new Response($this->container['request']);
        // Test response without message object
        $this->assertEquals($response->sendJson(Response::OK_CODE, $body), json_encode($body));

        // Test response with a message object
        $this->assertEquals(
            $this->response->sendJson(Response::OK_CODE, $body),
            json_encode(array('code' => 200, 'message' => $body))
        );

    }

    public function testResponseObjectReturnsSerializedMessage()
    {
        $body = array('key' => 'value');
        $this->assertEquals(
            $this->response->sendJson(Response::OK_CODE, $body),
            json_encode(array('code' => 200, 'message' => $body))
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testResponseObjectReturnsJsonContentType()
    {
        $response = new Response($this->container['request']);
        $response->sendJson(Response::OK_CODE);
        $this->assertContains('Content-Type: application/json', xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testResponseObjectReturnsAllowedHeaderWhenWrongMethodUsed()
    {
        $request = $this->container['request'];
        $request->setSupportedRequestMethods(
            array($request::POST, $request::PUT, $request::DELETE)
        );

        $response = $this->container['response'];
        $this->response->send($response::METHOD_NOT_ALLOWED_CODE);

        $this->assertContains('Allow: POST, PUT, DELETE', xdebug_get_headers());
    }
}
