<?php

namespace RestService\tests\app\Components\Controllers;

use RestService\tests\BaseTestCase;
use RestService\app\Components\Controllers\FrontController;

class FrontControllerTest extends BaseTestCase
{
    private $host;
    private $requestMock;

    public function setUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->host = 'http://www.rest-service.loc/';
        $this->requestMock = $this->getMockBuilder('RestService\app\Components\Http\Request')
            ->getMock();

        $this->requestMock->expects($this->any())
            ->method('isRequestMethodSupported')
            ->will($this->returnValue(true));
    }

    /**
     * @param  string $originalString       Path to be parsed
     * @param  string $expectedController   Used controller
     * @param  string $expectedAction       Used action
     *
     * @dataProvider providerFrontControllerUsesCorrectControllerActionBasedOnRoute
     */
    public function testFrontControllerUsesCorrectControllerActionBasedOnRoute($originalString, $expectedController, $expectedAction)
    {
        $_SERVER['REQUEST_URI'] = $this->host.$originalString;
        $frontController = new FrontController($this->requestMock);
        $frontController->run($this->container);
        $this->assertTrue($frontController->getController() === '\RestService\src\Controllers\\'.$expectedController);
        $this->assertTrue($frontController->getAction() === $expectedAction);
    }

    public function testFrontControllerThrowsExceptionIfNoControllerFound()
    {
        $_SERVER['REQUEST_URI'] = $this->host.'none';
        $this->setExpectedException('InvalidArgumentException');
        $frontController = new FrontController($this->requestMock);
        $frontController->run($this->container);
    }

    public function providerFrontControllerUsesCorrectControllerActionBasedOnRoute()
    {
        return array(
            array('', 'IndexController', 'indexAction'),
            array('index/', 'IndexController', 'indexAction'),
            array('index/index', 'IndexController', 'indexAction'),
            array('index/about', 'IndexController', 'aboutAction'),
            array('index/none', 'IndexController', 'indexAction'),
            array('index/none/test', 'IndexController', 'indexAction'),
            array('post', 'PostController', 'indexAction'),
            array('post/index/test', 'PostController', 'indexAction'),
            array('post/index/test', 'PostController', 'indexAction')
        );
    }
}
