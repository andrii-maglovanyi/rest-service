<?php

namespace RestService\tests\app\Components\Http;

use RestService\tests\BaseTestCase;
use RestService\app\Components\Http\Request;

class RequestTest extends BaseTestCase
{
    protected $request;

    public function setUp()
    {
        $_SERVER['CONTENT_TYPE'] = Request::APPLICATION_JSON_CONTENT_TYPE;
        $_SERVER['REQUEST_METHOD'] = Request::GET;

        $this->request = $this->container['request'];
    }

    public function testGetRequestMethodReturnsCorrectValue()
    {
        $this->assertEquals(
            $_SERVER['REQUEST_METHOD'],
            $this->request->getRequestMethod()
        );
    }

    public function testRequestReturnsExpectedParamValues()
    {
        $this->assertEquals(null, $this->request->getParam('id'));
        $this->request->setParams(array('some value'));
        $this->assertEquals('some value', $this->request->getParam('id'));
        $this->request->setParams(array('param' => 'value'));
        $this->assertEquals('value', $this->request->getParam('param'));
    }

    public function testTheExpectedRequestMethodIsSupported()
    {
        $this->request->setSupportedRequestMethods(array(Request::GET));
        $this->assertTrue($this->request->isRequestMethodSupported());
    }

    public function testTheExpectedRequestMethodIsNotSupported()
    {
        $this->request->setSupportedRequestMethods(array(Request::POST));
        $this->assertFalse($this->request->isRequestMethodSupported());
    }

    public function testTheExpectedContentTypeIsSupported()
    {
        $this->request->setSupportedContentTypes(
            array(Request::APPLICATION_JSON_CONTENT_TYPE)
        );

        $this->assertTrue($this->request->isContentTypeSupported());
    }

    public function testTheExpectedContentTypeIsNotSupported()
    {
        $this->request->setSupportedContentTypes(
            array(Request::APPLICATION_X_WWW_FORM_URLENCODED)
        );

        $this->assertFalse($this->request->isContentTypeSupported());
    }

    public function testRequestGetsAndSetsParametersCorrectly()
    {
        $this->request->setParams(array('one', 'two', 'three'));
        $this->assertEquals($this->request->getParams(), array('one', 'two', 'three'));
    }
}
